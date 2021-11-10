<?php

namespace App\Tests\Modules\Comments\Contracts\Outbound;

use App\Modules\Comments\Domain\Dto\CommentDto;
use App\Modules\Comments\Domain\Event\Outbound\CommentCreatedOEvent;
use App\Tests\TestUtils\Contracts\ApplicationOutboundEventContract;
use Symfony\Component\Uid\Ulid;

class CommentCreatedOEventContract extends ApplicationOutboundEventContract
{

    public function testCommentCreatedOEvent()
    {
        self::assertTrue(
            $this->verifyContracts($this->createEvent(), ['Posts/CommentCreatedPostsIEvent'])
        );
    }

    /**
     * @return CommentCreatedOEvent
     */
    protected function createEvent(): CommentCreatedOEvent
    {
        return new CommentCreatedOEvent(
            new Ulid(),
            new CommentDto(
                new Ulid(),
                'Post Body',
                'Post Body',
                new Ulid(),
                new \DateTime()
            ));
    }
}