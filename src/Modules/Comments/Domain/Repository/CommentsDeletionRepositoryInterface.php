<?php

namespace App\Modules\Comments\Domain\Repository;

use Symfony\Component\Uid\Ulid;

interface CommentsDeletionRepositoryInterface
{
    /**
     * @param Ulid $postId
     * @return mixed
     */
    public function deleteCommentsForPost(Ulid $postId);
}