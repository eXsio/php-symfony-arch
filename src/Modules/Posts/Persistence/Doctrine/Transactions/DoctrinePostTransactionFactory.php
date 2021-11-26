<?php

namespace App\Modules\Posts\Persistence\Doctrine\Transactions;

use App\Infrastructure\Doctrine\Transactions\DoctrineTransactionFactory;
use App\Modules\Posts\Domain\Transactions\PostTransactionFactoryInterface;
use Doctrine\Persistence\ManagerRegistry;

class DoctrinePostTransactionFactory extends DoctrineTransactionFactory implements PostTransactionFactoryInterface
{
    /**
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(
        ManagerRegistry $managerRegistry
    )
    {
        parent::__construct($managerRegistry);
    }

    /**
     * @return string
     */
   public function getManagerName(): string
    {
        return "posts";
    }
}