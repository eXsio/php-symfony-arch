<?php

namespace App\Tests\Modules\Tags\Contracts\Inbound;

use App\Modules\Tags\Api\Event\Inbound\PostCreatedTagsIEvent;
use App\Tests\TestUtils\Contracts\ApplicationInboundEventContract;

class PostCreatedIEventContract extends ApplicationInboundEventContract
{

    public function testPostCreatedIEvent()
    {
        self::assertTrue(
            $this->verifyContract(PostCreatedTagsIEvent::class, "Tags/PostCreatedTagsIEvent")
        );
    }

}