<?php

namespace App\Modules\Security\Domain\Repository;

use App\Modules\Security\Domain\Dto\CreateNewUserDto;
use Symfony\Component\Uid\Ulid;

interface UserCreationRepositoryInterface
{
    /**
     * @param CreateNewUserDto $newUser
     * @return Ulid
     */
    function createUser(CreateNewUserDto $newUser): Ulid;
}