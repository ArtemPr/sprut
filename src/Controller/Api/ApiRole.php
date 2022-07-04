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
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

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

        $data['roles_alt'] = strtoupper($data['roles_alt']);
        // ROLE_
        if (substr($data['roles_alt'],0, 5) !== "ROLE_") {
            $data['roles_alt'] = 'ROLE_' . $data['roles_alt'];
        }

        if (is_array($data['role'])) {
            $auth_list = serialize($data['role']);
        } else {
            $auth_list = serialize([]);
        }

        $tc = new Roles();
        $tc->setName(trim($data['name']));
        $tc->setRolesAlt(trim($data['roles_alt']));
        $tc->setAuthList($auth_list);
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

    public function update()
    {
        $request = new Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);
        $data = $request->request->all();

        $data['roles_alt'] = strtoupper($data['roles_alt']);
        // ROLE_
        if (substr($data['roles_alt'],0, 5) !== "ROLE_") {
            $data['roles_alt'] = 'ROLE_' . $data['roles_alt'];
        }

        if (is_array($data['role'])) {
            $auth_list = serialize($data['role']);
        } else {
            $auth_list = serialize([]);
        }

        $tc = $this->doctrine->getRepository(Roles::class)->find($data['id']);

        $tc->setName(trim($data['name']));
        $tc->setRolesAlt(trim($data['roles_alt']));
        $tc->setAuthList($auth_list);
        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($tc);
        $entityManager->flush();
        $lastId = $tc->getId();

        $loger = new Loger();
        $loger->setTime(new \DateTime());
        $loger->setAction('update_role');
        $loger->setUserLoger($this->getUser());
        $loger->setIp($request->server->get('REMOTE_ADDR'));
        $loger->setChapter('Роли');
        $loger->setComment($lastId . ' ' . $data['name']);
        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($loger);
        $entityManager->flush();

        return $this->json(['result' => 'success', 'id'=>$lastId]);
    }

    public function hide($id)
    {
        $request = new Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);
        $data = $request->request->all();

        $kafedra = $this->doctrine->getRepository(Roles::class)->find((int)$id);
        $kafedra->setDelete(true);
        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($kafedra);
        $entityManager->flush();

        $data = $this->doctrine->getRepository(Roles::class)->find((int)$id);

        $loger = new Loger();
        $loger->setTime(new \DateTime());
        $loger->setAction('delete_role');
        $loger->setUserLoger($this->getUser());
        $loger->setIp($request->server->get('REMOTE_ADDR'));
        $loger->setChapter('Роли');
        $loger->setComment($id . ' ' . $data->getName());
        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($loger);
        $entityManager->flush();

        return $this->json(['result' => 'success', 'id'=>$id]);
    }
}
