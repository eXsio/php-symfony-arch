<?php

namespace App\Modules\Security\Domain;

use App\Infrastructure\Events\Api\ApplicationEventPublisherInterface;
use App\Infrastructure\Events\Api\ApplicationEventSubscriber;
use App\Infrastructure\Events\Api\EventHandlerReference;
use App\Infrastructure\Security\LoggedInUserProviderInterface;
use App\Modules\Security\Api\Event\Inbound\CommentsCountUpdatedSecurityIEvent;
use App\Modules\Security\Api\Event\Inbound\PostCreatedSecurityIEvent;
use App\Modules\Security\Api\Event\Inbound\PostDeletedSecurityIEvent;
use App\Modules\Security\Api\Event\Inbound\PostUpdatedSecurityIEvent;
use App\Modules\Security\Api\SecurityApiInterface;
use App\Modules\Security\Domain\Logic\CommentsEventsHandler;
use App\Modules\Security\Domain\Logic\JWTSecurityListener;
use App\Modules\Security\Domain\Logic\PostHeadersFinder;
use App\Modules\Security\Domain\Logic\PostsEventsHandler;
use App\Modules\Security\Domain\Logic\SecurityValidator;
use App\Modules\Security\Domain\Logic\UserCreator;
use App\Modules\Security\Domain\Logic\UserUpdater;
use App\Modules\Security\Domain\Repository\SecurityCommentsEventHandlingRepositoryInterface;
use App\Modules\Security\Domain\Repository\SecurityPostEventsHandlingRepositoryInterface;
use App\Modules\Security\Domain\Repository\UserCreationRepositoryInterface;
use App\Modules\Security\Domain\Repository\UserFindingRepositoryInterface;
use App\Modules\Security\Domain\Repository\UserPostHeadersFindingRepositoryInterface;
use App\Modules\Security\Domain\Repository\UserUpdatingRepositoryInterface;
use App\Modules\Security\Domain\Transactions\SecurityTransactionFactoryInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SecurityApi extends ApplicationEventSubscriber implements SecurityApiInterface
{
    use UserCreator {
        UserCreator::__construct as __userCreatorConstruct;
    }

    use UserUpdater {
        UserUpdater::__construct as __userUpdaterConstruct;
    }

    use JWTSecurityListener {
        JWTSecurityListener::__construct as __jwtTokenDecoratorConstruct;
    }

    use PostsEventsHandler {
        PostsEventsHandler::__construct as __postEventsHandlerConstruct;
    }

    use CommentsEventsHandler {
        CommentsEventsHandler::__construct as __commentsEventsHandlerConstruct;
    }

    use PostHeadersFinder {
        PostHeadersFinder::__construct as __postHeadersFinderConstruct;
    }


    /**
     * @param SecurityTransactionFactoryInterface $transactionFactory
     * @param UserPasswordHasherInterface $passwordHasher
     * @param UserCreationRepositoryInterface $creationRepository
     * @param LoggedInUserProviderInterface $databaseLoggedInUserProvider
     * @param SecurityPostEventsHandlingRepositoryInterface $postEventsSecurityRepository
     * @param UserPostHeadersFindingRepositoryInterface $headersFindingRepository
     * @param SecurityCommentsEventHandlingRepositoryInterface $commentsEventHandlingRepository
     * @param UserUpdatingRepositoryInterface $updatingRepository
     * @param UserFindingRepositoryInterface $findingRepository
     * @param LoggerInterface $logger
     * @param ValidatorInterface $validator
     * @param ApplicationEventPublisherInterface $eventPublisher
     */
    public function __construct(
        SecurityTransactionFactoryInterface              $transactionFactory,
        UserPasswordHasherInterface                      $passwordHasher,
        UserCreationRepositoryInterface                  $creationRepository,
        LoggedInUserProviderInterface                    $databaseLoggedInUserProvider,
        SecurityPostEventsHandlingRepositoryInterface    $postEventsSecurityRepository,
        UserPostHeadersFindingRepositoryInterface        $headersFindingRepository,
        SecurityCommentsEventHandlingRepositoryInterface $commentsEventHandlingRepository,
        UserUpdatingRepositoryInterface                  $updatingRepository,
        UserFindingRepositoryInterface                   $findingRepository,
        LoggerInterface                                  $logger,
        ValidatorInterface                               $validator,
        ApplicationEventPublisherInterface               $eventPublisher
    )
    {
        parent::__construct($logger);
        $securityValidator = new SecurityValidator($validator, $findingRepository);
        $this->__userUpdaterConstruct($eventPublisher, $transactionFactory, $updatingRepository, $securityValidator, $passwordHasher);
        $this->__userCreatorConstruct($passwordHasher, $transactionFactory, $creationRepository, $securityValidator);
        $this->__jwtTokenDecoratorConstruct($databaseLoggedInUserProvider);
        $this->__postEventsHandlerConstruct($transactionFactory, $postEventsSecurityRepository);
        $this->__postHeadersFinderConstruct($headersFindingRepository);
        $this->__commentsEventsHandlerConstruct($transactionFactory, $commentsEventHandlingRepository);
    }

    /**
     * @return array<string, EventHandlerReference>
     */
    protected function subscribe(): array
    {
        return [
            PostCreatedSecurityIEvent::EVENT_NAME => EventHandlerReference::create('onPostCreated', PostCreatedSecurityIEvent::class),
            PostUpdatedSecurityIEvent::EVENT_NAME => EventHandlerReference::create('onPostUpdated', PostUpdatedSecurityIEvent::class),
            PostDeletedSecurityIEvent::EVENT_NAME => EventHandlerReference::create('onPostDeleted', PostDeletedSecurityIEvent::class),
            CommentsCountUpdatedSecurityIEvent::EVENT_NAME => EventHandlerReference::create('onCommentsCountUpdated', CommentsCountUpdatedSecurityIEvent::class),
        ];
    }
}