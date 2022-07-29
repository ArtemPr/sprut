<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller\Api;

use App\Entity\Loger;
use App\Entity\ProfStandarts;
use App\Service\ApiService;
use App\Service\LoggerService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ApiPS extends AbstractController
{
    use ApiService;
    use LoggerService;

    public function __construct(
        private readonly ManagerRegistry $doctrine
    ) {
    }

    public function add()
    {
        $request = new Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);
        $data = $request->request->all();

        $tc = new ProfStandarts();
        $tc->setName(trim($data['name']));
        // add
        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($tc);
        $entityManager->flush();
        $lastId = $tc->getId();

        $this->logAction('add_ps', 'Профессиональные стандарты', $lastId . ' ' . $data['name']);

        return $this->json(['result' => 'success', 'id'=>$lastId]);
    }

    public function update()
    {
        $request = new Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);
        $data = $request->request->all();

        $tc = $this->doctrine->getRepository(ProfStandarts::class)->find((int)$data['id']);
        $tc->setName(trim($data['name']));
        // add
        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($tc);
        $entityManager->flush();

        $this->logAction('add_update', 'Профессиональные стандарты', $data['id'] . ' ' . $data['name']);

        return $this->json(['result' => 'success', 'id'=>$data['id']]);
    }
}
