<?php

namespace App\Modules\Comments\Domain\Dto;

use Symfony\Component\Uid\Ulid;

class CommentWithPostDto
{

    /**
     * @param Ulid $id
     * @param string $author
     * @param string $body
     * @param Ulid|null $parentId
     * @param \DateTime $createdAt
     * @param Ulid $postId
     * @param string $postTitle
     * @param string $postSummary
     * @param int $postCommentsCount
     * @param array $postTags
     */
    public function __construct(
        private Ulid      $id,
        private string    $author,
        private string    $body,
        private ?Ulid     $parentId,
        private \DateTime $createdAt,
        private Ulid      $postId,
        private string    $postTitle,
        private string    $postSummary,
        private int       $postCommentsCount,
        private array     $postTags
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

    /**
     * @return Ulid
     */
    public function getPostId(): Ulid
    {
        return $this->postId;
    }

    /**
     * @return string
     */
    public function getPostTitle(): string
    {
        return $this->postTitle;
    }

    /**
     * @return string
     */
    public function getPostSummary(): string
    {
        return $this->postSummary;
    }

    /**
     * @return int
     */
    public function getPostCommentsCount(): int
    {
        return $this->postCommentsCount;
    }

    /**
     * @return int
     */
    public function getPostTags(): array
    {
        return $this->postTags;
    }


}