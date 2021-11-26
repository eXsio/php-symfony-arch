<?php

namespace App\Tests\Modules\Posts\Unit;

use App\Modules\Posts\Api\Command\CreatePostCommand;
use App\Modules\Posts\Api\Query\FindPostByIdQuery;
use App\Modules\Posts\Domain\Event\Outbound\PostCreatedOEvent;
use App\Tests\TestUtils\Events\InMemoryEventPublisher;
use App\Tests\TestUtils\Security\InMemoryLoggedInUserProvider;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class PostCreationSpec extends PostsSpec
{

    /**
     * @test
     * @dataProvider getValidNewPostData
     */
    function shouldCreateNewValidPost(string $title, string $body, array $tags, string $expectedSummary)
    {
        //given: there is a new Blog Post to be created with valid data
        $command = new CreatePostCommand($title, $body, $tags);

        //when: the Post is created
        $response = $this->postsApi->createPost($command);

        //then: the ID of the new Post was assigned and returned
        self::assertNotNull($response);
        self::assertNotNull($response->getId());

        //and: the Post can be found
        $findResponse = $this->postsApi->findPostById(new FindPostByIdQuery($response->getId()));
        self::assertNotNull($findResponse);
        self::assertEquals($findResponse->getId(), $response->getId());

        //and: a Post Created Output Event was Published
        $events = InMemoryEventPublisher::get(PostCreatedOEvent::class);
        self::assertCount(1, $events);

        //and: The Event has the correct data
        InMemoryEventPublisher::assertEventData([
            'title' => $title,
            'body' => $body,
            'tags' => $tags,
            'summary' => $expectedSummary,
            'createdByName' => InMemoryLoggedInUserProvider::$USER_NAME,
            'createdAt' => 'createdAt',
            'createdById' => InMemoryLoggedInUserProvider::$USER_ID
        ], $events[0]);
    }

    /**
     * @test
     * @dataProvider getInvalidNewPostData
     */
    function shouldThrowErrorWhenTryingToCreateNewInvalidPost(string $title, string $body, array $tags)
    {
        //given: there is a new Blog Post to be created with invalid data
        $command = new CreatePostCommand($title, $body, $tags);

        //expect: a Bad Request Error
        $this->expectException(BadRequestHttpException::class);

        //when: the Post is created
        $this->postsApi->createPost($command);

    }

    public function getValidNewPostData(): array
    {
        return [
            ['title', 'body', ['t1', 't2'], 'body'],
            ['title', 'body', [], 'body']
        ];
    }

    public function getInvalidNewPostData(): array
    {
        return [
            ['', 'body', ['t1', 't2']],
            ['title', '', ['t1', 't2']],
            ['title', 'body', ['t1', '']],
        ];
    }
}