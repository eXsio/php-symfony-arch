<?php

namespace App\Tests\Modules\Comments\Unit;

use App\Modules\Comments\Api\Event\Inbound\PostCreatedCommentsIEvent;
use App\Modules\Comments\Api\Event\Inbound\PostDeletedCommentsIEvent;
use App\Modules\Comments\Api\Event\Inbound\PostUpdatedCommentsIEvent;
use App\Modules\Comments\Api\Query\FindCommentsPostHeadersQuery;
use App\Tests\TestUtils\Contracts\ApplicationEventContractLoader;
use Symfony\Component\Uid\Ulid;

class PostEventsCommentsHandlingSpec extends CommentsSpec
{
    use ApplicationEventContractLoader;

    /**
     * @test
     */
    public function shouldCreatePostHeaderUponPostCreatedIEvent()
    {
        //given: there was a PostCreatedIEvent to be published
        $event = new PostCreatedCommentsIEvent($this->getInboundEvent("Comments/PostCreatedCommentsIEvent"));

        //when: the Event was published
        $this->commentsApi->onPostCreated($event);

        //then: Post Header was created - no Exception was thrown
        $headers = $this->commentsApi->findPostHeaders();
        self::assertNotNull($headers);
        self::assertCount(1, $headers);
        self::assertTrue(isset($headers[0]));
        self::assertEquals($event->getId(), $headers[0]->getId());
        self::assertEquals($event->getTitle(), $headers[0]->getTitle());
        self::assertEquals($event->getSummary(), $headers[0]->getSummary());
        self::assertEquals($event->getTags(), $headers[0]->getTags());
        self::assertEquals($event->getCreatedById(), $headers[0]->getCreatedById());
        self::assertEquals($event->getCreatedByName(), $headers[0]->getCreatedByName());
        self::assertEquals($event->getCreatedAt(), $headers[0]->getCreatedAt());
        self::assertEquals(1, $headers[0]->getVersion());
    }

    /**
     * @test
     */
    public function shouldUpdatePostHeaderUponPostUpdatedIEvent(): void
    {
        //given: there was a PostCreatedIEvent to be published
        $event = new PostCreatedCommentsIEvent($this->getInboundEvent("Comments/PostCreatedCommentsIEvent"));

        //and: the Event was already published
        $this->commentsApi->onPostCreated($event);

        //and: the Header was to be updated
        $event = new PostUpdatedCommentsIEvent($this->getInboundEvent("Comments/PostUpdatedCommentsIEvent"));

        //when: the Event was published
        $this->commentsApi->onPostUpdated($event);

        //then: Post Header was created - no Exception was thrown
        $headers = $this->commentsApi->findPostHeaders();
        self::assertNotNull($headers);
        self::assertCount(1, $headers);
        self::assertTrue(isset($headers[0]));
        self::assertEquals($event->getId(), $headers[0]->getId());
        self::assertEquals($event->getTitle(), $headers[0]->getTitle());
        self::assertEquals($event->getSummary(), $headers[0]->getSummary());
        self::assertEquals($event->getTags(), $headers[0]->getTags());
        self::assertEquals($event->getLastVersion(), $headers[0]->getVersion());
    }

    /**
     * @test
     */
    public function shouldDeletePostHeaderUponPostUpdatedIEvent(): void
    {
        //given: there was a PostCreatedIEvent to be published
        $event = new PostCreatedCommentsIEvent($this->getInboundEvent("Comments/PostCreatedCommentsIEvent"));

        //and: the Event was already published
        $this->commentsApi->onPostCreated($event);

        //and: the Header was to be updated
        $event = new PostDeletedCommentsIEvent($this->getInboundEvent("Comments/PostDeletedCommentsIEvent"));

        //when: the Event was published
        $this->commentsApi->onPostDeleted($event);

        //then: Post Header was created - no Exception was thrown
        $headers = $this->commentsApi->findPostHeaders();
        self::assertNotNull($headers);
        self::assertCount(0, $headers);
    }
}