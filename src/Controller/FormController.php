<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller;

use App\Controller\Administrator\AdminDirectoryCity;
use App\Controller\Administrator\AdminDirectoryCluster;
use App\Controller\Administrator\AdminDirectoryEmployerRequirements;
use App\Controller\Administrator\AdminDirectoryFGOS;
use App\Controller\Administrator\AdminDirectoryKafedra;
use App\Controller\Administrator\AdminDirectoryPotentialJobs;
use App\Controller\Administrator\AdminDirectoryProgramType;
use App\Controller\Administrator\AdminDirectorySubdivisions;
use App\Controller\Administrator\AdminDirectoryTrainingCentre;
use App\Controller\Administrator\AdministratorOperationsController;
use App\Controller\Administrator\AdministratorRoleController;
use App\Controller\Administrator\AdministratorUserController;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/form', name: 'form')]
class FormController extends AbstractController
{
    public function __construct(
        private ManagerRegistry $managerRegistry
    ) {
    }

    #[Route('/kafedra_edit/{id}', name: 'edit_kafedra')]
    public function getKafedraForm($id, AdminDirectoryKafedra $adminDirectoryKafedra): Response
    {
        return $adminDirectoryKafedra->getKafedraForm($id);
    }

    #[Route('/training_centre_edit/{id}', name: 'training_centre_edit')]
    public function getTrainingCentreForm($id, AdminDirectoryTrainingCentre $adminDirectoryTrainingCentre): Response
    {
        return $adminDirectoryTrainingCentre->getTrainingCentreForm($id);
    }

    #[Route('/operations_edit/{id}', name: 'operations_edit')]
    public function getOperationsForm(
        $id,
        AdministratorOperationsController $administratorOperationsController
    ): Response {
        return $administratorOperationsController->getOperationsForm($id);
    }

    #[Route('/AdminUser_edit/{id}', name: 'user_edit')]
    public function getUserForm($id, AdministratorUserController $administratorUserController): Response
    {
        return $administratorUserController->getUserForm($id);
    }

    #[Route('/discipline_edit/{id}', name: 'discipline_edit')]
    public function getDisciplineForm($id, DisciplineController $adminDirectoryDiscipline): Response
    {
        return $adminDirectoryDiscipline->getDisciplineForm($id);
    }

    #[Route('/role_edit/{id}', name: 'role_edit')]
    public function getRoleForm($id, AdministratorRoleController $administratorRoleController): Response
    {
        return $administratorRoleController->getRoleForm((int) $id);
    }

    #[Route('/program_edit/{id}', name: 'program_edit')]
    public function getProgramForm($id, ProgramController $programController): Response
    {
        return $programController->getProgramForm($id);
    }

    #[Route('/federal_standart_edit/{id}', name: 'federal_standart_edit')]
    public function getFgosForm($id, AdminDirectoryFGOS $adminDirectoryFGOS): Response
    {
        return $adminDirectoryFGOS->getFgosForm($id);
    }

    #[Route('/city_edit/{id}', name: 'city_edit')]
    public function getCityForm($id, AdminDirectoryCity $adminCity): Response
    {
        return $adminCity->getCityForm($id);
    }

    #[Route('/program_type_edit/{id}', name: 'program_type_edit')]
    public function getProgramTypeForm($id, AdminDirectoryProgramType $adminProgram): Response
    {
        return $adminProgram->getProgramTypeForm($id);
    }

    #[Route('/employer_requirements_edit/{id}', name: 'employer_requirements_edit')]
    public function getEmployerRequirementsForm($id, AdminDirectoryEmployerRequirements $adminEmployerRequirements): Response
    {
        return $adminEmployerRequirements->getEmployerRequirementsForm($id);
    }

    #[Route('/potential_jobs_edit/{id}', name: 'potential_jobs_edit')]
    public function getPotentialJobsForm($id, AdminDirectoryPotentialJobs $adminDirectoryPotentialJobs): Response
    {
        return $adminDirectoryPotentialJobs->getPotentialJobsForm($id);
    }

    #[Route('/subdivisions_edit/{id}', name: 'subdivisions_edit')]
    public function getSubdivisionsForm($id, AdminDirectorySubdivisions $adminDirectorySubdivisions): Response
    {
        return $adminDirectorySubdivisions->getSubdivisionsForm($id);
    }

    #[Route('/cluster_edit/{id}', name: 'form_edit')]
    public function getClusterForm($id, AdminDirectoryCluster $adminDirectoryCluster): Response
    {
        return $adminDirectoryCluster->getClusterForm($id);
    }
}
