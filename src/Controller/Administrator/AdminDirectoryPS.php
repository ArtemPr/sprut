<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller\Administrator;

use App\Entity\ProfStandarts;
use App\Service\AuthService;
use App\Service\LinkService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminDirectoryPS extends AbstractController
{
    use LinkService;
    use AuthService;

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
            ['', '', 'bool', true],
            ['name', 'Название', 'string', true],
            ['short_name', 'Короткое название', 'string', true]
        ];
    }

    private function get(bool $full = false)
    {
        $page = $this->request->get('page') ?? null;
        $on_page = $this->request->get('on_page') ?? 25;
        $sort = $this->request->get('sort') ?? null;
        $search = $this->request->get('search') ?? null;

        if ($full === false) {
            $result = $this->managerRegistry->getRepository(ProfStandarts::class)->getList($page, $on_page, $sort, $search);
            $count = $this->managerRegistry->getRepository(ProfStandarts::class)->findAll();
        } else {
            $result = $this->managerRegistry->getRepository(ProfStandarts::class)->getList(0, 9999999999, $sort, $search);
            $count = $this->managerRegistry->getRepository(ProfStandarts::class)->findAll();
        }

        $count = count($count);

        return [
            'data' => $result,
            'search' => strip_tags($search) ?? '',
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

    public function getList(): Response
    {
        $auth = $this->getAuthValue($this->getUser(), 'auth_directory', $this->managerRegistry);
        if (!is_array($auth)) {
            return $auth;
        }

        $tpl = $this->request->get('ajax') ? 'administrator/directory/ps_table.html.twig' : 'administrator/directory/ps.html.twig';
        $result = $this->get();
        $result['auth'] = $auth;

        return $this->render($tpl,
            $result
        );
    }

    public function getCSV()
    {
        $result = $this->get(true);
        $table = '';

        foreach ($this->setTable() as $tbl) {
            $table .= '"' . $tbl[1] . '";';
        }
        $table = substr($table, 0, -1) . "\n";

        $data = $result['data'];

//        dd([
//            'data' => $data ?? '-',
//        ]);

        foreach ($data as $val) {
            $table .= '"' . ( $val['archive_flag'] ? 'да' : 'нет') . '";"' . $val['name'] . '";"' . $val['short_name'] . '"' . "\n";
        }

        $table = mb_convert_encoding($table, 'utf8');
        $table = htmlspecialchars_decode($table);

        $response = new Response($table);
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="ps.csv"');

        return $response;
    }

    public function getPSForm($id)
    {
        $data_out = $this->managerRegistry->getRepository(ProfStandarts::class)->get($id);

        return $this->render(
            'administrator/directory/form/ps_update.html.twig',
            [
                'data' => $data_out[0] ?? null,
                'controller' => 'AdminTrainingCentre',
            ]
        );
    }
}
