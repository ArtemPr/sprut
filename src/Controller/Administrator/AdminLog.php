<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller\Administrator;

use App\Entity\Loger;
use App\Service\AuthService;
use App\Service\LinkService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminLog extends AbstractController
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
        $auth = $this->getAuthValue($this->getUser(), 'auth_log', $this->managerRegistry);
        if(!empty($auth)) {
            return $auth;
        }

        $request = new Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);
        $page = $request->get('page') ?? null;
        $on_page = $request->get('on_page') ?? 25;
        $sort = $request->get('sort') ?? null;
        $search = $request->get('search') ?? null;

        $data = $this->managerRegistry->getRepository(Loger::class)->getList($page, $on_page, $sort, $search);

        foreach ($data as $key=>$value) {
            $data[$key]['time'] =$data[$key]['time']->format(
                'H:i d.m.Y'
            );
        }

        $data_action = $this->managerRegistry->getRepository(Loger::class)->getActionList();

        $count = $this->managerRegistry->getRepository(Loger::class)->findAll();
        $count = count($count);

        $table = [
            ['chapter', 'Раздел', 'string', true],
            ['action', 'Тип события', 'string', true],
            ['', 'Название события', 'string', true],
            ['us.username', 'Инициатор события (ФИО)', 'string', true],
            ['us.email', 'Электронная почта', 'string', true],
            ['ip', 'IP-адрес', 'string', true],
            ['time', 'Дата', 'string', true],
            ['comment', 'Комментарий', 'string', true]
        ];

        $da_out = [];

        foreach ($data_action as $key=>$value) {
            $da_out[$value['name']] = $value['value'];
        }

        $page = $page ?? 1;
        $tpl = $request->get('ajax') ? 'administrator/log/log_table.html.twig' : 'administrator/log/log.html.twig' ;

        return $this->render($tpl,
        [
            'data' => $data,
            'data_action' => $da_out,
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
