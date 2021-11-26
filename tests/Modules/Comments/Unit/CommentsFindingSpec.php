<?php

namespace App\Tests\Modules\Comments\Unit;

use App\Modules\Comments\Api\Command\CreateCommentCommand;
use App\Modules\Comments\Api\Query\FindCommentsByPostIdQuery;
use App\Modules\Comments\Api\Query\FindLatestCommentsQuery;

class CommentsFindingSpec extends CommentsSpec
{

    /**
     * @test
     */
    public function shouldFetchCommentsForPost()
    {
        //given: there was a Post Created
        $postId = $this->createPost();

        //and: someone authored a Comment for that Post
        $command = new CreateCommentCommand($postId, 'Parent Author', 'Parent Body', null);

        //and: comment is Created
        $parentCommentId = $this->commentsApi->createComment($command)->getId();

        //and: someone authored a Child Comment for that Post and Parent Comment
        $command = new CreateCommentCommand($postId, 'Author', 'Body', $parentCommentId);

        //and: Child Comment is Created
        $commentId = $this->commentsApi->createComment($command)->getId();

        //when: list of Comments for that Post is fetched
        $comments = $this->commentsApi->findCommentsForPost(new FindCommentsByPostIdQuery($postId));

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

    }

    /**
     * @test
     */
    public function shouldFetchLatestComments()
    {
        //given: there was a Post Created
        $postId = $this->createPost();

        //and: someone authored a Comment for that Post
        $command = new CreateCommentCommand($postId, 'Parent Author', 'Parent Body', null);

        //and: comment is Created
        $parentCommentId = $this->commentsApi->createComment($command)->getId();

        //and: someone authored a Child Comment for that Post and Parent Comment
        $command = new CreateCommentCommand($postId, 'Author', 'Body', $parentCommentId);

        //and: Child Comment is Created
        $commentId = $this->commentsApi->createComment($command)->getId();

        //when: list of Comments for that Post is fetched
        $comments = $this->commentsApi->findLatestComments(new FindLatestCommentsQuery(1));

        //then: the Post Comments are fetched correctly
        self::assertNotNull($comments);
        self::assertCount(2, $comments->getData());
        self::assertEquals(2, $comments->getCount());
        self::assertTrue(isset($comments->getData()[0]));
        self::assertTrue(isset($comments->getData()[1]));

        self::assertEquals($parentCommentId, $comments->getData()[1]->getId());
        self::assertEquals('Parent Author', $comments->getData()[1]->getAuthor());
        self::assertEquals('Parent Body', $comments->getData()[1]->getBody());
        self::assertNotNull($comments->getData()[1]->getCreatedAt());
        self::assertNull($comments->getData()[1]->getParentId());

        self::assertEquals($commentId, $comments->getData()[0]->getId());
        self::assertEquals('Author', $comments->getData()[0]->getAuthor());
        self::assertEquals('Body', $comments->getData()[0]->getBody());
        self::assertNotNull($comments->getData()[0]->getCreatedAt());
        self::assertEquals($parentCommentId, $comments->getData()[0]->getParentId());

    }



}