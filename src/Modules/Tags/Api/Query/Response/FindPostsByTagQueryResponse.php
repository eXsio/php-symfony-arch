<?php

namespace App\Modules\Tags\Api\Query\Response;

use Symfony\Component\Uid\Ulid;

class FindPostsByTagQueryResponse
{
    /**
     * @param Ulid $id
     * @param string $title
     * @param string $summary
     * @param Ulid $createdById
     * @param string $createdByName
     * @param \DateTime $createdAt
     * @param int $version
     * @param int $commentsCount
     */
    public function __construct(private Ulid      $id,
                                private string    $title,
                                private string    $summary,
                                private Ulid      $createdById,
                                private string    $createdByName,
                                private \DateTime $createdAt,
                                private int       $version,
                                private int       $commentsCount,
                                private array     $tags
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
     * @return array<string>
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }




}