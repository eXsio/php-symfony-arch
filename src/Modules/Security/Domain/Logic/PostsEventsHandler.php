<?php

namespace App\Modules\Security\Domain\Logic;

use App\Modules\Security\Api\Event\Inbound\PostCreatedSecurityIEvent;
use App\Modules\Security\Api\Event\Inbound\PostDeletedSecurityIEvent;
use App\Modules\Security\Api\Event\Inbound\PostUpdatedSecurityIEvent;
use App\Modules\Security\Domain\Dto\CreateNewUserPostHeaderDto;
use App\Modules\Security\Domain\Dto\DeleteExistingUserPostHeaderDto;
use App\Modules\Security\Domain\Dto\UpdateExistingUserPostHeaderDto;
use App\Modules\Security\Domain\Repository\SecurityPostEventsHandlingRepositoryInterface;
use App\Modules\Security\Domain\Transactions\SecurityTransactionFactoryInterface;

trait PostsEventsHandler
{
    /**
     * @param SecurityTransactionFactoryInterface $transactionFactory
     * @param SecurityPostEventsHandlingRepositoryInterface $postEventsSecurityRepository
     */
    public function __construct(
        private SecurityTransactionFactoryInterface   $transactionFactory,
        private SecurityPostEventsHandlingRepositoryInterface $postEventsSecurityRepository
    )
    {
    }

    /**
     * @param PostCreatedSecurityIEvent $event
     */
    public function onPostCreated(PostCreatedSecurityIEvent $event): void
    {
        $this->transactionFactory->createTransaction(function () use ($event) {
            $this->postEventsSecurityRepository->createPostHeader(
                new CreateNewUserPostHeaderDto(
                    $event->getId(),
                    $event->getTitle(),
                    $event->getSummary(),
                    $event->getTags(),
                    $event->getCreatedById(),
                    $event->getCreatedByName(),
                    $event->getCreatedAt()
                )
            );
        })->execute();

    }

    /**
     * @param PostUpdatedSecurityIEvent $event
     */
    public function onPostUpdated(PostUpdatedSecurityIEvent $event): void
    {
        $this->transactionFactory->createTransaction(function () use ($event) {
            $this->postEventsSecurityRepository->updatePostHeader(
                new UpdateExistingUserPostHeaderDto(
                    $event->getId(),
                    $event->getTitle(),
                    $event->getSummary(),
                    $event->getTags(),
                    $event->getLastVersion()
                )
            );
        })->execute();

    }

    /**
     * @param PostDeletedSecurityIEvent $event
     */
    public function onPostDeleted(PostDeletedSecurityIEvent $event): void
    {
        $this->transactionFactory->createTransaction(function () use ($event) {
            $this->postEventsSecurityRepository->deletePostHeader(
                new DeleteExistingUserPostHeaderDto($event->getId())
            );
        })->execute();

    }
}