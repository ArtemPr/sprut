<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller\Administrator;

use App\Entity\Operations;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class AdministratorOperationsController extends AbstractController
{
    public function __construct(
        private ManagerRegistry $managerRegistry
    ) {
    }

    public function getOperationsList(): Response
    {
        $result = $this->managerRegistry->getRepository(Operations::class)->getList();

        return $this->render('administrator/operations/index.html.twig',
            [
                'data' => $result,
            ]
        );
    }
}
