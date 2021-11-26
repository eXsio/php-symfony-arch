<?php

namespace App\Modules\Posts\Domain\Logic;

use App\Infrastructure\Events\Api\ApplicationEventPublisherInterface;
use App\Infrastructure\Utils\StringUtil;
use App\Modules\Posts\Api\Command\UpdatePostCommand;
use App\Modules\Posts\Domain\Dto\UpdateExistingPostDto;
use App\Modules\Posts\Domain\Event\Outbound\PostUpdatedOEvent;
use App\Modules\Posts\Domain\Repository\PostsUpdatingRepositoryInterface;
use App\Modules\Posts\Domain\Transactions\PostTransactionFactoryInterface;

trait PostUpdater
{

    /**
     * @param ApplicationEventPublisherInterface $eventPublisher
     * @param PostsUpdatingRepositoryInterface $updatingRepository
     * @param PostTransactionFactoryInterface $transactionFactory
     * @param PostsValidator $validator
     */
    public function __construct(
        private ApplicationEventPublisherInterface $eventPublisher,
        private PostsUpdatingRepositoryInterface   $updatingRepository,
        private PostTransactionFactoryInterface    $transactionFactory,
        private PostsValidator                     $validator
    )
    {
    }

    /**
     * @param UpdatePostCommand $command
     */
    public function updatePost(UpdatePostCommand $command): void
    {
        $post = $this->validator->preUpdate($command);
        $updatedPost = $this->fromUpdateCommand($command);
        $this->transactionFactory
            ->createTransaction(function () use ($updatedPost) {
                $this->updatingRepository->updatePost($updatedPost);
            })
            ->afterCommit(function () use ($post, $updatedPost) {
                $this->publisher->publish(new PostUpdatedOEvent($updatedPost, $post->getVersion()));
            })
            ->execute();

    }

    /**
     * @param UpdatePostCommand $command
     * @return UpdateExistingPostDto
     */
    private function fromUpdateCommand(UpdatePostCommand $command): UpdateExistingPostDto
    {
        return new UpdateExistingPostDto(
            $command->getId(),
            $command->getTitle(),
            $command->getBody(),
            StringUtil::getSummary($command->getBody()),
            $command->getTags(),
            new \DateTime()
        );
    }

}