<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller\Api;

use App\Entity\Loger;
use App\Entity\Operations;
use App\Service\ApiService;
use App\Service\LoggerService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ApiOperations extends AbstractController
{
    use ApiService;
    use LoggerService;

    public function __construct(
        private readonly ManagerRegistry $doctrine
    ) {
    }
    public function update()
    {
        $request = new Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);
        $data = $request->request->all();

        $tc = $this->doctrine->getRepository(Operations::class)->find((int)$data['id']);
        $tc->setName(trim($data['name']));
        $tc->setComment(trim($data['comment']));
        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($tc);
        $entityManager->flush();

        $this->logAction('update_operations', 'Операции', $data['id'] . ' ' . $data['name']);

        return $this->json(['result' => 'success', 'id'=>$data['id']]);
    }
}
