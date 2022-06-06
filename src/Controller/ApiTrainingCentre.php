<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller;

use App\Repository\TrainingCentersRepository;
use App\Service\ApiService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api')]
class ApiTrainingCentre extends AbstractController
{
    use ApiService;

    public function __construct(
        private TrainingCentersRepository $trainingCentersRepository,
        private ManagerRegistry $doctrine
    )
    {
    }

    #[Route('/training_centre_short', name: 'api_get_training_centre_short', methods: ['GET'])]
    public function getTrainingCentre(ManagerRegistry $doctrine): Response
    {
        if ($this->getAuth() === false) {
            return $this->json(['error'=>'error auth']);
        }

        $result = $this->trainingCentersRepository->findAll();

        foreach ($result as $val) {
            $out[] = [
                'centre_id' => $val->getId(),
                'centre_name' => \mb_convert_encoding($val->getName(), 'utf8'),
            ];
        }

        return $this->render('api/index.html.twig', [
            'out' => $this->convertJson($out ?? ['error' => 'no results'])
        ]);
    }

}
