<?php

namespace App\Modules\Security\Domain;

use App\Infrastructure\Events\Api\ApplicationEventPublisherInterface;
use App\Infrastructure\Security\LoggedInUserProviderInterface;
use App\Modules\Security\Api\SecurityApiInterface;
use App\Modules\Security\Domain\Logic\JWTSecurityListener;
use App\Modules\Security\Domain\Logic\SecurityValidator;
use App\Modules\Security\Domain\Logic\UserCreator;
use App\Modules\Security\Domain\Logic\UserUpdater;
use App\Modules\Security\Domain\Repository\UserCreationRepositoryInterface;
use App\Modules\Security\Domain\Repository\UserFindingRepositoryInterface;
use App\Modules\Security\Domain\Repository\UserUpdatingRepositoryInterface;
use App\Modules\Security\Domain\Transactions\SecurityTransactionFactoryInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SecurityApi implements SecurityApiInterface
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

    /**
     * @param SecurityTransactionFactoryInterface $transactionFactory
     * @param UserPasswordHasherInterface $passwordHasher
     * @param UserCreationRepositoryInterface $creationRepository
     * @param LoggedInUserProviderInterface $databaseLoggedInUserProvider
     * @param UserUpdatingRepositoryInterface $updatingRepository
     * @param UserFindingRepositoryInterface $findingRepository
     * @param LoggerInterface $logger
     * @param ValidatorInterface $validator
     * @param ApplicationEventPublisherInterface $eventPublisher
     */
    public function __construct(
        SecurityTransactionFactoryInterface $transactionFactory,
        UserPasswordHasherInterface         $passwordHasher,
        UserCreationRepositoryInterface     $creationRepository,
        LoggedInUserProviderInterface       $databaseLoggedInUserProvider,
        UserUpdatingRepositoryInterface     $updatingRepository,
        UserFindingRepositoryInterface      $findingRepository,
        LoggerInterface                     $logger,
        ValidatorInterface                  $validator,
        ApplicationEventPublisherInterface  $eventPublisher
    )
    {
        $securityValidator = new SecurityValidator($validator, $findingRepository);
        $this->__userUpdaterConstruct($eventPublisher, $transactionFactory, $updatingRepository, $securityValidator, $passwordHasher);
        $this->__userCreatorConstruct($passwordHasher, $transactionFactory, $creationRepository, $securityValidator);
        $this->__jwtTokenDecoratorConstruct($databaseLoggedInUserProvider);
    }
}