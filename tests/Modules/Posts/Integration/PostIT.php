<?php

namespace App\Tests\Modules\Posts\Integration;

use App\Modules\Posts\Api\Command\BaselinePostsCommand;
use App\Modules\Posts\Api\Command\CreatePostCommand;
use App\Modules\Posts\Api\Command\DeletePostCommand;
use App\Modules\Posts\Api\Command\Response\CreatePostCommandResponse;
use App\Modules\Posts\Api\Command\UpdatePostCommand;
use App\Modules\Posts\Api\Event\Inbound\CommentCreatedPostsIEvent;
use App\Modules\Posts\Api\Event\Inbound\CommentsBaselinedPostsIEvent;
use App\Modules\Posts\Api\Event\Inbound\UserRenamedPostsIEvent;
use App\Modules\Posts\Api\PostsApiInterface;
use App\Modules\Posts\Api\Query\FindAllPostsQuery;
use App\Modules\Posts\Api\Query\FindPostByIdQuery;
use App\Modules\Posts\Api\Query\Response\FindPostHeaderQueryResponse;
use App\Modules\Posts\Domain\Event\Outbound\PostBaselinedOEvent;
use App\Modules\Posts\Domain\Event\Outbound\PostCreatedOEvent;
use App\Modules\Posts\Domain\Event\Outbound\PostDeletedOEvent;
use App\Modules\Posts\Domain\Event\Outbound\PostUpdatedOEvent;
use App\Modules\Posts\Domain\Repository\PostsFindingRepositoryInterface;
use App\Tests\Modules\Posts\Integration\Http\PostsHttpTrait;
use App\Tests\TestUtils\Contracts\ApplicationEventContractLoader;
use App\Tests\TestUtils\Events\InMemoryEventPublisher;
use App\Tests\TestUtils\IntegrationTest;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Uid\Ulid;

class PostIT extends IntegrationTest
{
    use PostsHttpTrait;
    use ApplicationEventContractLoader;

    /**
     * @test
     */
    public function shouldCreateNewBlogPost(): void
    {
        //given: User has written a new Blog Post
        $command = new CreatePostCommand('Post Title', 'Post Body', ['t1']);

        //when: User posts the Post
        $response = $this->createPost($command);

        //then: the response is OK
        self::assertResponseIsSuccessful();

        //and: the Response contains the proper Object
        self::assertInstanceOf(CreatePostCommandResponse::class, $response);

        //and: The Response Object contains ID of newly created Blog Post
        self::assertNotNull($response->getId());

        //and: a Post Created Output Event was Published
        $events = InMemoryEventPublisher::get(PostCreatedOEvent::class);
        self::assertCount(1, $events);

        //and: The Event has the correct data
        InMemoryEventPublisher::assertEventData([
            'title' => 'Post Title',
            'body' => 'Post Body',
            'tags' => ['t1'],
            'summary' => 'Post Body',
            'createdByName' => IntegrationTest::DEFAULT_USER_ID,
            'createdAt' => 'createdAt',
            'createdById' => 'createdById'
        ], $events[0]);
    }

    /**
     * @param Ulid $postId
     * @return Ulid
     * @test
     */
    public function shouldUpdateExistingBlogPost(): void
    {
        //given: There is a Post created
        $createCommand = new CreatePostCommand('Post Title', 'Post Body', ['t1']);
        $createResponse = $this->createPost($createCommand);

        //when: the Post is updated
        $updateCommand = new UpdatePostCommand('Post Title updated', 'Post Body updated', ['t2']);
        $updateCommand->setId($createResponse->getId());
        $this->updatePost($updateCommand);

        //then: the response is OK
        self::assertResponseIsSuccessful();

        //and: a Post Updated Output Event was Published
        $events = InMemoryEventPublisher::get(PostUpdatedOEvent::class);
        self::assertCount(1, $events);

        //and: The Event has the correct data
        InMemoryEventPublisher::assertEventData([
            'id' => $createResponse->getId(),
            'title' => 'Post Title updated',
            'body' => 'Post Body updated',
            'tags' => ['t2'],
            'summary' => 'Post Body updated',
            'updatedAt' => 'updatedAt',
        ], $events[0]);

        //when: the User Renamed Event is handled
        $data = $this->getInboundEvent("Posts/UserRenamedPostsIEvent");
        $event = new UserRenamedPostsIEvent($data);
        $this->getPostsApi()->onUserRenamed($event);

        //and: User fetches the Post
        $findResponse = $this->findPostById(new FindPostByIdQuery($createResponse->getId()));

        //then: Previously created Post is fetched with the Comment
        self::assertNotNull($findResponse);
        self::assertEquals($createResponse->getId(), $findResponse->getId());
        self::assertEquals($event->getNewLogin(), $findResponse->getCreatedByName());
    }

