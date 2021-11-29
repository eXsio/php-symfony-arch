<?php

namespace App\Modules\Tags\Api\Event\Inbound;

use App\Infrastructure\Events\ApplicationInboundEvent;
use Symfony\Component\Uid\Ulid;

class PostCreatedTagsIEvent extends ApplicationInboundEvent
{
    const EVENT_NAME = "POST_CREATED";

    private Ulid $id;

    private string $title;

    private string $summary;

    private Ulid $createdById;

    private string $createdByName;

    private \DateTime $createdAt;

    private array $tags;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        parent::__construct($data);
        $this->id = $this->ulid('id');
        $this->title = $this->string('title');
        $this->summary = $this->string('summary');
        $this->createdById = $this->ulid('createdById');
        $this->createdByName = $this->string('createdByName');
        $this->tags = $this->array('tags');
        $this->createdAt = $this->dateTime('createdAt');
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
     * @return Ulid
     */
    public function getCreatedById(): Ulid
    {
        return $this->createdById;
    }


    /**
     * @return string
     */
    public function getCreatedByName(): string
    {
        return $this->createdByName;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
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