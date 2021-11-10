<?php

namespace App\Modules\Posts\Domain\Repository;

use App\Infrastructure\Pagination\Page;
use App\Modules\Posts\Domain\Dto\PostDto;
use App\Modules\Posts\Domain\Dto\PostForBaselineDto;
use App\Modules\Posts\Domain\Dto\PostHeaderDto;
use Symfony\Component\Uid\Ulid;

interface PostsFindingRepositoryInterface
{
    const PAGE_SIZE = 10;

    /**
     * @param Ulid $id
     * @return PostDto|null
     */
    public function findPost(Ulid $id): ?PostDto;

    /**
     * @param int $pageNo
     * @return Page<PostHeaderDto>
     */
    public function findPosts(int $pageNo): Page;

    /**
     * @param \DateTime|null $from
     * @return array<PostForBaselineDto>
     */
    public function findExistingPostsForBaseline(?\DateTime $from): array;

    /**
     * @param \DateTime|null $from
     * @return array<Ulid>
     */
    public function findDeletedPostIdsForBaseline(?\DateTime $from): array;
}