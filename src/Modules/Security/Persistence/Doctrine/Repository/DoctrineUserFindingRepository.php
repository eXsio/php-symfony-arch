<?php

namespace App\Modules\Security\Persistence\Doctrine\Repository;

use App\Modules\Security\Domain\Repository\UserFindingRepositoryInterface;
use App\Modules\Security\Persistence\Doctrine\Entity\User;

class DoctrineUserFindingRepository extends DoctrineSecurityRepository implements UserFindingRepositoryInterface
{

    public function exists(string $login): bool
    {
        $entityClass = User::class;
        return $this->getEntityManager()
                ->createQuery("select count(u.id) as cnt from $entityClass u 
                where u.email = :login")
                ->setParameter("login", $login)
                ->getSingleResult()["cnt"] > 0;
    }
}