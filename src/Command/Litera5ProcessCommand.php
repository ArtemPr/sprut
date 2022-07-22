<?php
/*
 * Created admhome <admhome@ya.ru> 2022.
 */

namespace App\Command;

use App\Entity\Litera;
use App\Repository\LiteraRepository;
use App\Service\Litera5API;
use Doctrine\Persistence\ManagerRegistry;
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
        private LiteraRepository $literaRepository,
        private readonly ManagerRegistry $doctrine,
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

        if (!empty($itemId)) {
            $itemRow = $this->doctrine->getRepository(Litera::class)->find(['id' => $itemId]);
        } else {
            $qb = $this->doctrine->getRepository(Litera::class)
                ->createQueryBuilder('lit')
                ->where('lit.status IN (1, 2)')
                ->orderBy('lit.data_create')
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

        $result = $this->litera5API->setCheck([
            'document' => '', // Идентификатор документа в Литере, если его нет - будет создан новый документ
            'name' => '', // Название документа, если нет - будет сформировано автоматически из текста
            'title' => '', // Дополнительное поле - заголовок документа для проверки
            'description' => '', // Дополнительное поле - краткое описание страницы
            'keywords' => '', // Дополнительное поле - ключевые слова страницы
            'html' => '', // текст для проверки в формате html
        ]);
        $output->writeln('[ ] Result: '.var_export($result, true));

        return Command::SUCCESS;
    }
}
