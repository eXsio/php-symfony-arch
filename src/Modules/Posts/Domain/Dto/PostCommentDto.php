<?php

namespace App\Modules\Posts\Domain\Dto;

use Symfony\Component\Uid\Ulid;

class PostCommentDto
{

    /**
     * @param Ulid $id
     * @param string $author
     * @param string $comment
     * @param \DateTime $createdAt
     */
    public function __construct(
        private Ulid $id,
        private string $author,
        private string $comment,
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
    public function getComment(): string
    {
        return $this->comment;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }


}