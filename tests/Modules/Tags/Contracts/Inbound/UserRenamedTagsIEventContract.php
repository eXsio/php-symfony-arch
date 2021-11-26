<?php

namespace App\Tests\Modules\Tags\Contracts\Inbound;

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