<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller\Api;

use App\Entity\Loger;
use App\Entity\PotentialJobs;
use App\Repository\PotentialJobsRepository;
use App\Service\ApiService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiPotentialJobsController extends AbstractController
{
    use ApiService;

    public function __construct(private ManagerRegistry $managerRegistry, private readonly ManagerRegistry $doctrine)
    {
    }

    public function add(): Response
    {
        $request = new Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);
        $data = $request->request->all();
        $name = $this->managerRegistry->getRepository(PotentialJobs::class)->findOneBy(['jobs_name' => trim($data['jobs_name'])]);
        if (!empty($name)) {
            return $this->json(['result' => 'error']);
        }
        $potential_jobs = new PotentialJobs();
        $potential_jobs->setJobsName(trim($data['jobs_name']));
        $potential_jobs->setComment(trim($data['comment']));
        $potential_jobs->setDelete(false);
        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($potential_jobs);
        $entityManager->flush();
        $lastId = $potential_jobs->getId();

        $loger = new Loger();
        $loger->setTime(new \DateTime());
        $loger->setAction('add_potential_jobs');
        $loger->setUserLoger($this->getUser());
        $loger->setIp($request->server->get('REMOTE_ADDR'));
        $loger->setChapter('Потенциальное место работы');
        $loger->setComment('Добавление потенциального места работы '.$lastId.' '.$data['jobs_name'].' '.$data['comment']);
        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($loger);
        $entityManager->flush();

        return $this->json(['result' => 'success', 'id' => $lastId]);
    }

    public function update()
    {
        $request = new Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);
        $data = $request->request->all();
        $potential_jobs = $this->doctrine->getRepository(PotentialJobs::class)->find((int) $data['id']);
        $potential_jobs->setJobsName(trim($data['jobs_name']));
        $potential_jobs->setComment(trim($data['comment']));
        $potential_jobs->setDelete(false);
        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($potential_jobs);
        $entityManager->flush();

        $loger = new Loger();
        $loger->setTime(new \DateTime());
        $loger->setAction('update_potential_jobs');
        $loger->setUserLoger($this->getUser());
        $loger->setIp($request->server->get('REMOTE_ADDR'));
        $loger->setChapter('Потенциальное место работы');
        $loger->setComment('Обновление потенциального места работы '.$data['id'].' '.$data['jobs_name'].' '.$data['comment']);
        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($loger);
        $entityManager->flush();

        return $this->json(['result' => 'success', 'id' => $data['id']]);
    }

    public function hide($id): self
    {
        $request = new Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);
        $data_comment = $request->request->all();

        $potential_jobs = $this->doctrine->getRepository(PotentialJobs::class)->find((int) $id);
        $potential_jobs->setDelete(true);
        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($potential_jobs);
        $entityManager->flush();

        $data = $this->doctrine->getRepository(PotentialJobs::class)->find((int) $id);
        $loger = new Loger();
        $loger->setTime(new \DateTime());
        $loger->setAction('delete_potential_jobs');
        $loger->setUserLoger($this->getUser());
        $loger->setIp($request->server->get('REMOTE_ADDR'));
        $loger->setChapter('Потенциальное место работы');
        $loger->setComment('Удалено потенциального места работы '.$data_comment['id'].' '.$data_comment['jobs_name'].' '.$data_comment['comment']);
        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($loger);
        $entityManager->flush();

        return $this->json(['result' => 'success', 'id' => $id]);
    }
}
