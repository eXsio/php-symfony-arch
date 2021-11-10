<?php

namespace App\Modules\Posts\Persistence\Doctrine\Repository;

use App\Modules\Posts\Domain\Dto\UpdatedPostsUserNameDto;
use App\Modules\Posts\Domain\Repository\PostsSecurityEventsHandlingRepositoryInterface;
use App\Modules\Posts\Persistence\Doctrine\Entity\Post;

class DoctrinePostsSecurityEventsHandlingRepository extends DoctrinePostsRepository implements PostsSecurityEventsHandlingRepositoryInterface
{

    public function updateUserName(UpdatedPostsUserNameDto $updatedUserName): void
    {
        $res = $this->getEntityManager()
            ->createQueryBuilder()
            ->update(Post::class, 'p')
            ->set('p.createdByName', ':newUserName')
            ->where('p.createdByName = :oldUserName')
            ->getQuery()
            ->setParameter('newUserName', $updatedUserName->getNewUserName())
            ->setParameter('oldUserName', $updatedUserName->getOldUserName())
            ->execute();
    }
}