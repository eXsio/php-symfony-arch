<?php

namespace App\Modules\Comments\Api;

use App\Infrastructure\Pagination\Page;
use App\Modules\Comments\Api\Command\BaselineCommentsCommand;
use App\Modules\Comments\Api\Command\CreateCommentCommand;
use App\Modules\Comments\Api\Command\Response\CreateCommentCommandResponse;
use App\Modules\Comments\Api\Event\Inbound\PostBaselinedCommentsIEvent;
use App\Modules\Comments\Api\Event\Inbound\PostCreatedCommentsIEvent;
use App\Modules\Comments\Api\Event\Inbound\PostDeletedCommentsIEvent;
use App\Modules\Comments\Api\Event\Inbound\PostUpdatedCommentsIEvent;
use App\Modules\Comments\Api\Event\Inbound\UserRenamedCommentsIEvent;
use App\Modules\Comments\Api\Query\Response\FindCommentsByPostIdQueryResponse;
use App\Modules\Comments\Api\Query\Response\FindCommentsPostHeadersQueryResponse;
use App\Modules\Comments\Api\Query\Response\FindLatestCommentsQueryResponse;

interface CommentsApiInterface
{
    /**
     * @param PostBaselinedCommentsIEvent $event
     */
    public function onPostBaselined(PostBaselinedCommentsIEvent $event): void;

    /**
     * @param PostCreatedCommentsIEvent $event
     */
    public function onPostCreated(PostCreatedCommentsIEvent $event): void;

    /**
     * @param PostUpdatedCommentsIEvent $event
     */
    public function onPostUpdated(PostUpdatedCommentsIEvent $event): void;

    /**
     * @param PostDeletedCommentsIEvent $event
     */
    public function onPostDeleted(PostDeletedCommentsIEvent $event): void;

    /**
     * @return array<FindCommentsPostHeadersQueryResponse>
     */
    public function findPostHeaders(): array;

    /**
     * @param CreateCommentCommand $command
     */
    public function createComment(CreateCommentCommand $command): CreateCommentCommandResponse;

    /**
     * @param Query\FindCommentsByPostIdQuery $param
     * @return array<FindCommentsByPostIdQueryResponse>
     */
    public function findCommentsForPost(Query\FindCommentsByPostIdQuery $param): array;

    /**
     * @param Query\FindLatestCommentsQuery $param
     * @return Page<FindLatestCommentsQueryResponse>
     */
    public function findLatestComments(Query\FindLatestCommentsQuery $param): Page;

    /**
     * @param BaselineCommentsCommand $command
     */
    public function baseline(BaselineCommentsCommand $command): void;

}