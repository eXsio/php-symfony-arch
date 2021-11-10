<?php

namespace App\Modules\Comments\Domain\Repository;

use App\Modules\Comments\Domain\Dto\CreateNewCommentDto;
use Symfony\Component\Uid\Ulid;

interface CommentsCreationRepositoryInterface
{
    /**
     * @param CreateNewCommentDto $newComment
     * @return Ulid
     */
    public function createComment(CreateNewCommentDto $newComment): Ulid;
}