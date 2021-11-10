<?php

namespace App\Modules\Comments\Domain\Logic;

use App\Infrastructure\Events\Api\ApplicationEventPublisherInterface;
use App\Modules\Comments\Api\Command\BaselineCommentsCommand;
use App\Modules\Comments\Domain\Event\Outbound\CommentsBaselinedOEvent;
use App\Modules\Comments\Domain\Event\Outbound\CommentsCountUpdatedOEvent;
use App\Modules\Comments\Domain\Repository\CommentsFindingRepositoryInterface;
use App\Modules\Comments\Domain\Repository\CommentsPostHeadersFindingRepositoryInterface;

trait CommentsBaseliner
{


    public function __construct(
        private ApplicationEventPublisherInterface            $eventPublisher,
        private CommentsFindingRepositoryInterface            $commentsFindingRepository,
        private CommentsPostHeadersFindingRepositoryInterface $postHeadersFindingRepository
    )
    {
    }

    public function baseline(BaselineCommentsCommand $command): void
    {
        foreach ($this->postHeadersFindingRepository->findPostHeaders($command->getFrom()) as $postHeader) {
            $comments = $this->commentsFindingRepository->findCommentsByPostId($postHeader->getId());
            $this->eventPublisher->publish(new CommentsBaselinedOEvent($postHeader->getId(), $comments));
            $this->eventPublisher->publish(new CommentsCountUpdatedOEvent($postHeader->getId(), count($comments)));
        }

    }
}