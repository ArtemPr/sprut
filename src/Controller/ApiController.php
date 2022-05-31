<?php

namespace App\Controller;

use App\Entity\MasterProgram;
use App\Entity\TrainingCenters;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api')]
class ApiController extends AbstractController
{
    #[Route('/program_info', name: 'api_get_program_info', methods: ['GET'])]
    public function getProgramInfo(ManagerRegistry $doctrine)
    {
        $result = $doctrine->getRepository(MasterProgram::class)->getApiProgramInfo();

        return $this->render('api/index.html.twig', [
            'out' => $this->convertJson($result ?? ['error' => 'no results'])
        ]);
    }

    #[Route('/program_short', name: 'api_get_programs_short_list', methods: ['GET'])]
    public function getProgramsList(ManagerRegistry $doctrine): Response
    {
        $request = new Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);

        $page = (int)($request->get('page') ?? 1);
        if ($page > 0) {
            $page = $page - 1;
        } else {
            return $this->render('api/index.html.twig', ['out' => $this->convertJson(['error' => 'no results'])]);
        }
        $max_result = $request->get('max_result') ?? null;


        $result = $doctrine->getRepository(MasterProgram::class)->getApiList((int)$page, $max_result);

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
        $val = $doctrine->getRepository(MasterProgram::class)->findBy(['id' => $id]);

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

    #[Route('/training_centre_short', name: 'api_get_training_centre_short', methods: ['GET'])]
    public function getTrainingCentre(ManagerRegistry $doctrine): Response
    {
        $result = $doctrine->getRepository(TrainingCenters::class)->findAll();

        foreach ($result as $val) {
            $out[] = [
                'centre_id' => $val->getId(),
                'centre_name' => \mb_convert_encoding($val->getName(), 'utf8'),
            ];
        }

        return $this->render('api/index.html.twig', [
            'out' => $this->convertJson($out ?? ['error' => 'no results'])
        ]);
    }

    private function convertJson(array $arr = [])
    {
        try {
            return \json_encode($arr, JSON_UNESCAPED_UNICODE);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}
