<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller\Api;

use App\Entity\Loger;
use App\Entity\Subdivisions;
use App\Service\ApiService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiSubdivisionsController extends AbstractController
{
    use ApiService;

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

        $loger = new Loger();
        $loger->setTime(new \DateTime());
        $loger->setAction('add_subdivisions');
        $loger->setUserLoger($this->getUser());
        $loger->setIp($request->server->get('REMOTE_ADDR'));
        $loger->setChapter('Подразделения');
        $loger->setComment('Добавлено подразделение '.$lastId.' '.$data['subdivisions_name']);
        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($loger);
        $entityManager->flush();

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

        $loger = new Loger();
        $loger->setTime(new \DateTime());
        $loger->setAction('update_subdivisions');
        $loger->setUserLoger($this->getUser());
        $loger->setIp($request->server->get('REMOTE_ADDR'));
        $loger->setChapter('Подразделения');
        $loger->setComment('Обновлено подразделение '.$data['id'].' '.$data['subdivisions_name']);
        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($loger);
        $entityManager->flush();

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
        $loger = new Loger();
        $loger->setTime(new \DateTime());
        $loger->setAction('delete_subdivisions');
        $loger->setUserLoger($this->getUser());
        $loger->setIp($request->server->get('REMOTE_ADDR'));
        $loger->setChapter('Подразделения');
        $loger->setComment('Удалено подразделение '.$data->getId().' '.$data->getSubdivisionsName());
        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($loger);
        $entityManager->flush();

        return $this->json(['result' => 'success', 'id' => $id]);
    }
}
