<?php

namespace App\Modules\Comments\Persistence\Doctrine\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Ulid;

#[ORM\Entity]
#[ORM\Table(name: "COMMENTS")]
class Comment
{
    #[ORM\Id]
    #[ORM\Column(type: "ulid", unique: true)]
    private Ulid $id;

    #[ORM\Column(type: "string")]
    private string $author;

    #[ORM\Column(type: "string")]
    private string $body;

    #[ORM\Column(type: "datetime")]
    private \DateTime $createdAt;

    #[ORM\ManyToOne(targetEntity: Comment::class)]
    #[ORM\JoinColumn(name: "parentId", referencedColumnName: "id")]
    private ?Comment $parentComment;


    #[ORM\ManyToOne(targetEntity: CommentPostHeader::class)]
    #[ORM\JoinColumn(name: "postId", referencedColumnName: "id")]
    private CommentPostHeader $post;

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
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * @param string $author
     */
    public function setAuthor(string $author): void
    {
        $this->author = $author;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @param string $body
     */
    public function setBody(string $body): void
    {
        $this->body = $body;
    }

    /**
     * @return Comment|null
     */
    public function getParentComment(): ?Comment
    {
        return $this->parentComment;
    }

    /**
     * @param Comment|null $parentComment
     */
    public function setParentComment(?Comment $parentComment): void
    {
        $this->parentComment = $parentComment;
    }

    /**
     * @return CommentPostHeader
     */
    public function getPost(): CommentPostHeader
    {
        return $this->post;
    }

    /**
     * @param CommentPostHeader $post
     */
    public function setPost(CommentPostHeader $post): void
    {
        $this->post = $post;
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


}