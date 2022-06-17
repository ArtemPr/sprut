<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller\Administrator;

use App\Entity\Roles;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class AdministratorRoleController extends AbstractController
{
    public function __construct(private ManagerRegistry $managerRegistry)
    {

    }

    public function getRoleList(): Response
    {

        $user_list = $this->managerRegistry->getRepository(Roles::class)->getList();
        return $this->render(
            'administrator/role/list.html.twig',
            ['role_list' => $user_list,]
        );
    }
}
