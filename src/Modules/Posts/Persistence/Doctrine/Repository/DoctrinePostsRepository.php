<?php

namespace App\Modules\Posts\Persistence\Doctrine\Repository;

use App\Infrastructure\Doctrine\Repository\DoctrineRepository;

class DoctrinePostsRepository extends DoctrineRepository
{

    /**
     * @return string
     */
    protected function getManagerName(): string
    {
        return "posts";
    }
}