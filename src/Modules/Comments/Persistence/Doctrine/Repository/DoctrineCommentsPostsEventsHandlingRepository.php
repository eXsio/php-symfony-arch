<?php

namespace App\Modules\Comments\Persistence\Doctrine\Repository;

use App\Modules\Comments\Domain\Dto\CreateNewCommentsPostHeaderDto;
use App\Modules\Comments\Domain\Dto\DeleteExistingCommentsPostHeaderDto;
use App\Modules\Comments\Domain\Dto\UpdateExistingCommentsPostHeaderDto;
use App\Modules\Comments\Domain\Repository\CommentsPostsEventsHandlingRepositoryInterface;
use App\Modules\Comments\Persistence\Doctrine\Entity\Comment;
use App\Modules\Comments\Persistence\Doctrine\Entity\CommentPostHeader;

class DoctrineCommentsPostsEventsHandlingRepository extends DoctrineCommentsRepository implements CommentsPostsEventsHandlingRepositoryInterface
{

    /**
     * @param CreateNewCommentsPostHeaderDto $newPostHeader
     */
    function createPostHeader(CreateNewCommentsPostHeaderDto $newPostHeader): void
    {
        $post = new CommentPostHeader();
        $post->setId($newPostHeader->getId());
        $post->setTitle($newPostHeader->getTitle());
        $post->setSummary($newPostHeader->getSummary());
        $post->setVersion($newPostHeader->getVersion());
        $post->setTags($newPostHeader->getTags());
        $post->setCreatedAt($newPostHeader->getCreatedAt());
        $post->setCreatedById($newPostHeader->getCreatedById());
        $post->setCreatedByName($newPostHeader->getCreatedByName());
        $post->setCommentsCount($newPostHeader->getCommentsCount());
        $this->getEntityManager()->persist($post);
    }

    /**
     * @param UpdateExistingCommentsPostHeaderDto $updatedPostHeader
     */
    function updatePostHeader(UpdateExistingCommentsPostHeaderDto $updatedPostHeader): void
    {
        $this->getEntityManager()
            ->createQueryBuilder()
            ->update(CommentPostHeader::class, 'p')
            ->set('p.title', ':title')
            ->set('p.summary', ':summary')
            ->set('p.tags', ':tags')
            ->set('p.version', ':version')
            ->where('p.id = :id and p.version <= :version')
            ->setParameter("id", $updatedPostHeader->getId(), 'ulid')
            ->setParameter("version", $updatedPostHeader->getVersion(),)
            ->setParameter("title", $updatedPostHeader->getTitle())
            ->setParameter("summary", $updatedPostHeader->getSummary())
            ->setParameter("tags", json_encode($updatedPostHeader->getTags()))
            ->getQuery()
            ->execute();
    }

    /**
     * @param DeleteExistingCommentsPostHeaderDto $deletedPostHeader
     * @throws \Doctrine\ORM\ORMException
     */
    function deletePostHeader(DeleteExistingCommentsPostHeaderDto $deletedPostHeader): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager
            ->createQueryBuilder()
            ->delete(Comment::class, 'c')
            ->where('c.post = :post')
            ->setParameter('post', $this->getEntityManager()->getReference(CommentPostHeader::class, $deletedPostHeader->getId()))
            ->getQuery()
            ->execute();

        $entityManager
            ->createQueryBuilder()
            ->delete(CommentPostHeader::class, 'p')
            ->where('p.id = :id')
            ->setParameter('id', $deletedPostHeader->getId(), 'ulid')
            ->getQuery()
            ->execute();


    }
}