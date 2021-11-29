<?php

namespace App\Modules\Tags\Persistence\Doctrine\Repository;

use App\Infrastructure\Pagination\Page;
use App\Modules\Tags\Domain\Dto\TagsPostHeaderDto;
use App\Modules\Tags\Domain\Repository\TagsPostHeadersFindingRepositoryInterface;
use App\Modules\Tags\Persistence\Doctrine\Entity\TagPostHeader;
use Symfony\Component\Uid\Ulid;

class DoctrineTagsPostHeadersFindingRepository extends DoctrineTagsRepository implements TagsPostHeadersFindingRepositoryInterface
{

    /**
     * @return array<TagsPostHeaderDto>
     */
   public function findPostHeaders(): array
    {
        $headerClass = TagPostHeader::class;
        $dtoClass = TagsPostHeaderDto::class;
        $query = $this->getEntityManager()->createQuery(
            "select new $dtoClass(p.id, p.title, p.summary, p.createdById, p.createdByName, p.createdAt, p.version, p.commentsCount, p.flatTags) 
                from $headerClass p order by p.id desc"
        );
        return $query->getResult();
    }

    /**
     * @param string $tag
     * @return Page<TagsPostHeaderDto>
     */
    public function findPostHeadersByTag(string $tag, int $pageNo): Page
    {
        $headerClass = TagPostHeader::class;
        $dtoClass = TagsPostHeaderDto::class;
        $query = $this->getEntityManager()->createQuery(
            "select new $dtoClass(p.id, p.title, p.summary, p.createdById, p.createdByName, p.createdAt, p.version, p.commentsCount, p.flatTags) 
                from $headerClass p join p.tagPosts tp join tp.tag t where t.tag=:tag order by p.id desc"
        );
        $data = $query
            ->setParameter("tag", $tag)
            ->setFirstResult(($pageNo - 1) * self::PAGE_SIZE)
            ->setMaxResults(self::PAGE_SIZE)
            ->getArrayResult();
        return new Page(
            $data,
            $this->getCount($tag),
            $pageNo,
            self::PAGE_SIZE
        );
    }

    /**
     * @param string $tag
     * @return int
     */
    private function getCount(string $tag): int
    {
        $headerClass = TagPostHeader::class;
        $query = $this->getEntityManager()->createQuery(
            "select count(p.id) as count from $headerClass p join p.tagPosts tp join tp.tag t where t.tag = :tag"
        );
        return $query->setParameter("tag", $tag)->getResult()[0]["count"];
    }

    /**
     * @param Ulid $postId
     * @return bool
     */
    public function postExists(Ulid $postId): bool
    {
        $headerClass = TagPostHeader::class;
        $query = $this->getEntityManager()->createQuery(
            "select count(p.id) as count from $headerClass p where p.id = :id"
        );
        return $query
                ->setParameter("id", $postId, "ulid")
                ->getResult()[0]["count"] > 0;
    }
}