<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller;

use App\Entity\Discipline;
use App\Service\AuthService;
use App\Service\CSVHelper;
use App\Service\LinkService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DisciplineController extends AbstractController
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
            ['', '', '', true],
            ['name', 'Тип', 'string', true],
            ['practicum_flag', 'Практикум', 'int', true],
            ['type', 'Название', 'string', true],
            ['comment', 'Комментарий', 'string', true],
            ['comment', 'Цель освоения', 'string', true]
        ];
    }

    private function get(bool $full = false)
    {
        $page = $this->request->get('page') ?? null;
        $on_page = $this->request->get('on_page') ?? 25;
        $sort = $this->request->get('sort') ?? null;
        $search = $this->request->get('search') ?? null;

        if ($full === false) {
            $result = $this->managerRegistry->getRepository(\App\Entity\Discipline::class)->getList($page, $on_page, $sort, $search);
            $count = $this->managerRegistry->getRepository(\App\Entity\Discipline::class)->getListAll($page, $on_page, $sort, $search);
        } else {
            $result = $this->managerRegistry->getRepository(\App\Entity\Discipline::class)->getList(0, 9999999999, $sort, $search);
            $count = $this->managerRegistry->getRepository(\App\Entity\Discipline::class)->getListAll(0, 9999999999, $sort, $search);
        }

        $page = $page ?? 1;

        return [
            'data' => $result,
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
        $table = '';

        foreach ($this->setTable() as $tbl) {
            $table .= '"' . $tbl[1] . '";';
        }

        $table = substr($table, 0, -1) . "\n";
        $data = $result['data'];

        foreach ($data as $val) {
            $table .= '"' . ( $val['status'] == 'accept' ? 'да' : 'нет') . '";' .
                '"' . $val['type'] . '";' . // надо типы
                '"' . ($val['practice'] ? 'да' : 'нет') . '";' .
                '"' . $val['name'] . '";' .
                '"' . $val['comment'] . '";' .
                '"' . $val['purpose'] . '"' . "\n";
        }

        $table = mb_convert_encoding($table, 'utf8');
        $table = htmlspecialchars_decode($table);

        $response = new Response($table);
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="discipline.csv"');

        return $response;
    }

    public function getDisciplineForm($id)
    {
        return $this->render('discipline/form/discipline_update.html.twig');
    }
}
