<?php

namespace App\Modules\Comments\Persistence\Doctrine\Repository;

use App\Modules\Comments\Domain\Dto\UpdatedCommentsPostHeadersUserNameDto;
use App\Modules\Comments\Domain\Repository\CommentsSecurityEventsHandlingRepositoryInterface;
use App\Modules\Comments\Persistence\Doctrine\Entity\CommentPostHeader;

class DoctrineCommentsSecurityEventsHandlingRepository extends DoctrineCommentsRepository implements CommentsSecurityEventsHandlingRepositoryInterface
{

    public function updateUserName(UpdatedCommentsPostHeadersUserNameDto $updatedUserName): void
    {

        $this->getEntityManager()
            ->createQueryBuilder()
            ->update(CommentPostHeader::class, 'p')
            ->set('p.createdByName', ':newUserName')
            ->where('p.createdByName = :oldUserName')
            ->getQuery()
            ->setParameter('newUserName', $updatedUserName->getNewUserName())
            ->setParameter('oldUserName', $updatedUserName->getOldUserName())
            ->execute();
    }
}