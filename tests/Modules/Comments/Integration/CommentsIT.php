<?php

namespace App\Tests\Modules\Comments\Integration;

use App\Modules\Comments\Api\Command\BaselineCommentsCommand;
use App\Modules\Comments\Api\Command\CreateCommentCommand;
use App\Modules\Comments\Api\CommentsApiInterface;
use App\Modules\Comments\Api\Event\Inbound\PostBaselinedCommentsIEvent;
use App\Modules\Comments\Api\Event\Inbound\PostCreatedCommentsIEvent;
use App\Modules\Comments\Api\Event\Inbound\PostDeletedCommentsIEvent;
use App\Modules\Comments\Api\Event\Inbound\PostUpdatedCommentsIEvent;
use App\Modules\Comments\Api\Query\FindCommentsByPostIdQuery;
use App\Modules\Comments\Api\Query\FindLatestCommentsQuery;
use App\Modules\Comments\Api\Query\Response\FindLatestCommentsQueryResponse;
use App\Modules\Comments\Domain\Event\Outbound\CommentsBaselinedOEvent;
use App\Tests\Modules\Comments\Integration\Http\CommentsHttpTrait;
use App\Tests\TestUtils\Contracts\ApplicationEventContractLoader;
use App\Tests\TestUtils\Events\InMemoryEventPublisher;
use App\Tests\TestUtils\IntegrationTest;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Uid\Ulid;

class CommentsIT extends IntegrationTest
{
    use CommentsHttpTrait;
    use ApplicationEventContractLoader;

    /**
     * @test
     */
    public function shouldCreateFetchAndDeleteCommentsForPost()
    {
        //given: there was a Post Created
        $postId = $this->createAndUpdatePost();

        //and: someone authored a Comment for that Post
        $command = new CreateCommentCommand($postId, 'Parent Author', 'Parent Body', null);

        //and: comment is Created
        $parentCommentId = $this->createComment($command)->getId();

        //and: someone authored a Child Comment for that Post and Parent Comment
        $command = new CreateCommentCommand($postId, 'Author', 'Body', $parentCommentId);

        //and: Child Comment is Created
        $commentId = $this->createComment($command)->getId();

        //when: list of Comments for that Post is fetched
        $comments = $this->findCommentsForPost(new FindCommentsByPostIdQuery($postId));

        //then: the Post Comments are fetched correctly
        self::assertNotNull($comments);
        self::assertCount(2, $comments);
        self::assertTrue(isset($comments[0]));
        self::assertTrue(isset($comments[1]));

        self::assertEquals($parentCommentId, $comments[0]->getId());
        self::assertEquals('Parent Author', $comments[0]->getAuthor());
        self::assertEquals('Parent Body', $comments[0]->getBody());
        self::assertNotNull($comments[0]->getCreatedAt());
        self::assertNull($comments[0]->getParentId());

        self::assertEquals($commentId, $comments[1]->getId());
        self::assertEquals('Author', $comments[1]->getAuthor());
        self::assertEquals('Body', $comments[1]->getBody());
        self::assertNotNull($comments[1]->getCreatedAt());
        self::assertEquals($parentCommentId, $comments[1]->getParentId());

        //when: list of Comments for that Post is fetched
        $comments = $this->findLatestComments(new FindLatestCommentsQuery(1));

        //then: the Post Comments are fetched correctly
        self::assertNotNull($comments);
        self::assertCount(2, $comments->getData());
        self::assertEquals(2, $comments->getCount());
        self::assertTrue(isset($comments->getData()[0]));
        self::assertTrue(isset($comments->getData()[1]));

        $parentComment = $this->convert($comments->getData()[1], FindLatestCommentsQueryResponse::class);
        $comment = $this->convert($comments->getData()[0], FindLatestCommentsQueryResponse::class);

        self::assertEquals($parentCommentId, $parentComment->getId());
        self::assertEquals('Parent Author', $parentComment->getAuthor());
        self::assertEquals('Parent Body', $parentComment->getBody());
        self::assertNotNull($parentComment->getCreatedAt());
        self::assertNull($parentComment->getParentId());

        self::assertEquals($commentId, $comment->getId());
        self::assertEquals('Author', $comment->getAuthor());
        self::assertEquals('Body', $comment->getBody());
        self::assertNotNull($comment->getCreatedAt());
        self::assertEquals($parentCommentId, $comment->getParentId());

        //when: the Post was Deleted
        $this->deletePost($postId);

        //and: the list of Comments was fetched
        $comments = $this->findLatestComments(new FindLatestCommentsQuery(1));

        //then: there were no Comments left
        self::assertNotNull($comments);
        self::assertCount(0, $comments->getData());
        self::assertEquals(0, $comments->getCount());
    }

