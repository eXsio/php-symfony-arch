<?php

namespace App\Infrastructure\Doctrine\Transactions;

use App\Infrastructure\Transactions\TransactionInterface;
use Doctrine\ORM\EntityManagerInterface;
use DusanKasan\Knapsack\Collection;

class DoctrineTransaction implements TransactionInterface
{
    private Collection $afterCommit;
    private Collection $afterRollback;

    /**
     * @param EntityManagerInterface $entityManager
     * @param mixed $func
     */
    public function __construct(
        private EntityManagerInterface $entityManager,
        private mixed                  $func
    )
    {
        $this->afterCommit = Collection::from([]);
        $this->afterRollback = Collection::from([]);
    }

    /**
     * After Transaction has been executed, the related Entity Manager
     * is cleaned and closed, so it cannot be re-used.
     *
     * @return mixed
     * @throws \Exception
     */
    public function execute(): mixed
    {
        try {
            $result = $this->entityManager->wrapInTransaction($this->func);
            $this->afterCommit
                ->each(function ($successFn) use ($result) {
                    $successFn($result);
                })
                ->realize();
            $this->cleanup();
            return $result;
        } catch (\Exception $e) {
            $this->afterRollback
                ->each(function ($rollbackFn) {
                    $rollbackFn();
                })
                ->realize();
            $this->cleanup();
            throw $e;
        }
    }

    /**
     * @param $func
     * @return $this
     */
    public function afterCommit($func): self
    {
        $this->afterCommit = $this->afterCommit->append($func);
        return $this;
    }

    /**
     * @param $func
     * @return $this
     */
    public function afterRollback($func): self
    {
        $this->afterRollback = $this->afterRollback->append($func);
        return $this;
    }

    private function cleanup(): void
    {
        if ($this->entityManager->isOpen()) {
            $this->entityManager->clear();
            $this->entityManager->close();
        }
    }
}