<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller\Administrator;

use App\Entity\City;
use App\Entity\Roles;
use App\Entity\User;
use App\Service\AuthService;
use App\Service\LinkService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdministratorUserController extends AbstractController
{
    use LinkService;
    use AuthService;

    private $request;
    private $roles;

    public function __construct(
        private ManagerRegistry $managerRegistry
    )
    {
        $this->request = new Request($_GET);
    }

    private function processRoles(?array $rawRoles)
    {
        if (!empty($rawRoles)) {
            foreach ($rawRoles as $rawRoleItem) {
                $this->roles[$rawRoleItem['roles_alt']] = $rawRoleItem;
            }
        }
    }

    private function get(bool $full = false)
    {
        $page = $this->request->get('page') ?? null;
        $on_page = $this->request->get('on_page') ?? 25;
        $sort = $this->request->get('sort') ?? null;
        $search = $this->request->get('search') ?? null;

        if ($full === false) {
            $user_list = $this->managerRegistry->getRepository(User::class)->getList($page, $on_page, $sort, $search);
        } else {
            $user_list = $this->managerRegistry->getRepository(User::class)->getList(0, 9999999999, $sort, $search);
        }

        $city = $this->managerRegistry->getRepository(City::class)->getList();
        $roles = $this->managerRegistry->getRepository(Roles::class)->getList();
        $this->processRoles($roles);

        $count = $this->managerRegistry->getRepository(User::class)->getCount();

        $page = $page ?? 1;

        return [
            'controller' => 'AdminUser',
            'user_list' => $user_list,
            'city_list' => $city,
            'roles' => $roles,
            'table' => $this->setTable(),
            'search' => strip_tags($search) ?? '',
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
            'csv_link' => $this->getCSVLink()
        ];
    }

    public function getUserList(): Response
    {
        $auth = $this->getAuthValue($this->getUser(), 'auth_user', $this->managerRegistry);
        if (!is_array($auth)) {
            return $auth;
        }

        $tpl = $this->request->get('ajax') ? 'administrator/user/user_table.html.twig' : 'administrator/user/list.html.twig' ;
        $result = $this->get();
        $result['auth'] = $auth;

        return $this->render($tpl,
            $result
        );
    }

    public function getUserListCSV()
    {
        $result = $this->get(true);
        $table = '';

        foreach ($this->setTable() as $tbl) {
            $table .= '"' . $tbl[1] . '";';
        }
        $table = substr($table, 0, -1) . "\n";

        $data = $result['user_list'];

        foreach ($data as $val) {
            $currRoles = [];

            if (!empty($val['roles'])) {
                foreach ($val['roles'] as $currRoleItem) {
                    $currRoles[] = $this->roles[$currRoleItem]['name'];
                }
            }

            $table .= '"' . ( $val['activity'] ? 'да' : 'нет') . '";' .
                        '"' . ( $val['delete'] ? 'удалён' : 'активен') . '";' .
                        '"' . (!empty($val['fullname']) ? $val['fullname'] : $val['username'] . ' ' . $val['surname'] . ' ' . $val['patronymic']) . '";' .
                        '"' . implode(', ', $currRoles) . '";' .
                        '"' . $val['position'] . '";' .
                        '"' . $val['departament'] . '";' .
                        '"' . $val['email'] . '";' .
                        '"' . $val['phone'] . '";' .
                        "\n";
        }

        $table = mb_convert_encoding($table, 'utf8');
        $table = htmlspecialchars_decode($table);

        $response = new Response($table);
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="userlist.csv"');

        return $response;
    }

    public function getUserForm($id)
    {
        $data = $this->managerRegistry->getRepository(User::class)->getUser($id);

        $city = $this->managerRegistry->getRepository(City::class)->getList();

        $roles = $this->managerRegistry->getRepository(Roles::class)->getList();

        return $this->render(
            'administrator/user/form/update_form.html.twig',
            [
                'data' => $data[0] ?? [],
                'city_list' => $city,
                'roles' => $roles
            ]
        );
    }

    private function setTable()
    {
        return [
            ['', '', 'bool', true],
            ['', '', 'bool', true],
            ['username', 'ФИО', 'bool', true],
            ['', 'Роль', 'string', true],
            ['position', 'Должность', 'string', true],
            ['departament', 'Подразделение', 'string', true],
            ['email', 'Email', 'string', true],
            ['phone', 'Телефон', 'string', true]
        ];
    }
}
