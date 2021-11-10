<?php

namespace App\Modules\Posts\Domain\Event\Outbound;

use App\Infrastructure\Events\ApplicationOutboundEvent;
use App\Modules\Posts\Domain\Dto\CreateNewPostDto;
use Symfony\Component\Uid\Ulid;

class PostCreatedOEvent extends ApplicationOutboundEvent
{
    const EVENT_NAME = "POST_CREATED";

    /**
     * @param Ulid $id
     * @param CreateNewPostDto $newPost
     */
    public function __construct(
        Ulid             $id,
        CreateNewPostDto $newPost
    )
    {
        parent::__construct(PostCreatedOEvent::EVENT_NAME,
            [
                'id' => $id,
                'title' => $newPost->getTitle(),
                'summary' => $newPost->getSummary(),
                'body' => $newPost->getBody(),
                'tags' => $newPost->getTags(),
                'createdById' => $newPost->getCreatedById(),
                'createdByName' => $newPost->getCreatedByName(),
                'createdAt' => $newPost->getCreatedAt()
            ]
        );
    }


}