<?php

namespace App\Modules\Comments\Domain\Dto;

use Symfony\Component\Uid\Ulid;

class CommentDto implements \JsonSerializable
{
    /**
     * @param Ulid $id
     * @param string $author
     * @param string $body
     * @param Ulid|null $parentId
     * @param \DateTime $createdAt
     */
    public function __construct(
        private Ulid      $id,
        private string    $author,
        private string    $body,
        private ?Ulid     $parentId,
        private \DateTime $createdAt
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

    /**
     * @return string
     */
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @return Ulid|null
     */
    public function getParentId(): ?Ulid
    {
        return $this->parentId;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }


    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'author' => $this->author,
            'body' => $this->body,
            'parentId' => $this->parentId,
            'createdAt' => $this->createdAt,
        ];
    }
}