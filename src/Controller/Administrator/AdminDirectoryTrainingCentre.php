<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller\Administrator;

use App\Entity\TrainingCenters;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminDirectoryTrainingCentre extends AbstractController
{
    public function __construct(
        private ManagerRegistry $managerRegistry
    )
    {
    }

    public function getList(): Response
    {
        $result = $this->managerRegistry->getRepository(TrainingCenters::class)->getList();

        return $this->render('administrator/directory/training_centre.html.twig',
            [
                'data' => $result,
            ]
        );
    }
}
