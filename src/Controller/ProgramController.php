<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller;

use App\Entity\MasterProgram;
use App\Entity\ProgramType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
        $program_type = $this->managerRegistry->getRepository(ProgramType::class)->findAll();
        $program_list = $this->managerRegistry->getRepository(MasterProgram::class)->getProgramList();
        return $this->render(
            '/program/index.html.twig',
            [
                'data' => $program_list,
                'program_type' => $program_type
            ]
        );
    }
}
