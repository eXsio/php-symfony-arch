<?php

namespace App\Modules\Security\Domain\Logic;

use App\Modules\Security\Api\Event\Inbound\CommentsCountUpdatedSecurityIEvent;
use App\Modules\Security\Domain\Dto\UpdatePostsCommentsCountDto;
use App\Modules\Security\Domain\Repository\SecurityCommentsEventHandlingRepositoryInterface;
use App\Modules\Security\Domain\Transactions\SecurityTransactionFactoryInterface;

trait CommentsEventsHandler
{

    /**
     * @param SecurityTransactionFactoryInterface $transactionFactory
     * @param SecurityCommentsEventHandlingRepositoryInterface $commentsEventHandlingRepository
     */
    public function __construct(
        private SecurityTransactionFactoryInterface              $transactionFactory,
        private SecurityCommentsEventHandlingRepositoryInterface $commentsEventHandlingRepository
    )
    {
    }

    /**
     * @param CommentsCountUpdatedSecurityIEvent $event
     */
    function onCommentsCountUpdated(CommentsCountUpdatedSecurityIEvent $event): void
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