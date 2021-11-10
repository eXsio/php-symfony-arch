<?php

namespace App\Modules\Security\Api\Command;

use App\Modules\Security\Api\SecurityApiInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Validator\Constraints as Assert;

class RenameUserCommand extends Command
{

    protected static $defaultName = 'app:security:rename-user';

    private string $login;

    private string $newLogin;


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
            ->addArgument('login', InputArgument::REQUIRED, 'User login')
            ->addArgument('newLogin', InputArgument::REQUIRED, 'New login');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $this->login = $input->getArgument("login");
        $this->newLogin = $input->getArgument("newLogin");
        $this->securityApi->renameUser($this);
        $output->writeln(sprintf("Successfully renamed user '%s' to '%s'.", $this->login, $this->newLogin));
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
    #[Assert\Email(message: "The user login has to be e-mail")]
    public function getNewLogin(): string
    {
        return $this->newLogin;
    }


}