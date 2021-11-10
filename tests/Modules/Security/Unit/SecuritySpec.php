<?php

namespace App\Tests\Modules\Security\Unit;

use App\Infrastructure\Security\LoggedInUserProviderInterface;
use App\Modules\Security\Api\SecurityApiInterface;
use App\Modules\Security\Domain\SecurityApi;
use App\Tests\Modules\Security\Unit\Repository\InMemorySecurityRepository;
use App\Tests\Modules\Security\Unit\Transaction\InMemorySecurityTransactionFactory;
use App\Tests\TestUtils\Events\InMemoryEventPublisher;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class SecuritySpec extends TestCase
{
    protected SecurityApiInterface $securityApi;

    /**
     * @before
     */
    protected function setupSecurityApi()
    {
        $repository = new InMemorySecurityRepository();
        $this->securityApi = new SecurityApi(
            new InMemorySecurityTransactionFactory(),
            $this->createMock(UserPasswordHasherInterface::class),
            $repository,
            $this->createMock(LoggedInUserProviderInterface::class),
            $repository,
            $repository,
            $repository,
            $repository,
            $repository,
            $this->createMock(LoggerInterface::class),
            $this->createMock(ValidatorInterface::class),
            new InMemoryEventPublisher()
        );
    }
}