<?php

namespace App\Modules\Comments\Domain\Event\Outbound;

use App\Infrastructure\Events\ApplicationOutboundEvent;
use App\Modules\Comments\Domain\Dto\CommentDto;
use Symfony\Component\Uid\Ulid;

class CommentsBaselinedOEvent extends ApplicationOutboundEvent
{
    const EVENT_NAME = "COMMENTS_BASELINED";

    /**
     * @param Ulid $postId
     * @param array<CommentDto> $comments
     */
    public function __construct(
        Ulid  $postId,
        array $comments
    )
    {
        parent::__construct(self::EVENT_NAME,
            [
                'postId' => $postId,
                'comments' => json_encode($comments),
            ]
        );
    }
}