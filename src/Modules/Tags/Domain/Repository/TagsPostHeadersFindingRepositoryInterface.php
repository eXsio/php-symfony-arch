<?php

namespace App\Modules\Tags\Domain\Repository;

use App\Infrastructure\Pagination\Page;
use App\Modules\Tags\Domain\Dto\TagsPostHeaderDto;

interface TagsPostHeadersFindingRepositoryInterface
{
    const PAGE_SIZE = 10;

    /**
     * @return array<TagsPostHeaderDto>
     */
    public function findPostHeaders(): array;

    /**
     * @param string $tag
     * @return Page<TagsPostHeaderDto>
     */
    public function findPostHeadersByTag(string $tag, int $pageNo): Page;

}