<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller\Administrator;

use App\Entity\City;
use App\Entity\Roles;
use App\Entity\User;
use App\Service\LinkService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdministratorUserController extends AbstractController
{
    use LinkService;

    private $request;

    public function __construct(
        private ManagerRegistry $managerRegistry
    )
    {
        $this->request = new Request($_GET);
    }

    public function getUserList(): Response
    {
        $page = $this->request->get('page') ?? null;
        $on_page = $this->request->get('on_page') ?? 25;
        $sort = $this->request->get('sort') ?? null;
        $search = $this->request->get('search') ?? null;

        $user_list = $this->managerRegistry->getRepository(User::class)->getList($page, $on_page, $sort, $search);
        $city = $this->managerRegistry->getRepository(City::class)->getList();
        $roles = $this->managerRegistry->getRepository(Roles::class)->getList();
        $count = $this->managerRegistry->getRepository(User::class)->getCount();

        $tpl = $this->request->get('ajax') ? 'administrator/user/user_table.html.twig' : 'administrator/user/list.html.twig' ;

        return $this->render($tpl,
            [
                'controller' => 'AdminUser',
                'user_list' => $user_list,
                'city_list' => $city,
                'roles' => $roles,
                'table' => $this->setTable(),
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
            ]
        );
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
