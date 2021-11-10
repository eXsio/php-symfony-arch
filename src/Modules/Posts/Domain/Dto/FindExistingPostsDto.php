<?php

namespace App\Modules\Posts\Domain\Dto;

class FindExistingPostsDto
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