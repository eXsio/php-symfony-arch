<?php

namespace App\Modules\Security\Domain\Dto;

use Symfony\Component\Uid\Ulid;

class UpdatePostsCommentsCountDto
{
    /**
     * @param Ulid $postId
     * @param int $commentsCount
     */
    public function __construct(
        private Ulid $postId,
        private int  $commentsCount
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
     * @return int
     */
    public function getCommentsCount(): int
    {
        return $this->commentsCount;
    }


}