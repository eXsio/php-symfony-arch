<?php

namespace App\Modules\Tags\Persistence\Doctrine\Repository;

use App\Modules\Tags\Domain\Repository\TagsDeletingRepositoryInterface;
use App\Modules\Tags\Persistence\Doctrine\Entity\Tag;
use App\Modules\Tags\Persistence\Doctrine\Entity\TagPost;

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
                        ->select('subTag.id')
                        ->from(TagPost::class, 'subTp')
                        ->innerJoin('subTp.tag', 'subTag')
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