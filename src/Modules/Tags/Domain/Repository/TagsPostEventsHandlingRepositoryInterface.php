<?php

namespace App\Modules\Tags\Domain\Repository;

use App\Modules\Tags\Domain\Dto\CreateNewTagsPostHeaderDto;
use App\Modules\Tags\Domain\Dto\DeleteExistingTagsPostHeaderDto;
use App\Modules\Tags\Domain\Dto\UpdateExistingTagsPostHeaderDto;

interface TagsPostEventsHandlingRepositoryInterface
{
    /**
     * @param CreateNewTagsPostHeaderDto $newPostHeader
     */
    function createPostHeader(CreateNewTagsPostHeaderDto $newPostHeader): void;

    /**
     * @param UpdateExistingTagsPostHeaderDto $updatedPostHeader
     */
    function updatePostHeader(UpdateExistingTagsPostHeaderDto $updatedPostHeader): void;

    /**
     * @param DeleteExistingTagsPostHeaderDto $deletedPostHeader
     */
    function deletePostHeader(DeleteExistingTagsPostHeaderDto $deletedPostHeader): void;
}