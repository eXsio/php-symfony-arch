<?php

namespace App\Modules\Security\Persistence\Doctrine\Repository;

use App\Modules\Security\Domain\Dto\CreateNewUserPostHeaderDto;
use App\Modules\Security\Domain\Dto\DeleteExistingUserPostHeaderDto;
use App\Modules\Security\Domain\Dto\UpdateExistingUserPostHeaderDto;
use App\Modules\Security\Domain\Repository\SecurityPostEventsHandlingRepositoryInterface;
use App\Modules\Security\Persistence\Doctrine\Entity\User;
use App\Modules\Security\Persistence\Doctrine\Entity\UserPostHeader;

class DoctrineSecurityPostEventsHandlingRepository extends DoctrineSecurityRepository implements SecurityPostEventsHandlingRepositoryInterface
{
    /**
     * @param CreateNewUserPostHeaderDto $newPostHeader
     * @throws \Doctrine\ORM\ORMException
     */
    function createPostHeader(CreateNewUserPostHeaderDto $newPostHeader): void
    {
        $post = new UserPostHeader();
        $post->setId($newPostHeader->getId());
        $post->setTitle($newPostHeader->getTitle());
        $post->setSummary($newPostHeader->getSummary());
        $post->setVersion($newPostHeader->getVersion());
        $post->setTags($newPostHeader->getTags());
        $post->setCreatedAt($newPostHeader->getCreatedAt());
        $post->setCommentsCount($newPostHeader->getCommentsCount());
        $post->setUser($this->getEntityManager()->getReference(User::class, $newPostHeader->getCreatedById()));
        $this->getEntityManager()->persist($post);
    }

    /**
     * @param UpdateExistingUserPostHeaderDto $updatedPostHeader
     */
    function updatePostHeader(UpdateExistingUserPostHeaderDto $updatedPostHeader): void
    {
        $this->getEntityManager()
            ->createQueryBuilder()
            ->update(UserPostHeader::class, 'p')
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
     * @param DeleteExistingUserPostHeaderDto $deletedPostHeader
     */
    function deletePostHeader(DeleteExistingUserPostHeaderDto $deletedPostHeader): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager
            ->createQueryBuilder()
            ->delete(UserPostHeader::class, 'p')
            ->where('p.id = :id')
            ->setParameter('id', $deletedPostHeader->getId(), 'ulid')
            ->getQuery()
            ->execute();


    }
}