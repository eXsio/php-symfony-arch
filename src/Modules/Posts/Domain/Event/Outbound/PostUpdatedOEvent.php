<?php

namespace App\Modules\Posts\Domain\Event\Outbound;

use App\Infrastructure\Events\ApplicationOutboundEvent;
use App\Modules\Posts\Domain\Dto\UpdateExistingPostDto;

class PostUpdatedOEvent extends ApplicationOutboundEvent
{
    const EVENT_NAME = "POST_UPDATED";

    /**
     * @param UpdateExistingPostDto $updatedPost
     * @param int $lastVersion
     */
    public function __construct(
        UpdateExistingPostDto $updatedPost, int $lastVersion
    )
    {
        parent::__construct(PostUpdatedOEvent::EVENT_NAME,
            [
                'id' => $updatedPost->getId(),
                'body' => $updatedPost->getBody(),
                'tags' => $updatedPost->getTags(),
                'title' => $updatedPost->getTitle(),
                'summary' => $updatedPost->getSummary(),
                'updatedAt' => $updatedPost->getUpdatedAt(),
                'lastVersion' => $lastVersion
            ]
        );
    }


}