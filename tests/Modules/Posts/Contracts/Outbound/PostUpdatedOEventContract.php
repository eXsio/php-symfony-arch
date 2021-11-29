<?php

namespace App\Tests\Modules\Posts\Contracts\Outbound;

use App\Modules\Posts\Domain\Dto\UpdateExistingPostDto;
use App\Modules\Posts\Domain\Event\Outbound\PostUpdatedOEvent;
use App\Tests\TestUtils\Contracts\ApplicationOutboundEventContract;
use Symfony\Component\Uid\Ulid;

class PostUpdatedOEventContract extends ApplicationOutboundEventContract
{

    public function testPostCreatedOEvent()
    {
        self::assertTrue(
            $this->verifyContracts($this->createEvent(), [
                'Comments/PostUpdatedCommentsIEvent',
                'Tags/PostUpdatedTagsIEvent',
            ])
        );
    }

    /**
     * @return PostUpdatedOEvent
     */
    protected function createEvent(): PostUpdatedOEvent
    {
        return new PostUpdatedOEvent(
            new UpdateExistingPostDto(
                new Ulid(),
                'Post Title',
                'Post Body',
                'Post Body',
                ['t1', 't2'],
                new \DateTime()
            ),
            1);
    }
}