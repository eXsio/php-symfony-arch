<?php

namespace App\Modules\Security\Persistence\Doctrine\Repository;

use App\Modules\Security\Domain\Repository\UserFindingRepositoryInterface;
use App\Modules\Security\Persistence\Doctrine\Entity\User;

class DoctrineUserFindingRepository extends DoctrineSecurityRepository implements UserFindingRepositoryInterface
{

    /**
     * @param string $login
     * @return bool
     */
    public function exists(string $login): bool
    {
        $entityClass = User::class;
        return $this->getEntityManager()
                ->createQuery("select count(u.id) as count from $entityClass u 
                where u.email = :login")
                ->setParameter("login", $login)
                ->getResult()[0]["count"] > 0;
    }
}