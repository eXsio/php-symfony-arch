<?php

namespace App\Modules\Security\Api;

use App\Infrastructure\Pagination\Page;
use App\Modules\Security\Api\Command\ChangeUserPasswordCommand;
use App\Modules\Security\Api\Command\CreateUserCommand;
use App\Modules\Security\Api\Command\RenameUserCommand;
use App\Modules\Security\Api\Command\Response\CreateUserCommandResponse;
use App\Modules\Security\Api\Event\Inbound\CommentsCountUpdatedSecurityIEvent;
use App\Modules\Security\Api\Event\Inbound\PostCreatedSecurityIEvent;
use App\Modules\Security\Api\Event\Inbound\PostDeletedSecurityIEvent;
use App\Modules\Security\Api\Event\Inbound\PostUpdatedSecurityIEvent;
use App\Modules\Security\Api\Query\FindPostsByUserIdQuery;
use App\Modules\Security\Api\Query\FindUserPostHeadersQuery;
use App\Modules\Security\Api\Query\Response\FindPostsByUserIdQueryResponse;
use App\Modules\Security\Api\Query\Response\FindUserPostHeadersQueryResponse;

interface SecurityApiInterface
{
    /**
     * @param CreateUserCommand $command
     * @return CreateUserCommandResponse
     */
    function createUser(CreateUserCommand $command): CreateUserCommandResponse;

    /**
     * @param PostCreatedSecurityIEvent $event
     */
    function onPostCreated(PostCreatedSecurityIEvent $event): void;

    /**
     * @param PostUpdatedSecurityIEvent $event
     */
    function onPostUpdated(PostUpdatedSecurityIEvent $event): void;

    /**
     * @param PostDeletedSecurityIEvent $event
     */
    function onPostDeleted(PostDeletedSecurityIEvent $event): void;

    /**
     * @param CommentsCountUpdatedSecurityIEvent $event
     */
    function onCommentsCountUpdated(CommentsCountUpdatedSecurityIEvent $event): void;

    /**
     * @param FindUserPostHeadersQuery $query
     * @return array<FindUserPostHeadersQueryResponse>
     */
    public function findPostHeaders(FindUserPostHeadersQuery $query): array;

    /**
     * @param FindPostsByUserIdQuery $query
     * @return Page<FindPostsByUserIdQueryResponse>
     */
    public function findPostsByUserId(FindPostsByUserIdQuery $query): Page;

    /**
     * @param RenameUserCommand $command
     */
    public function renameUser(RenameUserCommand $command): void;

    /**
     * @param ChangeUserPasswordCommand $command
     */
    public function changePassword(ChangeUserPasswordCommand $command): void;
}