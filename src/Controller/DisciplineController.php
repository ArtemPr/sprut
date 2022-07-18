<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller;

use App\Entity\Discipline;
use App\Service\AuthService;
use App\Service\CSVHelper;
use App\Service\LinkService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DisciplineController extends BaseController implements BaseInterface
{
    use LinkService;
    use AuthService;
    use CSVHelper;

    private function setTable()
    {
        return [
            ['', '', '', true],
            ['name', 'Тип', 'string', true],
            ['practicum_flag', 'Практикум', 'int', true],
            ['type', 'Название', 'string', true],
            ['comment', 'Комментарий', 'string', true],
            ['comment', 'Цель освоения', 'string', true],
        ];
    }

    public function get(bool $full = false)
    {
        $page = $this->request->get('page') ?? null;
        $on_page = $this->request->get('on_page') ?? 25;
        $sort = $this->request->get('sort') ?? null;
        $search = $this->request->get('search') ?? null;

        if ($full === false) {
            $result = $this->managerRegistry->getRepository(Discipline::class)->getList($page, $on_page, $sort, $search);
            $count = $this->managerRegistry->getRepository(Discipline::class)->getListAll($page, $on_page, $sort, $search);
        } else {
            $result = $this->managerRegistry->getRepository(Discipline::class)->getList(0, 9999999999, $sort, $search);
            $count = $this->managerRegistry->getRepository(Discipline::class)->getListAll(0, 9999999999, $sort, $search);
        }

        $page = $page ?? 1;

        return [
            'data' => $result,
            'pager' => [
                'count_all_position' => $count,
                'current_page' => $page,
                'count_page' => (int) ceil($count / $on_page),
                'paginator_link' => $this->getParinatorLink(),
                'on_page' => $on_page,
            ],
            'sort' => [
                'sort_link' => $this->getSortLink(),
                'current_sort' => $this->request->get('sort') ?? null,
            ],
            'table' => $this->setTable(),
            'csv_link' => $this->getCSVLink(),
        ];
    }

    #[Route('/discipline', name: 'discipline')]
    public function getList(): Response
    {
        $auth = $this->getAuthValue($this->getUser(), 'auth_desktop', $this->managerRegistry);
        if (!is_array($auth)) {
            return $auth;
        }

        $tpl = $this->request->get('ajax') ? 'discipline/discipline_table.html.twig' : 'discipline/discipline.html.twig';
        $result = $this->get();
        $result['auth'] = $auth;

        return $this->render($tpl,
            $result
        );
    }

    #[Route('/discipline_csv', name: 'discipline_csv')]
    public function getListCSV()
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
                ($val['status'] == 'accept' ? 'да' : 'нет'),
                $val['type'], // надо типы
                ($val['practice'] ? 'да' : 'нет'),
                $val['name'],
                $val['comment'],
                $val['purpose'],
            ];
        }

        return $this->processCSV($data, 'discipline.csv');
    }

    public function getDisciplineForm($id)
    {
        return $this->render('discipline/form/discipline_update.html.twig');
    }
}
