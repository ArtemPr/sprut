<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller\Administrator;

use App\Entity\City;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdministratorUserController extends AbstractController
{
    public function __construct(private ManagerRegistry $managerRegistry)
    {
    }

    public function getUserList(): Response
    {
        $request = new Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);
        $page = $request->get('page') ?? null;
        $on_page = $request->get('on_page') ?? 25;
        $sort = $request->get('sort') ?? null;

        $user_list = $this->managerRegistry->getRepository(User::class)->getList();

        $city = $this->managerRegistry->getRepository(City::class)->getList();

        $tpl = $request->get('ajax') ? 'administrator/user/user_table.html.twig' : 'administrator/user/list.html.twig' ;

        return $this->render($tpl,
            [
                'controller' => 'AdminUser',
                'user_list' => $user_list,
                'city_list' => $city
            ]
        );
    }

    public function getUserForm($id)
    {
        $data = $this->managerRegistry->getRepository(User::class)->getUser($id);

        $city = $this->managerRegistry->getRepository(City::class)->getList();
        return $this->render(
            'administrator/user/form/update_form.html.twig',
            [
                'data' => $data[0] ?? [],
                'city_list' => $city
            ]
        );
    }
}
