<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller;

use App\Controller\Administrator\AdminDirectoryDiscipline;
use App\Controller\Administrator\AdminDirectoryKafedra;
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
    public function getOperationsForm($id, AdministratorOperationsController $administratorOperationsController): Response
    {
        return $administratorOperationsController->getOperationsForm($id);
    }

    #[Route('/user_edit/{id}', name: 'user_edit')]
    public function getUserForm($id, AdministratorUserController $administratorUserController): Response
    {
        return $administratorUserController->getUserForm($id);
    }

    #[Route('/discipline_edit/{id}', name: 'discipline_edit')]
    public function getDisciplineForm($id, AdminDirectoryDiscipline $adminDirectoryDiscipline): Response
    {
        return $adminDirectoryDiscipline->getDisciplineForm($id);
    }

    #[Route('/role_edit/{id}', name: 'role_edit')]
    public function getRoleForm($id, AdministratorRoleController $administratorRoleController): Response
    {
        return $administratorRoleController->getRoleForm((int)$id);
    }
}
