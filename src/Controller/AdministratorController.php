<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller;

use App\Controller\Administrator\AdministratorRoleController;
use App\Controller\Administrator\AdministratorUserController;
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

}
