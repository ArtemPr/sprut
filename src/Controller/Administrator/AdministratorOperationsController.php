<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller\Administrator;

use App\Entity\Operations;
use App\Entity\TrainingCenters;
use App\Service\AuthService;
use App\Service\CSVHelper;
use App\Service\LinkService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdministratorOperationsController extends AbstractController
{
    use LinkService;
    use AuthService;
    use CSVHelper;

    private $request;

    public function __construct(
        private ManagerRegistry $managerRegistry
    )
    {
        $this->request = new Request($_GET);
    }

    private function setTable()
    {
        return [
            ['group', 'Группа', 'string', true],
            ['name', 'Название', 'string', true],
            ['comment', 'Комментарий', 'string', true],
            ['code', 'Код операции', 'string', true]
        ];
    }

    private function get(bool $full = false)
    {
        $page = $this->request->get('page') ?? null;
        $on_page = $this->request->get('on_page') ?? 25;
        $sort = $this->request->get('sort') ?? null;
        $search = $this->request->get('search') ?? null;

        if ($full === false) {
            $result = $this->managerRegistry->getRepository(Operations::class)->getList($page, $on_page, $sort,$search);
            $count = $this->managerRegistry->getRepository(Operations::class)->getAll($page, $on_page, $sort,$search);
        } else {
            $result = $this->managerRegistry->getRepository(Operations::class)->getList(0, 9999999999, $sort,$search);
            $count = $this->managerRegistry->getRepository(Operations::class)->getAll(0, 9999999999, $sort,$search);
        }

        $page = $page ?? 1;

        return [
            'data' => $result,
            'search' => $search,
            'pager' => [
                'count_all_position' => $count,
                'current_page' => $page,
                'count_page' => (int)ceil($count / $on_page),
                'paginator_link' => $this->getParinatorLink(),
                'on_page' => $on_page
            ],
            'sort' => [
                'sort_link' => $this->getSortLink(),
                'current_sort' => $this->request->get('sort') ?? null,
            ],
            'table' => $this->setTable(),
            'csv_link' => $this->getCSVLink()
        ];
    }

    public function getOperationsList(): Response
    {
        $auth = $this->getAuthValue($this->getUser(), 'auth_operations', $this->managerRegistry);
        if (!is_array($auth)) {
            return $auth;
        }

        $tpl = $this->request->get('ajax') ? 'administrator/operations/operation_table.html.twig' : 'administrator/operations/index.html.twig' ;
        $result = $this->get();
        $result['auth'] = $auth;

        return $this->render(
            $tpl,
            $result,
        );
    }

    public function getOperationsCSV()
    {
        $result = $this->get(true);
        $data = [];

        $dataRow = [];
        foreach ($this->setTable() as $tbl) {
            $dataRow[] = $tbl[1];
        }

        $data[] = $dataRow;

        foreach ($result['data'] as $val) {
            $data[] = [
                $val['group'],
                $val['name'],
                $val['comment'],
                $val['code'],
            ];
        }

        return $this->processCSV($data, 'operations.csv');
    }

    public function getOperationsForm($id)
    {
        $data_out = $this->managerRegistry->getRepository(Operations::class)->get($id);
        return $this->render(
            'administrator/operations/form/update_form.html.twig',
            [
                'data' => $data_out[0] ?? null,
                'controller' => 'AdminOperations',
            ]
        );
    }
}
