<?php

namespace App\Infrastructure\Transactions;

/**
 * This is a convenience Utility that can Wrap a piece of Logic into a Transaction
 * and execute the additional Logic after the Transaction was either rolled back or committed.
 */
interface TransactionInterface
{
    function afterCommit($func): self;

    function afterRollback($func): self;

    function execute(): mixed;
}