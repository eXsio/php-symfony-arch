<?php

namespace App\Modules\Tags\Domain\Logic;

use App\Infrastructure\Pagination\Page;
use App\Modules\Tags\Api\Query\FindPostsByTagQuery;
use App\Modules\Tags\Api\Query\FindTagsPostHeadersQuery;
use App\Modules\Tags\Api\Query\Response\FindPostsByTagQueryResponse;
use App\Modules\Tags\Api\Query\Response\FindTagsPostHeadersQueryResponse;
use App\Modules\Tags\Domain\Repository\TagsPostHeadersFindingRepositoryInterface;
use DusanKasan\Knapsack\Collection;

trait PostHeadersFinder
{
    /**
     * @param TagsPostHeadersFindingRepositoryInterface $headersFindingRepository
     */
    public function __construct(
        private TagsPostHeadersFindingRepositoryInterface $headersFindingRepository
    )
    {
    }

    /**
     * @param FindTagsPostHeadersQuery $query
     * @return array<FindTagsPostHeadersQueryResponse>
     */
    public function findPostHeaders(FindTagsPostHeadersQuery $query): array
    {
        return Collection::from($this->headersFindingRepository->findPostHeaders())
            ->map(function ($header) {
                return new FindTagsPostHeadersQueryResponse(
                    $header->getId(),
                    $header->getTitle(),
                    $header->getSummary(),
                    $header->getCreatedById(),
                    $header->getCreatedByName(),
                    $header->getCreatedAt(),
                    $header->getVersion(),
                    $header->getCommentsCount()
                );
            })
            ->toArray();
    }

    /**
     * @param FindPostsByTagQuery $query
     * @return Page<FindPostsByTagQueryResponse>
     */
    public function findPostsByTag(FindPostsByTagQuery $query): Page
    {
        $page = $this->headersFindingRepository->findPostHeadersByTag($query->getTag(), $query->getPageNo());
        $data = Collection::from($page->getData())
            ->map(function ($header) {
                return new FindPostsByTagQueryResponse(
                    $header->getId(),
                    $header->getTitle(),
                    $header->getSummary(),
                    $header->getCreatedById(),
                    $header->getCreatedByName(),
                    $header->getCreatedAt(),
                    $header->getVersion(),
                    $header->getCommentsCount(),
                    $header->getTags()
                );
            })
            ->toArray();
        return new Page($data, $page->getCount(), $page->getPageNo(), $page->getPageSize());
    }
}