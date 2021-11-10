<?php

namespace App\Tests\Modules\Comments\Contracts\Inbound;

use App\Modules\Comments\Api\Event\Inbound\PostDeletedCommentsIEvent;
use App\Tests\TestUtils\Contracts\ApplicationInboundEventContract;

class PostDeletedIEventContract extends ApplicationInboundEventContract
{

    public function testPostCreatedIEvent()
    {
        self::assertTrue(
            $this->verifyContract(PostDeletedCommentsIEvent::class, "Comments/PostDeletedCommentsIEvent")
        );
    }

}