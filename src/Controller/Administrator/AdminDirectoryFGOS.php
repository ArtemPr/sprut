<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller\Administrator;

use App\Entity\FederalStandart;
use App\Entity\ProfStandarts;
use App\Service\AuthService;
use App\Service\LinkService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminDirectoryFGOS extends AbstractController
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

    public function getList(): Response
    {
        $auth = $this->getAuthValue($this->getUser(), 'auth_directory', $this->managerRegistry);
        if(!empty($auth)) {
            return $auth;
        }

        $tpl = $this->request->get('ajax') ? 'administrator/directory/fgos_table.html.twig' : 'administrator/directory/fgos.html.twig';

        return $this->render($tpl,
            $this->get()
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

        foreach ($data as $val) {
            $table .= '"' . ( $val['active'] ? 'да' : 'нет') . '";"' . $val['short_name'] . '";"' . $val['name'] . '"' . "\n";
        }

        $response = new Response($table);
        $response->headers->set('Content-Type', 'text/csv');

        return $response;
    }

    private function setTable()
    {
        return [
            ['', 'Активность', 'bool', true],
            ['short_name', 'Код', 'string', true],
            ['name', 'Название', 'string', true]
        ];
    }

    private function get(bool $full = false)
    {
        $page = $this->request->get('page') ?? null;
        $on_page = $this->request->get('on_page') ?? 25;
        $sort = $this->request->get('sort') ?? null;
        $search = $this->request->get('search') ?? null;

        if ($full === false) {
            $result = $this->managerRegistry->getRepository(FederalStandart::class)->getList($page, $on_page, $sort, $search);
            $count = $this->managerRegistry->getRepository(FederalStandart::class)->getListAll($page, $on_page, $sort, $search);
        } else {
            $result = $this->managerRegistry->getRepository(FederalStandart::class)->getList(0, 9999999999, $sort, $search);
            $count = $this->managerRegistry->getRepository(FederalStandart::class)->getListAll(0, 9999999999, $sort, $search);
        }

        $page = $page ?? 1;

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
            'search_link' => $this->getSearchLink(),
            'table' => $this->setTable()
        ];
    }
}
