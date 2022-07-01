<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller\Administrator;

use App\Entity\Operations;
use App\Entity\Roles;
use App\Service\LinkService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdministratorRoleController extends AbstractController
{
    use LinkService;
    public function __construct(private ManagerRegistry $managerRegistry)
    {
    }

    public function getRoleList(): Response
    {$request = new Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);
        $page = $request->get('page') ?? null;
        $on_page = $request->get('on_page') ?? 25;
        $sort = $request->get('sort') ?? null;

        $result = $this->managerRegistry->getRepository(Roles::class)->getList($page, $on_page, $sort);
        $count = $this->managerRegistry->getRepository(Operations::class)->findAll();
        $count = count($count);


        $table = [
            ['name', 'Название', 'string', true],
            ['comment', 'Комментарий', 'string', true],
            ['roles_alt', 'Идентификатор', 'string', true]
        ];

        $page = $page ?? 1;

        $tpl = $request->get('ajax') ? 'administrator/role/role_table.html.twig' : 'administrator/role/list.html.twig' ;

        return $this->render(
            $tpl,
            [
                'role_list' => $result,
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
                'operations' => $this->getOperations()
            ]
        );
    }

    public function getRoleForm(int $id)
    {
        $data = $this->managerRegistry->getRepository(Roles::class)->get($id);

        $auth_list = $data['auth_list'];
        if(!empty($auth_list)) {
            $data['auth_list'] = (unserialize($auth_list));
        }

        return $this->render(
            'administrator/role/form/update_form.html.twig',
            [
                'data' => $data,
                'operations' => $this->getOperations()
            ]
        );
    }

    private function getOperations(): array
    {
        $out = [];
        $operations = $this->managerRegistry->getRepository(Operations::class)->getList();
        foreach ($operations as $op) {
            $out[$op['code']] = $op['name'];
        }
        return $out;
    }
}
