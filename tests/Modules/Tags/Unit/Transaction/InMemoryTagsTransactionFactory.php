<?php

namespace App\Tests\Modules\Tags\Unit\Transaction;

use App\Modules\Tags\Domain\Transactions\TagsTransactionFactoryInterface;
use App\Tests\TestUtils\Transaction\InMemoryTransactionFactory;

class InMemoryTagsTransactionFactory extends InMemoryTransactionFactory implements TagsTransactionFactoryInterface
{

}