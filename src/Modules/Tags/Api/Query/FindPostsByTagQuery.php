<?php

namespace App\Modules\Tags\Api\Query;

class FindPostsByTagQuery
{
    /**
     * @param string $tag
     * @param int $pageNo
     */
    public function __construct(
        private string $tag,
        private int    $pageNo
    )
    {
    }

    /**
     * @return string
     */
    public function getTag(): string
    {
        return $this->tag;
    }

    /**
     * @return int
     */
    public function getPageNo(): int
    {
        return $this->pageNo;
    }


}