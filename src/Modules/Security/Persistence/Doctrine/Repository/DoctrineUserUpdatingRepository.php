<?php

namespace App\Modules\Security\Persistence\Doctrine\Repository;

use App\Modules\Security\Domain\Dto\ChangeExistingUserPasswordDto;
use App\Modules\Security\Domain\Dto\RenameExistingUserDto;
use App\Modules\Security\Domain\Repository\UserUpdatingRepositoryInterface;
use App\Modules\Security\Persistence\Doctrine\Entity\User;

class DoctrineUserUpdatingRepository extends DoctrineSecurityRepository implements UserUpdatingRepositoryInterface
{

    public function renameUser(RenameExistingUserDto $renamedUser): void
    {
        $this->getEntityManager()
            ->createQueryBuilder()
            ->update(User::class, "u")
            ->set("u.email", ":newLogin")
            ->where("u.email = :oldLogin")
            ->getQuery()
            ->setParameter("newLogin", $renamedUser->getNewLogin())
            ->setParameter("oldLogin", $renamedUser->getCurrentLogin())
            ->execute();
    }

    public function changePassword(ChangeExistingUserPasswordDto $changedPassword): void
    {
        $this->getEntityManager()
            ->createQueryBuilder()
            ->update(User::class, "u")
            ->set("u.password", ":password")
            ->where("u.email = :login")
            ->getQuery()
            ->setParameter("password", $changedPassword->getPassword())
            ->setParameter("login", $changedPassword->getLogin())
            ->execute();
    }
}