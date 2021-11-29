<?php

namespace App\Modules\Tags\Api\Event\Inbound;

use App\Infrastructure\Events\ApplicationInboundEvent;
use Symfony\Component\Uid\Ulid;

class PostDeletedTagsIEvent extends ApplicationInboundEvent
{
    const EVENT_NAME = "POST_DELETED";

    private Ulid $id;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        parent::__construct($data);
        $this->id = $this->ulid('id');
    }


    /**
     * @return Ulid
     */
    public function getId(): Ulid
    {
        return $this->id;
    }


    /**
     * @return string
     */
    public static function getName(): string
    {
        return self::EVENT_NAME;
    }

}