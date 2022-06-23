<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller;

use App\Entity\MasterProgram;
use App\Entity\ProgramType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ProgramController extends AbstractController
{
    public function __construct(
        private ManagerRegistry $managerRegistry
    )
    {
    }

    #[Route('/program', name: 'program')]
    public function program(): Response
    {
        $request = new Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);
        $page = $request->get('page') ?? null;
        $on_page = $request->get('on_page') ?? 25;
        $sort = $request->get('sort') ?? null;

        if ($sort != null) {
            $sort = ['order'=>[$sort => "DESC"]];
        }

        $program_type = $this->managerRegistry->getRepository(ProgramType::class)->findAll();
        $program_list = $this->managerRegistry->getRepository(MasterProgram::class)->getProgramList((int)$page, (int)$on_page, $sort);
        $count = $this->managerRegistry->getRepository(MasterProgram::class)->getApiProgramInfo();


        return $this->render(
            '/program/index.html.twig',
            [
                'data' => $program_list,
                'program_type' => $program_type
            ]
        );
    }
}
