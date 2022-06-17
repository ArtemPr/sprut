<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller\Api;

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

    /**
     * @param ManagerRegistry $doctrine
     * @return Response
     */
    #[Route('/training_centre', name: 'api_get_training_centre_list', methods: ['GET'])]
    public function getTrainingCentreList(ManagerRegistry $doctrine): Response
    {
        if ($this->getAuth('ROLE_API_USER','api_get_training_centre_list') === false) {
            return $this->json(['error'=>'error auth']);
        }

        $result = $this->trainingCentersRepository->findAll();

        foreach ($result as $val) {
            $out[] = [
                'centre_id' => $val->getId(),
                'centre_name' => \mb_convert_encoding($val->getName(), 'utf8'),
            ];
        }

        return $this->json($out ?? ['error' => 'no results']);
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @TODO сделать выборку в репозитории
     */
    #[Route('/training_centre/{id}', name: 'api_get_training_centre', methods: ['GET'])]
    public function getTrainingCentre($id)
    {
        if ($this->getAuth('ROLE_API_USER','api_get_training_centre') === false) {
            return $this->json(['error'=>'error auth']);
        }

        $result = $this->trainingCentersRepository->find($id);
        return $this->json($result ?? []);
    }

}
