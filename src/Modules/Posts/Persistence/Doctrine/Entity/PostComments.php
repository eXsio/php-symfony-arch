<?php

namespace App\Modules\Posts\Persistence\Doctrine\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Ulid;

#[ORM\Entity]
#[ORM\Table(name: "POST_COMMENTS")]
class PostComments
{

    #[ORM\Id]
    #[ORM\Column(type: "ulid", unique: true)]
    private Ulid $postId;


    #[ORM\Column(type: "integer")]
    private int $commentsCount;

    #[ORM\Column(type: "json")]
    private array $comments = [];

    /**
     * @return Ulid
     */
    public function getPostId(): Ulid
    {
        return $this->postId;
    }

    /**
     * @param Ulid $postId
     */
    public function setPostId(Ulid $postId): void
    {
        $this->postId = $postId;
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
}