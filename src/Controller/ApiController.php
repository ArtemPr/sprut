<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller;

use App\Controller\Api\ApiAntiplagiat;
use App\Controller\Api\ApiCityController;
use App\Controller\Api\ApiClusterController;
use App\Controller\Api\ApiDirectionsController;
use App\Controller\Api\apiDocumentsController;
use App\Controller\Api\ApiDocumentVariablesController;
use App\Controller\Api\ApiEmployerRequirementsController;
use App\Controller\Api\ApiFgosController;
use App\Controller\Api\ApiKafedra;
use App\Controller\Api\ApiLitera;
use App\Controller\Api\ApiOperations;
use App\Controller\Api\ApiPotentialJobsController;
use App\Controller\Api\ApiProductLineController;
use App\Controller\Api\ApiProgramController;
use App\Controller\Api\ApiProgramTypeController;
use App\Controller\Api\ApiRole;
use App\Controller\Api\ApiSubdivisionsController;
use App\Controller\Api\ApiTrainingCentre;
use App\Controller\Api\ApiUserController;
use App\Repository\KaferdaRepository;
use App\Repository\ProgramTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api')]
class ApiController extends AbstractController
{
    #[Route('/program_info', name: 'api_get_program_info', methods: ['GET'])]
    public function api_get_program_info(ApiProgramController $apiProgramController): Response
    {
        return $apiProgramController->getProgramInfo();
    }

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

    #[Route('/role_update', name: 'role_update', methods: ['POST'])]
    public function update_role(ApiRole $apiRole): Response
    {
        return $apiRole->update();
    }

    #[Route('/role_hide/{id}', name: 'role_hide')]
    public function role_hide($id, ApiRole $apiRole): Response
    {
        return $apiRole->hide($id);
    }

    #[Route('/user_add', name: 'user_add', methods: ['POST'])]
    public function add_user(ApiUserController $apiUserController): Response
    {
        return $apiUserController->add();
    }

    #[Route('/user_hide/{id}', name: 'user_hide')]
    public function user_hide($id, ApiUserController $apiUserController): Response
    {
        return $apiUserController->hide($id);
    }

    #[Route('/user_update', name: 'user_update', methods: ['POST'])]
    public function user_update(ApiUserController $apiUserController): Response
    {
        return $apiUserController->update();
    }

    #[Route('/add_program', name: 'add_program', methods: ['POST'])]
    public function add_program(ApiProgramController $apiProgramController): Response
    {
        return $apiProgramController->add();
    }

    #[Route('/update_program', name: 'update_program', methods: ['POST'])]
    public function update_program(ApiProgramController $apiProgramController): Response
    {
        return $apiProgramController->update();
    }

    #[Route('/fgos_update', name: 'update_fgos', methods: ['POST'])]
    public function update_fgos(ApiFgosController $apiFgosController): Response
    {
        return $apiFgosController->update();
    }

    #[Route('/fgos_add', name: 'fgos_add', methods: ['POST'])]
    public function fgos_add(ApiFgosController $apiFgosController): Response
    {
        return $apiFgosController->add();
    }

    #[Route('/add_antiplagiat', name: 'add_antiplagiat', methods: ['POST'])]
    public function add_antiplagiat(ApiAntiplagiat $apiAntiplagiat): Response
    {
        return $apiAntiplagiat->add();
    }

    #[Route('/update_antiplagiat', name: 'update_antiplagiat', methods: ['POST'])]
    public function update_antiplagiat(ApiAntiplagiat $apiAntiplagiat): Response
    {
        return $apiAntiplagiat->update();
    }

    #[Route('/add_litera', name: 'add_litera', methods: ['POST'])]
    public function add_litera(ApiLitera $apiLitera): Response
    {
        return $apiLitera->add();
    }

    #[Route('/update_litera', name: 'update_litera', methods: ['POST'])]
    public function update_litera(ApiLitera $apiLitera): Response
    {
        return $apiLitera->update();
    }

    #[Route('/city_update', name: 'city_update', methods: ['POST'])]
    public function city_update(ApiCityController $apiCityController): Response
    {
        return $apiCityController->update();
    }

    #[Route('/city_add', name: 'city_add', methods: ['POST'])]
    public function city_add(ApiCityController $apiCityController): Response
    {
        return $apiCityController->add();
    }

    #[Route('/program_type_update', name: 'program_type_update', methods: ['POST'])]
    public function program_type_update(ApiProgramTypeController $apiProgramTypeController): Response
    {
        return $apiProgramTypeController->update();
    }

    #[Route('/program_type_add', name: 'program_type_add', methods: ['POST'])]
    public function program_type_add(ApiProgramTypeController $apiProgramTypeController): Response
    {
        return $apiProgramTypeController->add();
    }

    #[Route('/employer_requirements_update', name: 'employer_requirements_update', methods: ['POST'])]
    public function employer_requirements_update(ApiEmployerRequirementsController $apiEmployerRequirementsController): Response
    {
        return $apiEmployerRequirementsController->update();
    }

