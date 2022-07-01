<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller;

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

#[Route('/administrator', name: 'administrator')]
class AdministratorController extends AbstractController
{
    #[Route('/user', name: '_user')]
    public function getUserList(AdministratorUserController $administratorUserController): Response
    {
        return $administratorUserController->getUserList();
    }

    #[Route('/role', name: '_role')]
    public function getRoleList(AdministratorRoleController $administratorRoleController): Response
    {
        return $administratorRoleController->getRoleList();
    }

    #[Route('/operation', name: '_operations')]
    public function getOperationsList(AdministratorOperationsController $administratorOperationsController): Response
    {
        return $administratorOperationsController->getOperationsList();
    }

    #[Route('/kafedra', name: '_kafedra')]
    public function getKafedraList(AdminDirectoryKafedra $adminDirectoryKafedra): Response
    {
        return $adminDirectoryKafedra->getList();
    }

    #[Route('/log', name: '_log')]
    public function getLog(AdminLog $adminLog): Response
    {
        return $adminLog->getList();
    }

    #[Route('/directory/fros', name: '_directory_fgos')]
    public function getDirectoryFgos(AdminDirectoryFGOS $adminDirectoryFGOS): Response
    {
        return $adminDirectoryFGOS->getList();
    }

    #[Route('/directory/fros_csv', name: '_directory_fgos_csv')]
    public function getDirectoryFgosCSV(AdminDirectoryFGOS $adminDirectoryFGOS): Response
    {
        return $adminDirectoryFGOS->getCSV();
    }

    #[Route('/directory/ps', name: '_directory_ps')]
    public function getDirectoryPS(AdminDirectoryPS $adminDirectoryPS): Response
    {
        return $adminDirectoryPS->getList();
    }

    #[Route('/directory/tc', name: '_directory_tc')]
    public function getDirectoryTrainingCentre(AdminDirectoryTrainingCentre $adminDirectoryTrainingCentre): Response
    {
        return $adminDirectoryTrainingCentre->getList();
    }
}
