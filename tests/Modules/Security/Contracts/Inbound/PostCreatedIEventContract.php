<?php

namespace App\Tests\Modules\Security\Contracts\Inbound;

use App\Modules\Security\Api\Event\Inbound\PostCreatedSecurityIEvent;
use App\Tests\TestUtils\Contracts\ApplicationInboundEventContract;

class PostCreatedIEventContract extends ApplicationInboundEventContract
{

    public function testPostCreatedIEvent()
    {
        self::assertTrue(
            $this->verifyContract(PostCreatedSecurityIEvent::class, "Security/PostCreatedSecurityIEvent")
        );
    }

}