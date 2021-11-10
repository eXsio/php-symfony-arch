<?php

namespace App\Modules\Comments\Domain\Dto;

use Symfony\Component\Uid\Ulid;

class DeleteExistingCommentsPostHeaderDto
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