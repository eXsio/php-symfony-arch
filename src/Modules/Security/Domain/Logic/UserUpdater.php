<?php

namespace App\Modules\Security\Domain\Logic;

use App\Infrastructure\Events\Api\ApplicationEventPublisherInterface;
use App\Modules\Security\Api\Command\ChangeUserPasswordCommand;
use App\Modules\Security\Api\Command\RenameUserCommand;
use App\Modules\Security\Domain\Dto\ChangeExistingUserPasswordDto;
use App\Modules\Security\Domain\Dto\RenameExistingUserDto;
use App\Modules\Security\Domain\Event\Outbound\UserRenamedOEvent;
use App\Modules\Security\Domain\Repository\UserUpdatingRepositoryInterface;
use App\Modules\Security\Domain\Transactions\SecurityTransactionFactoryInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

trait UserUpdater
{
    use PasswordHasher {
        PasswordHasher::__construct as __hasherConstruct;
    }

    public function __construct(
        private ApplicationEventPublisherInterface  $eventPublisher,
        private SecurityTransactionFactoryInterface $transactionFactory,
        private UserUpdatingRepositoryInterface     $updatingRepository,
        private SecurityValidator                   $validator,
        private UserPasswordHasherInterface         $passwordHasher,
    )
    {
        $this->__hasherConstruct($this->passwordHasher);
    }

    /**
     * @param RenameUserCommand $command
     */
    public function renameUser(RenameUserCommand $command): void
    {
        $this->validator->preRenameUser($command);
        $this->transactionFactory
            ->createTransaction(function () use ($command) {
                $this->updatingRepository->renameUser(
                    new RenameExistingUserDto($command->getLogin(), $command->getNewLogin())
                );
            })
            ->afterCommit(function () use ($command) {
                $this->eventPublisher->publish(
                    new UserRenamedOEvent($command->getLogin(), $command->getNewLogin())
                );
            })
            ->execute();
    }

    /**
     * @param ChangeUserPasswordCommand $command
     */
    public function changePassword(ChangeUserPasswordCommand $command): void
    {
        $this->validator->preChangeUserPassword($command);
        $this->transactionFactory
            ->createTransaction(function () use ($command) {
                $this->updatingRepository->changePassword(
                    new ChangeExistingUserPasswordDto(
                        $command->getLogin(),
                        $this->hashPassword($command->getPassword())
                    )
                );
            })
            ->execute();
    }
}