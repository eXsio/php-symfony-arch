<?php

namespace App\Modules\Security\Domain\Repository;

use App\Modules\Security\Domain\Dto\ChangeExistingUserPasswordDto;
use App\Modules\Security\Domain\Dto\RenameExistingUserDto;

interface UserUpdatingRepositoryInterface
{

    /**
     * @param RenameExistingUserDto $renamedUser
     */
    public function renameUser(RenameExistingUserDto $renamedUser): void;

    /**
     * @param ChangeExistingUserPasswordDto $changedPassword
     */
    public function changePassword(ChangeExistingUserPasswordDto $changedPassword): void;
}