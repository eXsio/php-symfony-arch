<?php

namespace App\Modules\Posts\Api\Event\Inbound;

use App\Infrastructure\Events\ApplicationInboundEvent;
use Symfony\Component\Uid\Ulid;

class CommentCreatedPostsIEvent extends ApplicationInboundEvent
{
    const EVENT_NAME = "COMMENT_CREATED";

    private Ulid $postId;

    private array $comments;


    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        parent::__construct(self::EVENT_NAME, $data);
        $this->postId = $this->ulid('postId');
        $this->comments = [$this->array('comment')];
    }


    /**
     * @return Ulid|null
     */
    public function getPostId(): ?Ulid
    {
        return $this->postId;
    }

    /**
     * @return array
     */
    public function getComments(): array
    {
        return $this->comments;
    }

}