<?php

namespace App\Infrastructure\Security;

use Lexik\Bundle\JWTAuthenticationBundle\Security\User\JWTUserInterface;
use Symfony\Component\Uid\Ulid;

class LoggedInUser implements JWTUserInterface
{

    /**
     * @param string $id
     * @param string $email
     * @param array<string> $roles
     */
    public function __construct(
        private Ulid   $id,
        private string $email,
        private array  $roles
    )
    {
    }

    /**
     * @return Ulid
     */
    public function getId(): Ulid
    {
        return $this->id;
    }


    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string[]
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @param string $username
     * @param array $payload
     * @return static
     */
    public static function createFromPayload($username, array $payload): self
    {
        return new self(
            new Ulid($payload['id']),
            $username,
            $payload['roles']
        );
    }

    public function eraseCredentials(): void
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }
}