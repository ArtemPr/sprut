<?php

namespace App\Controller\Administrator;

use App\Controller\BaseController;
use App\Entity\Cluster;
use App\Entity\ProductLine;
use App\Service\AuthService;
use App\Service\CSVHelper;
use App\Service\LinkService;
use Symfony\Component\HttpFoundation\Response;

class AdminDirectoryProductLine extends BaseController
{
    use LinkService;
    use AuthService;
    use CSVHelper;

    public function getList(): Response
    {
        $auth = $this->getAuthValue($this->getUser(), 'auth_directory', $this->managerRegistry);
        if (!is_array($auth)) {
            return $auth;
        }

        $clusters = $this->managerRegistry->getRepository(Cluster::class)->getList();

        $tpl = $this->request->get('ajax') ? 'administrator/directory/product_line/product_line_table.html.twig' : 'administrator/directory/product_line/product_line.html.twig';
        $result = $this->get();
        $result['auth'] = $auth;
        $result['clusters'] = $clusters;

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
            $dataRow[] = $tbl['header'];
        }

        $data[] = $dataRow;

        foreach ($result['data'] as $val) {
            $data[] = [
                $val['name'],
            ];
        }

        return $this->processCSV($data, 'cluster.csv');
    }

    public function getClusterForm($id): Response
    {
        $data = $this->managerRegistry->getRepository(Cluster::class)->get($id);

        return $this->render(
            'administrator/directory/cluster/form/update.html.twig',
            [
                'data' => $data,
            ]
        );
    }

    private function get(bool $full = false)
    {
        $page = $this->request->get('page') ?? null;
        $on_page = $this->request->get('on_page') ?? 25;
        $sort = $this->request->get('sort') ?? null;
        $search = $this->request->get('search') ?? null;

        if (false === $full) {
            $result = $this->managerRegistry->getRepository(ProductLine::class)->getList($page, $on_page, $sort, $search);
            $count = $this->managerRegistry->getRepository(ProductLine::class)->getListAll($page, $on_page, $sort, $search);
        } else {
            $result = $this->managerRegistry->getRepository(ProductLine::class)->getList(0, 9999999999, $sort, $search);
            $count = $this->managerRegistry->getRepository(ProductLine::class)->getListAll(0, 9999999999, $sort, $search);
        }

        $page = $page ?? 1;

        return [
            'data' => $result,
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
                'current_sort' => $this->request->get('sort') ?? null,
            ],
            'search_link' => $this->getSearchLink(),
            'table' => $this->setTable(),
            'csv_link' => $this->getCSVLink(),
        ];
    }

    private function setTable()
    {
        return [
            [
                'name' => 'name',
                'header' => 'Название',
                'type' => 'string',
                'filter' => true,
                'show' => true,
                'sort' => true,
            ],
            [
                'name' => 'cluster.name',
                'header' => 'Кластер',
                'type' => 'string',
                'filter' => true,
                'show' => true,
                'sort' => true,
            ],
        ];
    }
}
