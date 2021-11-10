<?php

namespace App\Modules\Security\Domain\Repository;

use App\Modules\Security\Domain\Dto\UpdatePostsCommentsCountDto;

interface SecurityCommentsEventHandlingRepositoryInterface
{
    /**
     * @param UpdatePostsCommentsCountDto $commentsCount
     */
    public function updatePostCommentsCount(UpdatePostsCommentsCountDto $commentsCount): void;

}