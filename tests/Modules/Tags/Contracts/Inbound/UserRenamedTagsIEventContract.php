<?php

namespace App\Tests\Modules\Tags\Contracts\Inbound;

use App\Modules\Comments\Api\Event\Inbound\PostCreatedCommentsIEvent;
use App\Modules\Comments\Api\Event\Inbound\UserRenamedCommentsIEvent;
use App\Modules\Posts\Api\Event\Inbound\UserRenamedPostsIEvent;
use App\Modules\Tags\Api\Event\Inbound\UserRenamedTagsIEvent;
use App\Tests\TestUtils\Contracts\ApplicationInboundEventContract;

class UserRenamedTagsIEventContract extends ApplicationInboundEventContract
{

    public function testPostCreatedIEvent()
    {
        self::assertTrue(
            $this->verifyContract(UserRenamedTagsIEvent::class, "Tags/UserRenamedTagsIEvent")
        );
    }

}