<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller\Administrator;

use App\Entity\FederalStandart;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class AdminDirectoryFGOS extends AbstractController
{
    public function __construct(
        private ManagerRegistry $managerRegistry
    ) {
    }

    public function getList(): Response
    {
        $result = $this->managerRegistry->getRepository(FederalStandart::class)->getList();

        return $this->render('administrator/directory/fgos.html.twig',
            [
                'data' => $result,
            ]
        );
    }
}
