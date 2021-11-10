<?php

namespace App\Modules\Security\Api\Command\Response;

use Symfony\Component\Uid\Ulid;

class CreateUserCommandResponse
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