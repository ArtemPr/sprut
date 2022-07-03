<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Service;


use App\Entity\Roles;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;

trait AuthService
{
    public function getAuthValue($user, string $value, ManagerRegistry $managerRegistry)
    {
        $roles = $user->getRoles();

        $out = $managerRegistry->getRepository(Roles::class)->findBy(
          ['roles_alt'=>$roles]
        );

        $auth_value = [];
        foreach ($out as $val) {
            $auth_value = $auth_value + unserialize($val->getAuthList());
        }

        if(empty($auth_value[$value])) {
            $response = new Response('Доступ запрещен');
            $response->headers->set('HTTP/1.1 403', 'Forbidden');
            return $response;
        }
    }
}
