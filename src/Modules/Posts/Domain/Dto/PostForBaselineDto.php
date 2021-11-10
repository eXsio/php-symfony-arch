<?php

namespace App\Modules\Posts\Domain\Dto;

class PostForBaselineDto
{
    /**
     * @param string $id
     * @param string $title
     * @param string $body
     * @param string $summary
     * @param array<string> $tags
     * @param \DateTime $updatedAt
     */
    public function __construct(
        private string    $id,
        private string    $title,
        private string    $body,
        private string    $summary,
        private array     $tags,
        private \DateTime $updatedAt,
        private int       $version
    )
    {
    }

    /**
     * @return string
     */
    public function getId(): string
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
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @return string
     */
    public function getSummary(): string
    {
        return $this->summary;
    }

    /**
     * @return string[]
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @return int
     */
    public function getVersion(): int
    {
        return $this->version;
    }


}