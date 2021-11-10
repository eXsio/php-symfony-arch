<?php

namespace App\Infrastructure\Transactions;

/**
 * This factory creates a new instance of TransactionInterface
 * with the handler that contains the Logic that should be wrapped in Transaction.
 */
interface TransactionFactoryInterface
{
    /**
     * @param $func
     * @return TransactionInterface
     */
    function createTransaction($func): TransactionInterface;

}