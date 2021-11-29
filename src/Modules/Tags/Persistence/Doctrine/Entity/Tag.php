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
    #[ORM\Column(type: "ulid", unique: true)]
    private Ulid $id;

    #[ORM\Column(type: "string", unique: true)]
    private string $tag;

    #[ORM\OneToMany(mappedBy: 'tag', targetEntity: TagPost::class)]
    private Collection $tagPosts;


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
     * @return Collection
     */
    public function getTagPosts(): Collection
    {
        return $this->tagPosts;
    }

    /**
     * @param Collection $tagPosts
     */
    public function setTagPosts(Collection $tagPosts): void
    {
        $this->tagPosts = $tagPosts;
    }




}