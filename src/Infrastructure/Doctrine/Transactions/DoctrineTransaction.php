<?php

namespace App\Infrastructure\Doctrine\Transactions;

use App\Infrastructure\Transactions\TransactionInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineTransaction implements TransactionInterface
{
    private ArrayCollection $afterCommit;
    private ArrayCollection $afterRollback;

    /**
     * @param EntityManagerInterface $entityManager
     * @param mixed $func
     */
    public function __construct(
        private EntityManagerInterface $entityManager,
        private mixed                  $func
    )
    {
        $this->afterCommit = new ArrayCollection();
        $this->afterRollback = new ArrayCollection();
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
            foreach ($this->afterCommit->toArray() as $rollbackFn) {
                $rollbackFn($result);
            }
            $this->cleanup();
            return $result;
        } catch (\Exception $e) {
            foreach ($this->afterRollback->toArray() as $rollbackFn) {
                $rollbackFn();
            }
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
        $this->afterCommit->add($func);
        return $this;
    }

    /**
     * @param $func
     * @return $this
     */
    public function afterRollback($func): self
    {
        $this->afterRollback->add($func);
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