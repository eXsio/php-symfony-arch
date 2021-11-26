<?php

namespace App\Tests\Modules\Comments\Unit;

use App\Modules\Comments\Api\Command\CreateCommentCommand;
use App\Modules\Comments\Api\Event\Inbound\PostCreatedCommentsIEvent;
use App\Modules\Comments\Api\Query\FindCommentsByPostIdQuery;
use App\Modules\Comments\Domain\Event\Outbound\CommentCreatedOEvent;
use App\Modules\Comments\Domain\Event\Outbound\CommentsCountUpdatedOEvent;
use App\Tests\TestUtils\Events\InMemoryEventPublisher;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Uid\Ulid;

class CommentsCreationSpec extends CommentsSpec
{

    /**
     * @test
     */
    public function shouldCreateCommentForExistingPost()
    {
        //given: there was a Post Created
        $postId = $this->createPost();

        //and: someone authored a Comment for that Post
        $command = new CreateCommentCommand($postId, 'Author', 'Body', null);

        //when: comment is Created
        $commentId = $this->commentsApi->createComment($command)->getId();

        //then: Comment was Created correctly
        self::assertNotNull($commentId);

        //and: Comment can be found by Post Id
        $comments = $this->commentsApi->findCommentsForPost(new FindCommentsByPostIdQuery($postId));
        self::assertNotNull($comments);
        self::assertCount(1, $comments);
        self::assertTrue(isset($comments[0]));
        self::assertEquals($commentId, $comments[0]->getId());
        self::assertEquals('Author', $comments[0]->getAuthor());
        self::assertEquals('Body', $comments[0]->getBody());
        self::assertNotNull($comments[0]->getCreatedAt());
        self::assertNull($comments[0]->getParentId());

        //and: CommentCreated Event was Published
        $events = InMemoryEventPublisher::get(CommentCreatedOEvent::class);
        self::assertCount(1, $events);
        self::assertTrue(isset($events[0]));
        InMemoryEventPublisher::assertEventData([
            'postId' => $postId,
        ], $events[0]);
        $commentData = json_decode($events[0]->getData()['comment'], true);
        self::assertEquals('Author', $commentData['author']);
        self::assertEquals('Body', $commentData['body']);

        //and: CommentCountUpdated Event was Published
        $events = InMemoryEventPublisher::get(CommentsCountUpdatedOEvent::class);
        self::assertCount(1, $events);
        self::assertTrue(isset($events[0]));
        InMemoryEventPublisher::assertEventData([
            'postId' => $postId,
            'commentsCount' => 1,
        ], $events[0]);
    }

    /**
     * @test
     */
    public function shouldCreateCommentForExistingPostWithParent()
    {
        //given: there was a Post Created
        $postId = $this->createPost();

        //and: someone authored a Comment for that Post
        $command = new CreateCommentCommand($postId, 'Parent Author', 'Parent Body', null);

        //and: comment is Created
        $parentCommentId = $this->commentsApi->createComment($command)->getId();

        //and: someone authored a Child Comment for that Post and Parent Comment
        $command = new CreateCommentCommand($postId, 'Author', 'Body', $parentCommentId);

        //when: Child Comment is Created
        $commentId = $this->commentsApi->createComment($command)->getId();

        //then: Child Comment was Created correctly
        self::assertNotNull($commentId);

        //and: Child Comment can be found by Post Id
        $comments = $this->commentsApi->findCommentsForPost(new FindCommentsByPostIdQuery($postId));
        self::assertNotNull($comments);
        self::assertCount(2, $comments);
        self::assertTrue(isset($comments[1]));
        self::assertEquals($commentId, $comments[1]->getId());
        self::assertEquals('Author', $comments[1]->getAuthor());
        self::assertEquals('Body', $comments[1]->getBody());
        self::assertNotNull($comments[1]->getCreatedAt());
        self::assertEquals($parentCommentId, $comments[1]->getParentId());

        //and: CommentCreated Event was Published
        $events = InMemoryEventPublisher::get(CommentCreatedOEvent::class);
        self::assertCount(2, $events);
        self::assertTrue(isset($events[1]));
        InMemoryEventPublisher::assertEventData([
            'postId' => $postId,
        ], $events[1]);
        $commentData = json_decode($events[1]->getData()['comment'], true);
        self::assertEquals('Author', $commentData['author']);
        self::assertEquals('Body', $commentData['body']);
        self::assertEquals($parentCommentId, new Ulid($commentData['parentId']));
    }

    /**
     * @test
     */
    public function shouldNotCreateCommentForNotExistingPost()
    {

        //given: someone authored a Comment for not existing Post
        $command = new CreateCommentCommand(new Ulid(), 'Author', 'Body', null);

        //expect:
        $this->expectException(BadRequestHttpException::class);

        //when: comment is Created
        $this->commentsApi->createComment($command);

    }

    protected function createPost(): Ulid
    {
        $event = new PostCreatedCommentsIEvent($this->getInboundEvent("Comments/PostCreatedCommentsIEvent"));
        $this->commentsApi->onPostCreated($event);
        $headers = $this->commentsApi->findPostHeaders();
        self::assertNotNull($headers);
        self::assertCount(1, $headers);
        self::assertTrue(isset($headers[0]));
        return $headers[0]->getId();
    }
}