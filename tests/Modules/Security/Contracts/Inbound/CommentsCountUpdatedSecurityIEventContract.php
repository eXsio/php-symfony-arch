<?php

namespace App\Tests\Modules\Security\Contracts\Inbound;

use App\Modules\Security\Api\Event\Inbound\CommentsCountUpdatedSecurityIEvent;
use App\Tests\TestUtils\Contracts\ApplicationInboundEventContract;

class CommentsCountUpdatedSecurityIEventContract extends ApplicationInboundEventContract
{

    public function testPostCreatedIEvent()
    {
        self::assertTrue(
            $this->verifyContract(CommentsCountUpdatedSecurityIEvent::class, "Security/CommentsCountUpdatedSecurityIEvent")
        );
    }

}