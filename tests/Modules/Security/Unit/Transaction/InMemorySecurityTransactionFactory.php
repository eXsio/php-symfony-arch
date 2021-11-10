<?php

namespace App\Tests\Modules\Security\Unit\Transaction;

use App\Modules\Security\Domain\Transactions\SecurityTransactionFactoryInterface;
use App\Tests\TestUtils\Transaction\InMemoryTransactionFactory;

class InMemorySecurityTransactionFactory extends InMemoryTransactionFactory implements SecurityTransactionFactoryInterface
{

}