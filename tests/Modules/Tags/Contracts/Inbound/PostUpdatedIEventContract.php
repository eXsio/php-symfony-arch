<?php

namespace App\Tests\Modules\Tags\Contracts\Inbound;

use App\Modules\Tags\Api\Event\Inbound\PostUpdatedTagsIEvent;
use App\Tests\TestUtils\Contracts\ApplicationInboundEventContract;

class PostUpdatedIEventContract extends ApplicationInboundEventContract
{

    public function testPostCreatedIEvent()
    {
        self::assertTrue(
            $this->verifyContract(PostUpdatedTagsIEvent::class, "Tags/PostUpdatedTagsIEvent")
        );
    }

}