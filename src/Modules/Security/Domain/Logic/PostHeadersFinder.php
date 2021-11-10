<?php

namespace App\Modules\Security\Domain\Logic;

use App\Infrastructure\Pagination\Page;
use App\Modules\Security\Api\Query\FindPostsByUserIdQuery;
use App\Modules\Security\Api\Query\FindUserPostHeadersQuery;
use App\Modules\Security\Api\Query\Response\FindPostsByUserIdQueryResponse;
use App\Modules\Security\Api\Query\Response\FindUserPostHeadersQueryResponse;
use App\Modules\Security\Domain\Repository\UserPostHeadersFindingRepositoryInterface;
use DusanKasan\Knapsack\Collection;

trait PostHeadersFinder
{

    /**
     * @param UserPostHeadersFindingRepositoryInterface $headersFindingRepository
     */
    public function __construct(
        private UserPostHeadersFindingRepositoryInterface $headersFindingRepository
    )
    {
    }

    /**
     * @param FindUserPostHeadersQuery $query
     * @return array<FindUserPostHeadersQueryResponse>
     */
    public function findPostHeaders(FindUserPostHeadersQuery $query): array
    {
        return Collection::from($this->headersFindingRepository->findPostHeaders())
            ->map(function ($header) {
                return new FindUserPostHeadersQueryResponse(
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

    /**
     * @param FindPostsByUserIdQuery $query
     * @return Page<FindPostsByUserIdQueryResponse>
     */
    public function findPostsByUserId(FindPostsByUserIdQuery $query): Page
    {
        $page = $this->headersFindingRepository->findPostsByUserId($query->getUserId(), $query->getPageNo());
        $data = Collection::from($page->getData())
            ->map(function ($header) {
                return new FindPostsByUserIdQueryResponse(
                    $header->getId(),
                    $header->getTitle(),
                    $header->getSummary(),
                    $header->getTags(),
                    $header->getCreatedById(),
                    $header->getCreatedByName(),
                    $header->getCreatedAt(),
                    $header->getVersion(),
                    $header->getCommentsCount()
                );
            })
            ->toArray();
        return new Page($data, $page->getCount(), $page->getPageNo(), $page->getPageSize());
    }
}