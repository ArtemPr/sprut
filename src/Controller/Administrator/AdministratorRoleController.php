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

        $role_array = [
            [
                'name' => 'Рабочий стол',
                'id' => 'desktop_view',
            ],
            [
                'name' => 'Курсы',
                'id' => 'program_view',
                'action' => [
                    [

                        'name' => 'Создание',
                        'id' => 'program_add',
                    ],
                    [

                        'name' => 'Редактирование',
                        'id' => 'program_edit',
                    ],
                    [

                        'name' => 'Удаление',
                        'id' => 'program_delete',
                    ],
                ],
            ],
            [
                'name' => 'Панель управления',
                'id' => 'admin',
                'item' => [
                    [
                        'name' => 'Пользователи',
                        'id' => 'user_view',
                        'item' => [
                            [
                                'name' => 'Пользователи',
                                'id' => 'user_list_view',
                                'action' => [
                                    [

                                        'name' => 'Создание',
                                        'id' => 'user_list_add',
                                    ],
                                    [

                                        'name' => 'Редактирование',
                                        'id' => 'user_list_edit',
                                    ],
                                    [

                                        'name' => 'Удаление',
                                        'id' => 'user_list_delete',
                                    ],
                                ]
                            ]
                        ],
                    ],
                    [
                        'name' => 'Справочники',
                        'id' => 'directory_view'
                    ],
                    [
                        'name' => 'Журнал событий',
                        'id' => 'log_view'
                    ],
                ],
            ],
        ];



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
                'table' => $table
            ]
        );
    }
}
