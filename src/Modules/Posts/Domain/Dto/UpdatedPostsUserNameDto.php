<?php

namespace App\Modules\Posts\Domain\Dto;

class UpdatedPostsUserNameDto
{

    /**
     * @param string $oldUserName
     * @param string $newUserName
     */
    public function __construct(
        private string $oldUserName,
        private string $newUserName
    )
    {
    }

    /**
     * @return string
     */
    public function getOldUserName(): string
    {
        return $this->oldUserName;
    }

    /**
     * @return string
     */
    public function getNewUserName(): string
    {
        return $this->newUserName;
    }


}