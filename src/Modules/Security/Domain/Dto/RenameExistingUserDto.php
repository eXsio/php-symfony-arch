<?php

namespace App\Modules\Security\Domain\Dto;

class RenameExistingUserDto
{

    public function __construct(
        private string $currentLogin,
        private string $newLogin,
    )
    {
    }

    /**
     * @return string
     */
    public function getCurrentLogin(): string
    {
        return $this->currentLogin;
    }

    /**
     * @return string
     */
    public function getNewLogin(): string
    {
        return $this->newLogin;
    }


}