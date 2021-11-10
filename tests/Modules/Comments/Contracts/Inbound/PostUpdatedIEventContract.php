<?php

namespace App\Tests\Modules\Comments\Contracts\Inbound;

use App\Modules\Comments\Api\Event\Inbound\PostDeletedCommentsIEvent;
use App\Modules\Comments\Api\Event\Inbound\PostUpdatedCommentsIEvent;
use App\Tests\TestUtils\Contracts\ApplicationInboundEventContract;

class PostUpdatedIEventContract extends ApplicationInboundEventContract
{

    public function testPostCreatedIEvent()
    {
        self::assertTrue(
            $this->verifyContract(PostUpdatedCommentsIEvent::class, "Comments/PostUpdatedCommentsIEvent")
        );
    }

}