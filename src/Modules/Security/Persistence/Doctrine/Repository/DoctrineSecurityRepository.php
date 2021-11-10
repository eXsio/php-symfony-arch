<?php

namespace App\Modules\Security\Persistence\Doctrine\Repository;

use App\Infrastructure\Doctrine\Repository\DoctrineRepository;

class DoctrineSecurityRepository extends DoctrineRepository {

    /**
     * @return string
     */
    protected function getManagerName(): string
    {
        return "security";
    }
}
{

}