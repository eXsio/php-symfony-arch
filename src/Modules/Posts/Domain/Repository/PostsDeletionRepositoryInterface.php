<?php

namespace App\Modules\Posts\Domain\Repository;

use App\Modules\Posts\Domain\Dto\DeleteExistingPostDto;

interface PostsDeletionRepositoryInterface
{
    /**
     * @param DeleteExistingPostDto $dto
     */
    function deletePost(DeleteExistingPostDto $dto): void;
}