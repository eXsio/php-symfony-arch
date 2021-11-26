<?php

namespace App\Modules\Security\Domain\Repository;

use App\Modules\Security\Domain\Dto\CreateNewUserPostHeaderDto;
use App\Modules\Security\Domain\Dto\DeleteExistingUserPostHeaderDto;
use App\Modules\Security\Domain\Dto\UpdateExistingUserPostHeaderDto;

interface SecurityPostEventsHandlingRepositoryInterface
{
    /**
     * @param CreateNewUserPostHeaderDto $newPostHeader
     */
   public function createPostHeader(CreateNewUserPostHeaderDto $newPostHeader): void;

    /**
     * @param UpdateExistingUserPostHeaderDto $updatedPostHeader
     */
   public function updatePostHeader(UpdateExistingUserPostHeaderDto $updatedPostHeader): void;

    /**
     * @param DeleteExistingUserPostHeaderDto $deletedPostHeader
     */
   public function deletePostHeader(DeleteExistingUserPostHeaderDto $deletedPostHeader): void;
}