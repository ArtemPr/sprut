<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Service;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

trait ApiService
{
    public function __construct(
        private UserRepository $userRepository,
        private PasswordAuthenticatedUserInterface $user
    )
    {
    }

    public function convertJson(array $arr = [])
    {
        try {
            return \json_encode($arr, JSON_UNESCAPED_UNICODE);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    public function getAuth($role = 'ROLE_API_USER', $auth_type = '')
    {
        $request = new Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);
        $token = $request->get('t') ?? false;

        if (empty($token)) {
            return false;
        }

        $result = $this->doctrine->getRepository(User::class)->findBy(['apiHash'=>$token]);

        if (empty($result)) {
            return false;
        }

        $role_list = $result[0]->getRoles();

        if (!in_array($role, $role_list)) {
            return false;
        }
    }

}
