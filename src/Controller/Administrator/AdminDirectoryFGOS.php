<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller\Administrator;

use App\Entity\FederalStandart;
use App\Entity\ProfStandarts;
use App\Service\LinkService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminDirectoryFGOS extends AbstractController
{
    use LinkService;

    public function __construct(
        private ManagerRegistry $managerRegistry
    ) {
    }

    public function getList(): Response
    {
        $request = new Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);
        $page = $request->get('page') ?? null;
        $on_page = $request->get('on_page') ?? 25;
        $sort = $request->get('sort') ?? null;
        $search = $request->get('search') ?? null;

        $result = $this->managerRegistry->getRepository(FederalStandart::class)->getList($page, $on_page, $sort, $search);
        $count = $this->managerRegistry->getRepository(FederalStandart::class)->findAll();
        $count = count($count);

        $table = [
            ['', '', 'bool', true],
            ['short_name', 'Код', 'string', true],
            ['name', 'Название', 'string', true]
        ];

        $tpl = $request->get('ajax') ? 'administrator/directory/fgos_table.html.twig' : 'administrator/directory/fgos.html.twig' ;

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
}
