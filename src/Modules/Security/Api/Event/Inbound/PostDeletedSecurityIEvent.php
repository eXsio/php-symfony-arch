<?php

namespace App\Modules\Security\Api\Event\Inbound;

use App\Infrastructure\Events\ApplicationInboundEvent;
use Symfony\Component\Uid\Ulid;

class PostDeletedSecurityIEvent extends ApplicationInboundEvent
{
    const EVENT_NAME = "POST_DELETED";

    private Ulid $id;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        parent::__construct(self::EVENT_NAME, $data);
        $this->id = $this->ulid('id');
    }


    /**
     * @return Ulid
     */
    public function getId(): Ulid
    {
        return $this->id;
    }

}