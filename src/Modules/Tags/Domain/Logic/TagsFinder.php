<?php

namespace App\Modules\Tags\Domain\Logic;

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
     * @return array<FindTagsQueryResponse>
     */
    public function findTags(): array
    {
        return
            Collection::from($this->tagsFindingRepository->findTags())
                ->map(function ($tag) {
                    return new FindTagsQueryResponse($tag->getTag(), $tag->getPostsCount());
                })
                ->toArray();
    }
}