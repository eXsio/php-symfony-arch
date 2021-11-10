<?php

namespace App\Modules\Posts\Domain\Logic;

use App\Modules\Posts\Api\Event\Inbound\CommentCreatedPostsIEvent;
use App\Modules\Posts\Api\Event\Inbound\CommentsBaselinedPostsIEvent;
use App\Modules\Posts\Domain\Dto\UpdatePostCommentsDto;
use App\Modules\Posts\Domain\Repository\PostsCommentsEventHandlingRepositoryInterface;
use App\Modules\Posts\Domain\Transactions\PostTransactionFactoryInterface;

trait CommentsEventsHandler
{

    /**
     * @param PostTransactionFactoryInterface $transactionFactory
     * @param PostsCommentsEventHandlingRepositoryInterface $commentsEventHandlingRepository
     * @param PostsValidator $postValidator
     */
    public function __construct(
        private PostTransactionFactoryInterface               $transactionFactory,
        private PostsCommentsEventHandlingRepositoryInterface $commentsEventHandlingRepository,
        private PostsValidator                                $postValidator
    )
    {
    }

    /**
     * @param CommentCreatedPostsIEvent $event
     */
    function onCommentCreated(CommentCreatedPostsIEvent $event): void
    {
        $this->postValidator->preHandleCommentCreated($event);
        $this->transactionFactory
            ->createTransaction(function () use ($event) {
                $this->commentsEventHandlingRepository->updateAllComments(
                    new UpdatePostCommentsDto(
                        $event->getPostId(),
                        $event->getComments()
                    )
                );
            })
            ->execute();
    }

    function onCommentsBaselined(CommentsBaselinedPostsIEvent $event): void
    {
        $this->postValidator->preHandleCommentsBaselined($event);
        $this->transactionFactory
            ->createTransaction(function () use ($event) {
                $this->commentsEventHandlingRepository->updateAllComments(
                    new UpdatePostCommentsDto(
                        $event->getPostId(),
                        $event->getComments()
                    ), false
                );
            })
            ->execute();
    }
}