<?php

namespace App\Tests\Modules\Posts\Contracts\Outbound;

use App\Modules\Posts\Domain\Dto\DeleteExistingPostDto;
use App\Modules\Posts\Domain\Event\Outbound\PostDeletedOEvent;
use App\Tests\TestUtils\Contracts\ApplicationOutboundEventContract;
use Symfony\Component\Uid\Ulid;

class PostDeletedOEventContract extends ApplicationOutboundEventContract
{

    public function testPostCreatedOEvent()
    {
        self::assertTrue(
            $this->verifyContracts($this->createEvent(), [
                'Comments/PostDeletedCommentsIEvent',
                'Tags/PostDeletedTagsIEvent',
            ])
        );
    }

    /**
     * @return PostDeletedOEvent
     */
    protected function createEvent(): PostDeletedOEvent
    {
        return new PostDeletedOEvent(new DeleteExistingPostDto(new Ulid()));
    }
}