<?php

namespace App\Tests\TestUtils\Security;

use App\Infrastructure\Security\LoggedInUser;
use App\Infrastructure\Security\LoggedInUserProviderInterface;
use Symfony\Component\Uid\Ulid;

class InMemoryLoggedInUserProvider implements LoggedInUserProviderInterface
{
    public static string $USER_NAME = "user@exsio.com";
    public static array $USER_ROLES = ["ROLE_USER"];
    public static Ulid $USER_ID;

    public function __construct()
    {
        if (!isset(InMemoryLoggedInUserProvider::$USER_ID)) {
            InMemoryLoggedInUserProvider::$USER_ID = new Ulid();
        }
    }


   public function getUser(): LoggedInUser
    {
        return new LoggedInUser(
            InMemoryLoggedInUserProvider::$USER_ID,
            InMemoryLoggedInUserProvider::$USER_NAME,
            InMemoryLoggedInUserProvider::$USER_ROLES
        );
    }
}