<?php

namespace App\Modules\Posts\Domain\Dto;

use Symfony\Component\Uid\Ulid;

class CreateNewPostDto
{

    /**
     * @param string $title
     * @param string $body
     * @param string $summary
     * @param array<string> $tags
     * @param Ulid $createdById
     * @param string $createdByName
     * @param \DateTime $createdAt
     */
    public function __construct(
        private string    $title,
        private string    $body,
        private string    $summary,
        private array     $tags,
        private Ulid      $createdById,
        private string    $createdByName,
        private \DateTime $createdAt
    )
    {
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