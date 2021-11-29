<?php

namespace App\Modules\Comments\Api\Event\Inbound;

use App\Infrastructure\Events\ApplicationInboundEvent;
use Symfony\Component\Uid\Ulid;

class PostUpdatedCommentsIEvent extends ApplicationInboundEvent
{
    const EVENT_NAME = "POST_UPDATED";

    private Ulid $id;

    private string $title;


    private array $tags;

    private int $lastVersion;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        parent::__construct(self::EVENT_NAME, $data);
        $this->id = $this->ulid('id');
        $this->title = $this->string('title');
        $this->tags = $this->array('tags');
        $this->lastVersion = $this->int('lastVersion');
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
     * @return int
     */
    public function getLastVersion(): int
    {
        return $this->lastVersion;
    }
}