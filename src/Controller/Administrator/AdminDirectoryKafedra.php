<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller\Administrator;

use App\Entity\Kaferda;
use App\Entity\TrainingCenters;
use App\Entity\User;
use App\Service\AuthService;
use App\Service\CSVHelper;
use \App\Service\LinkService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminDirectoryKafedra extends AbstractController
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
                'sort' => true,
                'filter' => true,
            ],
            [
                'name' => 'training_centre.name',
                'header' => 'УЦ',
                'type' => 'string',
                'show' => true,
                'sort' => true,
                'filter' => true,
            ],
            [
                'name' => 'name',
                'header' => 'Название',
                'type' => 'string',
                'show' => true,
                'sort' => true,
                'filter' => true,
            ],
            [
                'name' => 'director.username',
                'header' => 'ФИО руководителя',
                'type' => 'string',
                'show' => true,
                'sort' => true,
                'filter' => true,
            ],
            [
                'name' => 'director.email',
                'header' => 'E-mail руководителя',
                'type' => 'string',
                'show' => true,
                'sort' => true,
                'filter' => true,
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
            $result = $this->managerRegistry->getRepository(Kaferda::class)->getList($page, $on_page, $sort, $search);
            $count = $this->managerRegistry->getRepository(Kaferda::class)->getListAll($page, $on_page, $sort, $search);
        } else {
            $result = $this->managerRegistry->getRepository(Kaferda::class)->getList(0, 9999999999, $sort, $search);
            $count = $this->managerRegistry->getRepository(Kaferda::class)->getListAll(0, 9999999999, $sort, $search);
        }

        $training_centre = $this->managerRegistry->getRepository(TrainingCenters::class)->getList();
        $user = $this->managerRegistry->getRepository(User::class)->getList();

        // Сводим ФИО в одно поле
        foreach ($result as $key => $val) {
            $director_name = ($result[$key]['director']['username'] ?? '') . ' ' . ($result[$key]['director']['surname'] ?? '') . ' ' . ($result[$key]['director']['patronymic'] ?? '');
            $result[$key]['director']['username'] = $director_name;
            unset($result[$key]['director']['surname'], $result[$key]['director']['patronymic']);
        }

        // Фикс страниц
        $page = $page ?? 1;

        return [
            'data' => $result,
            'traning_centre' => $training_centre,
            'user'  => $user,
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

        $tpl = $this->request->get('ajax') ? 'administrator/directory/kafedra/kafedra_table.html.twig' : 'administrator/directory/kafedra/kafedra.html.twig' ;
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
            $dataRow[] = $tbl['header'];
        }

        $data[] = $dataRow;

        foreach ($result['data'] as $val) {
            $data[] = [
                $val['id'],
                $val['training_centre']['name'],
                $val['name'],
                $val['director']['username'],
            ];
        }

        return $this->processCSV($data, 'kafedra.csv');
    }

    public function getKafedraForm($id)
    {
        $training_centre = $this->managerRegistry->getRepository(TrainingCenters::class)->getList();
        $user = $this->managerRegistry->getRepository(User::class)->getList();

        $data_out = $this->managerRegistry->getRepository(Kaferda::class)->get($id);

        return $this->render(
            'administrator/directory/kafedra/form/kafedra_update.html.twig',
            [
                'data' => $data_out[0] ?? null,
                'controller' => 'AdminKafedra',
                'traning_centre' => $training_centre,
                'user'  => $user,
            ]
        );
    }
}
