<?php

namespace App\Modules\Comments\Api\Query;

use Symfony\Component\Uid\Ulid;

class FindCommentsByPostIdQuery
{

    /**
     * @param Ulid $postId
     */
    public function __construct(
        private Ulid $postId
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


}