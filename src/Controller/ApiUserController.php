<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller;

use App\Repository\UserRepository;
use App\Service\ApiService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api')]
class ApiUserController extends AbstractController
{
    use ApiService;

    public function __construct(
        private UserRepository $userRepository,
        private ManagerRegistry $doctrine
    )
    {
    }

    /**
     * @param $id
     * @return Response
     */
    #[Route('/user/{id}', name: 'api_get_user', methods: ['GET'])]
    public function getUserInfo($id): Response
    {
        if ($this->getAuth('api_get_user') === false) {
            return $this->json(['error'=>'error auth']);
        }

        $result = $this->userRepository->find($id);
        return $this->json($result ?? []);
    }

    /**
     * @return Response
     */
    #[Route('/user', name: 'api_get_user_list', methods: ['GET'])]
    public function getUserList(): Response
    {
        $param = [];

        if ($this->getAuth('api_get_users') === false) {
            return $this->json(['error'=>'error auth']);
        }

        $request = new Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);


        $result = $this->userRepository->findBy($param);
        return $this->json($result ?? []);
    }
}
