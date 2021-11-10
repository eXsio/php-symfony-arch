<?php

namespace App\Modules\Posts\Domain\Repository;

use App\Modules\Posts\Domain\Dto\UpdateExistingPostDto;

interface PostsUpdatingRepositoryInterface
{
    /**
     * @param UpdateExistingPostDto $dto
     */
    function updatePost(UpdateExistingPostDto $dto): void;
}