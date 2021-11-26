<?php

namespace App\Modules\Comments\Domain;

use App\Infrastructure\Events\Api\ApplicationEventPublisherInterface;
use App\Infrastructure\Events\Api\ApplicationEventSubscriber;
use App\Infrastructure\Events\Api\EventHandlerReference;
use App\Modules\Comments\Api\CommentsApiInterface;
use App\Modules\Comments\Api\Event\Inbound\PostCreatedCommentsIEvent;
use App\Modules\Comments\Api\Event\Inbound\PostDeletedCommentsIEvent;
use App\Modules\Comments\Api\Event\Inbound\PostUpdatedCommentsIEvent;
use App\Modules\Comments\Api\Event\Inbound\UserRenamedCommentsIEvent;
use App\Modules\Comments\Domain\Logic\CommentsBaseliner;
use App\Modules\Comments\Domain\Logic\CommentsCreator;
use App\Modules\Comments\Domain\Logic\CommentsFinder;
use App\Modules\Comments\Domain\Logic\CommentsValidator;
use App\Modules\Comments\Domain\Logic\PostEventsHandler;
use App\Modules\Comments\Domain\Logic\PostHeadersFinder;
use App\Modules\Comments\Domain\Logic\SecurityEventsHandler;
use App\Modules\Comments\Domain\Repository\CommentsCreationRepositoryInterface;
use App\Modules\Comments\Domain\Repository\CommentsDeletionRepositoryInterface;
use App\Modules\Comments\Domain\Repository\CommentsFindingRepositoryInterface;
use App\Modules\Comments\Domain\Repository\CommentsPostHeadersFindingRepositoryInterface;
use App\Modules\Comments\Domain\Repository\CommentsPostsEventsHandlingRepositoryInterface;
use App\Modules\Comments\Domain\Repository\CommentsSecurityEventsHandlingRepositoryInterface;
use App\Modules\Comments\Domain\Transactions\CommentsTransactionFactoryInterface;
use Psr\Log\LoggerInterface;

class CommentsApi extends ApplicationEventSubscriber implements CommentsApiInterface
{

    use PostEventsHandler {
        PostEventsHandler::__construct as __postEventsHandlerConstruct;
    }

    use SecurityEventsHandler {
        SecurityEventsHandler::__construct as __securityEventsHandlerConstruct;
    }

    use PostHeadersFinder {
        PostHeadersFinder::__construct as __postHeadersFinderConstruct;
    }

    use CommentsCreator {
        CommentsCreator::__construct as __commentsCreatorConstruct;
    }

    use CommentsFinder {
        CommentsFinder::__construct as __commentsFinderConstruct;
    }

    use CommentsBaseliner {
        CommentsBaseliner::__construct as __commentsBaselinerConstruct;
    }

    /**
     * @param CommentsTransactionFactoryInterface $transactionFactory
     * @param CommentsPostsEventsHandlingRepositoryInterface $postEventsCommentsRepository
     * @param CommentsPostHeadersFindingRepositoryInterface $headersFindingRepository
     * @param CommentsCreationRepositoryInterface $commentsCreationRepository
     * @param CommentsFindingRepositoryInterface $commentsFindingRepository
     * @param CommentsDeletionRepositoryInterface $commentsDeletionRepository
     * @param ApplicationEventPublisherInterface $eventPublisher
     * @param LoggerInterface $logger
     */
    public function __construct(
        CommentsTransactionFactoryInterface               $transactionFactory,
        CommentsPostsEventsHandlingRepositoryInterface    $postEventsCommentsRepository,
        CommentsPostHeadersFindingRepositoryInterface     $headersFindingRepository,
        CommentsCreationRepositoryInterface               $commentsCreationRepository,
        CommentsFindingRepositoryInterface                $commentsFindingRepository,
        CommentsDeletionRepositoryInterface               $commentsDeletionRepository,
        CommentsSecurityEventsHandlingRepositoryInterface $securityEventsHandlingRepository,
        ApplicationEventPublisherInterface                $eventPublisher,
        LoggerInterface                                   $logger
    )
    {
        parent::__construct($logger);
        $validator = new CommentsValidator($headersFindingRepository, $commentsFindingRepository);
        $this->__postEventsHandlerConstruct($transactionFactory, $postEventsCommentsRepository, $commentsDeletionRepository);
        $this->__postHeadersFinderConstruct($headersFindingRepository);
        $this->__commentsCreatorConstruct($commentsCreationRepository, $commentsFindingRepository, $transactionFactory, $eventPublisher, $validator);
        $this->__commentsFinderConstruct($commentsFindingRepository);
        $this->__commentsBaselinerConstruct($eventPublisher, $commentsFindingRepository, $headersFindingRepository);
        $this->__securityEventsHandlerConstruct($transactionFactory, $securityEventsHandlingRepository);
    }

    /**
     * @return array<string, EventHandlerReference>
     */
    protected function subscribe(): array
    {
        return [
            PostCreatedCommentsIEvent::EVENT_NAME => EventHandlerReference::create('onPostCreated', PostCreatedCommentsIEvent::class),
            PostUpdatedCommentsIEvent::EVENT_NAME => EventHandlerReference::create('onPostUpdated', PostUpdatedCommentsIEvent::class),
            PostDeletedCommentsIEvent::EVENT_NAME => EventHandlerReference::create('onPostDeleted', PostDeletedCommentsIEvent::class),
            UserRenamedCommentsIEvent::EVENT_NAME => EventHandlerReference::create('onUserRenamed', UserRenamedCommentsIEvent::class)
        ];
    }
}