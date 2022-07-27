<?php

namespace App\Controller\Api;

use App\Entity\Cluster;
use App\Entity\Loger;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiClusterController extends AbstractController
{
    public function __construct(
        private ManagerRegistry $managerRegistry
    ) {
    }

    public function update()
    {

    }

    public function add(): Response
    {
        $request = new Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);
        $data = $request->request->all();

        $cluster = new Cluster();
        $cluster->setName($data['name']);

        $entityManager = $this->managerRegistry->getManager();
        $entityManager->persist($cluster);
        $entityManager->flush();
        $lastId = $cluster->getId();

        $loger = new Loger();
        $loger->setTime(new \DateTime());
        $loger->setAction('add_cluster');
        $loger->setUserLoger($this->getUser());
        $loger->setIp($request->server->get('REMOTE_ADDR'));
        $loger->setChapter('Кластеры');
        $loger->setComment('Создание кластера '.$lastId.' '.$data['name']);
        $entityManager = $this->managerRegistry->getManager();
        $entityManager->persist($loger);
        $entityManager->flush();

        return $this->json(['result' => 'success', 'id' => $lastId]);
    }
}
