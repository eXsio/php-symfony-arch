<?php

namespace App\Modules\Comments\Domain\Logic;

use App\Modules\Comments\Api\Event\Inbound\UserRenamedCommentsIEvent;
use App\Modules\Comments\Domain\Dto\UpdatedCommentsPostHeadersUserNameDto;
use App\Modules\Comments\Domain\Repository\CommentsSecurityEventsHandlingRepositoryInterface;
use App\Modules\Comments\Domain\Transactions\CommentsTransactionFactoryInterface;

trait SecurityEventsHandler
{
    public function __construct(
        private CommentsTransactionFactoryInterface               $transactionFactory,
        private CommentsSecurityEventsHandlingRepositoryInterface $securityEventsHandlingRepository
    )
    {
    }


    /**
     * @param UserRenamedCommentsIEvent $event
     */
    public function onUserRenamed(UserRenamedCommentsIEvent $event): void
    {
        $this->transactionFactory
            ->createTransaction(function () use ($event) {
                $this->securityEventsHandlingRepository->updateUserName(
                    new UpdatedCommentsPostHeadersUserNameDto(
                        $event->getOldLogin(),
                        $event->getNewLogin()
                    )
                );
            })
            ->execute();
    }
}