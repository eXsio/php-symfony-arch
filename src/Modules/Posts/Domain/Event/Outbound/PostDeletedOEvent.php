<?php

namespace App\Modules\Posts\Domain\Event\Outbound;

use App\Infrastructure\Events\ApplicationOutboundEvent;
use App\Modules\Posts\Domain\Dto\DeleteExistingPostDto;

class PostDeletedOEvent extends ApplicationOutboundEvent
{
    const EVENT_NAME = "POST_DELETED";

    /**
     * @param DeleteExistingPostDto $deletedPost
     */
    public function __construct(
        DeleteExistingPostDto $deletedPost
    )
    {
        parent::__construct(PostDeletedOEvent::EVENT_NAME, ['id' => $deletedPost->getId()]);
    }


}