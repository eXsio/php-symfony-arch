<?php

namespace App\Tests\Modules\Tags\Contracts\Inbound;

use App\Modules\Security\Api\Event\Inbound\CommentsCountUpdatedSecurityIEvent;
use App\Modules\Tags\Api\Event\Inbound\CommentsCountUpdatedTagsIEvent;
use App\Tests\TestUtils\Contracts\ApplicationInboundEventContract;

class CommentsCountUpdatedTagsIEventContract extends ApplicationInboundEventContract
{

    public function testPostCreatedIEvent()
    {
        self::assertTrue(
            $this->verifyContract(CommentsCountUpdatedTagsIEvent::class, "Tags/CommentsCountUpdatedTagsIEvent")
        );
    }

}