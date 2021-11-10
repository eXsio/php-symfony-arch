<?php

namespace App\Modules\Security\Persistence\Doctrine\Entity;

use App\Modules\Security\Persistence\Doctrine\Repository\DoctrineInternalUserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UlidGenerator;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Ulid;

#[ORM\Entity]
#[ORM\Table(name: "USERS")]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{

    /**
     * @var Ulid
     */
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: UlidGenerator::class)]
    #[ORM\Column(type: "ulid", unique: true)]
    private Ulid $id;

    /**
     * @var string
     */
    #[ORM\Column(type: "string", length: 180, unique: true)]
    private string $email;

    /**
     * @var array<string>
     */
    #[ORM\Column(type: "json")]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column(type: "string")]
    private string $password;

    #[ORM\Version]
    #[ORM\Column(type: "integer")]
    private $version;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: UserPostHeader::class)]
    private Collection $posts;


    public function getId(): ?Ulid
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * The public representation of the user (e.g. a username, an email address, etc.)
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string)$this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return mixed
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param mixed $version
     */
    public function setVersion($version): void
    {
        $this->version = $version;
    }

    /**
     * @return array
     */
    public function getPosts(): array
    {
        return $this->posts;
    }

    /**
     * @param array $posts
     */
    public function setPosts(array $posts): void
    {
        $this->posts = $posts;
    }




}