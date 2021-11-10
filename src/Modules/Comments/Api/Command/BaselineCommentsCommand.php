<?php

namespace App\Modules\Comments\Api\Command;

use App\Modules\Comments\Api\CommentsApiInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BaselineCommentsCommand extends Command
{

    protected static $defaultName = 'app:comments:baseline';

    private ?\DateTime $from = null;

    /**
     * @param CommentsApiInterface $commentsApi
     */
    public function __construct(
        private CommentsApiInterface $commentsApi
    )
    {
        parent::__construct();
    }


    protected function configure(): void
    {
        $this->addArgument('from', InputArgument::OPTIONAL, 'from');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $from = $input->getArgument("from");
        if ($from != null) {
            $this->from = \DateTime::createFromFormat("Y-m-d H:i:s", $from . "00:00:00");
        }
        $this->commentsApi->baseline($this);
        if ($from != null) {
            $output->writeln(sprintf("Successfully base-lined Comments from %s", $from));
        } else {
            $output->writeln(sprintf("Successfully base-lined all Comments"));
        }

        return Command::SUCCESS;

    }

    /**
     * @return \DateTime|null
     */
    public function getFrom(): ?\DateTime
    {
        return $this->from;
    }




}