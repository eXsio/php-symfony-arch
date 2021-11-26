<?php

namespace App\Tests\Modules\Tags\Unit;

use App\Modules\Tags\Api\Event\Inbound\PostCreatedTagsIEvent;
use App\Modules\Tags\Api\Event\Inbound\PostDeletedTagsIEvent;
use App\Modules\Tags\Api\Event\Inbound\PostUpdatedTagsIEvent;
use App\Tests\TestUtils\Contracts\ApplicationEventContractLoader;

class PostEventsTagsHandlingSpec extends TagsSpec
{
    use ApplicationEventContractLoader;

    /**
     * @test
     */
    public function shouldCreatePostHeaderUponPostCreatedIEvent()
    {
        //given: there was a PostCreatedIEvent to be published
        $event = new PostCreatedTagsIEvent($this->getInboundEvent("Tags/PostCreatedTagsIEvent"));

        //when: the Event was published
        $this->tagsApi->onPostCreated($event);

        //then: Post Header was created - no Exception was thrown
        $headers = $this->tagsApi->findPostHeaders();
        self::assertNotNull($headers);
        self::assertCount(1, $headers);
        self::assertTrue(isset($headers[0]));
        self::assertEquals($event->getId(), $headers[0]->getId());
        self::assertEquals($event->getTitle(), $headers[0]->getTitle());
        self::assertEquals($event->getSummary(), $headers[0]->getSummary());
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
        $event = new PostCreatedTagsIEvent($this->getInboundEvent("Tags/PostCreatedTagsIEvent"));

        //and: the Event was already published
        $this->tagsApi->onPostCreated($event);

        //and: the Header was to be updated
        $event = new PostUpdatedTagsIEvent($this->getInboundEvent("Tags/PostUpdatedTagsIEvent"));

        //when: the Event was published
        $this->tagsApi->onPostUpdated($event);

        //then: Post Header was created - no Exception was thrown
        $headers = $this->tagsApi->findPostHeaders();
        self::assertNotNull($headers);
        self::assertCount(1, $headers);
        self::assertTrue(isset($headers[0]));
        self::assertEquals($event->getId(), $headers[0]->getId());
        self::assertEquals($event->getTitle(), $headers[0]->getTitle());
        self::assertEquals($event->getSummary(), $headers[0]->getSummary());
        self::assertEquals($event->getLastVersion(), $headers[0]->getVersion());
    }

    /**
     * @test
     */
    public function shouldDeletePostHeaderUponPostUpdatedIEvent(): void
    {
        //given: there was a PostCreatedIEvent to be published
        $event = new PostCreatedTagsIEvent($this->getInboundEvent("Tags/PostCreatedTagsIEvent"));

        //and: the Event was already published
        $this->tagsApi->onPostCreated($event);

        //and: the Header was to be updated
        $event = new PostDeletedTagsIEvent($this->getInboundEvent("Tags/PostDeletedTagsIEvent"));

        //when: the Event was published
        $this->tagsApi->onPostDeleted($event);

        //then: Post Header was created - no Exception was thrown
        $headers = $this->tagsApi->findPostHeaders();
        self::assertNotNull($headers);
        self::assertCount(0, $headers);
    }

}