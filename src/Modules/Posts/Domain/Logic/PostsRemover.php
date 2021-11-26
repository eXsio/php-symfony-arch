<?php

namespace App\Modules\Posts\Domain\Logic;

use App\Infrastructure\Events\Api\ApplicationEventPublisherInterface;
use App\Modules\Posts\Api\Command\DeletePostCommand;
use App\Modules\Posts\Domain\Dto\DeleteExistingPostDto;
use App\Modules\Posts\Domain\Event\Outbound\PostDeletedOEvent;
use App\Modules\Posts\Domain\Repository\PostsDeletionRepositoryInterface;
use App\Modules\Posts\Domain\Transactions\PostTransactionFactoryInterface;

trait PostsRemover
{
    /**
     * @param ApplicationEventPublisherInterface $eventPublisher
     * @param PostsDeletionRepositoryInterface $deletionRepository
     * @param PostTransactionFactoryInterface $transactionFactory
     * @param PostsValidator $validator
     */
    public function __construct(
        private ApplicationEventPublisherInterface $eventPublisher,
        private PostsDeletionRepositoryInterface   $deletionRepository,
        private PostTransactionFactoryInterface    $transactionFactory,
        private PostsValidator                     $validator
    )
    {
    }

    /**
     * @param DeletePostCommand $command
     */
   public function deletePost(DeletePostCommand $command): void
    {
        $this->validator->preDelete($command);
        $deletedPost = $this->fromDeleteCommand($command);
        $this->transactionFactory
            ->createTransaction(function () use ($deletedPost) {
                $this->deletionRepository->deletePost($deletedPost);
            })
            ->afterCommit(function () use ($deletedPost) {
                $this->publisher->publish(new PostDeletedOEvent($deletedPost));
            })
            ->execute();
    }

    /**
     * @param DeletePostCommand $command
     * @return DeleteExistingPostDto
     */
    private function fromDeleteCommand(DeletePostCommand $command): DeleteExistingPostDto
    {
        return new DeleteExistingPostDto($command->getId());
    }
}