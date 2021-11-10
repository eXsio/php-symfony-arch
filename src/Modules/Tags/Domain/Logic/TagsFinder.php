<?php

namespace App\Modules\Tags\Domain\Logic;

use App\Modules\Tags\Api\Query\FindTagsQuery;
use App\Modules\Tags\Api\Query\Response\FindTagsQueryResponse;
use App\Modules\Tags\Domain\Repository\TagsFindingRepositoryInterface;
use DusanKasan\Knapsack\Collection;

trait TagsFinder
{
    /**
     * @param TagsFindingRepositoryInterface $tagsFindingRepository
     */
    public function __construct(
        private TagsFindingRepositoryInterface $tagsFindingRepository
    )
    {
    }

    /**
     * @param FindTagsQuery $query
     * @return array<FindTagsQueryResponse>
     */
    public function findTags(FindTagsQuery $query): array
    {
        return
            Collection::from($this->tagsFindingRepository->findTags())
                ->map(function ($tag) {
                    return new FindTagsQueryResponse($tag->getTag(), $tag->getPostsCount());
                })
                ->toArray();
    }
}