<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller\Api;

use App\Entity\EmployerRequirements;
use App\Entity\Loger;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiEmployerRequirementsController extends AbstractController
{
    public function __construct(private ManagerRegistry $managerRegistry, private readonly ManagerRegistry $doctrine)
    {
    }

    public function add(): Response
    {
        $request = new Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);
        $data = $request->request->all();
        $name = $this->managerRegistry->getRepository(EmployerRequirements::class)->findOneBy(['requirement_name' => trim($data['requirement_name'])]);
        if (!empty($name)) {
            return $this->json(['result' => 'error']);
        }
        $employer_requirements = new EmployerRequirements();
        $employer_requirements->setRequirementName(trim($data['requirement_name']));
        $employer_requirements->setComment(trim($data['comment']));
        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($employer_requirements);
        $entityManager->flush();
        $lastId = $employer_requirements->getId();

        $loger = new Loger();
        $loger->setTime(new \DateTime());
        $loger->setAction('add_employer_requirements');
        $loger->setUserLoger($this->getUser());
        $loger->setIp($request->server->get('REMOTE_ADDR'));
        $loger->setChapter('Требования работодателя');
        $loger->setComment('Обновление требований работодателя '.$lastId.' '.$data['requirement_name'].' '.$data['comment']);
        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($loger);
        $entityManager->flush();

        return $this->json(['result' => 'success', 'id' => $lastId]);
    }

    public function update()
    {
        $request = new Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);
        $data = $request->request->all();
        $employer_requirements = $this->doctrine->getRepository(EmployerRequirements::class)->find((int) $data['id']);
        $employer_requirements->setRequirementName(trim($data['requirement_name']));
        $employer_requirements->setComment(trim($data['comment']));
        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($employer_requirements);
        $entityManager->flush();

        $loger = new Loger();
        $loger->setTime(new \DateTime());
        $loger->setAction('update_employer_requirements');
        $loger->setUserLoger($this->getUser());
        $loger->setIp($request->server->get('REMOTE_ADDR'));
        $loger->setChapter('Требования работодателя');
        $loger->setComment($data['id'].' '.$data['requirement_name'].' '.$data['comment']);
        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($loger);
        $entityManager->flush();

        return $this->json(['result' => 'success', 'id' => $data['id']]);
    }
}
