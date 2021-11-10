<?php

namespace App\Modules\Security\Domain\Logic;

use App\Modules\Security\Api\Command\CreateUserCommand;
use App\Modules\Security\Api\Command\Response\CreateUserCommandResponse;
use App\Modules\Security\Domain\Dto\CreateNewUserDto;
use App\Modules\Security\Domain\Repository\UserCreationRepositoryInterface;
use App\Modules\Security\Domain\Transactions\SecurityTransactionFactoryInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

trait UserCreator
{
    use PasswordHasher {
        PasswordHasher::__construct as __hasherConstruct;
    }

    /**
     * @param UserPasswordHasherInterface $passwordHasher
     * @param SecurityTransactionFactoryInterface $transactionFactory
     * @param UserCreationRepositoryInterface $creationRepository
     */
    public function __construct(
        private UserPasswordHasherInterface         $passwordHasher,
        private SecurityTransactionFactoryInterface $transactionFactory,
        private UserCreationRepositoryInterface     $creationRepository,
        private SecurityValidator                   $validator
    )
    {
        $this->__hasherConstruct($this->passwordHasher);
    }

    /**
     * @param CreateUserCommand $command
     * @return CreateUserCommandResponse
     */
    function createUser(CreateUserCommand $command): CreateUserCommandResponse
    {
        $this->validator->preCreateUser($command);
        $newUser = $this->fromCommand($command);
        $id = $this->transactionFactory
            ->createTransaction(function () use ($newUser) {
                return $this->creationRepository->createUser($newUser);
            })
            ->execute();
        return new CreateUserCommandResponse($id);
    }

    /**
     * @param CreateUserCommand $command
     * @return CreateNewUserDto
     */
    private function fromCommand(CreateUserCommand $command): CreateNewUserDto
    {
        return new CreateNewUserDto(
            $command->getLogin(),
            $this->hashPassword($command->getPassword()),
            $command->getRoles()
        );
    }
}