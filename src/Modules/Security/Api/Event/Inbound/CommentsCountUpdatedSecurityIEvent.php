<?php

namespace App\Modules\Security\Api\Event\Inbound;

use App\Infrastructure\Events\ApplicationInboundEvent;
use Symfony\Component\Uid\Ulid;

class CommentsCountUpdatedSecurityIEvent extends ApplicationInboundEvent
{
    const EVENT_NAME = "COMMENTS_COUNT_UPDATED";

    private Ulid $postId;

    private int $commentsCount;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        parent::__construct(self::EVENT_NAME, $data);
        $this->postId = $this->ulid('postId');
        $this->commentsCount = $this->int('commentsCount');
    }

    /**
     * @return Ulid|null
     */
    public function getPostId(): ?Ulid
    {
        return $this->postId;
    }

    /**
     * @return int|null
     */
    public function getCommentsCount(): ?int
    {
        return $this->commentsCount;
    }


}