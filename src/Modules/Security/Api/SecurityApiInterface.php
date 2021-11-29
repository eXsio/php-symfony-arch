<?php

namespace App\Modules\Security\Api;

use App\Modules\Security\Api\Command\ChangeUserPasswordCommand;
use App\Modules\Security\Api\Command\CreateUserCommand;
use App\Modules\Security\Api\Command\RenameUserCommand;
use App\Modules\Security\Api\Command\Response\CreateUserCommandResponse;

interface SecurityApiInterface
{
    /**
     * @param CreateUserCommand $command
     * @return CreateUserCommandResponse
     */
    public function createUser(CreateUserCommand $command): CreateUserCommandResponse;

    /**
     * @param RenameUserCommand $command
     */
    public function renameUser(RenameUserCommand $command): void;

    /**
     * @param ChangeUserPasswordCommand $command
     */
    public function changePassword(ChangeUserPasswordCommand $command): void;
}