<?php

namespace App\Modules\Posts\Persistence\Doctrine\Repository;

use App\Modules\Posts\Domain\Dto\CreateNewPostDto;
use App\Modules\Posts\Domain\Repository\PostsCreationRepositoryInterface;
use App\Modules\Posts\Persistence\Doctrine\Entity\Post;
use App\Modules\Posts\Persistence\Doctrine\Entity\PostComments;
use Symfony\Component\Uid\Ulid;

class DoctrinePostsCreationRepository extends DoctrinePostsRepository implements PostsCreationRepositoryInterface
{

    /**
     * @param CreateNewPostDto $newPost
     * @return Ulid
     */
   public function createPost(CreateNewPostDto $newPost): Ulid
    {
        $id = new Ulid();
        $em = $this->getEntityManager();

        $post = new Post();
        $post->setId($id);
        $post->setTitle($newPost->getTitle());
        $post->setSummary($newPost->getSummary());
        $post->setBody($newPost->getBody());
        $post->setTags($newPost->getTags());
        $post->setCreatedAt($newPost->getCreatedAt());
        $post->setUpdatedAt($newPost->getCreatedAt());
        $post->setCreatedById($newPost->getCreatedById());
        $post->setCreatedByName($newPost->getCreatedByName());
        $em->persist($post);
        $em->flush();

        $comments = new PostComments();
        $comments->setCommentsCount(0);
        $comments->setComments([]);
        $comments->setPostId($id);
        $em->persist($comments);

        return $id;
    }
}