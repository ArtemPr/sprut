<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller\Api;

use App\Entity\FederalStandart;
use App\Entity\Loger;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ApiFgosController extends AbstractController
{
    public function __construct(
        private ManagerRegistry $managerRegistry
    )
    {

    }

    public function update()
    {
        $request = new Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);
        $data = $request->request->all();

        $fs = $this->managerRegistry->getRepository(FederalStandart::class)->find((int) $data['id']);
        $fs->setName(trim($data['name']));
        $fs->setShortName(trim($data['short_name']));
        $fs->setActive(!empty($data['active']) ? true : false);
        $fs->setCode(trim($data['code']));
        $fs->setType(trim($data['type']));
        $fs->setPrNum(trim($data['pr_num']));
        $fs->setOldName(trim($data['old_name'] ?? null));
        if (!empty($data['data_create'])) {
            $data_create = explode('-', $data['data_create']);
            $data_create = mktime(1,0,0,$data_create[1],$data_create[2],$data_create[0]);
            $fs->setDateCreate(new \DateTime(date('r', $data_create)));
        }

        /**
         * @TODO
         */
        // dd($data['comp_name']);

        foreach ($data as $k=>$v) {

        }


        $entityManager = $this->managerRegistry->getManager();
        $entityManager->persist($fs);
        $entityManager->flush();

        $loger = new Loger();
        $loger->setTime(new \DateTime());
        $loger->setAction('update_fgos');
        $loger->setUserLoger($this->getUser());
        $loger->setIp($request->server->get('REMOTE_ADDR'));
        $loger->setChapter('ФГОС');
        $loger->setComment($data['id'] . ' ' . $data['name']);
        $entityManager = $this->managerRegistry->getManager();
        $entityManager->persist($loger);
        $entityManager->flush();

        return $this->json(['result' => 'success', 'id' => $data['id']]);
    }
}
