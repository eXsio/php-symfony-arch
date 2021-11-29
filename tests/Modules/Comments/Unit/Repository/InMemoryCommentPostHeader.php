<?php

namespace App\Tests\Modules\Comments\Unit\Repository;

use Symfony\Component\Uid\Ulid;

class InMemoryCommentPostHeader
{

    private array $comments = [];

    /**
     * @param Ulid $id
     * @param string $title
     * @param array $tags
     * @param int $version
     */
    public function __construct(private Ulid      $id,
                                private string    $title,
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
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
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
     * @param array $tags
     */
    public function setTags(array $tags): void
    {
        $this->tags = $tags;
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