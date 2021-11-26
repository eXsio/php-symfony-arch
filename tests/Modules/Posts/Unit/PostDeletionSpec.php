<?php

namespace App\Tests\Modules\Posts\Unit;

use App\Modules\Posts\Api\Command\CreatePostCommand;
use App\Modules\Posts\Api\Command\DeletePostCommand;
use App\Modules\Posts\Api\Query\FindPostByIdQuery;
use App\Modules\Posts\Domain\Event\Outbound\PostDeletedOEvent;
use App\Tests\TestUtils\Events\InMemoryEventPublisher;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Uid\Ulid;

class PostDeletionSpec extends PostsSpec
{

    /**
     * @test
     */
    public function shouldDeleteExistingPost()
    {
        //given: there is a new Blog Post already created
        $command = new CreatePostCommand('Post Title', 'Post Body', ['t1', 't2']);
        $createResponse = $this->postsApi->createPost($command);

        //and: the Post is to be deleted
        $command = new DeletePostCommand($createResponse->getId());

        //when: the Post is deleted
        $this->postsApi->deletePost($command);

        //then: the Post was deleted correctly
        $findResponse = $this->postsApi->findPostById(new FindPostByIdQuery($createResponse->getId()));
        self::assertNull($findResponse);

        //and: a Post Created Output Event was Published
        $events = InMemoryEventPublisher::get(PostDeletedOEvent::class);
        self::assertCount(1, $events);

        //and: The Event has the correct data
        InMemoryEventPublisher::assertEventData([
            'id' => $createResponse->getId()
        ], $events[0]);


    }

    /**
     * @test
     */
    public function shouldTrowErrorWhenTryingToDeleteNonExistingPost()
    {
        //expect: a Not Found Error
        $this->expectException(NotFoundHttpException::class);

        //when: the not-existent Post is deleted
        $this->postsApi->deletePost(new DeletePostCommand(new Ulid()));
    }

}