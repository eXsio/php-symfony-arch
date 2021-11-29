<?php

namespace App\Modules\Comments\Api\Event\Inbound;

use App\Infrastructure\Events\ApplicationInboundEvent;
use Symfony\Component\Uid\Ulid;

class PostCreatedCommentsIEvent extends ApplicationInboundEvent
{
    const EVENT_NAME = "POST_CREATED";

    private Ulid $id;

    private string $title;

    private array $tags;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        parent::__construct($data);
        $this->id = $this->ulid('id');
        $this->title = $this->string('title');
        $this->tags = $this->array('tags');
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
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return array
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return self::EVENT_NAME;
    }

}