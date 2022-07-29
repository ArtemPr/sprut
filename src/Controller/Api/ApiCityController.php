<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller\Api;

use App\Entity\City;
use App\Entity\Loger;
use App\Service\LoggerService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiCityController extends AbstractController
{
    use LoggerService;

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

        $this->logAction('add_city', 'Города', 'Добавление города '.$lastId.' '.$data['name']);

        return $this->json(['result' => 'success', 'id' => $lastId]);
    }

    public function update(): Response
    {
        $request = new Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);
        $data = $request->request->all();
        $fs = $this->managerRegistry->getRepository(City::class)->find((int) $data['id']);
        $fs->setName(trim($data['name']));

        $this->logAction('update_city', 'Города', $data['id'].' '.$data['name']);

        return $this->json(['result' => 'success', 'id' => $data['id']]);
    }
}
