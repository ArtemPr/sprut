<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller;

use App\Controller\Administrator\AdminDirectoryKafedra;
use App\Controller\Administrator\AdminDirectoryTrainingCentre;
use App\Entity\City;
use App\Entity\User;
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
}
