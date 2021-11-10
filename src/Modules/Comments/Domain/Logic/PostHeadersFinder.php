<?php

namespace App\Modules\Comments\Domain\Logic;

use App\Modules\Comments\Api\Query\FindCommentsPostHeadersQuery;
use App\Modules\Comments\Api\Query\Response\FindCommentsPostHeadersQueryResponse;
use App\Modules\Comments\Domain\Repository\CommentsPostHeadersFindingRepositoryInterface;
use DusanKasan\Knapsack\Collection;

trait PostHeadersFinder
{
    /**
     * @param CommentsPostHeadersFindingRepositoryInterface $headersFindingRepository
     */
    public function __construct(
        private CommentsPostHeadersFindingRepositoryInterface $headersFindingRepository
    )
    {
    }

    /**
     * @param FindCommentsPostHeadersQuery $query
     * @return array<FindCommentsPostHeadersQueryResponse>
     */
    public function findPostHeaders(FindCommentsPostHeadersQuery $query): array
    {
        return Collection::from($this->headersFindingRepository->findPostHeaders())
            ->map(function ($header) {
                return new FindCommentsPostHeadersQueryResponse(
                    $header->getId(),
                    $header->getTitle(),
                    $header->getSummary(),
                    $header->getTags(),
                    $header->getCreatedById(),
                    $header->getCreatedByName(),
                    $header->getCreatedAt(),
                    $header->getVersion()
                );
            })
            ->toArray();
    }
}