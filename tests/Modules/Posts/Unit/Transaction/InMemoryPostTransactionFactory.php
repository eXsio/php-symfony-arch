<?php

namespace App\Tests\Modules\Posts\Unit\Transaction;

use App\Modules\Posts\Domain\Transactions\PostTransactionFactoryInterface;
use App\Tests\TestUtils\Transaction\InMemoryTransactionFactory;

class InMemoryPostTransactionFactory extends InMemoryTransactionFactory implements PostTransactionFactoryInterface
{

}