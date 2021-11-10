<?php

namespace App\Modules\Tags\Domain\Logic;

use App\Modules\Tags\Api\Event\Inbound\UserRenamedTagsIEvent;
use App\Modules\Tags\Domain\Dto\UpdatedTagsPostHeadersUserNameDto;
use App\Modules\Tags\Domain\Repository\TagsSecurityEventsHandlingRepositoryInterface;
use App\Modules\Tags\Domain\Transactions\TagsTransactionFactoryInterface;

trait SecurityEventsHandler
{
    public function __construct(
        private TagsTransactionFactoryInterface               $transactionFactory,
        private TagsSecurityEventsHandlingRepositoryInterface $securityEventsHandlingRepository
    )
    {
    }


    /**
     * @param UserRenamedTagsIEvent $event
     */
    public function onUserRenamed(UserRenamedTagsIEvent $event): void
    {
        $this->transactionFactory
            ->createTransaction(function () use ($event) {
                $this->securityEventsHandlingRepository->updateUserName(
                    new UpdatedTagsPostHeadersUserNameDto(
                        $event->getOldLogin(),
                        $event->getNewLogin()
                    )
                );
            })
            ->execute();
    }
}