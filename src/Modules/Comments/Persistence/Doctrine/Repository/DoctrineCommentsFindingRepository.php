<?php

namespace App\Modules\Comments\Persistence\Doctrine\Repository;

use App\Infrastructure\Pagination\Page;
use App\Modules\Comments\Domain\Dto\CommentDto;
use App\Modules\Comments\Domain\Dto\CommentWithPostDto;
use App\Modules\Comments\Domain\Repository\CommentsFindingRepositoryInterface;
use App\Modules\Comments\Persistence\Doctrine\Entity\Comment;
use Symfony\Component\Uid\Ulid;

class DoctrineCommentsFindingRepository extends DoctrineCommentsRepository implements CommentsFindingRepositoryInterface
{

    /**
     * @param Ulid $postId
     * @return int
     */
    public function getCommentsCount(Ulid $postId): int
    {
        $commentClass = Comment::class;
        return $this->getEntityManager()
            ->createQuery("select count(c.id) as count from $commentClass c join c.post p where p.id = :postId")
            ->setParameter("postId", $postId, 'ulid')
            ->getResult()[0]["count"];
    }

    /**
     * @param Ulid $postId
     * @return array
     */
    public function findCommentsByPostId(Ulid $postId): array
    {
        $commentClass = Comment::class;
        $dtoClass = CommentDto::class;
        return $this->getEntityManager()
            ->createQuery("select new $dtoClass(c.id, c.author, c.body, pc.id, c.createdAt) 
                                from $commentClass c 
                                    join c.post p 
                                    left join c.parentComment pc 
                                    where p.id = :postId"
            )
            ->setParameter("postId", $postId, 'ulid')
            ->getResult();
    }

    /**
     * @param int $pageNo
     * @return Page
     */
    public function findLatestComments(int $pageNo): Page
    {
        $commentClass = Comment::class;
        $dtoClass = CommentWithPostDto::class;
        $result = $this->getEntityManager()
            ->createQuery("select new $dtoClass(c.id, c.author, c.body, pc.id, c.createdAt, 
                                p.id, p.title, p.tags)
                            from $commentClass c 
                                join c.post p 
                                left join c.parentComment pc
                                order by c.id desc"
            )
            ->setFirstResult(($pageNo - 1) * self::PAGE_SIZE)
            ->setMaxResults(self::PAGE_SIZE)
            ->getArrayResult();
        return new Page(
            $result,
            $this->getCount(),
            $pageNo,
            self::PAGE_SIZE
        );
    }

    /**
     * @return int
     */
    private function getCount(): int
    {
        $commentClass = Comment::class;
        $query = $this->getEntityManager()->createQuery(
            "select count(c.id) as count from $commentClass c"
        );
        return $query->getResult()[0]["count"];
    }

    /**
     * @param Ulid $commentId
     * @return bool
     */
    public function commentExists(Ulid $commentId): bool
    {
        $commentClass = Comment::class;
        $query = $this->getEntityManager()->createQuery(
            "select count(c.id) as count from $commentClass c where c.id = :id"
        );
        $count = $query
            ->setParameter("id", $commentId, "ulid")
            ->getResult()[0]["count"];
        return $count > 0;
    }
}