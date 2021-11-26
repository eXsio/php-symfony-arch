<?php

namespace App\Infrastructure\Security;

interface LoggedInUserProviderInterface
{
   public function getUser(): LoggedInUser;
}