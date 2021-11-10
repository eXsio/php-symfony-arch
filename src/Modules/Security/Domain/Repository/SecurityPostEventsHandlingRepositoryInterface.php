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
    function createPostHeader(CreateNewUserPostHeaderDto $newPostHeader): void;

    /**
     * @param UpdateExistingUserPostHeaderDto $updatedPostHeader
     */
    function updatePostHeader(UpdateExistingUserPostHeaderDto $updatedPostHeader): void;

    /**
     * @param DeleteExistingUserPostHeaderDto $deletedPostHeader
     */
    function deletePostHeader(DeleteExistingUserPostHeaderDto $deletedPostHeader): void;
}