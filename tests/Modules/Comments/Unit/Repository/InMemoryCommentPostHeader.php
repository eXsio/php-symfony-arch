<?php

namespace App\Tests\Modules\Comments\Unit\Repository;

use Symfony\Component\Uid\Ulid;

class InMemoryCommentPostHeader
{

    private array $comments = [];

    /**
     * @param Ulid $id
     * @param string $title
     * @param string $summary
     * @param array $tags
     * @param Ulid $createdById
     * @param string $createdByName
     * @param \DateTime $createdAt
     * @param int $version
     */
    public function __construct(private Ulid      $id,
                                private string    $title,
                                private string    $summary,
                                private array     $tags,
                                private Ulid      $createdById,
                                private string    $createdByName,
                                private \DateTime $createdAt,
                                private int       $version)
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
     * @return Ulid
     */
    public function getCreatedById(): Ulid
    {
        return $this->createdById;
    }

    /**
     * @param Ulid $createdById
     */
    public function setCreatedById(Ulid $createdById): void
    {
        $this->createdById = $createdById;
    }

    /**
     * @return string
     */
    public function getCreatedByName(): string
    {
        return $this->createdByName;
    }

    /**
     * @param string $createdByName
     */
    public function setCreatedByName(string $createdByName): void
    {
        $this->createdByName = $createdByName;
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
     * @return array<InMemoryComment>
     */
    public function getComments(): array
    {
        return $this->comments;
    }

    /**
     * @param array<InMemoryComment> $comments
     */
    public function setComments(array $comments): void
    {
        $this->comments = $comments;
    }


}