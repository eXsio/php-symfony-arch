<?php

namespace App\Modules\Security\Persistence\Doctrine\Repository;

use App\Modules\Security\Domain\Dto\CreateNewUserDto;
use App\Modules\Security\Domain\Repository\UserCreationRepositoryInterface;
use App\Modules\Security\Persistence\Doctrine\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Ulid;

class DoctrineUserCreationRepository extends DoctrineSecurityRepository implements UserCreationRepositoryInterface
{
    /**
     * @param CreateNewUserDto $newUser
     * @return Ulid
     */
    function createUser(CreateNewUserDto $newUser): Ulid
    {
        $user = new User();
        $user->setEmail($newUser->getLogin());
        $user->setPassword($newUser->getEncryptedPassword());
        $user->setRoles($newUser->getRoles());
        $this->getEntityManager()->persist($user);
        return $user->getId();
    }
}