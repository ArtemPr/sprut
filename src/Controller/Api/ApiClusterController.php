<?php

namespace App\Controller\Api;

use App\Entity\Cluster;
use App\Entity\Loger;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Service\LoggerService;

class ApiClusterController extends AbstractController
{
    use LoggerService;

    public function __construct(
        private readonly ManagerRegistry $doctrine
    ) {
    }

    public function update()
    {
        $request = new Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);
        $data = $request->request->all();

        $cluster = $this->doctrine->getRepository(Cluster::class)->find((int) $data['id']);
        $cluster->setName(trim($data['name']));

        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($cluster);
        $entityManager->flush();

        $this->logAction('update_cluster', 'Кластеры', 'Редактирование кластера'.$data['id'].' '.$data['name']);

        return $this->json(['result' => 'success', 'id' => $data['id']]);
    }

    public function add(): Response
    {
        $request = new Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);
        $data = $request->request->all();

        $cluster = new Cluster();
        $cluster->setName($data['name']);

        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($cluster);
        $entityManager->flush();
        $lastId = $cluster->getId();

        $this->logAction('add_cluster', 'Кластеры', 'Создание кластера '.$lastId.' '.$data['name']);

        return $this->json(['result' => 'success', 'id' => $lastId]);
    }
}
