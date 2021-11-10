<?php

namespace App\Modules\Tags\Domain\Logic;

use App\Modules\Tags\Domain\Repository\TagsCommentsEventHandlingRepositoryInterface;
use App\Modules\Tags\Api\Event\Inbound\CommentsCountUpdatedTagsIEvent;
use App\Modules\Tags\Domain\Dto\UpdatePostsCommentsCountDto;
use App\Modules\Tags\Domain\Transactions\TagsTransactionFactoryInterface;

trait CommentsEventsHandler
{

    /**
     * @param TagsTransactionFactoryInterface $transactionFactory
     * @param TagsCommentsEventHandlingRepositoryInterface $commentsEventHandlingRepository
     */
    public function __construct(
        private TagsTransactionFactoryInterface              $transactionFactory,
        private TagsCommentsEventHandlingRepositoryInterface $commentsEventHandlingRepository
    )
    {
    }

    /**
     * @param CommentsCountUpdatedTagsIEvent $event
     */
    function onCommentsCountUpdated(CommentsCountUpdatedTagsIEvent $event): void
    {
        $this->transactionFactory
            ->createTransaction(function () use ($event) {
                $this->commentsEventHandlingRepository->updatePostCommentsCount(
                    new UpdatePostsCommentsCountDto($event->getPostId(), $event->getCommentsCount())
                );
            })
            ->execute();
    }
}