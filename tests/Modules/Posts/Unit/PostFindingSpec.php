<?php

namespace App\Tests\Modules\Posts\Unit;

use App\Modules\Posts\Api\Command\CreatePostCommand;
use App\Modules\Posts\Api\Query\FindAllPostsQuery;
use App\Modules\Posts\Api\Query\FindPostByIdQuery;
use App\Modules\Posts\Domain\Repository\PostsFindingRepositoryInterface;

class PostFindingSpec extends PostsSpec
{

    /**
     * @test
     */
    function shouldFindExistingPost()
    {
        //given: there is a new Blog Post already created
        $command = new CreatePostCommand('Post Title', 'Post Body', ['t1', 't2']);
        $createResponse = $this->postsApi->createPost($command);

        //and: the Post is to be found
        $query = new FindPostByIdQuery($createResponse->getId());

        //when: the Post is found
        $findResponse = $this->postsApi->findPostById($query);

        //then: the Post was found
        self::assertNotNull($findResponse);
        self::assertEquals($createResponse->getId(), $findResponse->getId());
        self::assertEquals('Post Title', $findResponse->getTitle());
        self::assertEquals('Post Body', $findResponse->getBody());
        self::assertEquals(['t1', 't2'], $findResponse->getTags());
        self::assertNotNull($findResponse->getCreatedById());
        self::assertNotNull($findResponse->getCreatedByName());
        self::assertNotNull($findResponse->getCreatedAt());
        self::assertNotNull($findResponse->getUpdatedAt());

    }

    /**
     * @test
     */
    function shouldFindAllPosts()
    {
        //given: there is a new Blog Post already created
        $command = new CreatePostCommand('Post Title', 'Post Body', ['t1', 't2']);
        $createResponse = $this->postsApi->createPost($command);

        //and: the Post is to be found
        $query = new FindAllPostsQuery(1);

        //when: the Post is found
        $findResponse = $this->postsApi->findAllPosts($query);

        //then: the Post was found
        self::assertNotNull($findResponse);
        self::assertNotNull($findResponse->getData());
        self::assertEquals(PostsFindingRepositoryInterface::PAGE_SIZE, $findResponse->getPageSize());
        self::assertEquals(1, $findResponse->getCount());
        self::assertEquals(1, $findResponse->getPageNo());
        self::assertCount(1, $findResponse->getData());
        self::assertTrue(isset($findResponse->getData()[0]));

        $postHeader = $findResponse->getData()[0];

        self::assertEquals($createResponse->getId(), $postHeader->getId());
        self::assertEquals('Post Title', $postHeader->getTitle());
        self::assertEquals('Post Body', $postHeader->getSummary());
        self::assertEquals(['t1', 't2'], $postHeader->getTags());
        self::assertEquals(0, $postHeader->getCommentsCount());
        self::assertNotNull($postHeader->getCreatedById());
        self::assertNotNull($postHeader->getCreatedByName());
        self::assertNotNull($postHeader->getCreatedAt());

    }
}