<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller\Api;

use App\Entity\City;
use App\Entity\Loger;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiCityController extends AbstractController
{
    public function __construct(private ManagerRegistry $managerRegistry, private readonly ManagerRegistry $doctrine)
    {
    }

    public function add(): Response
    {
        $request = new Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);
        $data = $request->request->all();

        $fs = $this->managerRegistry->getRepository(City::class)->findOneBy(['name' => trim($data['name'])]);
        if (!empty($fs)) {
            return $this->json(['result' => 'error']);
        }

        $city = new City();
        $city->setName(trim($data['name']));
        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($city);
        $entityManager->flush();
        $lastId = $city->getId();

        $logger = new Loger();
        $logger->setTime(new \DateTime());
        $logger->setAction('add_city');
        $logger->setUserLoger($this->getUser());
        $logger->setIp($request->server->get('REMOTE_ADDR'));
        $logger->setChapter('Города');
        $logger->setComment('Добавление города '.$lastId.' '.$data['name']);
        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($logger);
        $entityManager->flush();

        return $this->json(['result' => 'success', 'id' => $lastId]);
    }

    public function update(): Response
    {
        $request = new Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);
        $data = $request->request->all();
        $fs = $this->managerRegistry->getRepository(City::class)->find((int) $data['id']);
        $fs->setName(trim($data['name']));

        $logger = new Loger();
        $logger->setTime(new \DateTime());
        $logger->setAction('update_city');
        $logger->setUserLoger($this->getUser());
        $logger->setIp($request->server->get('REMOTE_ADDR'));
        $logger->setChapter('Города');
        $logger->setComment($data['id'].' '.$data['name']);
        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($logger);
        $entityManager->flush();

        return $this->json(['result' => 'success', 'id' => $data['id']]);
    }
}
