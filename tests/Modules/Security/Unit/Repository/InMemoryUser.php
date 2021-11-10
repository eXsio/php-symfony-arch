<?php

namespace App\Tests\Modules\Security\Unit\Repository;

use Symfony\Component\Uid\Ulid;

class InMemoryUser
{
    const ID = "01FMMW7M0HG0MMT5R9FKPHYKQ1";

    /**
     * @param Ulid $id
     * @param string $email
     * @param array $roles
     * @param int $version
     */
    public function __construct(
        private Ulid   $id,
        private string $email,
        private array  $roles,
        private int    $version
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
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @param array $roles
     */
    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    /**
     * @return int
     */
    public function getVersion(): int
    {
        return $this->version;
    }

    /**
     * @param int $version
     */
    public function setVersion(int $version): void
    {
        $this->version = $version;
    }


}