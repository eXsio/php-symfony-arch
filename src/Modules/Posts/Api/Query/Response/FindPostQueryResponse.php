<?php

namespace App\Modules\Posts\Api\Query\Response;

use App\Modules\Posts\Domain\Dto\PostCommentDto;
use Symfony\Component\Uid\Ulid;

class FindPostQueryResponse
{
    /**
     * @param Ulid $id
     * @param string $title
     * @param string $body
     * @param array<string> $tags
     * @param array<PostCommentDto> $comments
     * @param Ulid $createdById
     * @param string $createdByName
     * @param \DateTime $createdAt
     * @param \DateTime $updatedAt
     */
    public function __construct(
        private Ulid      $id,
        private string    $title,
        private string    $body,
        private array     $tags,
        private array     $comments,
        private Ulid      $createdById,
        private string    $createdByName,
        private \DateTime $createdAt,
        private \DateTime $updatedAt
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
     * @return array
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * @return array
     */
    public function getComments(): array
    {
        return $this->comments;
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
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }
}