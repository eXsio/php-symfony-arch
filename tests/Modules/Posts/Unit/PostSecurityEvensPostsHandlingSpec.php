<?php

namespace App\Tests\Modules\Posts\Unit;

use App\Modules\Posts\Api\Command\CreatePostCommand;
use App\Modules\Posts\Api\Event\Inbound\CommentCreatedPostsIEvent;
use App\Modules\Posts\Api\Event\Inbound\UserRenamedPostsIEvent;
use App\Modules\Posts\Api\Query\FindPostByIdQuery;
use App\Tests\TestUtils\Contracts\ApplicationEventContractLoader;

class PostSecurityEvensPostsHandlingSpec extends PostsSpec
{

    use ApplicationEventContractLoader;

    /**
     * @test
     */
    public function shouldUpdateExistingPostWithRenamedUser()
    {
        //given: there is a new Blog Post already created
        $command = new CreatePostCommand('Post Title', 'Post Body', ['t1', 't2']);
        $createResponse = $this->postsApi->createPost($command);

        //and: There was a User Renamed
        $data = $this->getInboundEvent("Posts/UserRenamedPostsIEvent");
        $event = new UserRenamedPostsIEvent($data);

        //when: the User Renamed Event is handled
        $this->postsApi->onUserRenamed($event);

        //and: User fetches the Post
        $findResponse = $this->postsApi->findPostById(new FindPostByIdQuery($createResponse->getId()));

        //then: Previously created Post is fetched with the Comment
        self::assertNotNull($findResponse);
        self::assertEquals($createResponse->getId(), $findResponse->getId());
        self::assertEquals($event->getNewLogin(), $findResponse->getCreatedByName());
    }

}