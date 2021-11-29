<?php

namespace App\Modules\Tags\Persistence\Doctrine\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "TAGS_POSTS")]
class TagPost
{

    #[ORM\Id]
    #[ORM\JoinColumn(name: "tagId", referencedColumnName: "id")]
    #[ORM\ManyToOne(targetEntity: Tag::class)]
    private Tag $tag;

    #[ORM\Id]
    #[ORM\JoinColumn(name: "postId", referencedColumnName: "id")]
    #[ORM\ManyToOne(targetEntity: TagPostHeader::class)]
    private TagPostHeader $post;

    /**
     * @return Tag
     */
    public function getTag(): Tag
    {
        return $this->tag;
    }

    /**
     * @param Tag $tag
     */
    public function setTag(Tag $tag): void
    {
        $this->tag = $tag;
    }

    /**
     * @return TagPostHeader
     */
    public function getPost(): TagPostHeader
    {
        return $this->post;
    }

    /**
     * @param TagPostHeader $post
     */
    public function setPost(TagPostHeader $post): void
    {
        $this->post = $post;
    }


}