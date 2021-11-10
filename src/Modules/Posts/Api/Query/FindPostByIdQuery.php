<?php

namespace App\Modules\Posts\Api\Query;

use Symfony\Component\Uid\Ulid;

class FindPostByIdQuery
{
    /**
     * @param Ulid $id
     */
    public function __construct(
        private Ulid $id
    )
    {
    }

    /**
     * @return Ulid
     */
    public function getId(): Ulid
    {
        return $this->id;
    }
}