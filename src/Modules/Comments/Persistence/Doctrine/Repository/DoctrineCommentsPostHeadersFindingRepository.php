<?php

namespace App\Modules\Comments\Persistence\Doctrine\Repository;

use App\Modules\Comments\Domain\Dto\CommentsPostHeaderDto;
use App\Modules\Comments\Domain\Repository\CommentsPostHeadersFindingRepositoryInterface;
use App\Modules\Comments\Persistence\Doctrine\Entity\CommentPostHeader;
use Symfony\Component\Uid\Ulid;

class DoctrineCommentsPostHeadersFindingRepository extends DoctrineCommentsRepository implements CommentsPostHeadersFindingRepositoryInterface
{

    /**
     * @return array<CommentsPostHeaderDto>
     */
    public function findPostHeaders(?\DateTime $from = null): array
    {
        $headerClass = CommentPostHeader::class;
        $dtoClass = CommentsPostHeaderDto::class;
        $dql = "select new $dtoClass(p.id, p.title, p.summary, p.tags, p.createdById, p.createdByName, p.createdAt, p.version) from $headerClass p";
        if ($from != null) {
            $dql = $dql . " where p.createdAt >= :from";
        }
        $query = $this->getEntityManager()->createQuery($dql);
        if ($from != null) {
            $query = $query->setParameter("from", $from);
        }
        return $query->getArrayResult();
    }

    /**
     * @param Ulid $postId
     * @return bool
     */
    public function postExists(Ulid $postId): bool
    {
        $headerClass = CommentPostHeader::class;
        $query = $this->getEntityManager()->createQuery(
            "select count(p.id) as count from $headerClass p where p.id = :id"
        );
        return $query
                ->setParameter("id", $postId, "ulid")
                ->getResult()[0]["count"] > 0;
    }
}