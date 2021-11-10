<?php

namespace App\Modules\Security\Domain\Repository;

interface UserFindingRepositoryInterface
{

    /**
     * @param string $login
     * @return bool
     */
    public function exists(string $login): bool;

}