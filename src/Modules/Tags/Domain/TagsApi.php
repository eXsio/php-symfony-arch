<?php

namespace App\Modules\Tags\Domain;

use App\Infrastructure\Events\Api\ApplicationEventSubscriber;
use App\Infrastructure\Events\Api\EventHandlerReference;
use App\Modules\Tags\Api\Event\Inbound\CommentsCountUpdatedTagsIEvent;
use App\Modules\Tags\Api\Event\Inbound\PostCreatedTagsIEvent;
use App\Modules\Tags\Api\Event\Inbound\PostDeletedTagsIEvent;
use App\Modules\Tags\Api\Event\Inbound\PostUpdatedTagsIEvent;
use App\Modules\Tags\Api\Event\Inbound\UserRenamedTagsIEvent;
use App\Modules\Tags\Api\TagsApiInterface;
use App\Modules\Tags\Domain\Logic\CommentsEventsHandler;
use App\Modules\Tags\Domain\Logic\PostHeadersFinder;
use App\Modules\Tags\Domain\Logic\PostsEventsHandler;
use App\Modules\Tags\Domain\Logic\SecurityEventsHandler;
use App\Modules\Tags\Domain\Logic\TagsFinder;
use App\Modules\Tags\Domain\Logic\TagsUpdater;
use App\Modules\Tags\Domain\Repository\TagsCommentsEventHandlingRepositoryInterface;
use App\Modules\Tags\Domain\Repository\TagsDeletingRepositoryInterface;
use App\Modules\Tags\Domain\Repository\TagsFindingRepositoryInterface;
use App\Modules\Tags\Domain\Repository\TagsPostEventsHandlingRepositoryInterface;
use App\Modules\Tags\Domain\Repository\TagsPostHeadersFindingRepositoryInterface;
use App\Modules\Tags\Domain\Repository\TagsSecurityEventsHandlingRepositoryInterface;
use App\Modules\Tags\Domain\Repository\TagsUpdatingRepositoryInterface;
use App\Modules\Tags\Domain\Transactions\TagsTransactionFactoryInterface;
use Psr\Log\LoggerInterface;

class TagsApi extends ApplicationEventSubscriber implements TagsApiInterface
{

    use PostsEventsHandler {
        PostsEventsHandler::__construct as __postEventsHandlerConstruct;
    }

    use PostHeadersFinder {
        PostHeadersFinder::__construct as __postHeadersFinderConstruct;
    }

    use TagsFinder {
        TagsFinder::__construct as __tagsFinderConstruct;
    }

    use CommentsEventsHandler {
        CommentsEventsHandler::__construct as __commentsEventsHandlerConstruct;
    }

    use SecurityEventsHandler {
        SecurityEventsHandler::__construct as __securityEventsHandlerConstruct;
    }

    /**
     * @param TagsTransactionFactoryInterface $transactionFactory
     * @param TagsPostEventsHandlingRepositoryInterface $postEventsTagsRepository
     * @param TagsPostHeadersFindingRepositoryInterface $headersFindingRepository
     * @param LoggerInterface $logger
     * @param TagsUpdatingRepositoryInterface $tagsUpdatingRepository
     * @param TagsDeletingRepositoryInterface $tagsDeletingRepository
     * @param TagsFindingRepositoryInterface $tagsFindingRepository
     * @param TagsCommentsEventHandlingRepositoryInterface $commentsEventHandlingRepository
     * @param TagsSecurityEventsHandlingRepositoryInterface $securityEventsHandlingRepository
     */
    public function __construct(
        TagsTransactionFactoryInterface               $transactionFactory,
        TagsPostEventsHandlingRepositoryInterface     $postEventsTagsRepository,
        TagsPostHeadersFindingRepositoryInterface     $headersFindingRepository,
        LoggerInterface                               $logger,
        TagsUpdatingRepositoryInterface               $tagsUpdatingRepository,
        TagsDeletingRepositoryInterface               $tagsDeletingRepository,
        TagsFindingRepositoryInterface                $tagsFindingRepository,
        TagsCommentsEventHandlingRepositoryInterface  $commentsEventHandlingRepository,
        TagsSecurityEventsHandlingRepositoryInterface $securityEventsHandlingRepository
    )
    {
        parent::__construct($logger);
        $updater = new TagsUpdater($tagsUpdatingRepository, $tagsDeletingRepository);
        $this->__postEventsHandlerConstruct($transactionFactory, $postEventsTagsRepository, $headersFindingRepository, $updater);
        $this->__postHeadersFinderConstruct($headersFindingRepository);
        $this->__tagsFinderConstruct($tagsFindingRepository);
        $this->__commentsEventsHandlerConstruct($transactionFactory, $commentsEventHandlingRepository);
        $this->__securityEventsHandlerConstruct($transactionFactory, $securityEventsHandlingRepository);
    }

    /**
     * @return array<string, string>
     */
    protected function subscribe(): array
    {
        return [
            PostCreatedTagsIEvent::class => 'onPostCreated',
            PostUpdatedTagsIEvent::class => 'onPostUpdated',
            PostDeletedTagsIEvent::class => 'onPostDeleted',
            CommentsCountUpdatedTagsIEvent::class => 'onCommentsCountUpdated',
            UserRenamedTagsIEvent::class => 'onUserRenamed',
        ];
    }
}