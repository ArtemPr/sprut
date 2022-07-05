<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller;

use App\Controller\Administrator\Admin;
use App\Controller\Administrator\AdminDirectoryDiscipline;
use App\Controller\Administrator\AdminDirectoryFGOS;
use App\Controller\Administrator\AdminDirectoryKafedra;
use App\Controller\Administrator\AdminDirectoryPS;
use App\Controller\Administrator\AdminDirectoryTrainingCentre;
use App\Controller\Administrator\AdministratorOperationsController;
use App\Controller\Administrator\AdministratorRoleController;
use App\Controller\Administrator\AdministratorUserController;
use App\Controller\Administrator\AdminLog;
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

    #[Route('/administrator/role', name: 'administrator_role')]
    public function getRoleList(AdministratorRoleController $administratorRoleController): Response
    {
        return $administratorRoleController->getRoleList();
    }

    #[Route('/administrator/operation', name: 'administrator_operations')]
    public function getOperationsList(AdministratorOperationsController $administratorOperationsController): Response
    {
        return $administratorOperationsController->getOperationsList();
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
}
