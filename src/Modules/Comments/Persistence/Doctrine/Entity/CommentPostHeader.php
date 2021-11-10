<?php

namespace App\Modules\Comments\Persistence\Doctrine\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Ulid;

#[ORM\Entity]
#[ORM\Table(name: "POST_HEADERS")]
class CommentPostHeader
{
    #[ORM\Id]
    #[ORM\Column(type: "ulid", unique: true)]
    private Ulid $id;

    #[ORM\Column(type: "string")]
    private string $title;

    #[ORM\Column(type: "string")]
    private string $summary;

    #[ORM\Column(type: "json")]
    private array $tags = [];

    #[ORM\Column(type: "ulid")]
    private Ulid $createdById;

    #[ORM\Column(type: "string")]
    private string $createdByName;

    #[ORM\Column(type: "datetime")]
    private \DateTime $createdAt;

    #[ORM\Column(type: "integer")]
    private int $version;

    #[ORM\OneToMany(mappedBy: 'post', targetEntity: Comment::class)]
    private Collection $comments;

    #[ORM\Column(type: "integer")]
    private int $commentsCount;

    /**
     * @return Ulid
     */
    public function getId(): Ulid
    {
        return $this->id;
    }

    /**
     * @param Ulid $id
     */
    public function setId(Ulid $id): void
    {
        $this->id = $id;
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
     * @return mixed
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param mixed $version
     */
    public function setVersion($version): void
    {
        $this->version = $version;
    }

    /**
     * @return array
     */
    public function getComments(): array
    {
        return $this->comments;
    }

    /**
     * @param array $comments
     */
    public function setComments(array $comments): void
    {
        $this->comments = $comments;
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