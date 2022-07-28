<?php

namespace App\Controller\Api;

use App\Entity\Cluster;
use App\Entity\ProductLine;
use App\Service\LoggerService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiProductLineController extends AbstractController
{
    use LoggerService;

    public function __construct(
        private ManagerRegistry $managerRegistry
    ) {
    }

    public function update()
    {
//        $request = new Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);
//        $data = $request->request->all();
//
//        $cluster = $this->managerRegistry->getRepository(Cluster::class)->find((int) $data['id']);
//        $cluster->setName(trim($data['name']));
//
//        $entityManager = $this->managerRegistry->getManager();
//        $entityManager->persist($cluster);
//        $entityManager->flush();
//
//        $this->logAction('update_cluster', 'Кластеры', 'Редактирование кластера'.$data['id'].' '.$data['name']);
//
//        return $this->json(['result' => 'success', 'id' => $data['id']]);
    }

    public function add(): Response
    {
        $request = new Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);
        $data = $request->request->all();

        $cluster = $this->managerRegistry->getRepository(Cluster::class)->find($data['cluster']);

        $product_line = new ProductLine();
        $product_line->setName(trim($data['name']));
        $product_line->setCluster($cluster);

        $entityManager = $this->managerRegistry->getManager();
        $entityManager->persist($product_line);
        $entityManager->flush();
        $lastId = $product_line->getId();

//        $this->logAction('add_cluster', 'Кластеры', 'Создание кластера '.$lastId.' '.$data['name']);

        return $this->json(['result' => 'success', 'id' => $lastId]);
    }
}
