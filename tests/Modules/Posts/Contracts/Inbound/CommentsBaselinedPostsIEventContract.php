<?php

namespace App\Tests\Modules\Posts\Contracts\Inbound;

use App\Modules\Posts\Api\Event\Inbound\CommentsBaselinedPostsIEvent;
use App\Tests\TestUtils\Contracts\ApplicationInboundEventContract;

class CommentsBaselinedPostsIEventContract extends ApplicationInboundEventContract
{

    public function testCommentsBaselinedIEvent()
    {
        self::assertTrue(
            $this->verifyContract(CommentsBaselinedPostsIEvent::class, "Posts/CommentsBaselinedPostsIEvent")
        );
    }

}