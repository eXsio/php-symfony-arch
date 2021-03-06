<?php

namespace App\Modules\Security\Domain\Dto;

use Symfony\Component\Uid\Ulid;

class UpdateExistingUserPostHeaderDto
{
    /**
     * @param Ulid $id
     * @param string $title
     * @param string $summary
     * @param array $tags
     * @param int $version
     */
    public function __construct(private Ulid      $id,
                                private string    $title,
                                private string    $summary,
                                private array     $tags,
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
    public function getVersion(): int
    {
        return $this->version;
    }

}