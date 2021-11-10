<?php

namespace App\Modules\Security\Domain\Dto;

use Symfony\Component\Uid\Ulid;

class DeleteExistingUserPostHeaderDto
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