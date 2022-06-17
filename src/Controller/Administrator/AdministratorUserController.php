<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller\Administrator;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class AdministratorUserController extends AbstractController
{
    public function __construct(private ManagerRegistry $managerRegistry)
    {
    }

    public function getUserList(): Response
    {
        $user_list = $this->managerRegistry->getRepository(User::class)->getList();

        return $this->render(
            'administrator/user/list.html.twig',
            [
                'controller' => 'AdminUser',
                'user_list' => $user_list,
            ]
        );
    }
}
