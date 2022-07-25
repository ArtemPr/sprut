<?php

namespace App\Controller\Administrator;

use App\Entity\ProgramType;
use App\Service\AuthService;
use App\Service\CSVHelper;
use App\Service\LinkService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminDirectoryProgramType extends AbstractController
{
    use LinkService;
    use AuthService;
    use CSVHelper;

    private $request;

    public function __construct(private ManagerRegistry $managerRegistry)
    {
        $this->request = new Request($_GET);
    }

    public function getList(): Response
    {
        $auth = $this->getAuthValue($this->getUser(), 'auth_directory', $this->managerRegistry);
        if (!is_array($auth)) {
            return $auth;
        }
        $tpl = $this->request->get('ajax') ? 'administrator/directory/program_type/program_type_table.html.twig' : 'administrator/directory/program_type/program_type.html.twig';
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
                ($val['name']),
            ];
        }

        return $this->processCSV($data, 'program_type.csv');
    }

    private function get(bool $full = false)
    {
        $page = $this->request->get('page') ?? null;
        $on_page = $this->request->get('on_page') ?? 25;
        $sort = $this->request->get('sort') ?? null;
        $search = $this->request->get('search') ?? null;
        if (false === $full) {
            $result = $this->managerRegistry->getRepository(ProgramType::class)->getList($page, $on_page, $sort, $search);
            $count = $this->managerRegistry->getRepository(ProgramType::class)->getListAll($page, $on_page,
                $sort, $search);
        } else {
            $result = $this->managerRegistry->getRepository(ProgramType::class)->getList(0, 9999999999, $sort,
                $search);
            $count = $this->managerRegistry->getRepository(ProgramType::class)->getListAll(0, 9999999999,
                $sort, $search);
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

    public function getProgramTypeForm($id): Response
    {
        $data_out = $this->managerRegistry->getRepository(ProgramType::class)->get($id);

        return $this->render(
            'administrator/directory/program_type/form/update.html.twig',
            [
                'data' => $data_out[0] ?? null,
            ]
        );
    }

    private function setTable(): array
    {
        return [
            [
                'name' => 'id',
                'header' => 'ID',
                'type' => 'string',
                'filter' => true,
                'show' => true,
                'sort' => true,
            ],
            [
                'name' => 'name_type',
                'header' => 'Название',
                'type' => 'string',
                'filter' => true,
                'show' => true,
                'sort' => true,
            ],
            [
                'name' => 'short_name_type',
                'header' => 'Короткое название',
                'type' => 'string',
                'filter' => true,
                'show' => true,
                'sort' => true,
            ],
        ];
    }
}
