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
   public function createPostHeader(CreateNewTagsPostHeaderDto $newPostHeader): void;

    /**
     * @param UpdateExistingTagsPostHeaderDto $updatedPostHeader
     */
   public function updatePostHeader(UpdateExistingTagsPostHeaderDto $updatedPostHeader): void;

    /**
     * @param DeleteExistingTagsPostHeaderDto $deletedPostHeader
     */
   public function deletePostHeader(DeleteExistingTagsPostHeaderDto $deletedPostHeader): void;
}