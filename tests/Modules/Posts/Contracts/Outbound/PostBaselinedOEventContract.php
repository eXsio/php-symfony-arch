<?php

namespace App\Tests\Modules\Posts\Contracts\Outbound;

use App\Modules\Posts\Domain\Dto\PostForBaselineDto;
use App\Modules\Posts\Domain\Event\Outbound\PostBaselinedOEvent;
use App\Tests\TestUtils\Contracts\ApplicationOutboundEventContract;
use Symfony\Component\Uid\Ulid;

class PostBaselinedOEventContract extends ApplicationOutboundEventContract
{

    public function testPostBaselinedOEvent()
    {
        self::assertTrue(
            $this->verifyContracts($this->createEvent(), [
                'Comments/PostBaselinedCommentsIEvent',
                'Tags/PostBaselinedTagsIEvent',
            ])
        );
    }

    /**
     * @return PostBaselinedOEvent
     */
    protected function createEvent(): PostBaselinedOEvent
    {
        return new PostBaselinedOEvent(
            new PostForBaselineDto(
                'Post Title',
                'Post Body',
                'Post Body',
                'Post Body',
                ['t1', 't2'],
                new Ulid(),
                'userId',
                new \DateTime(),
                new \DateTime(),
                1
            ));
    }
}