<?php

namespace App\Tests\Modules\Posts\Contracts\Outbound;

use App\Modules\Posts\Domain\Dto\CreateNewPostDto;
use App\Modules\Posts\Domain\Dto\DeleteExistingPostDto;
use App\Modules\Posts\Domain\Event\Outbound\PostCreatedOEvent;
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
                'Security/PostDeletedSecurityIEvent',
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