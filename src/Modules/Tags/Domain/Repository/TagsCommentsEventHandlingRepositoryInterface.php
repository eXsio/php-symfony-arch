<?php

namespace App\Modules\Tags\Domain\Repository;

use App\Modules\Tags\Domain\Dto\UpdatePostsCommentsCountDto;

interface TagsCommentsEventHandlingRepositoryInterface
{
    /**
     * @param UpdatePostsCommentsCountDto $commentsCount
     */
    public function updatePostCommentsCount(UpdatePostsCommentsCountDto $commentsCount): void;

}