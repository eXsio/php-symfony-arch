<?php

namespace App\Modules\Security\Api\Query;

use Symfony\Component\Uid\Ulid;

class FindPostsByUserIdQuery
{

    /**
     * @param Ulid $userId
     * @param int $pageNo
     */
    public function __construct(
        private Ulid $userId,
        private int  $pageNo
    )
    {
    }

    /**
     * @return Ulid
     */
    public function getUserId(): Ulid
    {
        return $this->userId;
    }

    /**
     * @return int
     */
    public function getPageNo(): int
    {
        return $this->pageNo;
    }
}