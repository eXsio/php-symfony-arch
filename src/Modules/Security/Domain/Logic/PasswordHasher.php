<?php

namespace App\Modules\Security\Domain\Logic;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

trait PasswordHasher
{

    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
    )
    {
    }

    protected function hashPassword(string $plainPassword): string
    {
        return $this->passwordHasher->hashPassword(new class implements PasswordAuthenticatedUserInterface {
            public function getPassword(): ?string
            {
                return null;
            }
        }, $plainPassword);
    }
}