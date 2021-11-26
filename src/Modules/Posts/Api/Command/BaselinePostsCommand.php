<?php

namespace App\Modules\Posts\Api\Command;

use App\Modules\Posts\Api\PostsApiInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BaselinePostsCommand extends Command
{

    protected static $defaultName = 'app:posts:baseline';

    private ?\DateTime $from = null;

    /**
     * @param PostsApiInterface $postsApi
     */
    public function __construct(
        private PostsApiInterface $postsApi
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
        $this->postsApi->baseline($this);
        if ($from != null) {
            $output->writeln(sprintf("Successfully base-lined Posts from %s", $from));
        } else {
            $output->writeln(sprintf("Successfully base-lined all Posts"));
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