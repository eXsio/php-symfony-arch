<?php

namespace App\Tests\Modules\Comments\Unit;

use App\Modules\Comments\Api\Event\Inbound\PostCreatedCommentsIEvent;
use App\Modules\Comments\Api\Event\Inbound\UserRenamedCommentsIEvent;
use App\Tests\TestUtils\Contracts\ApplicationEventContractLoader;

class SecurityEventsCommentsHandlingSpec extends CommentsSpec
{
    use ApplicationEventContractLoader;


    /**
     * @test
     */
    public function shouldUpdatePostHeaderUponUserRenamedIEvent(): void
    {
        //given: there was a PostCreatedIEvent to be published
        $event = new PostCreatedCommentsIEvent($this->getInboundEvent("Comments/PostCreatedCommentsIEvent"));

        //and: the Event was already published
        $this->commentsApi->onPostCreated($event);

        //and: the Header was to be updated
        $event = new UserRenamedCommentsIEvent($this->getInboundEvent("Comments/UserRenamedCommentsIEvent"));

        //when: the Event was published
        $this->commentsApi->onUserRenamed($event);

        //then: Post Header was created - no Exception was thrown
        $headers = $this->commentsApi->findPostHeaders();
        self::assertNotNull($headers);
        self::assertCount(1, $headers);
        self::assertTrue(isset($headers[0]));
        self::assertEquals($event->getNewLogin(), $headers[0]->getCreatedByName());
    }


}