<?php

namespace App\Tests\Modules\Tags\Contracts\Inbound;

use App\Modules\Tags\Api\Event\Inbound\PostDeletedTagsIEvent;
use App\Tests\TestUtils\Contracts\ApplicationInboundEventContract;

class PostDeletedIEventContract extends ApplicationInboundEventContract
{

    public function testPostCreatedIEvent()
    {
        self::assertTrue(
            $this->verifyContract(PostDeletedTagsIEvent::class, "Tags/PostDeletedTagsIEvent")
        );
    }

}