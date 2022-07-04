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
use App\Service\LinkService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ProgramController extends AbstractController
{
    use LinkService;
    use AuthService;
    public function __construct(
        private ManagerRegistry $managerRegistry
    )
    {
    }

    #[Route('/program', name: 'program')]
    public function program(): Response
    {
        $auth = $this->getAuthValue($this->getUser(), 'auth_program', $this->managerRegistry);
        if (!is_array($auth)) {
            return $auth;
        }

        $request = new Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);
        $page = $request->get('page') ?? null;
        $on_page = $request->get('on_page') ?? 25;
        $sort = $request->get('sort') ?? null;
        $select_type = $request->get('type') ?? null;

        $program_type = $this->managerRegistry->getRepository(ProgramType::class)->findAll();
        $program_list = $this->managerRegistry->getRepository(MasterProgram::class)->getList((int)$page, (int)$on_page, $sort, $select_type);
        $count = $this->managerRegistry->getRepository(MasterProgram::class)->getApiProgramInfo();
        $count = $count['count_program'] ?? 0;

        $tc = $this->managerRegistry->getRepository(TrainingCenters::class)->findAll();

        $category = $this->managerRegistry->getRepository(Category::class)->getList();

        $fgos = $this->managerRegistry->getRepository(FederalStandart::class)->findAll();

        $type = $this->managerRegistry->getRepository(ProgramType::class)->findAll();

        $tpl = $request->get('ajax') ? '/program/program_table.html.twig' : '/program/index.html.twig' ;

        $table = [
            ['', '', 'bool', true],
            ['id', 'ID', 'string', true],
            ['history', 'Ист. данные', 'string', true],
            ['pt.id', 'Тип', 'string', true],
            ['name', 'Название', 'string', true],
            ['fs.id', 'ФГОС', 'string', true],
            ['ps.id', 'ПС', 'string', true],
        ];

        return $this->render(
            $tpl,
            [
                'data' => $program_list,
                'program_type' => $program_type,
                'training_centre' => $tc,
                'category' => $category,
                'fgos' => $fgos,
                'type' => $type,
                'select_type' => $select_type,
                'pager' => [
                    'count_all_position' => $count,
                    'current_page' => $page,
                    'count_page' => (int)ceil($count / $on_page),
                    'paginator_link' => $this->getParinatorLink(),
                    'on_page' => $on_page
                ],
                'table' => $table,
                'sort' => [
                    'sort_link' => $this->getSortLink(),
                    'current_sort' => $request->get('sort') ?? null,
                ],
                'auth' => $auth
            ]
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
                'fgos' => $fgos
            ]
        );
    }
}
