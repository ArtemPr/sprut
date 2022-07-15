<?php
/*
 * Created admhome <admhome@ya.ru> 2022.
 */

namespace App\Command;

use App\Controller\Api\ApiAntiplagiat;
use App\Entity\Antiplagiat;
use App\Repository\AntiplagiatRepository;
use App\Service\AntiplagiatAPI;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class AntiplagiatProcessCommand extends Command
{
    // название команды (часть после "bin/console")
    protected static $defaultName = 'antiplagiat:process';

    protected string $projectDir;

    public function __construct(
        private AntiplagiatRepository $antiplagiatRepository,
        private readonly ManagerRegistry $doctrine,
        protected AntiplagiatAPI $antiplagiatAPI,
        protected KernelInterface $appKernel,
        protected ApiAntiplagiat $apiAntiplagiat
    ) {
        $this->projectDir = $this->appKernel->getProjectDir().'/public';

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('id', InputArgument::OPTIONAL, 'id from antiplagiat table', 0)
            ->setDescription('This command run process antiplagiat check')
            ->setHelp('This command run process antiplagiat check')
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

        if (!empty($itemId)) {
            $itemRow = $this->doctrine->getRepository(Antiplagiat::class)->find(['id' => $itemId]);
        } else {
            $qb = $this->doctrine->getRepository(Antiplagiat::class)
                ->createQueryBuilder('ap')
                ->where('ap.status IN (1, 2)')
                ->orderBy('ap.data_create')
                ->setMaxResults(1)
            ;
            $query = $qb->getQuery();
            $itemRow = $query->execute(
                hydrationMode: Query::HYDRATE_OBJECT
            );

            if (!empty($itemRow)) {
                $itemRow = $itemRow[0];
            }
        }

        if (empty($itemRow)) {
            throw new \Exception('[E] Item is not found!');
        }

        // обработка

        $output->writeln('['.date('d/m/Y H:i:s').'] Start processing item');

        $this->apiAntiplagiat->processItem($itemRow, $this->projectDir);

        $output->writeln('['.date('d/m/Y H:i:s').'] Finish processing item');

        return Command::SUCCESS;
    }
}
