<?php

namespace App\Tests\Modules\Posts\Unit;

use App\Modules\Posts\Api\Command\CreatePostCommand;
use App\Modules\Posts\Api\Command\UpdatePostCommand;
use App\Modules\Posts\Api\Query\FindPostByIdQuery;
use App\Modules\Posts\Domain\Event\Outbound\PostUpdatedOEvent;
use App\Tests\TestUtils\Events\InMemoryEventPublisher;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Uid\Ulid;

class PostUpdatingSpec extends PostsSpec
{

    /**
     * @test
     * @dataProvider getUpdatedValidPostData
     */
    function shouldUpdateExistingPostWithValidData(string $title, string $body, array $tags, string $expectedSummary)
    {
        //given: there is a new Blog Post already created
        $command = new CreatePostCommand('Post Title', 'Post Body', ['t1', 't2']);
        $createResponse = $this->postsApi->createPost($command);

        //and: the Post is to be updated
        $command = new UpdatePostCommand($title, $body, $tags);
        $command->setId($createResponse->getId());

        //when: the Post is updated
        $this->postsApi->updatePost($command);

        //then: the Post was updated correctly
        $findResponse = $this->postsApi->findPostById(new FindPostByIdQuery($createResponse->getId()));
        self::assertNotNull($findResponse);
        self::assertEquals($findResponse->getId(), $createResponse->getId());
        self::assertEquals($title, $findResponse->getTitle());
        self::assertEquals($body, $findResponse->getBody());
        self::assertEquals($tags, $findResponse->getTags());

        //and: a Post Created Output Event was Published
        $events = InMemoryEventPublisher::get(PostUpdatedOEvent::class);
        self::assertCount(1, $events);

        //and: The Event has the correct data
        InMemoryEventPublisher::assertEventData([
            'id' => $createResponse->getId(),
            'title' => $title,
            'body' => $body,
            'tags' => $tags,
            'summary' => $expectedSummary,
            'updatedAt' => 'updatedAt',
        ], $events[0]);
    }

    /**
     * @test
     * @dataProvider getUpdatedInvalidPostData
     */
    function shouldThrowErrorWhenTryingToUpdateExistingPostWithInvalidData(string $title, string $body, array $tags)
    {
        //given: there is a new Blog Post already created
        $command = new CreatePostCommand('Post Title', 'Post Body', ['t1', 't2']);
        $createResponse = $this->postsApi->createPost($command);

        //and: the Post is to be updated
        $command = new UpdatePostCommand($title, $body, $tags);
        $command->setId($createResponse->getId());

        //expect: Bad Request Error
        $this->expectException(BadRequestHttpException::class);

        //when: the Post is updated
        $this->postsApi->updatePost($command);
    }

    /**
     * @test
     */
    function shouldTrowErrorWhenTryingToUpdateNonExistingPost()
    {
        //given: user wants to update a non-existent Post
        $command = new UpdatePostCommand('Post Title', 'Post Body', ['t1', 't2']);
        $command->setId(new Ulid());

        //expect: a Not Found Error
        $this->expectException(NotFoundHttpException::class);

        //when: the not-existent Post is updated
        $this->postsApi->updatePost($command);
    }

    public function getUpdatedValidPostData(): array
    {
        return [
            ['title updated', 'body updated', ['t3', 't4'], 'body updated'],
            ['title updated', 'body updated', [], 'body updated']
        ];
    }

    public function getUpdatedInvalidPostData(): array
    {
        return [
            ['', 'body', ['t1', 't2']],
            ['title', '', ['t1', 't2']],
            ['title', 'body', ['t1', '']],
        ];
    }
}