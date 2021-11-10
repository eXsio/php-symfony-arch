<?php

namespace App\Modules\Tags\Domain\Repository;

use App\Modules\Tags\Domain\Dto\UpdatedTagsPostHeadersUserNameDto;

interface TagsSecurityEventsHandlingRepositoryInterface
{

    public function updateUserName(UpdatedTagsPostHeadersUserNameDto $updatedUserName): void;
}