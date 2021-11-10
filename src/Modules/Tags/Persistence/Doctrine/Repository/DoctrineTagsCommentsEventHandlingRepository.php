<?php

namespace App\Modules\Tags\Persistence\Doctrine\Repository;

use App\Modules\Tags\Domain\Dto\UpdatePostsCommentsCountDto;
use App\Modules\Tags\Domain\Repository\TagsCommentsEventHandlingRepositoryInterface;
use App\Modules\Tags\Persistence\Doctrine\Entity\TagPostHeader;

class DoctrineTagsCommentsEventHandlingRepository extends DoctrineTagsRepository implements TagsCommentsEventHandlingRepositoryInterface
{

    /**
     * @param UpdatePostsCommentsCountDto $commentsCount
     */
    public function updatePostCommentsCount(UpdatePostsCommentsCountDto $commentsCount): void
    {
        $this->getEntityManager()
            ->createQueryBuilder()
            ->update(TagPostHeader::class, 'p')
            ->set('p.commentsCount', ':count')
            ->where('p.id = :id')
            ->getQuery()
            ->setParameter(':count', $commentsCount->getCommentsCount())
            ->setParameter(':id', $commentsCount->getPostId(), 'ulid')
            ->execute();
    }
}