<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller\Administrator;

use App\Entity\ProfStandarts;
use App\Service\AuthService;
use App\Service\CSVHelper;
use App\Service\LinkService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminDirectoryPS extends AbstractController
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
            [
                'name' => ' ',
                'header' => '',
                'type' => 'bool',
                'filter' => false,
                'show' => true,
                'sort' => false,
            ],
            [
                'name' => 'name',
                'header' => 'Название',
                'type' => 'string',
                'filter' => false,
                'show' => true,
                'sort' => false,
            ],
            [
                'name' => 'short_name',
                'header' => 'Короткое название',
                'type' => 'string',
                'filter' => false,
                'show' => true,
                'sort' => false,
            ],
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
            $count = $this->managerRegistry->getRepository(ProfStandarts::class)->getListAll($page, $on_page, $sort, $search);
        } else {
            $result = $this->managerRegistry->getRepository(ProfStandarts::class)->getList(0, 9999999999, $sort, $search);
            $count = $this->managerRegistry->getRepository(ProfStandarts::class)->getListAll(0, 9999999999, $sort, $search);
        }

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

        $tpl = $this->request->get('ajax') ? 'administrator/directory/ps/ps_table.html.twig' : 'administrator/directory/ps/ps.html.twig';
        $result = $this->get();
        $result['auth'] = $auth;

        return $this->render($tpl,
            $result
        );
    }

    public function getCSV()
    {
        $result = $this->get(true);
        $data = [];

        $dataRow = [];
        foreach ($this->setTable() as $tbl) {
            $dataRow[] = $tbl[1];
        }

        $data[] = $dataRow;

        foreach ($result['data'] as $val) {
            $name = !empty($val['name'])
                ? html_entity_decode($val['name'])
                : '-';
            $short_name = !empty($val['short_name'])
                ? html_entity_decode($val['short_name'])
                : '-';

            $data[] = [
                ($val['archive_flag'] ? 'да' : 'нет'),
                $name,
                $short_name,
            ];
        }

        return $this->processCSV($data, 'ps.csv');
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
