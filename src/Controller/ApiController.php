<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller;

use App\Controller\Api\ApiKafedra;
use App\Controller\Api\ApiProgramController;
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

    /**
     * @param ApiProgramController $apiProgramController
     * @param int                  $id
     *
     * @return Response
     */
    #[Route('/program/{id}', name: 'api_get_program', methods: ['GET'])]
    public function api_get_program(ApiProgramController $apiProgramController, int $id): Response
    {
        return $apiProgramController->getProgram($id);
    }

    /**
     * @return void
     */
    #[Route('/program_type', name: 'api_get_program_type', methods: ['GET'])]
    public function api_get_program_type(ProgramTypeRepository $programTypeRepository): Response
    {
        return $this->json($programTypeRepository->findAll());
    }

    #[Route('/kafedra', name: 'api_add_kafedra', methods: ['POST'])]
    public function add_kafedra(ApiKafedra $apiKafedra): Response
    {
        return $apiKafedra->add();
    }



}
