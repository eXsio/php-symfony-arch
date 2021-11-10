<?php

namespace App\Modules\Posts\Api\Query\Response;

use Symfony\Component\Uid\Ulid;

class FindPostHeaderQueryResponse
{
    /**
     * @param Ulid $id
     * @param string $title
     * @param string $summary
     * @param array<string> $tags
     * @param int $commentsCount
     * @param Ulid $createdById
     * @param string $createdByName
     * @param \DateTime $createdAt
     */
    public function __construct(
        private Ulid      $id,
        private string    $title,
        private string    $summary,
        private array     $tags,
        private int       $commentsCount,
        private Ulid      $createdById,
        private string    $createdByName,
        private \DateTime $createdAt
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


    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }
}