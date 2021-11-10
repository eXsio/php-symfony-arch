<?php

namespace App\Modules\Posts\Api\Command;

use App\Infrastructure\Security\SecuredResourceAwareInterface;
use Symfony\Component\Uid\Ulid;

class UpdatePostCommand implements SecuredResourceAwareInterface
{

    private ?Ulid $id = null;

    /**
     * @param string $title
     * @param string $body
     * @param array<string> $tags
     */
    public function __construct(
        private string $title,
        private string $body,
        private array  $tags
    )
    {
    }

    /**
     * @param Ulid $id
     */
    public function setId(Ulid $id): void
    {
        $this->id = $id;
    }


    /**
     * @return Ulid
     */
    public function getId(): ?Ulid
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
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @return string[]
     */
    public function getTags(): array
    {
        return $this->tags;
    }


    public function getResourceName(): string
    {
        return "post";
    }
}