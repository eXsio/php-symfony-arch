<?php

namespace App\Modules\Posts\Domain\Dto;

use Symfony\Component\Uid\Ulid;

class FindExistingPostDto
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