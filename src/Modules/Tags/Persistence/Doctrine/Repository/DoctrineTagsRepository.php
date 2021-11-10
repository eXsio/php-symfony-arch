<?php

namespace App\Modules\Tags\Persistence\Doctrine\Repository;

use App\Infrastructure\Doctrine\Repository\DoctrineRepository;

abstract class DoctrineTagsRepository extends DoctrineRepository
{

    /**
     * @return string
     */
    protected function getManagerName(): string
    {
        return "tags";
    }
}