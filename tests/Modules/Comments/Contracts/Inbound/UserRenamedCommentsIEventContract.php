<?php

namespace App\Tests\Modules\Comments\Contracts\Inbound;

use App\Modules\Comments\Api\Event\Inbound\PostCreatedCommentsIEvent;
use App\Modules\Comments\Api\Event\Inbound\UserRenamedCommentsIEvent;
use App\Tests\TestUtils\Contracts\ApplicationInboundEventContract;

class UserRenamedCommentsIEventContract extends ApplicationInboundEventContract
{

    public function testPostCreatedIEvent()
    {
        self::assertTrue(
            $this->verifyContract(UserRenamedCommentsIEvent::class, "Comments/UserRenamedCommentsIEvent")
        );
    }

}