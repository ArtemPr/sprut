<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller\Administrator;

use App\Entity\TrainingCenters;
use App\Service\AuthService;
use App\Service\CSVHelper;
use App\Service\LinkService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminDirectoryTrainingCentre extends AbstractController
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
                'name' => 'id',
                'header' => 'ID',
                'type' => 'string',
                'show' => true,
                'filter' => false,
                'sort' => true,
            ],
            [
                'name' => 'name',
                'header' => 'Название',
                'type' => 'string',
                'show' => true,
                'filter' => false,
                'sort' => true,
            ],
            [
                'name' => 'phone',
                'header' => 'Телефоны',
                'type' => 'string',
                'show' => true,
                'filter' => false,
                'sort' => true,
            ],
            [
                'name' => 'email',
                'header' => 'E-mail',
                'type' => 'string',
                'show' => true,
                'filter' => false,
                'sort' => true,
            ],
            [
                'name' => 'url',
                'header' => 'URL',
                'type' => 'string',
                'show' => true,
                'filter' => false,
                'sort' => true,
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
            $result = $this->managerRegistry->getRepository(TrainingCenters::class)->getList($page, $on_page, $sort, $search);
            $count = $this->managerRegistry->getRepository(TrainingCenters::class)->getListAll($page, $on_page, $sort, $search);
        } else {
            $result = $this->managerRegistry->getRepository(TrainingCenters::class)->getList(0, 9999999999, $sort, $search);
            $count = $this->managerRegistry->getRepository(TrainingCenters::class)->getListAll(0, 9999999999, $sort, $search);
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

        $tpl = $this->request->get('ajax') ? 'administrator/directory/training_centre/training_centre_table.html.twig' : 'administrator/directory/training_centre/training_centre.html.twig' ;
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
            $data[] = [
                $val['id'],
                $val['name'],
                $val['phone'],
                $val['email'],
                $val['url'],
            ];
        }

        return $this->processCSV($data, 'tc.csv');
    }

    public function getTrainingCentreForm($id)
    {
        $data_out = $this->managerRegistry->getRepository(TrainingCenters::class)->get($id);

        return $this->render(
            'administrator/directory/training_centre/form/training_centre_update.html.twig',
            [
                'data' => $data_out[0] ?? null,
                'controller' => 'AdminTrainingCentre',
            ]
        );
    }
}
