<?php

namespace App\Modules\Security\Persistence\Doctrine\Repository;

use App\Modules\Security\Domain\Dto\UpdatePostsCommentsCountDto;
use App\Modules\Security\Domain\Repository\SecurityCommentsEventHandlingRepositoryInterface;
use App\Modules\Security\Persistence\Doctrine\Entity\UserPostHeader;

class DoctrineSecurityCommentsEventHandlingRepository extends DoctrineSecurityRepository implements SecurityCommentsEventHandlingRepositoryInterface
{
    /**
     * @param UpdatePostsCommentsCountDto $commentsCount
     */
    public function updatePostCommentsCount(UpdatePostsCommentsCountDto $commentsCount): void
    {
        $this->getEntityManager()
            ->createQueryBuilder()
            ->update(UserPostHeader::class, 'p')
            ->set('p.commentsCount', ':count')
            ->where('p.id = :id')
            ->getQuery()
            ->setParameter(':count', $commentsCount->getCommentsCount())
            ->setParameter(':id', $commentsCount->getPostId(), 'ulid')
            ->execute();
    }
}