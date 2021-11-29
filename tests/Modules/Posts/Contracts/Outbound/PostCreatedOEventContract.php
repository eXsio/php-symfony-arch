<?php

namespace App\Tests\Modules\Posts\Contracts\Outbound;

use App\Modules\Posts\Domain\Dto\CreateNewPostDto;
use App\Modules\Posts\Domain\Event\Outbound\PostCreatedOEvent;
use App\Tests\TestUtils\Contracts\ApplicationOutboundEventContract;
use Symfony\Component\Uid\Ulid;

class PostCreatedOEventContract extends ApplicationOutboundEventContract
{

    public function testPostCreatedOEvent()
    {
        self::assertTrue(
            $this->verifyContracts($this->createEvent(), [
                'Comments/PostCreatedCommentsIEvent',
                'Tags/PostCreatedTagsIEvent',
            ])
        );
    }

    /**
     * @return PostCreatedOEvent
     */
    protected function createEvent(): PostCreatedOEvent
    {
        return new PostCreatedOEvent(
            new Ulid(),
            new CreateNewPostDto(
                'Post Title',
                'Post Body',
                'Post Body',
                ['t1', 't2'],
                new Ulid(),
                'userId',
                new \DateTime()
            ));
    }
}