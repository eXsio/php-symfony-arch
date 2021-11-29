<?php

namespace App\Modules\Tags\Persistence\Doctrine\Repository;

use App\Modules\Tags\Domain\Repository\TagsUpdatingRepositoryInterface;
use App\Modules\Tags\Persistence\Doctrine\Entity\Tag;
use App\Modules\Tags\Persistence\Doctrine\Entity\TagPost;
use App\Modules\Tags\Persistence\Doctrine\Entity\TagPostHeader;
use Doctrine\ORM\ORMException;
use Symfony\Component\Uid\Ulid;

class DoctrineTagsUpdatingRepository extends DoctrineTagsRepository implements TagsUpdatingRepositoryInterface
{
    /**
     * @param string $tag
     * @param Ulid $postId
     * @throws ORMException
     */
    public function addPostToTag(string $tag, Ulid $postId): void
    {
        $tag = $this->fetchTag($tag);
        $tagPost = new TagPost();
        $tagPost->setTag($tag);
        $tagPost->setPost($this->getEntityManager()->getReference(TagPostHeader::class, $postId));
        $this->getEntityManager()->persist($tagPost);
    }

    /**
     * @param string $tag
     * @return Tag
     */
    private function fetchTag(string $tag): Tag
    {
        $result = $this->getEntityManager()
            ->getRepository(Tag::class)
            ->findOneBy(['tag' => $tag]);

        return $result != null ? $result : $this->createTag($tag);

    }

    /**
     * @param string $tag
     * @return Tag
     */
    private function createTag(string $tag): Tag
    {
        $result = new Tag();
        $result->setTag($tag);
        $this->getEntityManager()->persist($result);
        return $result;
    }

    /**
     * @param Ulid $postId
     * @throws ORMException
     */
    public function removePostFromTags(Ulid $postId): void
    {
        $em = $this->getEntityManager();
        $post = $em->getReference(TagPostHeader::class, $postId);
        $delRes = $em->createQueryBuilder()
            ->delete(TagPost::class, 'tp')
            ->where('tp.post = :post')
            ->getQuery()
            ->setParameter("post", $post)
            ->execute();

        ///var_dump("Del Res for $postId: ".$delRes);
        $em->flush();
//        foreach ($em->getRepository(TagPost::class)->findAll() as $tagPost) {
//            var_dump("existent tp for post: ".$tagPost->getPost()->getTitle());
//            var_dump("existent tp for post: ".$tagPost->getTag()->getTag());
//
//        }
    }
}