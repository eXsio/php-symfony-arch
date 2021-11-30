<?php

namespace App\Tests\TestUtils\Transaction;

use App\Infrastructure\Transactions\TransactionInterface;
use Doctrine\Common\Collections\ArrayCollection;
use DusanKasan\Knapsack\Collection;

class InMemoryTransaction implements TransactionInterface
{

    private Collection $afterCommit;
    private Collection $afterRollback;

    /**
     * @param mixed $func
     */
    public function __construct(
        private mixed $func
    )
    {
        $this->afterCommit = Collection::from([]);
        $this->afterRollback = Collection::from([]);
    }

    /**
     * @return mixed
     * @throws \Exception
     */
   public function execute(): mixed
    {
        try {
            $handler = $this->func;
            $result = $handler();
            $this->afterCommit
                ->each(function ($successFn) use ($result) {
                    $successFn($result);
                })
                ->realize();
            return $result;
        } catch (\Exception $e) {
            $this->afterRollback
                ->each(function ($rollbackFn) {
                    $rollbackFn();
                })
                ->realize();
            throw $e;
        }
    }


    /**
     * @param $func
     * @return $this
     */
   public function afterCommit($func): TransactionInterface
    {
        $this->afterCommit = $this->afterCommit->append($func);
        return $this;
    }

    /**
     * @param $func
     * @return $this
     */
   public function afterRollback($func): TransactionInterface
    {
        $this->afterRollback = $this->afterRollback->append($func);
        return $this;
    }
}