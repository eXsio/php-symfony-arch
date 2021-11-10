<?php

namespace App\Modules\Comments\Domain\Logic;

use App\Infrastructure\Pagination\Page;
use App\Modules\Comments\Api\Query\FindCommentsByPostIdQuery;
use App\Modules\Comments\Api\Query\FindLatestCommentsQuery;
use App\Modules\Comments\Api\Query\Response\FindCommentsByPostIdQueryResponse;
use App\Modules\Comments\Api\Query\Response\FindLatestCommentsQueryResponse;
use App\Modules\Comments\Domain\Repository\CommentsFindingRepositoryInterface;
use DusanKasan\Knapsack\Collection;

trait CommentsFinder
{
    /**
     * @param CommentsFindingRepositoryInterface $commentsFindingRepository
     */
    public function __construct(
        private CommentsFindingRepositoryInterface $commentsFindingRepository
    )
    {
    }


    /**
     * @param FindCommentsByPostIdQuery $query
     * @return array<FindCommentsByPostIdQueryResponse>
     */
    public function findCommentsForPost(FindCommentsByPostIdQuery $query): array
    {
        return Collection::from(
            $this->commentsFindingRepository->findCommentsByPostId($query->getPostId())
        )->map(function ($comment) {
            return new FindCommentsByPostIdQueryResponse(
                $comment->getId(),
                $comment->getAuthor(),
                $comment->getBody(),
                $comment->getParentId(),
                $comment->getCreatedAt(),
            );
        })
            ->toArray();
    }

    /**
     * @param FindLatestCommentsQuery $query
     * @return Page<FindLatestCommentsQueryResponse>
     */
    public function findLatestComments(FindLatestCommentsQuery $query): Page
    {
        $result = $this->commentsFindingRepository->findLatestComments($query->getPageNo());
        $data = Collection::from(
            $result->getData()
        )->map(function ($comment) {
            return new FindLatestCommentsQueryResponse(
                $comment->getId(),
                $comment->getAuthor(),
                $comment->getBody(),
                $comment->getParentId(),
                $comment->getCreatedAt(),
                $comment->getPostId(),
                $comment->getPostTitle(),
                $comment->getPostSummary(),
                $comment->getPostCommentsCount(),
                $comment->getPostTags()
            );
        })
            ->toArray();

        return new Page($data, $result->getCount(), $result->getPageNo(), $result->getPageSize());
    }
}