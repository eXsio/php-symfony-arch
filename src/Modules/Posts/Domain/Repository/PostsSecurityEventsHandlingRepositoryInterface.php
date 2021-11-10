<?php

namespace App\Modules\Posts\Domain\Repository;

use App\Modules\Posts\Domain\Dto\UpdatedPostsUserNameDto;

interface PostsSecurityEventsHandlingRepositoryInterface
{

    public function updateUserName(UpdatedPostsUserNameDto $updatedUserName): void;
}