<?php

namespace App\Modules\Comments\Domain\Logic;

use App\Infrastructure\Events\Api\ApplicationEventPublisherInterface;
use App\Modules\Comments\Api\Command\CreateCommentCommand;
use App\Modules\Comments\Api\Command\Response\CreateCommentCommandResponse;
use App\Modules\Comments\Domain\Dto\CommentDto;
use App\Modules\Comments\Domain\Dto\CreateNewCommentDto;
use App\Modules\Comments\Domain\Event\Outbound\CommentCreatedOEvent;
use App\Modules\Comments\Domain\Event\Outbound\CommentsCountUpdatedOEvent;
use App\Modules\Comments\Domain\Repository\CommentsCreationRepositoryInterface;
use App\Modules\Comments\Domain\Repository\CommentsFindingRepositoryInterface;
use App\Modules\Comments\Domain\Transactions\CommentsTransactionFactoryInterface;

trait CommentsCreator
{

    /**
     * @param CommentsCreationRepositoryInterface $commentsCreationRepository
     * @param CommentsFindingRepositoryInterface $commentsFindingRepository
     * @param CommentsTransactionFactoryInterface $transactionFactory
     * @param ApplicationEventPublisherInterface $eventPublisher
     * @param CommentsValidator $validator
     */
    public function __construct(
        private CommentsCreationRepositoryInterface $commentsCreationRepository,
        private CommentsFindingRepositoryInterface  $commentsFindingRepository,
        private CommentsTransactionFactoryInterface $transactionFactory,
        private ApplicationEventPublisherInterface  $eventPublisher,
        private CommentsValidator                   $validator
    )
    {
    }

    /**
     * @param CreateCommentCommand $command
     * @return CreateCommentCommandResponse
     */
    public function createComment(CreateCommentCommand $command): CreateCommentCommandResponse
    {
        $this->validator->preCreate($command);
        $newComment = $this->fromCreateCommand($command);
        $id = $this->transactionFactory
            ->createTransaction(function () use ($newComment) {
                return $this->commentsCreationRepository->createComment($newComment);
            })
            ->afterCommit(function ($id) use ($newComment, $command) {
                $this->eventPublisher->publish(new CommentCreatedOEvent($command->getPostId(),
                        new CommentDto($id,
                            $newComment->getAuthor(),
                            $newComment->getBody(),
                            $newComment->getParentId(),
                            $newComment->getCreatedAt()
                        )
                    )
                );
                $commentsCount = $this->commentsFindingRepository->getCommentsCount($newComment->getPostId());
                $this->eventPublisher->publish(new CommentsCountUpdatedOEvent($newComment->getPostId(), $commentsCount));
            })
            ->execute();
        return new CreateCommentCommandResponse($id);
    }

    /**
     * @param CreateCommentCommand $command
     * @return CreateNewCommentDto
     */
    private function fromCreateCommand(CreateCommentCommand $command): CreateNewCommentDto
    {
        return new CreateNewCommentDto(
            $command->getPostId(),
            $command->getAuthor(),
            $command->getBody(),
            $command->getParentId(),
            new \DateTime()
        );
    }
}