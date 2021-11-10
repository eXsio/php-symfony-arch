<?php

namespace App\Modules\Tags\Persistence\Doctrine\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UlidGenerator;
use Symfony\Component\Uid\Ulid;

#[ORM\Entity]
#[ORM\Table(name: "TAGS")]
class Tag
{

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: UlidGenerator::class)]
    #[ORM\Column(type: "ulid", unique: true)]
    private Ulid $id;

    #[ORM\Column(type: "string", unique: true)]
    private string $tag;

    #[ORM\ManyToMany(targetEntity: TagPostHeader::class)]
    #[ORM\JoinTable(name: "TAGS_POSTS")]
    #[ORM\JoinColumn(name: "tagId", referencedColumnName: "id")]
    #[ORM\InverseJoinColumn(name: "postId", referencedColumnName: "id")]
    private ?Collection $posts = null;


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
    public function getTag(): string
    {
        return $this->tag;
    }

    /**
     * @param string $tag
     */
    public function setTag(string $tag): void
    {
        $this->tag = $tag;
    }

    /**
     * @return Collection<TagPostHeader>
     */
    public function getPosts(): Collection
    {
        if ($this->posts == null) {
            $this->posts = new ArrayCollection();
        }
        return $this->posts;
    }

    /**
     * @param Collection<TagPostHeader> $posts
     */
    public function setPosts(Collection $posts): void
    {
        $this->posts = $posts;
    }


}