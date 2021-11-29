<?php

namespace App\Modules\Comments\Domain\Logic;

use App\Modules\Comments\Api\Event\Inbound\PostCreatedCommentsIEvent;
use App\Modules\Comments\Api\Event\Inbound\PostDeletedCommentsIEvent;
use App\Modules\Comments\Api\Event\Inbound\PostUpdatedCommentsIEvent;
use App\Modules\Comments\Domain\Dto\CreateNewCommentsPostHeaderDto;
use App\Modules\Comments\Domain\Dto\DeleteExistingCommentsPostHeaderDto;
use App\Modules\Comments\Domain\Dto\UpdateExistingCommentsPostHeaderDto;
use App\Modules\Comments\Domain\Repository\CommentsDeletionRepositoryInterface;
use App\Modules\Comments\Domain\Repository\CommentsPostsEventsHandlingRepositoryInterface;
use App\Modules\Comments\Domain\Transactions\CommentsTransactionFactoryInterface;

trait PostEventsHandler
{
    /**
     * @param CommentsTransactionFactoryInterface $transactionFactory
     * @param CommentsPostsEventsHandlingRepositoryInterface $postEventsCommentsRepository
     * @param CommentsDeletionRepositoryInterface $commentsDeletionRepository
     */
    public function __construct(
        private CommentsTransactionFactoryInterface            $transactionFactory,
        private CommentsPostsEventsHandlingRepositoryInterface $postEventsCommentsRepository,
        private CommentsDeletionRepositoryInterface            $commentsDeletionRepository
    )
    {
    }

    /**
     * @param PostCreatedCommentsIEvent $event
     */
    public function onPostCreated(PostCreatedCommentsIEvent $event): void
    {
        $this->transactionFactory->createTransaction(function () use ($event) {
            $this->postEventsCommentsRepository->createPostHeader(
                new CreateNewCommentsPostHeaderDto(
                    $event->getId(),
                    $event->getTitle(),
                    $event->getTags()
                )
            );
        })->execute();

    }

    /**
     * @param PostUpdatedCommentsIEvent $event
     */
    public function onPostUpdated(PostUpdatedCommentsIEvent $event): void
    {
        $this->transactionFactory->createTransaction(function () use ($event) {
            $this->postEventsCommentsRepository->updatePostHeader(
                new UpdateExistingCommentsPostHeaderDto(
                    $event->getId(),
                    $event->getTitle(),
                    $event->getTags(),
                    $event->getLastVersion()
                )
            );
        })->execute();

    }

    /**
     * @param PostDeletedCommentsIEvent $event
     */
    public function onPostDeleted(PostDeletedCommentsIEvent $event): void
    {
        $this->transactionFactory->createTransaction(function () use ($event) {
            $this->commentsDeletionRepository->deleteCommentsForPost($event->getId());
            $this->postEventsCommentsRepository->deletePostHeader(
                new DeleteExistingCommentsPostHeaderDto($event->getId())
            );
        })->execute();

    }
}