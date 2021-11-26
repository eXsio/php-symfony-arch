<?php

namespace App\Tests\TestUtils\Transaction;

use App\Infrastructure\Transactions\TransactionInterface;
use Doctrine\Common\Collections\ArrayCollection;

class InMemoryTransaction implements TransactionInterface
{

    private ArrayCollection $afterCommit;
    private ArrayCollection $afterRollback;

    /**
     * @param mixed $func
     */
    public function __construct(
        private mixed $func
    )
    {
        $this->afterCommit = new ArrayCollection();
        $this->afterRollback = new ArrayCollection();
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
            foreach ($this->afterCommit->toArray() as $rollbackFn) {
                $rollbackFn($result);
            }
            return $result;
        } catch (\Exception $e) {
            foreach ($this->afterRollback->toArray() as $rollbackFn) {
                $rollbackFn();
            }
            throw $e;
        }
    }


    /**
     * @param $func
     * @return $this
     */
   public function afterCommit($func): TransactionInterface
    {
        $this->afterCommit->add($func);
        return $this;
    }

    /**
     * @param $func
     * @return $this
     */
   public function afterRollback($func): TransactionInterface
    {
        $this->afterRollback->add($func);
        return $this;
    }
}