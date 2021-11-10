<?php

namespace App\Modules\Security\Domain\Dto;

class ChangeExistingUserPasswordDto
{

    public function __construct(
        private string $login,
        private string $password
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
    public function getPassword(): string
    {
        return $this->password;
    }


}