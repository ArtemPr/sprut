<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller\Services;

use App\Controller\BaseController;
use App\Controller\BaseInterface;
use App\Entity\MasterProgram;
use App\Service\AuthService;
use App\Service\CSVHelper;
use App\Service\LinkService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReportsController extends BaseController implements BaseInterface
{

    use AuthService;
    use LinkService;
    use CSVHelper;

    #[Route('/service_reposts', name: 'service_reposts')]
    public function getList(): Response
    {
        $page = $this->get_data['page'] ?? null;
        $on_page = $this->get_data['on_page'] ?? 25;
        $sort = $this->get_data['sort'] ?? null;
        $search = $this->get_data['search'] ?? null;

        if (true == true) {
            $data = $this->managerRegistry->getRepository(MasterProgram::class)->getList($page, $on_page, $sort, $search);
            $count = $this->managerRegistry->getRepository(MasterProgram::class)->getApiProgramInfo();
            $count = $count['count_program'] ?? 0;
        } else {
            $data = $this->managerRegistry->getRepository(MasterProgram::class)->getList(0, 9999999999, $sort, $search);
            $count = $this->managerRegistry->getRepository(MasterProgram::class)->getApiProgramInfo();
            $count = $count['count_program'] ?? 0;
        }

        return $this->render(
            'services/reports/index.html.twig',
            [
                'data' => $data,
                'search' => strip_tags($search) ?? '',
                'pager' => [
                    'count_all_position' => $count,
                    'current_page' => $page,
                    'count_page' => (int) ceil($count / $on_page),
                    'paginator_link' => $this->getParinatorLink(),
                    'on_page' => $on_page,
                ],
                'sort' => [
                    'sort_link' => $this->getSortLink(),
                    'current_sort' => $this->get_data['sort'] ?? null,
                ],
                'search_link' => $this->getSearchLink(),
                'table' => $this->setTable(),
                'csv_link' => $this->getCSVLink(),
            ]
        );
    }

    private function getReportProgram()
    {
        return $this->managerRegistry->getRepository(MasterProgram::class)->findAll();
    }

    public function get()
    {
        // TODO: Implement get() method.
    }

    private function setTable(): array
    {
        return [
            [
                'name' => 'id',
                'header' => 'ID',
                'type' => 'int',
                'filter' => true,
                'show' => true,
                'sort' => true,
            ],
            [
                'name' => 'name',
                'header' => 'Название программы',
                'type' => 'string',
                'filter' => true,
                'show' => true,
                'sort' => true,
            ]

            ,
            [
                'name' => 'length_week',
                'header' => 'Продолжительность (нед.)',
                'type' => 'int',
                'filter' => true,
                'show' => true,
                'sort' => true,
            ]
            ,
            [
                'name' => 'length_hour',
                'header' => 'Продолжительность (час.)',
                'type' => 'int',
                'filter' => true,
                'show' => true,
                'sort' => true,
            ]
            ,
            [
                'name' => 'length_week_short',
                'header' => 'Ускоренное обучение (нед.)',
                'type' => 'int',
                'filter' => true,
                'show' => true,
                'sort' => true,
            ]

        ];
    }
}
