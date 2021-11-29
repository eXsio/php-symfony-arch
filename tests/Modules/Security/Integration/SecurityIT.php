<?php

namespace App\Tests\Modules\Security\Integration;

use App\Modules\Security\Api\Command\ChangeUserPasswordCommand;
use App\Modules\Security\Api\Command\CreateUserCommand;
use App\Modules\Security\Api\Command\RenameUserCommand;
use App\Modules\Security\Api\SecurityApiInterface;
use App\Modules\Security\Domain\Event\Outbound\UserRenamedOEvent;
use App\Tests\Modules\Security\Integration\Http\SecurityHttpTrait;
use App\Tests\TestUtils\Contracts\ApplicationEventContractLoader;
use App\Tests\TestUtils\Events\InMemoryEventPublisher;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class SecurityIT extends SecurityIntegrationTest
{
    use SecurityHttpTrait;
    use ApplicationEventContractLoader;

    /**
     * @test
     */
    public function shouldCreateNewUserAndUpdateIt(): void
    {
        //given: There is a new user to be created
        $userName = "it@exsio.com";
        $password = "itPassword";
        $roles = "ROLE_ADMIN";

        //and: the CLI application is bootable
        $application = $this->setupKernel();

        //when: the user is created using CLI Command
        $command = $application->find('app:security:create-user');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'command' => $command->getName(),
            'login' => $userName,
            'password' => $password,
            'roles' => $roles,
        ));

        //then: the user was created successfully
        $output = $commandTester->getDisplay();
        $this->assertStringStartsWith("Successfully created user '$userName' with id", $output);

        //when: the user is created using CLI Command
        $newUserName = "it-updated@exsio.com";
        $command = $application->find('app:security:rename-user');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'command' => $command->getName(),
            'login' => $userName,
            'newLogin' => $newUserName,
        ));

        //then: the user was renamed successfully
        $output = $commandTester->getDisplay();
        $this->assertStringStartsWith("Successfully renamed user '$userName' to '$newUserName'", $output);

        //and: The RenamedUserOEvent was Published
        $events = InMemoryEventPublisher::get(UserRenamedOEvent::class);
        self::assertCount(1, $events);
        self::assertEquals($userName, $events[0]->getData()['oldLogin']);
        self::assertEquals($newUserName, $events[0]->getData()['newLogin']);

        //when: the user is created using CLI Command
        $newPassword = "itPasswordUpdated";
        $command = $application->find('app:security:change-password');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'command' => $command->getName(),
            'login' => $newUserName,
            'password' => $newPassword
        ));

        //then: the password was changed successfully
        $output = $commandTester->getDisplay();
        $this->assertStringStartsWith("Successfully changed password for user '$newUserName'.", $output);


    }

    /**
     * @test
     */
    public function shouldNotCreateNewUserWithInvalidName(): void
    {
        //given: There is a new user to be created
        $userName = "it";
        $password = "itPassword";
        $roles = "ROLE_ADMIN";

        //and: the CLI application is bootable
        $application = $this->setupKernel();

        //expect:
        $this->expectException(\InvalidArgumentException::class);

        //when: the user is created using CLI Command
        $command = $application->find('app:security:create-user');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'command' => $command->getName(),
            'login' => $userName,
            'password' => $password,
            'roles' => $roles,
        ));
    }

    /**
     * @return Application
     */
    protected function setupKernel(): Application
    {
        $kernel = static::createKernel();
        $kernel->boot();
        $application = new Application($kernel);
        $application->add(new CreateUserCommand(
            $this->getContainer()->get(SecurityApiInterface::class)
        ));
        $application->add(new ChangeUserPasswordCommand(
            $this->getContainer()->get(SecurityApiInterface::class)
        ));
        $application->add(new RenameUserCommand(
            $this->getContainer()->get(SecurityApiInterface::class)
        ));
        return $application;
    }
}