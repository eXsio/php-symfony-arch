<?php

namespace App\Modules\Tags\Api;

use App\Infrastructure\Pagination\Page;
use App\Modules\Tags\Api\Event\Inbound\CommentsCountUpdatedTagsIEvent;
use App\Modules\Tags\Api\Event\Inbound\PostCreatedTagsIEvent;
use App\Modules\Tags\Api\Event\Inbound\PostDeletedTagsIEvent;
use App\Modules\Tags\Api\Event\Inbound\PostUpdatedTagsIEvent;
use App\Modules\Tags\Api\Event\Inbound\UserRenamedTagsIEvent;
use App\Modules\Tags\Api\Query\FindPostsByTagQuery;
use App\Modules\Tags\Api\Query\FindTagsQuery;
use App\Modules\Tags\Api\Query\Response\FindPostsByTagQueryResponse;
use App\Modules\Tags\Api\Query\Response\FindTagsPostHeadersQueryResponse;
use App\Modules\Tags\Api\Query\Response\FindTagsQueryResponse;

interface TagsApiInterface
{
    /**
     * @param PostCreatedTagsIEvent $event
     */
    public function onPostCreated(PostCreatedTagsIEvent $event): void;

    /**
     * @param PostUpdatedTagsIEvent $event
     */
    public function onPostUpdated(PostUpdatedTagsIEvent $event): void;

    /**
     * @param PostDeletedTagsIEvent $event
     */
    public function onPostDeleted(PostDeletedTagsIEvent $event): void;

    /**
     * @return array<FindTagsPostHeadersQueryResponse>
     */
    public function findPostHeaders(): array;

    /**
     * @param FindPostsByTagQuery $query
     * @return Page<FindPostsByTagQueryResponse>
     */
    public function findPostsByTag(FindPostsByTagQuery $query): Page;

    /**
     * @param FindTagsQuery $query
     * @return array<FindTagsQueryResponse>
     */
    public function findTags(FindTagsQuery $query): array;

    /**
     * @param CommentsCountUpdatedTagsIEvent $event
     * @return mixed
     */
    public function onCommentsCountUpdated(CommentsCountUpdatedTagsIEvent $event): void;

    /**
     * @param UserRenamedTagsIEvent $event
     */
    public function onUserRenamed(UserRenamedTagsIEvent $event): void;
}