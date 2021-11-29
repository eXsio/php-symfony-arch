<?php

namespace App\Tests\Modules\Comments\Contracts\Outbound;

use App\Modules\Comments\Domain\Event\Outbound\CommentsCountUpdatedOEvent;
use App\Tests\TestUtils\Contracts\ApplicationOutboundEventContract;
use Symfony\Component\Uid\Ulid;

class CommentsCountUpdatedOEventContract extends ApplicationOutboundEventContract
{

    public function testCommentCreatedOEvent()
    {
        self::assertTrue(
            $this->verifyContracts($this->createEvent(), [
                'Tags/CommentsCountUpdatedTagsIEvent',
            ])
        );
    }

    /**
     * @return CommentsCountUpdatedOEvent
     */
    protected function createEvent(): CommentsCountUpdatedOEvent
    {
        return new CommentsCountUpdatedOEvent(
            new Ulid(),
            3);
    }
}