    #[Route('/employer_requirements_add', name: 'employer_requirements_add', methods: ['POST'])]
    public function employer_requirements_add(ApiEmployerRequirementsController $apiEmployerRequirementsController): Response
    {
        return $apiEmployerRequirementsController->add();
    }

    #[Route('/employer_requirements_hide/{id}', name: 'employer_requirements_hide', methods: ['GET'])]
    public function employer_requirements_hide($id, ApiEmployerRequirementsController $apiEmployerRequirementsController): Response
    {
        return $apiEmployerRequirementsController->hide($id);
    }

    #[Route('/potential_jobs_update', name: 'potential_jobs_update', methods: ['POST'])]
    public function potential_jobs_update(ApiPotentialJobsController $apiPotentialJobsController): Response
    {
        return $apiPotentialJobsController->update();
    }

    #[Route('/potential_jobs_add', name: 'potential_jobs_add', methods: ['POST'])]
    public function potential_jobs_add(ApiPotentialJobsController $apiPotentialJobsController): Response
    {
        return $apiPotentialJobsController->add();
    }

    #[Route('/potential_jobs_hide/{id}', name: 'potential_jobs_hide', methods: ['GET'])]
    public function potential_jobs_hide($id, ApiPotentialJobsController $apiPotentialJobsController): Response
    {
        return $apiPotentialJobsController->hide($id);
    }

    #[Route('/subdivisions_update', name: 'subdivisions_update', methods: ['POST'])]
    public function subdivisions_update(ApiSubdivisionsController $apiSubdivisionsController): Response
    {
        return $apiSubdivisionsController->update();
    }

    #[Route('/subdivisions_add', name: 'subdivisions_add', methods: ['POST'])]
    public function subdivisions_add(ApiSubdivisionsController $apiSubdivisionsController): Response
    {
        return $apiSubdivisionsController->add();
    }

    #[Route('/subdivisions_hide/{id}', name: 'subdivisions_hide', methods: ['GET'])]
    public function subdivisions_hide($id, ApiSubdivisionsController $apiSubdivisionsController): Response
    {
        return $apiSubdivisionsController->hide($id);
    }

    #[Route('/document_templates_update', name: 'document_templates_update', methods: ['POST'])]
    public function document_templates_update(apiDocumentsController $apiDocumentsController): Response
    {
        return $apiDocumentsController->update();
    }

    #[Route('/document_templates_add', name: 'document_templates_add', methods: ['POST'])]
    public function document_templates_add(apiDocumentsController $apiDocumentsController): Response
    {
        return $apiDocumentsController->add();
    }

    #[Route('/document_templates_hide/{id}', name: 'document_templates_hide')]
    public function document_templates_hide($id, apiDocumentsController $apiDocumentsController): Response
    {
        return $apiDocumentsController->hide($id);
    }

    #[Route('/document_variables_update', name: 'document_variables_update', methods: ['POST'])]
    public function documents_variables_update(ApiDocumentVariablesController $apiDocumentsVariablesController): Response
    {
        return $apiDocumentsVariablesController->update();
    }

    #[Route('/document_variables_add', name: 'document_variables_add', methods: ['POST'])]
    public function documents_variables_add(ApiDocumentVariablesController $apiDocumentsVariablesController): Response
    {
        return $apiDocumentsVariablesController->add();
    }

    #[Route('/document_variables_hide/{id}', name: 'document_variables_hide')]
    public function document_variables_hide($id, ApiDocumentVariablesController $apiDocumentsVariablesController): Response
    {
        return $apiDocumentsVariablesController->hide($id);
    }

    #[Route('/cluster_add', name: 'cluster_add', methods: ['POST'])]
    public function cluster_add(ApiClusterController $apiClusterController): Response
    {
        return $apiClusterController->add();
    }

    #[Route('/cluster_update', name: 'cluster_update', methods: ['POST'])]
    public function cluster_update(ApiClusterController $apiClusterController): Response
    {
        return $apiClusterController->update();
    }

    #[Route('/directions_update', name: 'directions_update', methods: ['POST'])]
    public function directions_update(ApiDirectionsController $apiDirectionsController): Response
    {
        return $apiDirectionsController->update();
    }

    #[Route('/directions_add', name: 'directions_add', methods: ['POST'])]
    public function directions_add(ApiDirectionsController $apiDirectionsController): Response
    {
        return $apiDirectionsController->add();
    }

    #[Route('/directions_hide/{id}', name: 'directions_hide', methods: ['GET'])]
    public function directions_hide($id, ApiDirectionsController $apiDirectionsController): Response
    {
        return $apiDirectionsController->hide($id);
    }

    #[Route('/product_line_add', name: 'product_line_add', methods: ['POST'])]
    public function product_line_add(ApiProductLineController $apiProductLineController): Response
    {
        return $apiProductLineController->add();
    }

    #[Route('/product_line_update', name: 'product_line_update', methods: ['POST'])]
    public function product_line_update(ApiProductLineController $apiProductLineController): Response
    {
        return $apiProductLineController->update();
    }
}
