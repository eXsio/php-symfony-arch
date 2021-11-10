<?php

namespace App\Modules\Comments\Domain\Repository;

use App\Modules\Comments\Domain\Dto\CreateNewCommentsPostHeaderDto;
use App\Modules\Comments\Domain\Dto\DeleteExistingCommentsPostHeaderDto;
use App\Modules\Comments\Domain\Dto\UpdateExistingCommentsPostHeaderDto;

interface CommentsPostsEventsHandlingRepositoryInterface
{
    /**
     * @param CreateNewCommentsPostHeaderDto $newPostHeader
     */
    function createPostHeader(CreateNewCommentsPostHeaderDto $newPostHeader): void;

    /**
     * @param UpdateExistingCommentsPostHeaderDto $updatedPostHeader
     */
    function updatePostHeader(UpdateExistingCommentsPostHeaderDto $updatedPostHeader): void;

    /**
     * @param DeleteExistingCommentsPostHeaderDto $deletedPostHeader
     */
    function deletePostHeader(DeleteExistingCommentsPostHeaderDto $deletedPostHeader): void;
}