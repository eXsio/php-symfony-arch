<?php

namespace App\Tests\Modules\Tags\Unit;

use App\Modules\Tags\Api\Event\Inbound\CommentsCountUpdatedTagsIEvent;
use App\Modules\Tags\Api\Event\Inbound\PostCreatedTagsIEvent;
use App\Modules\Tags\Api\Event\Inbound\PostDeletedTagsIEvent;
use App\Modules\Tags\Api\Event\Inbound\PostUpdatedTagsIEvent;
use App\Modules\Tags\Api\Query\FindTagsPostHeadersQuery;
use App\Tests\TestUtils\Contracts\ApplicationEventContractLoader;
use Symfony\Component\Uid\Ulid;

class CommentsEventsTagsHandlingSpec extends TagsSpec
{
    use ApplicationEventContractLoader;


    /**
     * @test
     */
    public function shouldUpdatePostHeaderUponPostUpdatedIEvent(): void
    {
        //given: there was a PostCreatedIEvent to be published
        $event = new PostCreatedTagsIEvent($this->getInboundEvent("Tags/PostCreatedTagsIEvent"));

        //and: the Event was already published
        $this->tagsApi->onPostCreated($event);

        //and: the Comments Count was to be updated
        $event = new CommentsCountUpdatedTagsIEvent($this->getInboundEvent("Tags/CommentsCountUpdatedTagsIEvent"));

        //when: the Event was published
        $this->tagsApi->onCommentsCountUpdated($event);

        //then: Post Header was created - no Exception was thrown
        $headers = $this->tagsApi->findPostHeaders(new FindTagsPostHeadersQuery());
        self::assertNotNull($headers);
        self::assertCount(1, $headers);
        self::assertTrue(isset($headers[0]));
        self::assertEquals($event->getPostId(), $headers[0]->getId());
        self::assertEquals($event->getCommentsCount(), $headers[0]->getCommentsCount());

    }


}