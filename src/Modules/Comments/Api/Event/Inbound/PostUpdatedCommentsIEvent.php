<?php

namespace App\Modules\Comments\Api\Event\Inbound;

use App\Infrastructure\Events\ApplicationInboundEvent;
use Symfony\Component\Uid\Ulid;

class PostUpdatedCommentsIEvent extends ApplicationInboundEvent
{
    const EVENT_NAME = "POST_UPDATED";

    private Ulid $id;

    private string $title;

    private string $summary;

    private \DateTime $updatedAt;

    private array $tags;

    private int $lastVersion;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        parent::__construct(PostDeletedCommentsIEvent::EVENT_NAME, $data);
        $this->id = $this->ulid('id');
        $this->title = $this->string('title');
        $this->summary = $this->string('summary');
        $this->tags = $this->array('tags');
        $this->updatedAt = $this->dateTime('updatedAt');
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
     * @return mixed|string
     */
    public function getSummary(): mixed
    {
        return $this->summary;
    }

    /**
     * @return array
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @return int
     */
    public function getLastVersion(): int
    {
        return $this->lastVersion;
    }
}