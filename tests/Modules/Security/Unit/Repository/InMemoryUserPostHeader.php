<?php

namespace App\Tests\Modules\Security\Unit\Repository;

use Symfony\Component\Uid\Ulid;

class InMemoryUserPostHeader
{

    /**
     * @param Ulid $id
     * @param string $title
     * @param string $summary
     * @param array $tags
     * @param InMemoryUser $user
     * @param \DateTime $createdAt
     * @param int $version
     */
    public function __construct(private Ulid         $id,
                                private string       $title,
                                private string       $summary,
                                private array        $tags,
                                private InMemoryUser $user,
                                private \DateTime    $createdAt,
                                private int          $version,
                                private int          $commentsCount)
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
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getSummary(): string
    {
        return $this->summary;
    }

    /**
     * @param string $summary
     */
    public function setSummary(string $summary): void
    {
        $this->summary = $summary;
    }

    /**
     * @return array
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * @param array $tags
     */
    public function setTags(array $tags): void
    {
        $this->tags = $tags;
    }


    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
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

    /**
     * @return InMemoryUser
     */
    public function getUser(): InMemoryUser
    {
        return $this->user;
    }

    /**
     * @param InMemoryUser $user
     */
    public function setUser(InMemoryUser $user): void
    {
        $this->user = $user;
    }

    /**
     * @return int
     */
    public function getCommentsCount(): int
    {
        return $this->commentsCount;
    }

    /**
     * @param int $commentsCount
     */
    public function setCommentsCount(int $commentsCount): void
    {
        $this->commentsCount = $commentsCount;
    }


}