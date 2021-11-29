<?php

namespace App\Modules\Tags\Domain\Repository;

use App\Infrastructure\Pagination\Page;
use App\Modules\Tags\Domain\Dto\TagsPostHeaderDto;
use Symfony\Component\Uid\Ulid;

interface TagsPostHeadersFindingRepositoryInterface
{
    const PAGE_SIZE = 10;

    /**
     * @return array<TagsPostHeaderDto>
     */
    public function findPostHeaders(): array;

    /**
     * @param string $tag
     * @param int $pageNo
     * @return Page<TagsPostHeaderDto>
     */
    public function findPostHeadersByTag(string $tag, int $pageNo): Page;

    /**
     * @param Ulid $postId
     * @return bool
     */
    public function postExists(Ulid $postId): bool;

}