<?php

namespace App\Modules\Posts\Domain\Event\Outbound;

use App\Infrastructure\Events\ApplicationOutboundEvent;
use App\Modules\Posts\Domain\Dto\PostForBaselineDto;
use Symfony\Component\Uid\Ulid;

class PostBaselinedOEvent extends ApplicationOutboundEvent
{
    const EVENT_NAME = "POST_BASELINED";

    /**
     * @param PostForBaselineDto $baselinedPost
     */
    public function __construct(
        PostForBaselineDto $baselinedPost
    )
    {
        parent::__construct(self::EVENT_NAME,
            [
                'id' => $baselinedPost->getId(),
                'title' => $baselinedPost->getTitle(),
                'summary' => $baselinedPost->getSummary(),
                'body' => $baselinedPost->getBody(),
                'tags' => $baselinedPost->getTags(),
                'createdById' => $baselinedPost->getCreatedById(),
                'createdByName' => $baselinedPost->getCreatedByName(),
                'createdAt' => $baselinedPost->getCreatedAt(),
                'updatedAt' => $baselinedPost->getUpdatedAt(),
                'version' => $baselinedPost->getVersion()
            ]
        );
    }


}