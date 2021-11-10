<?php

namespace App\Modules\Comments\Api\Query;

class FindLatestCommentsQuery
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