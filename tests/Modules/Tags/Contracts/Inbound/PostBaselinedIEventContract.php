<?php

namespace App\Tests\Modules\Tags\Contracts\Inbound;

use App\Modules\Tags\Api\Event\Inbound\PostCreatedTagsIEvent;
use App\Tests\TestUtils\Contracts\ApplicationInboundEventContract;

class PostBaselinedIEventContract extends ApplicationInboundEventContract
{

    public function testPostBaselinedIEvent()
    {
        self::assertTrue(
            $this->verifyContract(PostCreatedTagsIEvent::class, "Tags/PostBaselinedTagsIEvent")
        );
    }

}