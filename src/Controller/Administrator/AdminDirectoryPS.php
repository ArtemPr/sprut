<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller\Administrator;

use App\Entity\ProfStandarts;
use App\Service\AuthService;
use App\Service\LinkService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminDirectoryPS extends AbstractController
{
    use LinkService;
    use AuthService;

    public function __construct(
        private ManagerRegistry $managerRegistry
    )
    {
    }

    public function getList(): Response
    {
        $auth = $this->getAuthValue($this->getUser(), 'auth_directory', $this->managerRegistry);
        if(!empty($auth)) {
            return $auth;
        }

        $request = new Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);
        $page = $request->get('page') ?? null;
        $on_page = $request->get('on_page') ?? 25;
        $sort = $request->get('sort') ?? null;

        $result = $this->managerRegistry->getRepository(ProfStandarts::class)->getList($page, $on_page, $sort);
        $count = $this->managerRegistry->getRepository(ProfStandarts::class)->findAll();
        $count = count($count);

        $table = [
            ['', '', 'bool', true],
            ['name', 'Название', 'string', true],
            ['short_name', 'Короткое название', 'string', true]
        ];

        $tpl = $request->get('ajax') ? 'administrator/directory/ps_table.html.twig' : 'administrator/directory/ps.html.twig' ;

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


    public function getPSForm($id)
    {
        $data_out = $this->managerRegistry->getRepository(ProfStandarts::class)->get($id);

        return $this->render(
            'administrator/directory/form/ps_update.html.twig',
            [
                'data' => $data_out[0] ?? null,
                'controller' => 'AdminTrainingCentre',
            ]
        );
    }
}
