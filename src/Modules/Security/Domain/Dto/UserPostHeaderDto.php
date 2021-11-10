<?php

namespace App\Modules\Security\Domain\Dto;

use Symfony\Component\Uid\Ulid;

class UserPostHeaderDto
{
    /**
     * @param Ulid $id
     * @param string $title
     * @param string $summary
     * @param array $tags
     * @param Ulid $createdById
     * @param string $createdByName
     * @param \DateTime $createdAt
     * @param int $version
     * @param int $commentsCount
     */
    public function __construct(private Ulid      $id,
                                private string    $title,
                                private string    $summary,
                                private array     $tags,
                                private Ulid      $createdById,
                                private string    $createdByName,
                                private \DateTime $createdAt,
                                private int       $version,
                                private int       $commentsCount)
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
     * @return string
     */
    public function getSummary(): string
    {
        return $this->summary;
    }

    /**
     * @return array
     */
    public function getTags(): array
    {
        return $this->tags;
    }


    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }


    /**
     * @return int
     */
    public function getVersion(): int
    {
        return $this->version;
    }


    /**
     * @return int
     */
    public function getCommentsCount(): int
    {
        return $this->commentsCount;
    }

    /**
     * @return Ulid
     */
    public function getCreatedById(): Ulid
    {
        return $this->createdById;
    }

    /**
     * @return string
     */
    public function getCreatedByName(): string
    {
        return $this->createdByName;
    }


}