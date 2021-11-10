<?php

namespace App\Tests\Modules\Posts\Unit;

use App\Modules\Posts\Api\Command\CreatePostCommand;
use App\Modules\Posts\Api\Event\Inbound\CommentCreatedPostsIEvent;
use App\Modules\Posts\Api\Query\FindPostByIdQuery;
use App\Tests\TestUtils\Contracts\ApplicationEventContractLoader;

class CommentsEvensPostsHandlingSpec extends PostsSpec
{

    use ApplicationEventContractLoader;

    /**
     * @test
     */
    public function shouldUpdateExistingPostWithComment()
    {
        //given: there is a new Blog Post already created
        $command = new CreatePostCommand('Post Title', 'Post Body', ['t1', 't2']);
        $createResponse = $this->postsApi->createPost($command);

        //and: There was a Comment Created
        $data = $this->getInboundEvent("Posts/CommentCreatedPostsIEvent");
        $data['postId'] = $createResponse->getId();
        $event = new CommentCreatedPostsIEvent($data);

        //when: the Comment Created Event is handled
        $this->postsApi->onCommentCreated($event);

        //and: User fetches the Post
        $findResponse = $this->postsApi->findPostById(new FindPostByIdQuery($createResponse->getId()));

        //then: Previously created Post is fetched with the Comment
        self::assertNotNull($findResponse);
        self::assertEquals($createResponse->getId(), $findResponse->getId());
        self::assertCount(1, $findResponse->getComments());
        self::assertEquals('Comment Author', $findResponse->getComments()[0]['author']);
        self::assertEquals('Comment Body', $findResponse->getComments()[0]['body']);
        self::assertNull($findResponse->getComments()[0]['parentId']);
    }

}