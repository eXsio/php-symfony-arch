<?php

namespace App\Tests\Modules\Posts\Contracts\Inbound;

use App\Modules\Posts\Api\Event\Inbound\UserRenamedPostsIEvent;
use App\Tests\TestUtils\Contracts\ApplicationInboundEventContract;

class UserRenamedPostsIEventContract extends ApplicationInboundEventContract
{

    public function testPostCreatedIEvent()
    {
        self::assertTrue(
            $this->verifyContract(UserRenamedPostsIEvent::class, "Posts/UserRenamedPostsIEvent")
        );
    }

}