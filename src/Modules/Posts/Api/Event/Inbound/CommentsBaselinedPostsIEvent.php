<?php

namespace App\Modules\Posts\Api\Event\Inbound;

use App\Infrastructure\Events\ApplicationInboundEvent;
use Symfony\Component\Uid\Ulid;

class CommentsBaselinedPostsIEvent extends ApplicationInboundEvent
{
    const EVENT_NAME = "COMMENTS_BASELINED";

    private Ulid $postId;

    private array $comments;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        parent::__construct($data);
        $this->postId = $this->ulid('postId');
        $this->comments = $this->array('comments');
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

    /**
     * @return string
     */
    public static function getName(): string
    {
        return self::EVENT_NAME;
    }


}