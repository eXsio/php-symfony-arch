<?php

namespace App\Modules\Security\Domain\Dto;

class CreateNewUserDto
{

    /**
     * @param string $login
     * @param string $encryptedPassword
     * @param array $roles
     */
    public function __construct(
        private string $login,
        private string $encryptedPassword,
        private array  $roles
    )
    {
    }

    /**
     * @return string
     */
    public function getLogin(): string
    {
        return $this->login;
    }

    /**
     * @return string
     */
    public function getEncryptedPassword(): string
    {
        return $this->encryptedPassword;
    }

    /**
     * @return array<string>
     */
    public function getRoles(): array
    {
        return $this->roles;
    }


}