<?php

namespace App\Modules\Posts\Api\Command;

use App\Infrastructure\Security\SecuredResourceAwareInterface;
use Symfony\Component\Uid\Ulid;

class DeletePostCommand implements SecuredResourceAwareInterface
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


    public function getResourceName(): string
    {
        return "post";
    }
}