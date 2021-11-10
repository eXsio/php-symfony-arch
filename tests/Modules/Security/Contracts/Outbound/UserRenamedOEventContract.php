<?php

namespace App\Tests\Modules\Security\Contracts\Outbound;

use App\Modules\Comments\Domain\Dto\CommentDto;
use App\Modules\Comments\Domain\Event\Outbound\CommentCreatedOEvent;
use App\Modules\Security\Domain\Event\Outbound\UserRenamedOEvent;
use App\Tests\TestUtils\Contracts\ApplicationOutboundEventContract;
use Symfony\Component\Uid\Ulid;

class UserRenamedOEventContract extends ApplicationOutboundEventContract
{

    public function testUserRenamedOEvent()
    {
        self::assertTrue(
            $this->verifyContracts($this->createEvent(), [
                'Posts/UserRenamedPostsIEvent',
                'Comments/UserRenamedCommentsIEvent',
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