<?php

namespace App\Tests\Modules\Security\Contracts\Inbound;

use App\Modules\Security\Api\Event\Inbound\PostUpdatedSecurityIEvent;
use App\Tests\TestUtils\Contracts\ApplicationInboundEventContract;

class PostUpdatedIEventContract extends ApplicationInboundEventContract
{

    public function testPostCreatedIEvent()
    {
        self::assertTrue(
            $this->verifyContract(PostUpdatedSecurityIEvent::class, "Security/PostUpdatedSecurityIEvent")
        );
    }

}