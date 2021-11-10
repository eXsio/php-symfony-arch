<?php

namespace App\Modules\Comments\Domain\Event\Outbound;

use App\Infrastructure\Events\ApplicationOutboundEvent;
use Symfony\Component\Uid\Ulid;

class CommentsCountUpdatedOEvent extends ApplicationOutboundEvent
{
    const EVENT_NAME = "COMMENTS_COUNT_UPDATED";

    /**
     * @param Ulid $postId
     * @param int $commentsCount
     */
    public function __construct(
        Ulid $postId,
        int  $commentsCount
    )
    {
        parent::__construct(self::EVENT_NAME,
            [
                'postId' => $postId,
                'commentsCount' => $commentsCount,
            ]
        );
    }
}