<?php

namespace App\Modules\Comments\Domain\Dto;

use Symfony\Component\Uid\Ulid;

class CreateNewCommentsPostHeaderDto
{
    /**
     * @param Ulid $id
     * @param string $title
     * @param array $tags
     * @param int $version
     */
    public function __construct(private Ulid   $id,
                                private string $title,
                                private array  $tags,
                                private int    $version = 1)
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