<?php

namespace App\Modules\Security\Persistence\Doctrine\Transactions;

use App\Infrastructure\Doctrine\Transactions\DoctrineTransactionFactory;
use App\Modules\Security\Domain\Transactions\SecurityTransactionFactoryInterface;
use Doctrine\Persistence\ManagerRegistry;

class DoctrineSecurityTransactionFactory extends DoctrineTransactionFactory implements SecurityTransactionFactoryInterface
{
    public function __construct(
        ManagerRegistry $managerRegistry
    )
    {
        parent::__construct($managerRegistry);
    }


    function getManagerName(): string
    {
        return "security";
    }
}