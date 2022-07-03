<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller\Administrator;

use App\Entity\Operations;
use App\Entity\TrainingCenters;
use App\Service\AuthService;
use App\Service\LinkService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdministratorOperationsController extends AbstractController
{
    use LinkService;
    use AuthService;

    public function __construct(
        private ManagerRegistry $managerRegistry
    ) {
    }

    public function getOperationsList(): Response
    {
        $auth = $this->getAuthValue($this->getUser(), 'auth_operations', $this->managerRegistry);
        if(!empty($auth)) {
            return $auth;
        }

        $request = new Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);
        $page = $request->get('page') ?? null;
        $on_page = $request->get('on_page') ?? 25;
        $sort = $request->get('sort') ?? null;

        $result = $this->managerRegistry->getRepository(Operations::class)->getList($page, $on_page, $sort);


        $count = $this->managerRegistry->getRepository(Operations::class)->findAll();
        $count = count($count);


        $table = [
            ['name', 'Название', 'string', true],
            ['comment', 'Комментарий', 'string', true],
            ['group', 'Группа', 'string', true],
            ['code', 'Код операции', 'string', true]
        ];

        $page = $page ?? 1;

        $tpl = $request->get('ajax') ? 'administrator/operations/operation_table.html.twig' : 'administrator/operations/index.html.twig' ;


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

    public function getOperationsForm($id)
    {
        $data_out = $this->managerRegistry->getRepository(Operations::class)->get($id);
        return $this->render(
            'administrator/operations/form/update_form.html.twig',
            [
                'data' => $data_out[0] ?? null,
                'controller' => 'AdminOperations',
            ]
        );
    }
}
