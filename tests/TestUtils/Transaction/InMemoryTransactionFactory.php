<?php

namespace App\Tests\TestUtils\Transaction;

use App\Infrastructure\Transactions\TransactionFactoryInterface;
use App\Infrastructure\Transactions\TransactionInterface;

class InMemoryTransactionFactory implements TransactionFactoryInterface
{

    function createTransaction($func): TransactionInterface
    {
        return new InMemoryTransaction($func);
    }
}