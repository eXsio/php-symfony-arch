<?php

namespace App\Tests\Modules\Tags\Integration;

use App\Modules\Tags\Api\Event\Inbound\CommentsCountUpdatedTagsIEvent;
use App\Modules\Tags\Api\Event\Inbound\PostCreatedTagsIEvent;
use App\Modules\Tags\Api\Event\Inbound\PostDeletedTagsIEvent;
use App\Modules\Tags\Api\Event\Inbound\PostUpdatedTagsIEvent;
use App\Modules\Tags\Api\Event\Inbound\UserRenamedTagsIEvent;
use App\Modules\Tags\Api\Query\FindPostsByTagQuery;
use App\Modules\Tags\Api\Query\Response\FindPostsByTagQueryResponse;
use App\Modules\Tags\Api\TagsApiInterface;
use App\Tests\Modules\Tags\Integration\Http\TagsHttpTrait;
use App\Tests\TestUtils\Contracts\ApplicationEventContractLoader;
use App\Tests\TestUtils\IntegrationTest;

class TagsIT extends IntegrationTest
{
    use TagsHttpTrait;
    use ApplicationEventContractLoader;


    /**
     * @test
     */
    public function shouldFindTagsAndPostsAfterPostWasCreated(): void
    {
        //given: there was a PostCreatedIEvent to be published
        $event = new PostCreatedTagsIEvent($this->getInboundEvent("Tags/PostCreatedTagsIEvent"));

        //and: the Event was already published
        $this->getTagsApi()->onPostCreated($event);

        //when: tags are queried
        $result = $this->findTags();

        //then: Post Header was created - no Exception was thrown
        self::assertNotNull($result);
        self::assertCount(1, $result);
        self::assertTrue(isset($result[0]));
        self::assertEquals('t1', $result[0]->getTag());
        self::assertEquals(1, $result[0]->getPostsCount());

        //when: posts are queried
        $result = $this->findPostByTag(new FindPostsByTagQuery('t1', 1));

        //then: Post Header was created - no Exception was thrown
        self::assertNotNull($result);
        self::assertCount(1, $result->getData());
        self::assertEquals(1, $result->getCount());
        self::assertTrue(isset($result->getData()[0]));
        $post = $this->convert($result->getData()[0], FindPostsByTagQueryResponse::class);
        self::assertEquals('Post Title', $post->getTitle());
        self::assertEquals('Post Body', $post->getSummary());
        self::assertEquals(1, $post->getVersion());
    }

    /**
     * @test
     */
    public function shouldFindTagsAndPostsAfterPostWasUpdated(): void
    {
        //given: there was a PostCreatedIEvent to be published
        $event = new PostCreatedTagsIEvent($this->getInboundEvent("Tags/PostCreatedTagsIEvent"));

        //and: the Event was already published
        $this->getTagsApi()->onPostCreated($event);

        //and: the Header was to be updated
        $event = new PostUpdatedTagsIEvent($this->getInboundEvent("Tags/PostUpdatedTagsIEvent"));

        //and: the Event was published
        $this->getTagsApi()->onPostUpdated($event);

        //and: the Comments Count was to be updated
        $event = new CommentsCountUpdatedTagsIEvent($this->getInboundEvent("Tags/CommentsCountUpdatedTagsIEvent"));

        //and: the Event was published
        $this->getTagsApi()->onCommentsCountUpdated($event);

        //and: the Comments Count was to be updated
        $event = new UserRenamedTagsIEvent($this->getInboundEvent("Tags/UserRenamedTagsIEvent"));

        //and: the Event was published
        $this->getTagsApi()->onUserRenamed($event);

        //when: tags are queried
        $result = $this->findTags();

        //then: Post Header was created - no Exception was thrown
        self::assertNotNull($result);
        self::assertCount(1, $result);
        self::assertTrue(isset($result[0]));
        self::assertEquals('t2', $result[0]->getTag());
        self::assertEquals(1, $result[0]->getPostsCount());

        //when: posts are queried
        $result = $this->findPostByTag(new FindPostsByTagQuery('t2', 1));

        //then: Post Header was created - no Exception was thrown
        self::assertNotNull($result);
        self::assertCount(1, $result->getData());
        self::assertEquals(1, $result->getCount());
        self::assertTrue(isset($result->getData()[0]));
        $post = $this->convert($result->getData()[0], FindPostsByTagQueryResponse::class);
        self::assertEquals('Post Title Updated', $post->getTitle());
        self::assertEquals('Post Body Updated', $post->getSummary());
        self::assertEquals(2, $post->getVersion());
        self::assertEquals(2, $post->getCommentsCount());
        self::assertEquals($event->getNewLogin(), $post->getCreatedByName());
    }

    /**
     * @test
     */
    public function shouldFindTagsAndPostsAfterPostWasDeleted(): void
    {
        //given: there was a PostCreatedIEvent to be published
        $event = new PostCreatedTagsIEvent($this->getInboundEvent("Tags/PostCreatedTagsIEvent"));

        //and: the Event was already published
        $this->getTagsApi()->onPostCreated($event);

        //and: the Header was to be deleted
        $event = new PostDeletedTagsIEvent($this->getInboundEvent("Tags/PostDeletedTagsIEvent"));

        //and: the Event was published
        $this->getTagsApi()->onPostDeleted($event);

        //when: tags are queried
        $result = $this->findTags();

        //then: Post Header was created - no Exception was thrown
        self::assertNotNull($result);
        self::assertCount(0, $result);

        //when: posts are queried
        $result = $this->findPostByTag(new FindPostsByTagQuery('t1', 1));

        //then: There is no Post to be found
        self::assertNotNull($result);
        self::assertCount(0, $result->getData());
        self::assertEquals(0, $result->getCount());
    }

    private function getTagsApi(): TagsApiInterface
    {
        return $this->getContainer()->get(TagsApiInterface::class);
    }

}