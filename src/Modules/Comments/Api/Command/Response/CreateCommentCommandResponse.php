<?php

namespace App\Modules\Comments\Api\Command\Response;

use Symfony\Component\Uid\Ulid;

class CreateCommentCommandResponse
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