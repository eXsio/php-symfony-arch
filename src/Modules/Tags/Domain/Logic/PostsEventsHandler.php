<?php

namespace App\Modules\Tags\Domain\Logic;

use App\Modules\Tags\Api\Event\Inbound\PostBaselinedTagsIEvent;
use App\Modules\Tags\Api\Event\Inbound\PostCreatedTagsIEvent;
use App\Modules\Tags\Api\Event\Inbound\PostDeletedTagsIEvent;
use App\Modules\Tags\Api\Event\Inbound\PostUpdatedTagsIEvent;
use App\Modules\Tags\Domain\Dto\CreateNewTagsPostHeaderDto;
use App\Modules\Tags\Domain\Dto\DeleteExistingTagsPostHeaderDto;
use App\Modules\Tags\Domain\Dto\UpdateExistingTagsPostHeaderDto;
use App\Modules\Tags\Domain\Repository\TagsPostEventsHandlingRepositoryInterface;
use App\Modules\Tags\Domain\Repository\TagsPostHeadersFindingRepositoryInterface;
use App\Modules\Tags\Domain\Transactions\TagsTransactionFactoryInterface;

trait PostsEventsHandler
{
    /**
     * @param TagsTransactionFactoryInterface $transactionFactory
     * @param TagsPostEventsHandlingRepositoryInterface $postEventsTagsRepository
     * @param TagsUpdater $tagsUpdater
     */
    public function __construct(
        private TagsTransactionFactoryInterface   $transactionFactory,
        private TagsPostEventsHandlingRepositoryInterface $postEventsTagsRepository,
        private TagsPostHeadersFindingRepositoryInterface $postHeadersFindingRepository,
        private TagsUpdater $tagsUpdater
    )
    {
    }

    /**
     * @param PostBaselinedTagsIEvent $event
     */
    public function onPostBaselined(PostBaselinedTagsIEvent $event): void
    {
        $this->transactionFactory->createTransaction(function () use ($event) {
            if($this->postHeadersFindingRepository->postExists($event->getId())) {
                $this->postEventsTagsRepository->updatePostHeader(
                    new UpdateExistingTagsPostHeaderDto(
                        $event->getId(),
                        $event->getTitle(),
                        $event->getSummary(),
                        $event->getTags(),
                        $event->getVersion()
                    )
                );
            } else {
                $this->postEventsTagsRepository->createPostHeader(
                    new CreateNewTagsPostHeaderDto(
                        $event->getId(),
                        $event->getTitle(),
                        $event->getSummary(),
                        $event->getTags(),
                        $event->getCreatedById(),
                        $event->getCreatedByName(),
                        $event->getCreatedAt(),
                        $event->getVersion()
                    )
                );
            }


            $this->tagsUpdater->createUpdateTags($event->getId(), $event->getTags());
        })->execute();

    }

    /**
     * @param PostCreatedTagsIEvent $event
     */
    public function onPostCreated(PostCreatedTagsIEvent $event): void
    {
        $this->transactionFactory->createTransaction(function () use ($event) {
            $this->postEventsTagsRepository->createPostHeader(
                new CreateNewTagsPostHeaderDto(
                    $event->getId(),
                    $event->getTitle(),
                    $event->getSummary(),
                    $event->getTags(),
                    $event->getCreatedById(),
                    $event->getCreatedByName(),
                    $event->getCreatedAt()
                )
            );

            $this->tagsUpdater->createUpdateTags($event->getId(), $event->getTags());
        })->execute();

    }

    /**
     * @param PostUpdatedTagsIEvent $event
     */
    public function onPostUpdated(PostUpdatedTagsIEvent $event): void
    {
        $this->transactionFactory->createTransaction(function () use ($event) {
            $this->postEventsTagsRepository->updatePostHeader(
                new UpdateExistingTagsPostHeaderDto(
                    $event->getId(),
                    $event->getTitle(),
                    $event->getSummary(),
                    $event->getTags(),
                    $event->getLastVersion()
                )
            );
            $this->tagsUpdater->createUpdateTags($event->getId(), $event->getTags());
        })->execute();

    }

    /**
     * @param PostDeletedTagsIEvent $event
     */
    public function onPostDeleted(PostDeletedTagsIEvent $event): void
    {
        $this->transactionFactory->createTransaction(function () use ($event) {
            $this->postEventsTagsRepository->deletePostHeader(
                new DeleteExistingTagsPostHeaderDto($event->getId())
            );
            $this->tagsUpdater->deleteEmptyTags();
        })->execute();

    }
}