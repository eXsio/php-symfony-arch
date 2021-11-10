<?php

namespace App\Modules\Tags\Persistence\Doctrine\Repository;

use App\Modules\Tags\Domain\Dto\UpdatedTagsPostHeadersUserNameDto;
use App\Modules\Tags\Domain\Repository\TagsSecurityEventsHandlingRepositoryInterface;
use App\Modules\Tags\Persistence\Doctrine\Entity\TagPostHeader;

class DoctrineTagsSecurityEventsHandlingRepository extends DoctrineTagsRepository implements TagsSecurityEventsHandlingRepositoryInterface
{

    public function updateUserName(UpdatedTagsPostHeadersUserNameDto $updatedUserName): void
    {

        $this->getEntityManager()
            ->createQueryBuilder()
            ->update(TagPostHeader::class, 'p')
            ->set('p.createdByName', ':newUserName')
            ->where('p.createdByName = :oldUserName')
            ->getQuery()
            ->setParameter('newUserName', $updatedUserName->getNewUserName())
            ->setParameter('oldUserName', $updatedUserName->getOldUserName())
            ->execute();
    }
}