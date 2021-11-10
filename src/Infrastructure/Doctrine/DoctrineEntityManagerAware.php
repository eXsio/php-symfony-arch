<?php

namespace App\Infrastructure\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * This is a convenience class that enables to easily work with
 * multiple Entity Managers.
 */
abstract class DoctrineEntityManagerAware
{
    private EntityManagerInterface $entityManager;

    public function __construct(
        ManagerRegistry $managerRegistry
    )
    {
        $this->entityManager = $this->getManager($managerRegistry);
    }

    /**
     * @param ManagerRegistry $managerRegistry
     * @return EntityManagerInterface
     */
    private function getManager(ManagerRegistry $managerRegistry): EntityManagerInterface
    {
        $name = $this->getManagerName();
        $manager = $managerRegistry->getManager($name);
        if ($manager instanceof EntityManagerInterface) {
            return $manager;
        }
        throw new \RuntimeException("No Entity Manager with name '$name'");
    }

    /**
     * @return EntityManagerInterface
     */
    protected function getEntityManager(): EntityManagerInterface
    {
        return $this->entityManager;
    }


    abstract protected function getManagerName(): string;
}