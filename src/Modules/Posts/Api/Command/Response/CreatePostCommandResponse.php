<?php

namespace App\Modules\Posts\Api\Command\Response;

use Symfony\Component\Uid\Ulid;

class CreatePostCommandResponse
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