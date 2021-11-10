<?php

namespace App\Modules\Comments\Persistence\Doctrine\Repository;

use App\Infrastructure\Doctrine\Repository\DoctrineRepository;

class DoctrineCommentsRepository extends DoctrineRepository
{

    /**
     * @return string
     */
    protected function getManagerName(): string
    {
        return "comments";
    }
}