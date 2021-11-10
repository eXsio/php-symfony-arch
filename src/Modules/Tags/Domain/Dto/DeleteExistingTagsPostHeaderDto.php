<?php

namespace App\Modules\Tags\Domain\Dto;

use Symfony\Component\Uid\Ulid;

class DeleteExistingTagsPostHeaderDto
{
    /**
     * @param Ulid $id
     */
    public function __construct(private Ulid $id)
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