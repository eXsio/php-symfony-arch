<?php

namespace App\Modules\Posts\Api;

use App\Infrastructure\Pagination\Page;
use App\Modules\Posts\Api\Command\BaselinePostsCommand;
use App\Modules\Posts\Api\Command\CreatePostCommand;
use App\Modules\Posts\Api\Command\DeletePostCommand;
use App\Modules\Posts\Api\Command\Response\CreatePostCommandResponse;
use App\Modules\Posts\Api\Command\UpdatePostCommand;
use App\Modules\Posts\Api\Event\Inbound\CommentCreatedPostsIEvent;
use App\Modules\Posts\Api\Event\Inbound\CommentsBaselinedPostsIEvent;
use App\Modules\Posts\Api\Event\Inbound\UserRenamedPostsIEvent;
use App\Modules\Posts\Api\Query\FindAllPostsQuery;
use App\Modules\Posts\Api\Query\FindPostByIdQuery;
use App\Modules\Posts\Api\Query\Response\FindPostQueryResponse;

interface PostsApiInterface
{
    /**
     * @param CreatePostCommand $command
     * @return CreatePostCommandResponse
     */
   public function createPost(CreatePostCommand $command): CreatePostCommandResponse;

    /**
     * @param UpdatePostCommand $command
     */
   public function updatePost(UpdatePostCommand $command): void;

    /**
     * @param DeletePostCommand $command
     */
   public function deletePost(DeletePostCommand $command): void;

    /**
     * @param FindAllPostsQuery $query
     * @return Page<FindPostQueryResponse>
     */
   public function findAllPosts(FindAllPostsQuery $query): Page;

    /**
     * @param FindPostByIdQuery $query
     * @return FindPostQueryResponse|null
     */
   public function findPostById(FindPostByIdQuery $query): ?FindPostQueryResponse;

    /**
     * @param CommentCreatedPostsIEvent $event
     */
   public function onCommentCreated(CommentCreatedPostsIEvent $event): void;

    /**
     * @param CommentsBaselinedPostsIEvent $event
     */
   public function onCommentsBaselined(CommentsBaselinedPostsIEvent $event): void;

    /**
     * @param BaselinePostsCommand $command
     */
    public function baseline(BaselinePostsCommand $command): void;

    /**
     * @param UserRenamedPostsIEvent $event
     */
    public function onUserRenamed(UserRenamedPostsIEvent $event): void;
}