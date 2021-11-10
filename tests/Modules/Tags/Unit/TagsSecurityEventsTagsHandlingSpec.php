<?php

namespace App\Tests\Modules\Tags\Unit;

use App\Modules\Tags\Api\Event\Inbound\CommentsCountUpdatedTagsIEvent;
use App\Modules\Tags\Api\Event\Inbound\PostCreatedTagsIEvent;
use App\Modules\Tags\Api\Event\Inbound\PostDeletedTagsIEvent;
use App\Modules\Tags\Api\Event\Inbound\PostUpdatedTagsIEvent;
use App\Modules\Tags\Api\Event\Inbound\UserRenamedTagsIEvent;
use App\Modules\Tags\Api\Query\FindTagsPostHeadersQuery;
use App\Tests\TestUtils\Contracts\ApplicationEventContractLoader;
use Symfony\Component\Uid\Ulid;

class TagsSecurityEventsTagsHandlingSpec extends TagsSpec
{
    use ApplicationEventContractLoader;


    /**
     * @test
     */
    public function shouldUpdatePostHeaderUponUserRenamedIEvent(): void
    {
        //given: there was a PostCreatedIEvent to be published
        $event = new PostCreatedTagsIEvent($this->getInboundEvent("Tags/PostCreatedTagsIEvent"));

        //and: the Event was already published
        $this->tagsApi->onPostCreated($event);

        //and: the Comments Count was to be updated
        $event = new UserRenamedTagsIEvent($this->getInboundEvent("Tags/UserRenamedTagsIEvent"));

        //when: the Event was published
        $this->tagsApi->onUserRenamed($event);

        //then: Post Header was created - no Exception was thrown
        $headers = $this->tagsApi->findPostHeaders(new FindTagsPostHeadersQuery());
        self::assertNotNull($headers);
        self::assertCount(1, $headers);
        self::assertTrue(isset($headers[0]));
        self::assertEquals($event->getNewLogin(), $headers[0]->getCreatedByName());

    }


}