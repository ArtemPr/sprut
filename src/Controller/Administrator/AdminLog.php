<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller\Administrator;

use App\Entity\Loger;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminLog extends AbstractController
{
    public function __construct(
        private ManagerRegistry $managerRegistry
    )
    {
    }

    public function getList(): Response
    {
        $request = new Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);
        $page = $request->get('page') ?? null;
        $on_page =$request->get('on_page');

        $data = $this->managerRegistry->getRepository(Loger::class)->getList($page, $on_page);

        foreach ($data as $key=>$value) {
            $data[$key]['time'] =$data[$key]['time']->format(
                'H:i d.m.Y'
            );
        }

        $data_action = $this->managerRegistry->getRepository(Loger::class)->getActionList();

        $count = $this->managerRegistry->getRepository(Loger::class)->findAll();
        $count = count($count);


        $da_out = [];

        foreach ($data_action as $key=>$value) {
            $da_out[$value['name']] = $value['value'];
        }

        return $this->render('administrator/log/log.html.twig',
        [
            'data' => $data,
            'data_action' => $da_out,
            'pager' => [
                'count_all_position' => $count,
                'current_page' => $page+1,
                'count_page' => (int)ceil($count / $page)
            ]
        ]
        );
    }
}
