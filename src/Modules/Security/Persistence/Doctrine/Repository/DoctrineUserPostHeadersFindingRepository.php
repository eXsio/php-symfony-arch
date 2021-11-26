<?php

namespace App\Modules\Security\Persistence\Doctrine\Repository;

use App\Infrastructure\Pagination\Page;
use App\Modules\Security\Domain\Dto\UserPostHeaderDto;
use App\Modules\Security\Domain\Repository\UserPostHeadersFindingRepositoryInterface;
use App\Modules\Security\Persistence\Doctrine\Entity\UserPostHeader;
use Symfony\Component\Uid\Ulid;

class DoctrineUserPostHeadersFindingRepository extends DoctrineSecurityRepository implements UserPostHeadersFindingRepositoryInterface
{

    /**
     * @return array<UserPostHeaderDto>
     */
   public function findPostHeaders(): array
    {
        $headerClass = UserPostHeader::class;
        $dtoClass = UserPostHeaderDto::class;
        $query = $this->getEntityManager()->createQuery(
            "select new $dtoClass(p.id, p.title, p.summary, p.tags, u.id, u.email, p.createdAt, p.version, p.commentsCount) 
                from $headerClass p join p.user u order by p.createdAt asc"
        );
        return $query->getArrayResult();
    }

    /**
     * @return Page<UserPostHeaderDto>
     */
    public function findPostsByUserId(Ulid $userId, int $pageNo): Page
    {
        $headerClass = UserPostHeader::class;
        $dtoClass = UserPostHeaderDto::class;
        $query = $this->getEntityManager()->createQuery(
            "select new $dtoClass(p.id, p.title, p.summary, p.tags, u.id, u.email, p.createdAt, p.version, p.commentsCount) 
                from $headerClass p join p.user u
                where u.id = :userId
                order by p.id desc"
        );
        $data = $query
            ->setParameter("userId", $userId, "ulid")
            ->setFirstResult(($pageNo - 1) * self::PAGE_SIZE)
            ->setMaxResults(self::PAGE_SIZE)
            ->getArrayResult();
        return new Page(
            $data,
            $this->getCount($userId),
            $pageNo,
            self::PAGE_SIZE
        );
    }

    /**
     * @param $userId
     * @return int
     */
    private function getCount($userId): int
    {
        $postClass = UserPostHeader::class;
        $query = $this->getEntityManager()->createQuery(
            "select count(p.id) as count from $postClass p join p.user u where u.id = :userId"
        );
        return $query->setParameter("userId", $userId, "ulid")->getResult()[0]["count"];
    }
}