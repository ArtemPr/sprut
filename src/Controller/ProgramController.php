<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller;

use App\Entity\Category;
use App\Entity\FederalStandart;
use App\Entity\MasterProgram;
use App\Entity\ProgramType;
use App\Entity\TrainingCenters;
use App\Service\AuthService;
use App\Service\CSVHelper;
use App\Service\LinkService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProgramController extends BaseController implements BaseInterface
{
    use AuthService;
    use LinkService;
    use CSVHelper;

    public function get(bool $full = false): array
    {
        $page = $this->get_data['page'] ?? null;
        $on_page = $this->get_data['on_page'] ?? 25;
        $sort = $this->get_data['sort'] ?? null;
        $select_type = $this->get_data['type'] ?? null;
        $search = $this->get_data['search'] ?? null;

        if (false === $full) {
            $program_list = $this->managerRegistry->getRepository(MasterProgram::class)->getList((int) $page, (int) $on_page, $sort, $select_type, $search);
            $count = $this->managerRegistry->getRepository(MasterProgram::class)->getApiProgramInfo();
            $count = $count['count_program'] ?? 0;
        } else {
            $program_list = [];
            $count = 0;
        }

        return [
            'data' => $program_list,
            'search' => strip_tags($search) ?? '',
            'program_type' => $this->managerRegistry->getRepository(ProgramType::class)->findAll(),
            'training_centre' => $this->managerRegistry->getRepository(TrainingCenters::class)->findAll(),
            'category' => $this->managerRegistry->getRepository(Category::class)->getList(),
            'fgos' => $this->managerRegistry->getRepository(FederalStandart::class)->findAll(),
            'type' => $this->managerRegistry->getRepository(ProgramType::class)->findAll(),
            'select_type' => $select_type,
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

    #[Route('/program', name: 'program')]
    public function getList(): Response
    {
        $auth = $this->getAuthValue($this->getUser(), 'auth_program', $this->managerRegistry);
        if (!is_array($auth)) {
            return $auth;
        }

        $tpl = !empty($this->request->get('ajax'))
            ? '/program/program_table.html.twig'
            : '/program/index.html.twig';

        $result = $this->get();
        $result['auth'] = $auth;

        return $this->render(
            $tpl,
            $result
        );
    }

    public function getProgramForm($id)
    {
        $data = $this->managerRegistry->getRepository(MasterProgram::class)->get($id);

        $fgos = $this->managerRegistry->getRepository(FederalStandart::class)->findAll();

        $type = $this->managerRegistry->getRepository(ProgramType::class)->findAll();

        return $this->render(
            'program/form/update_form.html.twig',
            [
                'data' => $data,
                'type' => $type,
                'fgos' => $fgos,
            ]
        );
    }

    private function setTable()
    {
        return [
            ['', '', 'bool', true],
            ['id', 'ID', 'string', true],
            ['history', 'Ист. данные', 'string', true],
            ['pt.id', 'Тип', 'string', true],
            ['name', 'Название', 'string', true],
            ['fs.id', 'ФГОС', 'string', true],
            ['ps.id', 'ПС', 'string', true],
        ];
    }
}
