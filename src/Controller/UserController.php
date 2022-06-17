<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/user', name: 'user')]
    public function user(): Response
    {
        return $this->render('user/index.html.twig');
    }

    #[Route('/role', name: 'role_v')]
    public function role(): Response
    {
        return $this->render('user/role.html.twig');
    }

    #[Route('/operation', name: 'operation_v')]
    public function operation(): Response
    {
        return $this->render('user/operation.html.twig');
    }
}
