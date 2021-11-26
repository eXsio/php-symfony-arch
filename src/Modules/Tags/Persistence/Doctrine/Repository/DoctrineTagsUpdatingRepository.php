<?php

namespace App\Modules\Tags\Persistence\Doctrine\Repository;

use App\Modules\Tags\Domain\Repository\TagsUpdatingRepositoryInterface;
use App\Modules\Tags\Persistence\Doctrine\Entity\Tag;
use App\Modules\Tags\Persistence\Doctrine\Entity\TagPostHeader;
use Symfony\Component\Uid\Ulid;

class DoctrineTagsUpdatingRepository extends DoctrineTagsRepository implements TagsUpdatingRepositoryInterface
{
    /**
     * @param string $tag
     * @param Ulid $postId
     * @throws \Doctrine\ORM\ORMException
     */
    public function addPostToTag(string $tag, Ulid $postId): void
    {
        $tag = $this->fetchTag($tag);
        $tag->getPosts()->add($this->getEntityManager()->getReference(TagPostHeader::class, $postId));
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
     * @throws \Doctrine\ORM\ORMException
     */
    public function removePostFromTags(Ulid $postId): void
    {
        $em = $this->getEntityManager();
        $post = $em->getReference(TagPostHeader::class, $postId);
        $tags = $this->getTags($postId);
        foreach ($tags as $tag) {
            $tag->getPosts()->removeElement($post);
        }
    }

    /**
     * @param Ulid $postId
     * @return array<Tag>
     */
    private function getTags(Ulid $postId): array
    {
        $em = $this->getEntityManager();
        return $em
            ->createQueryBuilder()
            ->select('t')
            ->from(Tag::class, 't')
            ->innerJoin('t.posts', 'p')
            ->where('p.id = :postId')
            ->getQuery()
            ->setParameter('postId', $postId, 'ulid')
            ->getResult();
    }
}