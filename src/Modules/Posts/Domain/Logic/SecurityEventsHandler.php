<?php

namespace App\Modules\Posts\Domain\Logic;

use App\Modules\Posts\Api\Event\Inbound\UserRenamedPostsIEvent;
use App\Modules\Posts\Domain\Dto\UpdatedPostsUserNameDto;
use App\Modules\Posts\Domain\Repository\PostsSecurityEventsHandlingRepositoryInterface;
use App\Modules\Posts\Domain\Transactions\PostTransactionFactoryInterface;

trait SecurityEventsHandler
{
    public function __construct(
        private PostTransactionFactoryInterface                $transactionFactory,
        private PostsSecurityEventsHandlingRepositoryInterface $securityEventsHandlingRepository
    )
    {
    }


    /**
     * @param UserRenamedPostsIEvent $event
     */
    public function onUserRenamed(UserRenamedPostsIEvent $event): void
    {
        $this->transactionFactory
            ->createTransaction(function () use ($event) {
                $this->securityEventsHandlingRepository->updateUserName(
                    new UpdatedPostsUserNameDto(
                        $event->getOldLogin(),
                        $event->getNewLogin()
                    )
                );
            })
            ->execute();
    }
}