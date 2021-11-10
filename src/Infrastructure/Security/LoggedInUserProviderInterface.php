<?php

namespace App\Infrastructure\Security;

interface LoggedInUserProviderInterface
{
    function getUser(): LoggedInUser;
}