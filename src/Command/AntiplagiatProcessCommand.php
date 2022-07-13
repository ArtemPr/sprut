<?php
/*
 * Created admhome <admhome@ya.ru> 2022.
 */

namespace App\Command;

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

    protected $projectDir;

    public function __construct(
        private readonly AntiplagiatRepository $antiplagiatRepository,
        private readonly ManagerRegistry $doctrine,
        protected AntiplagiatAPI $antiplagiatAPI,
        protected KernelInterface $appKernel
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

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $itemId = $input->getArgument('id');
        $itemRow = null;

        // подготовка данных

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
                hydrationMode: Query::HYDRATE_ARRAY
            );
        }

        if (empty($itemRow)) {
            throw new \Exception('[E] Item is not found!');
        }

        $itemRow = $itemRow[0];

        // обработка

        $output->writeln('[ ] Start processing item');

        /**
         * @todo Необходимо подумать над тем, что тот кусок кода из API контроллера обособить в отдельный метод и вызвать метод тут!
         */

        $docId = $this->antiplagiatAPI->uploadFile($this->projectDir.'/'.$itemRow['file']);
        $intDocId = $docID->Id ?? 0;

        //

        dd([
            '$itemRow' => $itemRow,
            '$docId' => $docId
        ]);

        //

        return Command::SUCCESS;
    }
}
