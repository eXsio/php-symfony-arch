<?php

namespace App\Modules\Comments\Domain\Repository;

use App\Modules\Comments\Domain\Dto\UpdatedCommentsPostHeadersUserNameDto;

interface CommentsSecurityEventsHandlingRepositoryInterface
{

    public function updateUserName(UpdatedCommentsPostHeadersUserNameDto $updatedUserName): void;
}