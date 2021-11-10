<?php

namespace App\Tests\Modules\Comments\Unit\Transaction;

use App\Modules\Comments\Domain\Transactions\CommentsTransactionFactoryInterface;
use App\Tests\TestUtils\Transaction\InMemoryTransactionFactory;

class InMemoryCommentsTransactionFactory extends InMemoryTransactionFactory implements CommentsTransactionFactoryInterface
{

}