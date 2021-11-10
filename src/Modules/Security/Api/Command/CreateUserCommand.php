<?php

namespace App\Modules\Security\Api\Command;

use App\Modules\Security\Api\SecurityApiInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Validator\Constraints as Assert;

class CreateUserCommand extends Command
{

    protected static $defaultName = 'app:security:create-user';

    private string $login;

    private string $password;

    private array $roles;

    /**
     * @param SecurityApiInterface $securityApi
     */
    public function __construct(
        private SecurityApiInterface $securityApi
    )
    {
        parent::__construct();
    }


    protected function configure(): void
    {
        $this
            // ...
            ->addArgument('login', InputArgument::REQUIRED, 'User login')
            ->addArgument('password', InputArgument::REQUIRED, 'User password')
            ->addArgument('roles', InputArgument::REQUIRED, 'User roles');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $this->login = $input->getArgument("login");
        $this->password = $input->getArgument("password");
        $this->roles = explode(",", trim($input->getArgument("roles")));
        $response = $this->securityApi->createUser($this);
        $output->writeln(sprintf("Successfully created user '%s' with id '%s'.", $this->login, $response->getId()));
        return Command::SUCCESS;

    }

    /**
     * @return string
     */
    #[Assert\Email(message: "The user login has to be e-mail")]
    public function getLogin(): string
    {
        return $this->login;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        return $this->roles;
    }


}