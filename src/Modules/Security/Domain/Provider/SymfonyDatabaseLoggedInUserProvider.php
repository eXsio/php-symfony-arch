<?php

namespace App\Modules\Security\Domain\Provider;

use App\Infrastructure\Security\LoggedInUser;
use App\Infrastructure\Security\LoggedInUserProviderInterface;
use App\Modules\Security\Persistence\Doctrine\Entity\User;
use Symfony\Component\Security\Core\Security;

class SymfonyDatabaseLoggedInUserProvider implements LoggedInUserProviderInterface
{

    /**
     * @param Security $security
     */
    public function __construct(
        private Security $security
    )
    {
    }

    /**
     * @return LoggedInUser
     */
   public function getUser(): LoggedInUser
    {
        $user = $this->security->getUser();
        if ($user == null) {
            throw new \RuntimeException("You are not logged in!");
        }
        if ($user instanceof LoggedInUser) {
            return $user;
        }
        if ($user instanceof User) {
            return new LoggedInUser($user->getId(), $user->getUserIdentifier(), $user->getRoles());
        }
        throw new \RuntimeException("Unsupported User Class: " . $user::class);
    }
}