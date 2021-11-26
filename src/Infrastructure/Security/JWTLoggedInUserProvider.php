<?php

namespace App\Infrastructure\Security;

use Symfony\Component\Security\Core\Security;

/**
 * This Provider uses User information stored in the Authorization JWT Token
 */
class JWTLoggedInUserProvider implements LoggedInUserProviderInterface
{

    public function __construct(
        private Security $security
    )
    {
    }

   public function getUser(): LoggedInUser
    {
        $user = $this->security->getUser();
        if ($user == null) {
            throw new \RuntimeException("You are not logged in!");
        }
        if ($user instanceof LoggedInUser) {
            return $user;
        }
        throw new \RuntimeException("Unsupported User Class: " . $user::class);
    }
}