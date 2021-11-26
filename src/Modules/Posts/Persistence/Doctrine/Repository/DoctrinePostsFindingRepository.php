<?php

namespace App\Modules\Posts\Persistence\Doctrine\Repository;

use App\Infrastructure\Pagination\Page;
use App\Modules\Posts\Domain\Dto\PostDto;
use App\Modules\Posts\Domain\Dto\PostForBaselineDto;
use App\Modules\Posts\Domain\Dto\PostHeaderDto;
use App\Modules\Posts\Domain\Repository\PostsFindingRepositoryInterface;
use App\Modules\Posts\Persistence\Doctrine\Entity\Post;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Component\Uid\Ulid;

class DoctrinePostsFindingRepository extends DoctrinePostsRepository implements PostsFindingRepositoryInterface
{

    /**
     * @param Ulid $id
     * @return PostDto|null
     */
    public function findPost(Ulid $id): ?PostDto
    {
        $dtoClass = PostDto::class;
        $postClass = Post::class;
        $query = $this->getEntityManager()->createQuery(
            "select new $dtoClass(
                    p.id, p.title, p.body, p.tags, c.comments, p.createdById, p.createdByName, p.createdAt, p.updatedAt, p.version
             ) from $postClass p join p.comments c where p.deletedAt is null and p.id = :id"
        );
        $query->setParameter("id", $id, "ulid");
        return $query->getResult()[0];

    }

    /**
     * @param int $pageNo
     * @return Page<PostHeaderDto>
     */
    public function findPosts(int $pageNo): Page
    {
        $dtoClass = PostHeaderDto::class;
        $postClass = Post::class;
        $query = $this->getEntityManager()->createQuery(
            "select new $dtoClass(
                        p.id, p.title, p.summary, p.tags, c.commentsCount, p.createdById, p.createdByName, p.createdAt
             ) from $postClass p join p.comments c 
             where p.deletedAt is null
             order by p.id desc"
        )
            ->setFirstResult(($pageNo - 1) * self::PAGE_SIZE)
            ->setMaxResults(self::PAGE_SIZE);

        return new Page(
            $query->getArrayResult(),
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
        $postClass = Post::class;
        $query = $this->getEntityManager()->createQuery(
            "select count(p.id) as count from $postClass p where p.deletedAt is null"
        );
        return $query->getScalarResult()[0]["count"];
    }

    /**
     * @param \DateTime|null $from
     * @return array<PostForBaselineDto>
     */
    public function findExistingPostsForBaseline(?\DateTime $from): array
    {
        $dtoClass = PostForBaselineDto::class;
        $postClass = Post::class;
        $dql = "select new $dtoClass(
                    p.id, p.title, p.body, p.summary, p.tags, p.updatedAt, p.version
             ) from $postClass p where p.deletedAt is null";
        if ($from != null) {
            $dql = $dql . " and p.createdAt >= :from";
        }
        $query = $this->getEntityManager()->createQuery(
            $dql
        );
        if ($from != null) {
            $query->setParameter("from", $from);
        }
        return $query->getResult();
    }

    /**
     * @param \DateTime|null $from
     * @return array<Ulid>
     */
    public function findDeletedPostIdsForBaseline(?\DateTime $from): array
    {
        $postClass = Post::class;
        $dql = "select p.id from $postClass p where p.deletedAt is not null";
        if ($from != null) {
            $dql = $dql . " and p.deletedAt >= :from";
        }
        $query = $this->getEntityManager()->createQuery(
            $dql
        );
        if ($from != null) {
            $query->setParameter("from", $from);
        }
        return $query->getResult();
    }
}