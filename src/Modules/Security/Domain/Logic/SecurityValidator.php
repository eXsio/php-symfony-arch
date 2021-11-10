<?php

namespace App\Modules\Security\Domain\Logic;

use App\Modules\Security\Api\Command\ChangeUserPasswordCommand;
use App\Modules\Security\Api\Command\CreateUserCommand;
use App\Modules\Security\Api\Command\RenameUserCommand;
use App\Modules\Security\Domain\Repository\UserFindingRepositoryInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SecurityValidator
{

    public function __construct(
        private ValidatorInterface             $validator,
        private UserFindingRepositoryInterface $findingRepository
    )
    {
    }

    public function preCreateUser(CreateUserCommand $command)
    {
        $violations = $this->validator->validate($command);
        if ($violations->count() > 0) {
            throw new \InvalidArgumentException(
                sprintf("Invalid Arguments for new User: %s", (string)$violations)
            );
        }
    }

    public function preRenameUser(RenameUserCommand $command)
    {
        $this->checkIfUserExists($command->getLogin());
        $violations = $this->validator->validate($command);
        if ($violations->count() > 0) {
            throw new \InvalidArgumentException(
                sprintf("Invalid Arguments for renaming a User: %s", (string)$violations)
            );
        }
    }

    public function preChangeUserPassword(ChangeUserPasswordCommand $command)
    {
        $this->checkIfUserExists($command->getLogin());
    }

    private function checkIfUserExists(string $login): void
    {
        if (!$this->findingRepository->exists($login)) {
            throw new \InvalidArgumentException(
                sprintf("There is no User with login: %s", ($login))
            );
        }
    }
}