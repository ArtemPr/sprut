<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller;

use App\Entity\Discipline;
use App\Service\AuthService;
use App\Service\LinkService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DisciplineController extends AbstractController
{
    use LinkService;

    use AuthService;

    public function __construct(
        private ManagerRegistry $managerRegistry
    )
    {
    }

    #[Route('/discipline', name: 'discipline')]
    public function getList(): Response
    {
        $auth = $this->getAuthValue($this->getUser(), 'auth_desktop', $this->managerRegistry);
        if (!is_array($auth)) {
            return $auth;
        }

        $request = new Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);
        $page = $request->get('page') ?? null;
        $on_page = $request->get('on_page') ?? 25;
        $sort = $request->get('sort') ?? null;

        $result = $this->managerRegistry->getRepository(\App\Entity\Discipline::class)->getList($page, $on_page, $sort);
        $count = $this->managerRegistry->getRepository(Discipline::class)->findAll();
        $count = count($count);

        $table = [
            ['', '', '', true],
            ['name', 'Тип', 'string', true],
            ['practicum_flag', 'Практикум', 'int', true],
            ['type', 'Название', 'string', true],
            ['comment', 'Комментарий', 'string', true],
            ['comment', 'Цель освоения', 'string', true]
        ];

        $tpl = $request->get('ajax') ? 'discipline/discipline_table.html.twig' : 'discipline/discipline.html.twig';

        return $this->render($tpl,
            [
                'data' => $result,
                'pager' => [
                    'count_all_position' => $count,
                    'current_page' => $page,
                    'count_page' => (int)ceil($count / $on_page),
                    'paginator_link' => $this->getParinatorLink(),
                    'on_page' => $on_page
                ],
                'sort' => [
                    'sort_link' => $this->getSortLink(),
                    'current_sort' => $request->get('sort') ?? null,
                ],
                'table' => $table,
                'auth' => $auth
            ]
        );
    }

    public function getDisciplineForm($id)
    {
        return $this->render('discipline/form/discipline_update.html.twig');
    }
}
