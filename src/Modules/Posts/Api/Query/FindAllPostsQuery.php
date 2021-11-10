<?php

namespace App\Modules\Posts\Api\Query;

class FindAllPostsQuery
{
    /**
     * @param int $pageNo
     */
    public function __construct(
        private int $pageNo
    )
    {
    }

    /**
     * @return int
     */
    public function getPageNo(): int
    {
        return $this->pageNo;
    }


}