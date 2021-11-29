<?php

namespace App\Modules\Tags\Persistence\Doctrine\Repository;

use App\Modules\Tags\Domain\Repository\TagsDeletingRepositoryInterface;
use App\Modules\Tags\Persistence\Doctrine\Entity\Tag;

class DoctrineTagsDeletingRepository extends DoctrineTagsRepository implements TagsDeletingRepositoryInterface
{

    public function deleteEmptyTags(): void
    {
        $em = $this->getEntityManager();
        $expr = $em->getExpressionBuilder();
        $emptyTags = $em->createQueryBuilder()
            ->select('tag.id')
            ->from(Tag::class, 'tag')
            ->where(
                $expr->notIn('tag.id',
                    $em->createQueryBuilder()
                        ->select('subPost.id')
                        ->from(Tag::class, 'subTag')
                        ->innerJoin('subTag.tagPosts', 'subTp')
                        ->innerJoin('subTp.post', 'subPost')
                        ->getDQL()
                )
            )
            ->getQuery()
            ->getDQL();

        $this->getEntityManager()
            ->createQueryBuilder()
            ->delete(Tag::class, 't')
            ->where($expr->in('t.id', $emptyTags))
            ->getQuery()
            ->execute();

    }
}