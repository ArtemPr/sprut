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
            ['id', 'ID', 'string', true],
            ['name', 'Название', 'string', true],
            ['phone', 'Телефоны', 'string', true],
            ['email', 'E-mail', 'string', true],
            ['url', 'URL', 'string', true]
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
            $count = $this->managerRegistry->getRepository(TrainingCenters::class)->findAll();
        } else {
            $result = $this->managerRegistry->getRepository(TrainingCenters::class)->getList(0, 9999999999, $sort, $search);
            $count = $this->managerRegistry->getRepository(TrainingCenters::class)->findAll();
        }

        $count = count($count);

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

        $tpl = $this->request->get('ajax') ? 'administrator/directory/training_centre_table.html.twig' : 'administrator/directory/training_centre.html.twig' ;
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

        foreach ($data as $val) {
            $table .= '"' . $val['id'] . '";"' . $val['name'] . '";"' . $val['phone'] . '";"' . $val['email'] . '";"' . $val['url'] . '"' . "\n";
        }

        return $this->getCSVFile($table, 'tc.csv');
    }

    public function getTrainingCentreForm($id)
    {
        $data_out = $this->managerRegistry->getRepository(TrainingCenters::class)->get($id);

        return $this->render(
            'administrator/directory/form/training_centre_update.html.twig',
            [
                'data' => $data_out[0] ?? null,
                'controller' => 'AdminTrainingCentre',
            ]
        );
    }
}