    /**
     * @test
     */
    public function shouldBaselineNonExistentPost()
    {
        //given: there was a PostCreatedIEvent to be published
        $event = new PostBaselinedCommentsIEvent($this->getInboundEvent("Comments/PostBaselinedCommentsIEvent"));

        //when: the Event was already published
        $this->getCommentsApi()->onPostBaselined($event);

        //then: Non-existent post was Baselined
        $posts = $this->getCommentsApi()->findPostHeaders();
        self::assertCount(1, $posts);
        self::assertEquals(3, $posts[0]->getVersion());

    }

    /**
     * @test
     */
    public function shouldBaselineAlreadyExistentPost()
    {
        //given: there was a Post Created
        $postId = $this->createAndUpdatePost();

        //given: there was a PostCreatedIEvent to be published
        $data = $this->getInboundEvent("Comments/PostBaselinedCommentsIEvent");
        $data['id'] = $postId;
        $event = new PostBaselinedCommentsIEvent($data);

        //when: the Event was already published
        $this->getCommentsApi()->onPostBaselined($event);

        //then: Non-existent post was Baselined
        $posts = $this->getCommentsApi()->findPostHeaders();
        self::assertCount(1, $posts);
        self::assertEquals(3, $posts[0]->getVersion());

    }

    /**
     * @test
     */
    public function shouldBaselineCommentsForPost()
    {
        //given: there was a Post Created
        $postId = $this->createAndUpdatePost();

        //and: someone authored a Comment for that Post
        $command = new CreateCommentCommand($postId, 'Parent Author', 'Parent Body', null);

        //and: comment is Created
        $parentCommentId = $this->createComment($command)->getId();

        //and: someone authored a Child Comment for that Post and Parent Comment
        $command = new CreateCommentCommand($postId, 'Author', 'Body', $parentCommentId);

        //and: Child Comment is Created
        $this->createComment($command)->getId();

        //when: Comments are baselined
        $application = $this->setupKernel();
        $command = $application->find('app:comments:baseline');
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);
        $output = $commandTester->getDisplay();

        //then: Comments were baselined correctly
        self::assertStringStartsWith('Successfully base-lined all Comments', $output);
        $events = InMemoryEventPublisher::get(CommentsBaselinedOEvent::class);
        self::assertCount(1, $events);
        self::assertEquals($postId, $events[0]->getData()['postId']);
        self::assertCount(2, json_decode($events[0]->getData()['comments']));
    }


    private function createAndUpdatePost(): Ulid
    {
        //given: there was a PostCreatedIEvent to be published
        $event = new PostCreatedCommentsIEvent($this->getInboundEvent("Comments/PostCreatedCommentsIEvent"));

        //and: the Event was already published
        $this->getCommentsApi()->onPostCreated($event);

        //and: the Header was to be updated
        $event = new PostUpdatedCommentsIEvent($this->getInboundEvent("Comments/PostUpdatedCommentsIEvent"));

        //and: the Event was published
        $this->getCommentsApi()->onPostUpdated($event);

        return $event->getId();
    }

    private function deletePost(Ulid $postId): void
    {
        //and: the Header was to be updated
        $data = $this->getInboundEvent("Comments/PostDeletedCommentsIEvent");
        $data["postId"] = $postId;
        $event = new PostDeletedCommentsIEvent($data);


        //and: the Event was published
        $this->getCommentsApi()->onPostDeleted($event);
    }

    private function getCommentsApi(): CommentsApiInterface
    {
        return $this->getContainer()->get(CommentsApiInterface::class);
    }

    /**
     * @return Application
     */
    protected function setupKernel(): Application
    {
        $kernel = static::createKernel();
        $kernel->boot();
        $application = new Application($kernel);
        $application->add(new BaselineCommentsCommand(
            $this->getContainer()->get(CommentsApiInterface::class)
        ));
        return $application;
    }
}