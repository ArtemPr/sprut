<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller;

use App\Controller\Api\ApiKafedra;
use App\Controller\Api\ApiOperations;
use App\Controller\Api\ApiProgramController;
use App\Controller\Api\ApiRole;
use App\Controller\Api\ApiTrainingCentre;
use App\Entity\Roles;
use App\Repository\KaferdaRepository;
use App\Repository\ProgramTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api')]
class ApiController extends AbstractController
{
    /**
     * @param ApiProgramController $apiProgramController
     *
     * @return Response
     */
    #[Route('/program_info', name: 'api_get_program_info', methods: ['GET'])]
    public function api_get_program_info(ApiProgramController $apiProgramController): Response
    {
        return $apiProgramController->getProgramInfo();
    }

    /**
     * @param ApiProgramController $apiProgramController
     *
     * @return Response
     */
    #[Route('/program', name: 'api_get_programs_list', methods: ['GET'])]
    public function api_get_programs_list(ApiProgramController $apiProgramController): Response
    {
        return $apiProgramController->getProgramsList();
    }

    #[Route('/program/{id}', name: 'api_get_program', methods: ['GET'])]
    public function api_get_program(ApiProgramController $apiProgramController, int $id): Response
    {
        return $apiProgramController->getProgram($id);
    }

    #[Route('/program_type', name: 'api_get_program_type', methods: ['GET'])]
    public function api_get_program_type(ProgramTypeRepository $programTypeRepository): Response
    {
        return $this->json($programTypeRepository->findAll());
    }

    #[Route('/kafedra/{id}', name: 'api_get_kafedra', methods: ['GET'])]
    public function getKafedra($id, KaferdaRepository $kaferdaRepository): Response
    {
        return $this->json($kaferdaRepository->get($id));
    }

    #[Route('/kafedra_add', name: 'api_add_kafedra', methods: ['POST'])]
    public function add_kafedra(ApiKafedra $apiKafedra): Response
    {
        return $apiKafedra->add();
    }

    #[Route('/kafedra_update', name: 'api_update_kafedra', methods: ['POST'])]
    public function update_kafedra(ApiKafedra $apiKafedra): Response
    {
        $update = $apiKafedra->update();
        return $update;
    }

    #[Route('/kafedra_hide/{id}', name: 'api_hide_kafedra', methods: ['GET'])]
    public function hide_kafedra($id, ApiKafedra $apiKafedra): Response
    {
        return $apiKafedra->hide($id);
    }

    #[Route('/training_centre_add', name: 'api_training_centr_add', methods: ['POST'])]
    public function add_tc(ApiTrainingCentre $apiTrainingCentre): Response
    {
        return $apiTrainingCentre->add();
    }

    #[Route('/training_centre_update', name: 'api_training_centr_update', methods: ['POST'])]
    public function update_tc(ApiTrainingCentre $apiTrainingCentre): Response
    {
        $update = $apiTrainingCentre->update();
        return $update;
    }

    #[Route('/training_centre_hide/{id}', name: 'api_training_centr_hide', methods: ['GET'])]
    public function hide_tc($id, ApiTrainingCentre $apiTrainingCentre): Response
    {
        return $apiTrainingCentre->hide($id);
    }

    #[Route('/ps_add', name: 'api_ps_add', methods: ['POST'])]
    public function add_ps(ApiTrainingCentre $apiTrainingCentre): Response
    {
        return $apiTrainingCentre->add();
    }

    #[Route('/ps_update', name: 'api_ps_update', methods: ['POST'])]
    public function update_ps(ApiTrainingCentre $apiTrainingCentre): Response
    {
        $update = $apiTrainingCentre->update();
        return $update;
    }

    #[Route('/operations_update', name: 'api_update_operations', methods: ['POST'])]
    public function update_operations(ApiOperations $apiOperations): Response
    {
        $update = $apiOperations->update();
        return $update;
    }

    #[Route('/role_add', name: 'role_add', methods: ['POST'])]
    public function add_role(ApiRole $apiRole): Response
    {
        return $apiRole->add();
    }
}
