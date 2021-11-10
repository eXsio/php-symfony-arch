<?php

namespace App\Tests\Modules\Comments\Contracts\Outbound;

use App\Modules\Comments\Domain\Dto\CommentDto;
use App\Modules\Comments\Domain\Event\Outbound\CommentsBaselinedOEvent;
use App\Tests\TestUtils\Contracts\ApplicationOutboundEventContract;
use Symfony\Component\Uid\Ulid;

class CommentsBaselinedOEventContract extends ApplicationOutboundEventContract
{

    public function testCommentCreatedOEvent()
    {
        self::assertTrue(
            $this->verifyContracts($this->createEvent(), ['Posts/CommentsBaselinedPostsIEvent'])
        );
    }

    /**
     * @return CommentsBaselinedOEvent
     */
    protected function createEvent(): CommentsBaselinedOEvent
    {
        return new CommentsBaselinedOEvent(
            new Ulid(),
            [new CommentDto(new Ulid(), '', '', null, new \DateTime())]);
    }
}