<?php

namespace App\Modules\Comments\Persistence\Doctrine\Repository;

use App\Modules\Comments\Domain\Repository\CommentsDeletionRepositoryInterface;
use App\Modules\Comments\Persistence\Doctrine\Entity\Comment;
use Symfony\Component\Uid\Ulid;

class DoctrineCommentsDeletionRepository extends DoctrineCommentsRepository implements CommentsDeletionRepositoryInterface
{

    /**
     * @param Ulid $postId
     * @return mixed|void
     */
    public function deleteCommentsForPost(Ulid $postId)
    {
        $em = $this->getEntityManager();
        $expr = $em->getExpressionBuilder();
        $qb = $em->createQueryBuilder();

        $subQuery = $qb->select('sc.id')
            ->from(Comment::class, 'sc')
            ->join('sc.post', 'sp')
            ->where('sp.id = :postId')
            ->getDQL();

        $qb
            ->delete(Comment::class, 'comment')
            ->where($expr->in('comment.id', $subQuery))
            ->setParameter('postId', $postId, 'ulid')
            ->getQuery()
            ->execute();
    }
}