    /**
     * @param Ulid $postId
     * @test
     */
    public function shouldDeleteExistingBlogPost(): void
    {
        //given: There is a Post created
        $createCommand = new CreatePostCommand('Post Title', 'Post Body', ['t1']);
        $createResponse = $this->createPost($createCommand);

        //when:
        $updateCommand = new DeletePostCommand($createResponse->getId());
        $this->deletePost($updateCommand);

        //then: the response is OK
        self::assertResponseIsSuccessful();

        //and: a Post Updated Output Event was Published
        $events = InMemoryEventPublisher::get(PostDeletedOEvent::class);
        self::assertCount(1, $events);

        //and: The Event has the correct data
        InMemoryEventPublisher::assertEventData([
            'id' => $createResponse->getId(),
        ], $events[0]);
    }

    /**
     * @test
     */
    public function shouldFetchAllCreatedPosts(): void
    {
        //given: There is a Post created
        $command = new CreatePostCommand('Post Title', 'Post Body', ['t1']);
        $createResponse = $this->createPost($command);

        //when: User fetches all the Posts
        $page = $this->findAllPosts(new FindAllPostsQuery(1));

        //then: the response is OK
        self::assertResponseIsSuccessful();

        //and: Previously created Post is fetched
        self::assertNotNull($page);
        self::assertEquals(1, $page->getPageNo());
        self::assertEquals(1, $page->getCount());
        self::assertCount(1, $page->getData());
        self::assertEquals(PostsFindingRepositoryInterface::PAGE_SIZE, $page->getPageSize());

        self::assertTrue(isset($page->getData()[0]));

        //and: The fetched Post is valid
        $postHeader = $this->convert($page->getData()[0], FindPostHeaderQueryResponse::class);
        self::assertNotNull($postHeader);
        self::assertEquals('Post Title', $postHeader->getTitle());
        self::assertEquals('Post Body', $postHeader->getSummary());
        self::assertEquals(['t1'], $postHeader->getTags());
        self::assertEquals(0, $postHeader->getCommentsCount());
        self::assertEquals($createResponse->getId(), $postHeader->getId());
    }

    /**
     * @param Ulid $postId
     * @return Ulid
     * @test
     */
    public function shouldFetchSingleCreatedPost(): void
    {
        //given: There is a Post created
        $command = new CreatePostCommand('Post Title', 'Post Body', ['t1']);
        $createResponse = $this->createPost($command);

        //when: User fetches the Post
        $post = $this->findPostById(new FindPostByIdQuery($createResponse->getId()));

        //then: the response is OK
        self::assertResponseIsSuccessful();

        //and: Previously created Post is fetched
        self::assertNotNull($post);
        self::assertEquals('Post Title', $post->getTitle());
        self::assertEquals('Post Body', $post->getBody());
        self::assertEquals(['t1'], $post->getTags());
        self::assertEquals([], $post->getComments());
        self::assertEquals($createResponse->getId(), $post->getId());
    }

