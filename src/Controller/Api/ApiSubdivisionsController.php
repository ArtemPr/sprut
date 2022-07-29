<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller\Api;

use App\Entity\Loger;
use App\Entity\Subdivisions;
use App\Service\ApiService;
use App\Service\LoggerService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiSubdivisionsController extends AbstractController
{
    use ApiService;
    use LoggerService;

    public function __construct(private ManagerRegistry $managerRegistry, private readonly ManagerRegistry $doctrine)
    {
    }

    public function add(): Response
    {
        $request = new Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);
        $data = $request->request->all();
        $name = $this->managerRegistry->getRepository(Subdivisions::class)->findOneBy(['subdivisions_name' => trim($data['subdivisions_name'])]);
        if (!empty($name)) {
            return $this->json(['result' => 'error']);
        }
        $subdivisions = new Subdivisions();
        $subdivisions->setSubdivisionsName(trim($data['subdivisions_name']));
        $subdivisions->setDelete(false);
        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($subdivisions);
        $entityManager->flush();
        $lastId = $subdivisions->getId();

        $this->logAction('add_subdivisions', 'Подразделения', 'Добавлено подразделение '.$lastId.' '.$data['subdivisions_name']);

        return $this->json(['result' => 'success', 'id' => $lastId]);
    }

    public function update()
    {
        $request = new Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);
        $data = $request->request->all();
        $subdivisions = $this->doctrine->getRepository(Subdivisions::class)->find((int) $data['id']);
        $subdivisions->setSubdivisionsName(trim($data['subdivisions_name']));
        $subdivisions->setDelete(false);
        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($subdivisions);
        $entityManager->flush();

        $this->logAction('update_subdivisions', 'Подразделения', 'Обновлено подразделение '.$data['id'].' '.$data['subdivisions_name']);

        return $this->json(['result' => 'success', 'id' => $data['id']]);
    }

    public function hide($id)
    {
        $request = new Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);
        $data = $request->request->all();

        $subdivisions = $this->doctrine->getRepository(Subdivisions::class)->find((int) $id);
        $subdivisions->setDelete(true);
        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($subdivisions);
        $entityManager->flush();

        $data = $this->doctrine->getRepository(Subdivisions::class)->find((int) $id);
        $this->logAction('delete_subdivisions', 'Подразделения', 'Удалено подразделение '.$data->getId().' '.$data->getSubdivisionsName());

        return $this->json(['result' => 'success', 'id' => $id]);
    }
}
