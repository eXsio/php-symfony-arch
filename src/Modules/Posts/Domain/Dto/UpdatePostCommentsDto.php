<?php

namespace App\Modules\Posts\Domain\Dto;

use Symfony\Component\Uid\Ulid;

class UpdatePostCommentsDto
{
    /**
     * @param Ulid $postId
     * @param array $comments
     */
    public function __construct(

        private Ulid  $postId,
        private array $comments,
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
     * @return array
     */
    public function getComments(): array
    {
        return $this->comments;
    }


}