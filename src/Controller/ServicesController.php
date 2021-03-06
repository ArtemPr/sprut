<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller;

use App\Controller\Services\AntiplagiatController;
use App\Controller\Services\LegalRegisterController;
use App\Controller\Services\LiteraController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/service', name: 'service')]
class ServicesController extends AbstractController
{
    #[Route('/antiplagiat', name: '_antiplagiat')]
    public function getAntiplagiat(AntiplagiatController $antiplagiatController): Response
    {
        return $antiplagiatController->getList();
    }

    #[Route('/litera', name: '_litera')]
    public function getLitera(LiteraController $literaController): Response
    {
        return $literaController->getList();
    }

    #[Route('/legalregister', name: '_legalregister')]
    public function getLegalRegister(LegalRegisterController $legalRegisterController): Response
    {
        return $legalRegisterController->getList();
    }
}
