<?php

namespace App\Infrastructure\Security;

interface SecuredResourceAwareInterface
{
    public function getResourceName(): string;
}