<?php

namespace App\Tests\Modules\Comments\Contracts\Inbound;

use App\Modules\Comments\Api\Event\Inbound\PostCreatedCommentsIEvent;
use App\Tests\TestUtils\Contracts\ApplicationInboundEventContract;

class PostCreatedIEventContract extends ApplicationInboundEventContract
{

    public function testPostCreatedIEvent()
    {
        self::assertTrue(
            $this->verifyContract(PostCreatedCommentsIEvent::class, "Comments/PostCreatedCommentsIEvent")
        );
    }

}