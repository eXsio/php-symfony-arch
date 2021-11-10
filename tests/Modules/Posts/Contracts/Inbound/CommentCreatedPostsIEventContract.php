<?php

namespace App\Tests\Modules\Posts\Contracts\Inbound;

use App\Modules\Posts\Api\Event\Inbound\CommentCreatedPostsIEvent;
use App\Tests\TestUtils\Contracts\ApplicationInboundEventContract;

class CommentCreatedPostsIEventContract extends ApplicationInboundEventContract
{

    public function testPostCreatedIEvent()
    {
        self::assertTrue(
            $this->verifyContract(CommentCreatedPostsIEvent::class, "Posts/CommentCreatedPostsIEvent")
        );
    }

}