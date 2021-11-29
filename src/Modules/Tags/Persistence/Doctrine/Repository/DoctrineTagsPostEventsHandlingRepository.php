<?php

namespace App\Modules\Tags\Persistence\Doctrine\Repository;

use App\Modules\Tags\Domain\Dto\CreateNewTagsPostHeaderDto;
use App\Modules\Tags\Domain\Dto\DeleteExistingTagsPostHeaderDto;
use App\Modules\Tags\Domain\Dto\UpdateExistingTagsPostHeaderDto;
use App\Modules\Tags\Domain\Repository\TagsPostEventsHandlingRepositoryInterface;
use App\Modules\Tags\Persistence\Doctrine\Entity\TagPost;
use App\Modules\Tags\Persistence\Doctrine\Entity\TagPostHeader;

class DoctrineTagsPostEventsHandlingRepository extends DoctrineTagsRepository implements TagsPostEventsHandlingRepositoryInterface
{
    /**
     * @param CreateNewTagsPostHeaderDto $newPostHeader
     */
   public function createPostHeader(CreateNewTagsPostHeaderDto $newPostHeader): void
    {
        $post = new TagPostHeader();
        $post->setId($newPostHeader->getId());
        $post->setTitle($newPostHeader->getTitle());
        $post->setSummary($newPostHeader->getSummary());
        $post->setVersion($newPostHeader->getVersion());
        $post->setCreatedAt($newPostHeader->getCreatedAt());
        $post->setCreatedById($newPostHeader->getCreatedById());
        $post->setCreatedByName($newPostHeader->getCreatedByName());
        $post->setCommentsCount($newPostHeader->getCommentsCount());
        $post->setFlatTags($newPostHeader->getTags());
        $this->getEntityManager()->persist($post);
    }

    /**
     * @param UpdateExistingTagsPostHeaderDto $updatedPostHeader
     */
   public function updatePostHeader(UpdateExistingTagsPostHeaderDto $updatedPostHeader): void
    {
        $this->getEntityManager()
            ->createQueryBuilder()
            ->update(TagPostHeader::class, 'p')
            ->set('p.title', ':title')
            ->set('p.summary', ':summary')
            ->set('p.version', ':version')
            ->set('p.flatTags', ':tags')
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
     * @param DeleteExistingTagsPostHeaderDto $deletedPostHeader
     */
   public function deletePostHeader(DeleteExistingTagsPostHeaderDto $deletedPostHeader): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager
            ->createQueryBuilder()
            ->delete(TagPost::class, 'tp')
            ->where('tp.post = :post')
            ->setParameter('post', $entityManager->getReference(TagPostHeader::class, $deletedPostHeader->getId()))
            ->getQuery()
            ->execute();

        $entityManager->flush();

        $entityManager
            ->createQueryBuilder()
            ->delete(TagPostHeader::class, 'p')
            ->where('p.id = :id')
            ->setParameter('id', $deletedPostHeader->getId(), 'ulid')
            ->getQuery()
            ->execute();
    }
}