<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller;

use App\Service\AuthService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    use AuthService;

    public function __construct(
        private ManagerRegistry $managerRegistry
    )
    {
    }

    #[Route('/', name: 'main')]
    public function getMain(ManagerRegistry $managerRegistry): Response
    {
        $auth = $this->getAuthValue($this->getUser(), 'auth_desktop', $this->managerRegistry);
        if (!is_array($auth)) {
            return $auth;
        }

        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('main/index.html.twig',
            [
                'auth' => $auth
            ]
        );
    }
}
