<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller\Api;

use App\Entity\Antiplagiat;
use App\Repository\AntiplagiatRepository;
use App\Entity\Loger;
use App\Repository\UserRepository;
use App\Service\ApiService;
use Doctrine\Persistence\ManagerRegistry;
use http\Env\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

class ApiAntiplagiat extends AbstractController
{
    use ApiService;

    public function __construct(
        private readonly AntiplagiatRepository $antiplagiatRepository,
        private readonly ManagerRegistry $doctrine
    ) {
    }

    public function add()
    {
        $request = new Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);
        $data = $request->request->all();

        dd($this->json(['$data' => $data]));
    }

    public function update()
    {
        $request = new Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);
        $data = $request->request->all();

        dd($this->json(['$data' => $data]));
    }
}
