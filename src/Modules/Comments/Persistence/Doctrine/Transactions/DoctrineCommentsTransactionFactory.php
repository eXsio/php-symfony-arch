<?php

namespace App\Modules\Comments\Persistence\Doctrine\Transactions;

use App\Infrastructure\Doctrine\Transactions\DoctrineTransactionFactory;
use App\Modules\Comments\Domain\Transactions\CommentsTransactionFactoryInterface;
use Doctrine\Persistence\ManagerRegistry;

class DoctrineCommentsTransactionFactory extends DoctrineTransactionFactory implements CommentsTransactionFactoryInterface
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
        return "comments";
    }
}