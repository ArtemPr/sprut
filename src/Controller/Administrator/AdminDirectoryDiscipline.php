<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller\Administrator;

use App\Entity\Discipline;
use App\Service\LinkService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminDirectoryDiscipline extends AbstractController
{
    use LinkService;

    public function __construct(
        private ManagerRegistry $managerRegistry
    )
    {
    }

    public function getList(): Response
    {
        $request = new Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);
        $page = $request->get('page') ?? null;
        $on_page = $request->get('on_page') ?? 25;
        $sort = $request->get('sort') ?? null;

        $result = $this->managerRegistry->getRepository(Discipline::class)->getList($page, $on_page, $sort);
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

        $tpl = $request->get('ajax') ? 'administrator/directory/discipline_table.html.twig' : 'administrator/directory/discipline.html.twig';

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
                'table' => $table
            ]
        );
    }

    public function getDisciplineForm($id)
    {
        return $this->render('administrator/directory/form/discipline_update.html.twig');
    }
}
