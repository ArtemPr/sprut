<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller\Api;

use App\Entity\FederalStandart;
use App\Entity\FederalStandartCompetencies;
use App\Entity\Loger;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiFgosController extends AbstractController
{
    public function __construct(
        private ManagerRegistry $managerRegistry
    ) {
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
            $data_create = mktime(1, 0, 0, $data_create[1], $data_create[2], $data_create[0]);
            $fs->setDateCreate(new \DateTime(date('r', $data_create)));
        }
        $entityManager = $this->managerRegistry->getManager();
        $entityManager->persist($fs);
        $entityManager->flush();

        $fs = $this->managerRegistry->getRepository(FederalStandart::class)->find((int) $data['id']);

        if (!empty($data['comp_delete'])) {
            foreach ($data['comp_delete'] as $v) {
                $qd = $this->managerRegistry->getRepository(FederalStandartCompetencies::class)->find($v);
                $qd->setDelete(true);
                $entityManager = $this->managerRegistry->getManager();
                $entityManager->persist($fs);
                $entityManager->flush();
            }
        }

        if (!empty($data['comp_name'])) {
            foreach ($data['comp_name'] as $k => $v) {
                $comp_name = $data['comp_name'][$k];
                $comp_code = $data['comp_code'][$k];
                $qb = $this->managerRegistry->getRepository(FederalStandartCompetencies::class)->find($k);
                $qb->setName($comp_name)->setCode($comp_code);
                $entityManager->persist($qb);
                $entityManager->flush();
                unset($qb);
            }
        }

        if (!empty($data['comp_name_new'])) {
            foreach ($data['comp_name_new'] as $k => $v) {
                $comp_name = $data['comp_name_new'][$k];
                $comp_code = $data['comp_code_new'][$k];
                $fs_comp = new FederalStandartCompetencies();
                $fs_comp->setName($comp_name);
                $fs_comp->setCode($comp_code);
                $fs_comp->setFederalStandart($fs);
                $entityManager->persist($fs_comp);
                $entityManager->flush();
                unset($qb);
            }
        }

        $loger = new Loger();
        $loger->setTime(new \DateTime());
        $loger->setAction('update_fgos');
        $loger->setUserLoger($this->getUser());
        $loger->setIp($request->server->get('REMOTE_ADDR'));
        $loger->setChapter('ФГОС');
        $loger->setComment($data['id'].' '.$data['name']);
        $entityManager = $this->managerRegistry->getManager();
        $entityManager->persist($loger);
        $entityManager->flush();

        return $this->json(['result' => 'success', 'id' => $data['id']]);
    }

    public function add(): Response
    {
        $request = new Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);
        $data = $request->request->all();

        $data_create_arr = explode('-' , $data['data_create']);
        $data_create = mktime(0, 0, 1, $data_create_arr['2'], $data_create_arr['1'], $data_create_arr['0']);

        $federal_s = new FederalStandart();
        $federal_s->setName(trim($data['name']));
        $federal_s->setShortName($data['short_name']);
        $federal_s->setActive(!empty($data['active']) ? true : false);
        $federal_s->setType(trim($data['type']));
        $federal_s->setDateCreate(new \DateTime(date('r', $data_create)));
        $federal_s->setCode(trim($data['code']));
        $federal_s->setPrNum($data['pr_num']);
        $federal_s->setOldName(null);

        $entityManager = $this->managerRegistry->getManager();
        $entityManager->persist($federal_s);
        $entityManager->flush();
        $lastId = $federal_s->getId();

        if (!empty($data['comp_name_new'])) {

            $fs = $this->managerRegistry->getRepository(FederalStandart::class)->find((int) $lastId);
            foreach ($data['comp_name_new'] as $k => $v) {
                $comp_name = $data['comp_name_new'][$k];
                $comp_code = $data['comp_code_new'][$k];
                $fs_comp = new FederalStandartCompetencies();
                $fs_comp->setName($comp_name);
                $fs_comp->setCode($comp_code);
                $fs_comp->setFederalStandart($fs);
                $entityManager->persist($fs_comp);
                $entityManager->flush();
                unset($qb);
            }
        }

        $loger = new Loger();
        $loger->setTime(new \DateTime());
        $loger->setAction('add_fs');
        $loger->setUserLoger($this->getUser());
        $loger->setIp($request->server->get('REMOTE_ADDR'));
        $loger->setChapter('Федеральные страндарты');
        $loger->setComment('Создание федерального страндарта '.$lastId.' '.$data['name']);
        $entityManager = $this->managerRegistry->getManager();
        $entityManager->persist($loger);
        $entityManager->flush();

        return $this->json(['result' => 'success', 'id' => $lastId]);
    }
}
