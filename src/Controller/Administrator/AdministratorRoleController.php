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

    private $request;

    public function __construct(
        private ManagerRegistry $managerRegistry
    )
    {
        $this->request = new Request($_GET);
    }

    private function setTable()
    {
        return [
            ['roles_alt', 'Идентификатор', 'string', true],
            ['name', 'Название', 'string', true],
            ['comment', 'Комментарий', 'string', true],
        ];
    }

    private function get(bool $full = false)
    {
        $page = $this->request->get('page') ?? null;
        $on_page = $this->request->get('on_page') ?? 25;
        $sort = $this->request->get('sort') ?? null;
        $search = $this->request->get('search') ?? null;

        if ($full === false) {
            $result = $this->managerRegistry->getRepository(Roles::class)->getList($page, $on_page, $sort, $search);
            $count = $this->managerRegistry->getRepository(Roles::class)->getListAll($page, $on_page, $sort, $search);
        } else {
            $result = $this->managerRegistry->getRepository(Roles::class)->getList(0, 9999999999, $sort, $search);
            $count = $this->managerRegistry->getRepository(Roles::class)->getListAll(0, 9999999999, $sort, $search);
        }

        $page = $page ?? 1;

        return [
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
                'current_sort' => $this->request->get('sort') ?? null,
            ],
            'table' => $this->setTable(),
            'operations' => $this->getOperations(),
            'csv_link' => $this->getCSVLink()
        ];
    }

    public function getRoleList(): Response
    {
        $auth = $this->getAuthValue($this->getUser(), 'auth_role', $this->managerRegistry);
        if (!is_array($auth)) {
            return $auth;
        }

        $tpl = $this->request->get('ajax') ? 'administrator/role/role_table.html.twig' : 'administrator/role/list.html.twig';
        $result = $this->get();
        $result['auth'] = $auth;

        return $this->render(
            $tpl,
            $result,
        );
    }

    public function getRoleListCSV()
    {
        $result = $this->get(true);
        $table = '';

        foreach ($this->setTable() as $tbl) {
            $table .= '"' . $tbl[1] . '";';
        }
        $table = substr($table, 0, -1) . "\n";

        $data = $result['role_list'];

        foreach ($data as $val) {
            $table .= '"' . $val['id'] . '";' .
                '"' . $val['name'] . '";' .
                '""' . "\n";
        }

        $table = mb_convert_encoding($table, 'utf8');
        $table = htmlspecialchars_decode($table);

        $response = new Response($table);
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="roles.csv"');

        return $response;
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