    /**
     * @param Ulid $postId
     * @return Ulid
     * @test
     */
    public function shouldUpdateCommentsOnExistingPost(): void
    {
        //given: There is a Post created
        $command = new CreatePostCommand('Post Title', 'Post Body', ['t1']);
        $createResponse = $this->createPost($command);

        //and: There was a Comment Created
        $data = $this->getInboundEvent("Posts/CommentCreatedPostsIEvent");
        $data['postId'] = $createResponse->getId();
        $event = new CommentCreatedPostsIEvent($data);

        //when: the Comment Created Event is handled
        $this->getPostsApi()->onCommentCreated($event);

        //and: User fetches the Post
        $post = $this->findPostById(new FindPostByIdQuery($createResponse->getId()));

        //then: the response is OK
        self::assertResponseIsSuccessful();

        //and: Previously created Post is fetched with the Comment
        self::assertNotNull($post);
        self::assertEquals('Post Title', $post->getTitle());
        self::assertEquals('Post Body', $post->getBody());
        self::assertEquals(['t1'], $post->getTags());
        self::assertCount(1, $post->getComments());
        self::assertEquals('Comment Author', $post->getComments()[0]['author']);
        self::assertEquals('Comment Body', $post->getComments()[0]['body']);
        self::assertEquals($createResponse->getId(), $post->getId());

        //when: User fetches all the Posts
        $page = $this->findAllPosts(new FindAllPostsQuery(1));

        //then: the response is OK
        self::assertResponseIsSuccessful();

        //and: Previously created Post is fetched with the correct Comments count
        self::assertNotNull($page);
        self::assertEquals(1, $page->getPageNo());
        self::assertEquals(1, $page->getCount());
        self::assertCount(1, $page->getData());
        self::assertEquals(PostsFindingRepositoryInterface::PAGE_SIZE, $page->getPageSize());
        self::assertTrue(isset($page->getData()[0]));
        $postHeader = $this->convert($page->getData()[0], FindPostHeaderQueryResponse::class);
        self::assertNotNull($postHeader);
        self::assertEquals(1, $postHeader->getCommentsCount());
        self::assertEquals($createResponse->getId(), $postHeader->getId());

        //and: There was a Comment Created
        $data = $this->getInboundEvent("Posts/CommentsBaselinedPostsIEvent");
        $data['postId'] = $createResponse->getId();
        $event = new CommentsBaselinedPostsIEvent($data);

        //when: the Comment Created Event is handled
        $this->getPostsApi()->onCommentsBaselined($event);

        //and: User fetches the Post
        $post = $this->findPostById(new FindPostByIdQuery($createResponse->getId()));

        //then: the response is OK
        self::assertResponseIsSuccessful();

        //and: Previously created Post is fetched with the Comment
        self::assertNotNull($post);
        self::assertCount(2, $post->getComments());
    }

    /**
     * @param Ulid $postId
     * @return Ulid
     * @test
     */
    public function shouldBaselineAllPosts(): void
    {
        //given: There is a Post created
        $command = new CreatePostCommand('Post Title', 'Post Body', ['t1']);
        $createResponse = $this->createPost($command);

        //when: Comments are baselined
        $application = $this->setupKernel();
        $command = $application->find('app:posts:baseline');
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);
        $output = $commandTester->getDisplay();

        //then: Comments were baselined correctly
        self::assertStringStartsWith('Successfully base-lined all Posts', $output);
        $events = InMemoryEventPublisher::get(PostBaselinedOEvent::class);
        self::assertCount(1, $events);
        self::assertEquals($createResponse->getId(), $events[0]->getData()['id']);
    }

    private function getPostsApi(): PostsApiInterface
    {
        return self::getContainer()->get(PostsApiInterface::class);
    }

    /**
     * @return Application
     */
    protected function setupKernel(): Application
    {
        $kernel = static::createKernel();
        $kernel->boot();
        $application = new Application($kernel);
        $application->add(new BaselinePostsCommand(
            $this->getContainer()->get(PostsApiInterface::class)
        ));
        return $application;
    }
}