<?php

namespace App\Tests\Modules\Security\Contracts\Outbound;

use App\Modules\Security\Domain\Event\Outbound\UserRenamedOEvent;
use App\Tests\TestUtils\Contracts\ApplicationOutboundEventContract;

class UserRenamedOEventContract extends ApplicationOutboundEventContract
{

    public function testUserRenamedOEvent()
    {
        self::assertTrue(
            $this->verifyContracts($this->createEvent(), [
                'Posts/UserRenamedPostsIEvent',
                'Tags/UserRenamedTagsIEvent',
            ])
        );
    }

    /**
     * @return UserRenamedOEvent
     */
    protected function createEvent(): UserRenamedOEvent
    {
        return new UserRenamedOEvent(
            "oldLogin@exsio.com",
            "newLogin@exsio.com"
        );
    }
}