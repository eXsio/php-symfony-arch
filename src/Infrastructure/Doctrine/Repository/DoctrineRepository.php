<?php

namespace App\Infrastructure\Doctrine\Repository;

use App\Infrastructure\Doctrine\DoctrineEntityManagerAware;
use Doctrine\Persistence\ManagerRegistry;

abstract class DoctrineRepository extends DoctrineEntityManagerAware
{

    public function __construct(
        ManagerRegistry $managerRegistry
    )
    {
        parent::__construct($managerRegistry);
    }
}