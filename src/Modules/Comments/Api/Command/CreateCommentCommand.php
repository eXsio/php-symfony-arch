<?php

namespace App\Modules\Comments\Api\Command;

use Symfony\Component\Uid\Ulid;

class CreateCommentCommand
{

    /**
     * @param Ulid $postId
     * @param string $author
     * @param string $body
     * @param Ulid|null $parentId
     */
    public function __construct(
        private Ulid $postId,
        private string $author,
        private string $body,
        private ?Ulid $parentId
    )
    {
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


}