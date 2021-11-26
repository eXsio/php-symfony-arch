<?php

namespace App\Modules\Comments\Domain\Logic;

use App\Modules\Comments\Api\Command\CreateCommentCommand;
use App\Modules\Comments\Domain\Repository\CommentsFindingRepositoryInterface;
use App\Modules\Comments\Domain\Repository\CommentsPostHeadersFindingRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Uid\Ulid;

class CommentsValidator
{

    /**
     * @param CommentsPostHeadersFindingRepositoryInterface $postHeadersFindingRepository
     * @param CommentsFindingRepositoryInterface $commentsFindingRepository
     */
    public function __construct(
        private CommentsPostHeadersFindingRepositoryInterface $postHeadersFindingRepository,
        private CommentsFindingRepositoryInterface            $commentsFindingRepository
    )
    {
    }

    /**
     * @param CreateCommentCommand $command
     */
    public function preCreate(CreateCommentCommand $command): void
    {
        $this->validatePostExists($command->getPostId());
        if ($command->getParentId() != null) {
            $this->validateCommentExists($command->getParentId());
        }
    }

    /**
     * @param Ulid $postId
     */
    private function validatePostExists(Ulid $postId): void
    {
        if(!$this->postHeadersFindingRepository->postExists($postId)) {
            throw new BadRequestHttpException("Requested Post doesn't exist");
        }
    }

    /**
     * @param Ulid $commentId
     */
    private function validateCommentExists(Ulid $commentId): void
    {
        if(!$this->commentsFindingRepository->commentExists($commentId)) {
            throw new BadRequestHttpException("Requested Parent Comment doesn't exist");
        }
    }
}