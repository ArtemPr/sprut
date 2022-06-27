<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller\Api;

use App\Entity\Loger;
use App\Entity\Roles;
use App\Service\ApiService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

class ApiRole extends \Symfony\Bundle\FrameworkBundle\Controller\AbstractController
{
    use ApiService;

    public function __construct(
        private readonly ManagerRegistry $doctrine
    ) {
    }
    public function add()
    {
        $request = new Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);
        $data = $request->request->all();

        $tc = new Roles();
        $tc->setName(trim($data['name']));
        $tc->setRolesAlt(trim($data['roles_alt']));
        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($tc);
        $entityManager->flush();
        $lastId = $tc->getId();

        $loger = new Loger();
        $loger->setTime(new \DateTime());
        $loger->setAction('add_role');
        $loger->setUserLoger($this->getUser());
        $loger->setIp($request->server->get('REMOTE_ADDR'));
        $loger->setChapter('Роли');
        $loger->setComment($lastId . ' ' . $data['name']);
        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($loger);
        $entityManager->flush();

        return $this->json(['result' => 'success', 'id'=>$lastId]);
    }
}
