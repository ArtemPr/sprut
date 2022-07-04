<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller\Administrator;

use App\Entity\Operations;
use App\Entity\Roles;
use App\Service\AuthService;
use App\Service\LinkService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdministratorRoleController extends AbstractController
{
    use LinkService;
    use AuthService;

    public function __construct(private ManagerRegistry $managerRegistry)
    {
    }

    public function getRoleList(): Response
    {
        $auth = $this->getAuthValue($this->getUser(), 'auth_role', $this->managerRegistry);
        if (!is_array($auth)) {
            return $auth;
        }

        $request = new Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);
        $page = $request->get('page') ?? null;
        $on_page = $request->get('on_page') ?? 25;
        $sort = $request->get('sort') ?? null;
        $search = $request->get('search') ?? null;

        $result = $this->managerRegistry->getRepository(Roles::class)->getList($page, $on_page, $sort, $search);
        $count = $this->managerRegistry->getRepository(Operations::class)->findAll();
        $count = count($count);


        $table = [
            ['roles_alt', 'Идентификатор', 'string', true],
            ['name', 'Название', 'string', true],
            ['comment', 'Комментарий', 'string', true],
        ];

        $page = $page ?? 1;

        $tpl = $request->get('ajax') ? 'administrator/role/role_table.html.twig' : 'administrator/role/list.html.twig';

        return $this->render(
            $tpl,
            [
                'role_list' => $result,
                'search' => $search,
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
                'operations' => $this->getOperations(),
                'auth' => $auth
            ]
        );
    }

    public function getRoleForm(int $id)
    {
        $data = $this->managerRegistry->getRepository(Roles::class)->get($id);

        $auth_list = $data['auth_list'];
        if (!empty($auth_list)) {
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
