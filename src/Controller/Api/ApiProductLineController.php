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
        private readonly ManagerRegistry $doctrine
    ) {
    }

    public function update()
    {
        $request = new Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);
        $data = $request->request->all();

        $cluster = $this->doctrine->getRepository(Cluster::class)->find((int) $data['cluster']);

        $productLine = $this->doctrine->getRepository(ProductLine::class)->find((int) $data['id']);
        $productLine->setName(trim($data['name']));
        $productLine->setCluster($cluster);

        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($productLine);
        $entityManager->flush();

        $this->logAction('update_product_line', 'Продуктовые направления', 'Редактирование продуктового направления '.$data['id'].' '.$data['name']);

        return $this->json(['result' => 'success', 'id' => $data['id']]);
    }

    public function add(): Response
    {
        $request = new Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);
        $data = $request->request->all();

        $cluster = $this->doctrine->getRepository(Cluster::class)->find($data['cluster']);

        $product_line = new ProductLine();
        $product_line->setName(trim($data['name']));
        $product_line->setCluster($cluster);

        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($product_line);
        $entityManager->flush();
        $lastId = $product_line->getId();

        $this->logAction('add_product_line', 'Продуктовые направления', 'Создание продуктового направления '.$lastId.' '.$data['name']);

        return $this->json(['result' => 'success', 'id' => $lastId]);
    }
}
