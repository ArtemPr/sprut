<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller;

use App\Controller\Administrator\Admin;
use App\Controller\Administrator\AdminDirectoryCity;
use App\Controller\Administrator\AdminDirectoryDiscipline;
use App\Controller\Administrator\AdminDirectoryEmployerRequirements;
use App\Controller\Administrator\AdminDirectoryFGOS;
use App\Controller\Administrator\AdminDirectoryKafedra;
use App\Controller\Administrator\AdminDirectoryPotentialJobs;
use App\Controller\Administrator\AdminDirectoryProgramType;
use App\Controller\Administrator\AdminDirectoryPS;
use App\Controller\Administrator\AdminDirectorySubdivisions;
use App\Controller\Administrator\AdminDirectoryTrainingCentre;
use App\Controller\Administrator\AdministratorOperationsController;
use App\Controller\Administrator\AdministratorRoleController;
use App\Controller\Administrator\AdministratorUserController;
use App\Controller\Administrator\AdminLog;
use App\Controller\Administrator\DocumentsController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdministratorController extends AbstractController
{
    #[Route('/administrator/user', name: 'administrator_user')]
    public function getUserList(AdministratorUserController $administratorUserController): Response
    {
        return $administratorUserController->getUserList();
    }

    #[Route('/administrator/user_csv', name: 'administrator_user_csv')]
    public function getUserListCSV(AdministratorUserController $administratorUserController): Response
    {
        return $administratorUserController->getUserListCSV();
    }

    #[Route('/administrator/role', name: 'administrator_role')]
    public function getRoleList(AdministratorRoleController $administratorRoleController): Response
    {
        return $administratorRoleController->getRoleList();
    }

    #[Route('/administrator/role_csv', name: 'administrator_role_csv')]
    public function getRoleListCSV(AdministratorRoleController $administratorRoleController): Response
    {
        return $administratorRoleController->getRoleListCSV();
    }

    #[Route('/administrator/operation', name: 'administrator_operations')]
    public function getOperationsList(AdministratorOperationsController $administratorOperationsController): Response
    {
        return $administratorOperationsController->getOperationsList();
    }

    #[Route('/administrator/operation_csv', name: 'administrator_operations_csv')]
    public function getOperationsCSV(AdministratorOperationsController $administratorOperationsController): Response
    {
        return $administratorOperationsController->getOperationsCSV();
    }

    #[Route('/administrator/kafedra', name: 'administrator_kafedra')]
    public function getKafedraList(AdminDirectoryKafedra $adminDirectoryKafedra): Response
    {
        return $adminDirectoryKafedra->getList();
    }

    #[Route('/administrator/kafedra_csv', name: 'administrator_kafedra_csv')]
    public function getKafedraListCSV(AdminDirectoryKafedra $adminDirectoryKafedra): Response
    {
        return $adminDirectoryKafedra->getCSV();
    }

    #[Route('/administrator/log', name: 'administrator_log')]
    public function getLog(AdminLog $adminLog): Response
    {
        return $adminLog->getList();
    }

    #[Route('/administrator/log_csv', name: 'administrator_log_csv')]
    public function getLogCSV(AdminLog $adminLog): Response
    {
        return $adminLog->getCSV();
    }

    #[Route('/administrator/directory/fros', name: 'administrator_directory_fgos')]
    public function getDirectoryFgos(AdminDirectoryFGOS $adminDirectoryFGOS): Response
    {
        return $adminDirectoryFGOS->getList();
    }

    #[Route('/administrator/directory/fros_csv', name: 'administrator_directory_fgos_csv')]
    public function getDirectoryFgosCSV(AdminDirectoryFGOS $adminDirectoryFGOS): Response
    {
        return $adminDirectoryFGOS->getCSV();
    }

    #[Route('/administrator/directory/ps', name: 'administrator_directory_ps')]
    public function getDirectoryPS(AdminDirectoryPS $adminDirectoryPS): Response
    {
        return $adminDirectoryPS->getList();
    }

    #[Route('/administrator/directory/ps_csv', name: 'administrator_directory_ps_csv')]
    public function getDirectoryPSCSV(AdminDirectoryPS $adminDirectoryPS): Response
    {
        return $adminDirectoryPS->getCSV();
    }

    #[Route('/administrator/directory/tc', name: 'administrator_directory_tc')]
    public function getDirectoryTrainingCentre(AdminDirectoryTrainingCentre $adminDirectoryTrainingCentre): Response
    {
        return $adminDirectoryTrainingCentre->getList();
    }

    #[Route('/administrator/directory/tc_csv', name: 'administrator_directory_tc_csv')]
    public function getDirectoryTrainingCentreCSV(AdminDirectoryTrainingCentre $adminDirectoryTrainingCentre): Response
    {
        return $adminDirectoryTrainingCentre->getCSV();
    }

    #[Route('/administrator/directory/city', name: 'administrator_directory_city')]
    public function getDirectoryCity(AdminDirectoryCity $adminDirectoryCity): Response
    {
        return $adminDirectoryCity->getList();
    }

    #[Route('/administrator/directory/city_csv', name: 'administrator_directory_city_csv')]
    public function getDirectoryCityCSV(AdminDirectoryCity $adminDirectoryCity): Response
    {
        return $adminDirectoryCity->getCSV();
    }

    #[Route('/administrator/directory/program_type', name: 'administrator_directory_program_type')]
    public function getDirectoryProgramType(AdminDirectoryProgramType $adminDirectoryProgramType): Response
    {
        return $adminDirectoryProgramType->getList();
    }

    #[Route('/administrator/directory/program_type_csv', name: 'administrator_directory_program_type_csv')]
    public function getDirectoryProgramTypeCSV(AdminDirectoryProgramType $adminDirectoryProgramType): Response
    {
        return $adminDirectoryProgramType->getCSV();
    }

    #[Route('/administrator/document_templates', name: 'document_templates')]
    public function getDirectoryDocumentTemplates(DocumentsController $adminDocumentsController): Response
    {
        return $adminDocumentsController->getList();
    }

    #[Route('/administrator/document_templates_csv', name: 'document_templates_csv')]
    public function getDirectoryDocumentTemplatesCSV(DocumentsController $adminDocumentsController): Response
    {
        return $adminDocumentsController->getCSV();
    }

    #[Route('/administrator/directory/employer_requirements', name: 'administrator_directory_employer_requirements')]
    public function getDirectoryEmployerRequirements(AdminDirectoryEmployerRequirements $adminDirectoryEmployerRequirements): Response
    {
        return $adminDirectoryEmployerRequirements->getList();
    }

    #[Route('/administrator/directory/employer_requirements_csv', name: 'administrator_directory_employer_requirements_csv')]
    public function getDirectoryEmployerRequirementsCSV(AdminDirectoryEmployerRequirements $adminDirectoryEmployerRequirements): Response
    {
        return $adminDirectoryEmployerRequirements->getCSV();
    }

    #[Route('/administrator/directory/potential_jobs', name: 'administrator_directory_potential_jobs')]
    public function getDirectoryPotentialJobs(AdminDirectoryPotentialJobs $adminDirectoryPotentialJobs): Response
    {
        return $adminDirectoryPotentialJobs->getList();
    }

    #[Route('/administrator/directory/potential_jobs_csv', name: 'administrator_directory_potential_jobs_csv')]
    public function getDirectoryPotentialJobsCSV(AdminDirectoryPotentialJobs $adminDirectoryPotentialJobs): Response
    {
        return $adminDirectoryPotentialJobs->getCSV();
    }

    #[Route('/administrator/directory/subdivisions', name: 'administrator_directory_subdivisions')]
    public function getDirectorySubdivisions(AdminDirectorySubdivisions $adminDirectorySubdivisions): Response
    {
        return $adminDirectorySubdivisions->getList();
    }

    #[Route('/administrator/directory/subdivisions_csv', name: 'administrator_directory_subdivisions_csv')]
    public function getDirectorySubdivisionsCSV(AdminDirectorySubdivisions $adminDirectorySubdivisions): Response
    {
        return $adminDirectorySubdivisions->getCSV();
    }
}
