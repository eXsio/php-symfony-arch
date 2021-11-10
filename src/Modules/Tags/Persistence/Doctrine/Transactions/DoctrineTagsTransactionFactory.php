<?php

namespace App\Modules\Tags\Persistence\Doctrine\Transactions;

use App\Infrastructure\Doctrine\Transactions\DoctrineTransactionFactory;
use App\Modules\Tags\Domain\Transactions\TagsTransactionFactoryInterface;
use Doctrine\Persistence\ManagerRegistry;

class DoctrineTagsTransactionFactory extends DoctrineTransactionFactory implements TagsTransactionFactoryInterface
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
    function getManagerName(): string
    {
        return "tags";
    }
}