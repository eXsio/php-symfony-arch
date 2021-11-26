<?php

namespace App\Modules\Posts\Domain\Logic;

use App\Infrastructure\Pagination\Page;
use App\Modules\Posts\Api\Query\FindAllPostsQuery;
use App\Modules\Posts\Api\Query\FindPostByIdQuery;
use App\Modules\Posts\Api\Query\Response\FindPostHeaderQueryResponse;
use App\Modules\Posts\Api\Query\Response\FindPostQueryResponse;
use App\Modules\Posts\Domain\Dto\PostDto;
use App\Modules\Posts\Domain\Dto\PostHeaderDto;
use App\Modules\Posts\Domain\Repository\PostsFindingRepositoryInterface;
use DusanKasan\Knapsack\Collection;

trait PostsFinder
{

    /**
     * @param PostsFindingRepositoryInterface $findingRepository
     */
    public function __construct(
        private PostsFindingRepositoryInterface $findingRepository
    )
    {
    }

    /**
     * @param FindAllPostsQuery $query
     * @return Page<FindPostHeaderQueryResponse>
     */
   public function findAllPosts(FindAllPostsQuery $query): Page
    {
        $page = $this->findingRepository->findPosts($query->getPageNo());
        return new Page(Collection::from(
            $page->getData()
        )
            ->map(function ($postHeader) {
                return $this->toHeaderResponse($postHeader);
            })
            ->toArray(),
            $page->getCount(),
            $page->getPageNo(),
            $page->getPageSize()
        );
    }

    /**
     * @param FindPostByIdQuery $query
     * @return FindPostQueryResponse|null
     */
   public function findPostById(FindPostByIdQuery $query): ?FindPostQueryResponse
    {
        return $this->toSinglePostResponse(
            $this->findingRepository->findPost($query->getId())
        );
    }

    /**
     * @param PostHeaderDto $postHeader
     * @return FindPostHeaderQueryResponse
     */
    private function toHeaderResponse(PostHeaderDto $postHeader): FindPostHeaderQueryResponse
    {
        return new FindPostHeaderQueryResponse(
            $postHeader->getId(),
            $postHeader->getTitle(),
            $postHeader->getSummary(),
            $postHeader->getTags(),
            $postHeader->getCommentsCount(),
            $postHeader->getCreatedById(),
            $postHeader->getCreatedByName(),
            $postHeader->getCreatedAt()
        );
    }

    /**
     * @param PostDto|null $post
     * @return FindPostQueryResponse|null
     */
    private function toSinglePostResponse(?PostDto $post): ?FindPostQueryResponse
    {
        if($post == null) {
            return null;
        }
        return new FindPostQueryResponse(
            $post->getId(),
            $post->getTitle(),
            $post->getBody(),
            $post->getTags(),
            $post->getComments(),
            $post->getCreatedById(),
            $post->getCreatedByName(),
            $post->getCreatedAt(),
            $post->getUpdatedAt()
        );
    }
}