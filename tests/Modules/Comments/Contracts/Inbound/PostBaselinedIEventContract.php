<?php

namespace App\Tests\Modules\Comments\Contracts\Inbound;

use App\Modules\Comments\Api\Event\Inbound\PostBaselinedCommentsIEvent;
use App\Modules\Comments\Api\Event\Inbound\PostCreatedCommentsIEvent;
use App\Tests\TestUtils\Contracts\ApplicationInboundEventContract;

class PostBaselinedIEventContract extends ApplicationInboundEventContract
{

    public function testPostBaselinedIEvent()
    {
        self::assertTrue(
            $this->verifyContract(PostBaselinedCommentsIEvent::class, "Comments/PostBaselinedCommentsIEvent")
        );
    }

}