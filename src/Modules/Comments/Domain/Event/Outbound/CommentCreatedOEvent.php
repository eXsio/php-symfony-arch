<?php

namespace App\Modules\Comments\Domain\Event\Outbound;

use App\Infrastructure\Events\ApplicationOutboundEvent;
use App\Modules\Comments\Domain\Dto\CommentDto;
use Symfony\Component\Uid\Ulid;

class CommentCreatedOEvent extends ApplicationOutboundEvent
{
    const EVENT_NAME = "COMMENT_CREATED";

    /**
     * @param Ulid $postId
     * @param CommentDto $comment
     */
    public function __construct(
        Ulid       $postId,
        CommentDto $comment
    )
    {
        parent::__construct(self::EVENT_NAME,
            [
                'postId' => $postId,
                'comment' => json_encode($comment)
            ]
        );
    }
}