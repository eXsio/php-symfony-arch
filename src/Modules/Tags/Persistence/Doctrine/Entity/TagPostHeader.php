<?php

namespace App\Modules\Tags\Persistence\Doctrine\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Ulid;

#[ORM\Entity]
#[ORM\Table(name: "POST_HEADERS")]
class TagPostHeader
{
    #[ORM\Id]
    #[ORM\Column(type: "ulid", unique: true)]
    private Ulid $id;

    #[ORM\Column(type: "string")]
    private string $title;

    #[ORM\Column(type: "string")]
    private string $summary;

    #[ORM\Column(type: "integer")]
    private int $commentsCount;

    #[ORM\Column(type: "ulid")]
    private Ulid $createdById;

    #[ORM\Column(type: "string")]
    private string $createdByName;

    #[ORM\Column(type: "datetime")]
    private \DateTime $createdAt;

    #[ORM\Column(type: "integer")]
    private int $version;

    #[ORM\OneToMany(mappedBy: 'post', targetEntity: TagPost::class)]
    private ?Collection $tagPosts = null;

    #[ORM\Column(type: "json")]
    private array $flatTags;

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

    /**
     * @return Collection
     */
    public function getTagPosts(): Collection
    {
        if($this->tagPosts === null) {
            $this->tagPosts = new ArrayCollection();
        }
        return $this->tagPosts;
    }

    /**
     * @param Collection $tagPosts
     */
    public function setTagPosts(Collection $tagPosts): void
    {
        $this->tagPosts = $tagPosts;
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
     * @return array
     */
    public function getFlatTags(): array
    {
        return $this->flatTags;
    }

    /**
     * @param array $flatTags
     */
    public function setFlatTags(array $flatTags): void
    {
        $this->flatTags = $flatTags;
    }


}