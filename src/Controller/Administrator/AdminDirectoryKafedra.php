<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller\Administrator;

use App\Entity\Kaferda;
use \App\Service\LinkService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminDirectoryKafedra extends AbstractController
{
    use LinkService;

    public function __construct(
        private ManagerRegistry $managerRegistry
    ) {
    }

    public function getList(): Response
    {
        $request = new Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);
        $page = $request->get('page') ?? null;
        $on_page =$request->get('on_page') ?? 5;

        $result = $this->managerRegistry->getRepository(Kaferda::class)->getList($page, $on_page);


        $count = $this->managerRegistry->getRepository(Kaferda::class)->findAll();
        $count = count($count);

        $page = $page ?? 1;
        return $this->render('administrator/directory/kafedra.html.twig',
            [
                'data' => $result,
                'pager' => [
                    'count_all_position' => $count,
                    'current_page' => $page,
                    'count_page' => (int)ceil($count / $on_page),
                    'paginator_link' => $this->getParinatorLink(),
                    'on_page' => $on_page
                ],
                'sort' => [
                    'sort_link' => $this->getSortLink(),
                    'current_sort' => $request->get('sort') ?? null,
                ]
            ]
        );
    }
}
