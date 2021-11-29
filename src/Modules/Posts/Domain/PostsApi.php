<?php

namespace App\Modules\Posts\Domain;

use App\Infrastructure\Events\Api\ApplicationEventPublisherInterface;
use App\Infrastructure\Events\Api\ApplicationEventSubscriber;
use App\Infrastructure\Security\LoggedInUserProviderInterface;
use App\Modules\Posts\Api\Event\Inbound\CommentCreatedPostsIEvent;
use App\Modules\Posts\Api\Event\Inbound\CommentsBaselinedPostsIEvent;
use App\Modules\Posts\Api\Event\Inbound\UserRenamedPostsIEvent;
use App\Modules\Posts\Api\PostsApiInterface;
use App\Modules\Posts\Domain\Logic\CommentsEventsHandler;
use App\Modules\Posts\Domain\Logic\PostsBaseliner;
use App\Modules\Posts\Domain\Logic\PostsCreator;
use App\Modules\Posts\Domain\Logic\PostsFinder;
use App\Modules\Posts\Domain\Logic\PostsRemover;
use App\Modules\Posts\Domain\Logic\PostsValidator;
use App\Modules\Posts\Domain\Logic\PostUpdater;
use App\Modules\Posts\Domain\Logic\SecurityEventsHandler;
use App\Modules\Posts\Domain\Repository\PostsCommentsEventHandlingRepositoryInterface;
use App\Modules\Posts\Domain\Repository\PostsCreationRepositoryInterface;
use App\Modules\Posts\Domain\Repository\PostsDeletionRepositoryInterface;
use App\Modules\Posts\Domain\Repository\PostsFindingRepositoryInterface;
use App\Modules\Posts\Domain\Repository\PostsSecurityEventsHandlingRepositoryInterface;
use App\Modules\Posts\Domain\Repository\PostsUpdatingRepositoryInterface;
use App\Modules\Posts\Domain\Transactions\PostTransactionFactoryInterface;
use Psr\Log\LoggerInterface;

class PostsApi extends ApplicationEventSubscriber implements PostsApiInterface
{
    use PostsCreator {
        PostsCreator::__construct as private __creatorConstruct;
    }

    use PostUpdater {
        PostUpdater::__construct as private __updaterConstruct;
    }

    use PostsRemover {
        PostsRemover::__construct as private __removerConstruct;
    }

    use PostsFinder {
        PostsFinder::__construct as private __finderConstruct;
    }

    use CommentsEventsHandler {
        CommentsEventsHandler::__construct as private __commentsEventHandlerConstruct;
    }

    use SecurityEventsHandler {
        SecurityEventsHandler::__construct as __securityEventsHandlerConstruct;
    }

    use PostsBaseliner {
        PostsBaseliner::__construct as private __postsBaselinerConstruct;
    }

    /**
     * @param ApplicationEventPublisherInterface $eventPublisher
     * @param PostsCreationRepositoryInterface $creationRepository
     * @param PostsUpdatingRepositoryInterface $updatingRepository
     * @param PostsDeletionRepositoryInterface $deletionRepository
     * @param PostsFindingRepositoryInterface $findingRepository
     * @param LoggedInUserProviderInterface $loggedInUserProvider
     * @param PostTransactionFactoryInterface $transactionFactory
     * @param PostsCommentsEventHandlingRepositoryInterface $commentsEventHandlingRepository
     * @param PostsSecurityEventsHandlingRepositoryInterface $securityEventsHandlingRepository
     * @param LoggerInterface $logger
     */
    public function __construct(
        ApplicationEventPublisherInterface             $eventPublisher,
        PostsCreationRepositoryInterface               $creationRepository,
        PostsUpdatingRepositoryInterface               $updatingRepository,
        PostsDeletionRepositoryInterface               $deletionRepository,
        PostsFindingRepositoryInterface                $findingRepository,
        LoggedInUserProviderInterface                  $loggedInUserProvider,
        PostTransactionFactoryInterface                $transactionFactory,
        PostsCommentsEventHandlingRepositoryInterface  $commentsEventHandlingRepository,
        PostsSecurityEventsHandlingRepositoryInterface $securityEventsHandlingRepository,
        LoggerInterface                                $logger
    )
    {
        parent::__construct($logger);
        $validator = new PostsValidator($findingRepository);
        $this->__creatorConstruct($eventPublisher, $creationRepository, $loggedInUserProvider, $transactionFactory, $validator);
        $this->__updaterConstruct($eventPublisher, $updatingRepository, $transactionFactory, $validator);
        $this->__removerConstruct($eventPublisher, $deletionRepository, $transactionFactory, $validator);
        $this->__finderConstruct($findingRepository);
        $this->__commentsEventHandlerConstruct($transactionFactory, $commentsEventHandlingRepository, $validator);
        $this->__postsBaselinerConstruct($findingRepository, $eventPublisher);
        $this->__securityEventsHandlerConstruct($transactionFactory, $securityEventsHandlingRepository);
    }

    /**
     * @return array<string, string>
     */
    protected function subscribe(): array
    {
        return [
            CommentCreatedPostsIEvent::class => 'onCommentCreated',
            CommentsBaselinedPostsIEvent::class => 'onCommentsBaselined',
            UserRenamedPostsIEvent::class => 'onUserRenamed',
        ];
    }
}