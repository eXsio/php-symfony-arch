<?php

namespace App\Tests\Modules\Security\Contracts\Inbound;

use App\Modules\Security\Api\Event\Inbound\PostDeletedSecurityIEvent;
use App\Tests\TestUtils\Contracts\ApplicationInboundEventContract;

class PostDeletedIEventContract extends ApplicationInboundEventContract
{

    public function testPostCreatedIEvent()
    {
        self::assertTrue(
            $this->verifyContract(PostDeletedSecurityIEvent::class, "Security/PostDeletedSecurityIEvent")
        );
    }

}