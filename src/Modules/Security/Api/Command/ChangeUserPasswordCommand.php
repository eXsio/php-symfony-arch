<?php

namespace App\Modules\Security\Api\Command;

use App\Modules\Security\Api\SecurityApiInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Validator\Constraints as Assert;

class ChangeUserPasswordCommand extends Command
{

    protected static $defaultName = 'app:security:change-password';

    private string $login;

    private string $password;

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
            ->addArgument('password', InputArgument::REQUIRED, 'User password');
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
        $this->securityApi->changePassword($this);
        $output->writeln(sprintf("Successfully changed password for user '%s'.", $this->login));
        return Command::SUCCESS;

    }

    /**
     * @return string
     */
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
}