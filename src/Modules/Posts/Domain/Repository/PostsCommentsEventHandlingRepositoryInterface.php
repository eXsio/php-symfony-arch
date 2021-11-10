<?php

namespace App\Modules\Posts\Domain\Repository;

use App\Modules\Posts\Domain\Dto\UpdatePostCommentsDto;

interface PostsCommentsEventHandlingRepositoryInterface
{

    /**
     * @param UpdatePostCommentsDto $updatedComments
     */
    public function updateAllComments(UpdatePostCommentsDto $updatedComments, bool $append = true): void;


}