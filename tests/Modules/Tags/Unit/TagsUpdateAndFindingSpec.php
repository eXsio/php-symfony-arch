<?php

namespace App\Tests\Modules\Tags\Unit;

use App\Modules\Tags\Api\Event\Inbound\PostCreatedTagsIEvent;
use App\Modules\Tags\Api\Event\Inbound\PostDeletedTagsIEvent;
use App\Modules\Tags\Api\Event\Inbound\PostUpdatedTagsIEvent;
use App\Modules\Tags\Api\Query\FindTagsPostHeadersQuery;
use App\Modules\Tags\Api\Query\FindTagsQuery;
use App\Tests\TestUtils\Contracts\ApplicationEventContractLoader;
use Symfony\Component\Uid\Ulid;

class TagsUpdateAndFindingSpec extends TagsSpec
{
    use ApplicationEventContractLoader;


    /**
     * @test
     */
    public function shouldFindTagsAfterPostWasCreated(): void
    {
        //given: there was a PostCreatedIEvent to be published
        $event = new PostCreatedTagsIEvent($this->getInboundEvent("Tags/PostCreatedTagsIEvent"));

        //and: the Event was already published
        $this->tagsApi->onPostCreated($event);

        //when: tags are queried
        $result = $this->tagsApi->findTags(new FindTagsQuery());

        //then: Post Header was created - no Exception was thrown
        self::assertNotNull($result);
        self::assertCount(1, $result);
        self::assertTrue(isset($result[0]));
        self::assertEquals('t1', $result[0]->getTag());
        self::assertEquals(1, $result[0]->getPostsCount());
    }

    /**
     * @test
     */
    public function shouldFindTagsAfterPostWasUpdated(): void
    {
        //given: there was a PostCreatedIEvent to be published
        $event = new PostCreatedTagsIEvent($this->getInboundEvent("Tags/PostCreatedTagsIEvent"));

        //and: the Event was already published
        $this->tagsApi->onPostCreated($event);

        //and: the Header was to be updated
        $event = new PostUpdatedTagsIEvent($this->getInboundEvent("Tags/PostUpdatedTagsIEvent"));

        //and: the Event was published
        $this->tagsApi->onPostUpdated($event);

        //when: tags are queried
        $result = $this->tagsApi->findTags(new FindTagsQuery());

        //then: Post Header was created - no Exception was thrown
        self::assertNotNull($result);
        self::assertCount(1, $result);
        self::assertTrue(isset($result[0]));
        self::assertEquals('t2', $result[0]->getTag());
        self::assertEquals(1, $result[0]->getPostsCount());
    }

    /**
     * @test
     */
    public function shouldFindTagsAfterPostWasDeleted(): void
    {
        //given: there was a PostCreatedIEvent to be published
        $event = new PostCreatedTagsIEvent($this->getInboundEvent("Tags/PostCreatedTagsIEvent"));

        //and: the Event was already published
        $this->tagsApi->onPostCreated($event);

        //and: the Header was to be deleted
        $event = new PostDeletedTagsIEvent($this->getInboundEvent("Tags/PostDeletedTagsIEvent"));

        //and: the Event was published
        $this->tagsApi->onPostDeleted($event);

        //when: tags are queried
        $result = $this->tagsApi->findTags(new FindTagsQuery());

        //then: Post Header was created - no Exception was thrown
        self::assertNotNull($result);
        self::assertCount(0, $result);
    }

}