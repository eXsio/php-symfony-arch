<?php

namespace App\Tests\Modules\Comments\Unit\Repository;

use Symfony\Component\Uid\Ulid;

class InMemoryComment
{
    /**
     * @param Ulid $id
     * @param string $author
     * @param string $body
     * @param \DateTime $createdAt
     * @param InMemoryComment|null $parentComment
     * @param InMemoryCommentPostHeader $post
     */
    public function __construct(
        private Ulid                      $id,
        private string                    $author,
        private string                    $body,
        private \DateTime                 $createdAt,
        private ?InMemoryComment          $parentComment,
        private InMemoryCommentPostHeader $post)
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
     * @param string $author
     */
    public function setAuthor(string $author): void
    {
        $this->author = $author;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @param string $body
     */
    public function setBody(string $body): void
    {
        $this->body = $body;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return InMemoryComment|null
     */
    public function getParentComment(): ?InMemoryComment
    {
        return $this->parentComment;
    }

    /**
     * @param InMemoryComment|null $parentComment
     */
    public function setParentComment(?InMemoryComment $parentComment): void
    {
        $this->parentComment = $parentComment;
    }

    /**
     * @return InMemoryCommentPostHeader
     */
    public function getPost(): InMemoryCommentPostHeader
    {
        return $this->post;
    }

    /**
     * @param InMemoryCommentPostHeader $post
     */
    public function setPost(InMemoryCommentPostHeader $post): void
    {
        $this->post = $post;
    }


}