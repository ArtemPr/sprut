<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller\Administrator;

use App\Entity\Kaferda;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminDirectoryKafedra extends AbstractController
{

    public function __construct(
        private ManagerRegistry $managerRegistry
    ) {
    }

    public function getList(): Response
    {

        $request = new Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);
        $page = $request->get('page') ?? null;
        $on_page =$request->get('on_page') ?? 25;

        $result = $this->managerRegistry->getRepository(Kaferda::class)->getList($page, $on_page);


        $count = $this->managerRegistry->getRepository(Kaferda::class)->findAll();
        $count = count($count);

        $page = $page ?? 1;
        return $this->render('administrator/directory/kafedra.html.twig',
            [
                'data' => $result,
                'pager' => [
                    'count_all_position' => $count,
                    'current_page' => $page+1,
                    'count_page' => (int)ceil($count / $on_page)
                ]
            ]
        );
    }
}
