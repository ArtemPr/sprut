<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller\Administrator;

use App\Entity\Loger;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
        $page = false;
        $on_page = false;

        $data = $this->managerRegistry->getRepository(Loger::class)->getList($page ?? 1, $on_page ?? 25);

        foreach ($data as $key=>$value) {
            $data[$key]['time'] =$data[$key]['time']->format(
                'H:i d.m.Y'
            );
        }

        $data_action = $this->managerRegistry->getRepository(Loger::class)->getActionList();

        $da_out = [];

        foreach ($data_action as $key=>$value) {
            $da_out[$value['name']] = $value['value'];
        }

        return $this->render('administrator/log/log.html.twig',
        [
            'data' => $data,
            'data_action' => $da_out,
        ]
        );
    }
}
