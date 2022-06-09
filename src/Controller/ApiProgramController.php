<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller;

use App\Entity\User;
use App\Repository\MasterProgramRepository;
use App\Service\ApiService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/api', name: 'api')]
class ApiProgramController  extends AbstractController
{
    use ApiService;

    public function __construct(
        private MasterProgramRepository $master_programm,
        private ManagerRegistry $doctrine
    )
    {
    }

    #[Route('/program_info', name: 'api_get_program_info', methods: ['GET'])]
    public function getProgramInfo()
    {
        if ($this->getAuth() === false) {
            return $this->json(['error'=>'error auth']);
        }

        $result = $this->master_programm->getApiProgramInfo();

        return $this->render('api/index.html.twig', [
            'out' => $this->convertJson($result ?? ['error' => 'no results'])
        ]);
    }

    #[Route('/program', name: 'api_get_programs_list', methods: ['GET'])]
    public function getProgramsList(): Response
    {
        if ($this->getAuth() === false) {
            return $this->json(['error'=>'error auth']);
        }

        $request = new Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);

        $page = (int)($request->get('page') ?? 1);
        if ($page > 0) {
            $page = $page - 1;
        } else {
            return $this->render('api/index.html.twig', ['out' => $this->convertJson(['error' => 'no results'])]);
        }
        $max_result = $request->get('max_result') ?? null;

        $param = $request->get('param') ?? null;

        $result = $this->master_programm->getApiList((int)$page, $max_result, $param);


        foreach ($result as $val) {
            $out[] = [
                'program_id' => $val['id'] ?? false,
                'unique_id' => $val['id'] ?? false,
                'program_name' => \html_entity_decode($val['name'] ?? ''),
                'length_hour' => $val['length_hour'] ?? false,
                'length_week' => $val['length_week'] ?? false,
                'program_type' => $val['program_type'] ?? false
            ];
        }

        return $this->render('api/index.html.twig', [
            'out' => $this->convertJson($out ?? ['error' => 'no results'])
        ]);
    }


    #[Route('/program/{id}', name: 'api_get_program', methods: ['GET'])]
    public function getProgram(ManagerRegistry $doctrine, int $id): Response
    {
        if ($this->getAuth() === false) {
            return $this->json(['error'=>'error auth']);
        }

        $val = $this->master_programm->findBy(['id' => $id]);

        if (!empty($val[0])) {
            $val = $val[0];
            $out[] = [
                'program_id' => $val->getId() ?? false,
                'unique_id' => $val->getId() ?? false,
                'program_name' => \html_entity_decode($val->getName() ?? ''),
                'length_hour' => $val->getLengthHour() ?? false,
                'length_week' => $val->getLengthWeek() ?? false,
                'program_type' => $val->getProgramType() ?? false
            ];
        }

        return $this->render('api/index.html.twig', [
            'out' => $this->convertJson($out ?? ['error' => 'no results'])
        ]);
    }

}
