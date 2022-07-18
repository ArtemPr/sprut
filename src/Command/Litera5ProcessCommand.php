<?php
/*
 * Created admhome <admhome@ya.ru> 2022.
 */

namespace App\Command;

use App\Service\Litera5API;
use App\Service\Litera5Helper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class Litera5ProcessCommand extends Command
{
    // название команды (часть после "bin/console")
    protected static $defaultName = 'litera5:process';

    protected string $projectDir;

    public function __construct(
        protected Litera5API $litera5API,
        protected KernelInterface $appKernel
    ) {
        $this->projectDir = $this->appKernel->getProjectDir().'/public';

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('id', InputArgument::OPTIONAL, 'id from litera5 table', 0)
            ->setDescription('This command run process litera5 check')
            ->setHelp('This command run process litera5 check')
        ;
    }

    /**
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $itemId = $input->getArgument('id');

        if (!is_numeric($itemId)) {
            throw new \InvalidArgumentException('[E] id must be a number!', Command::INVALID);
        }

        $result = $this->litera5API->setCheck([
            'login' => '',
            'token' => '',
        ]);
        $output->writeln('[ ] Result: '.var_export($result, true));

        return Command::SUCCESS;
    }
}
