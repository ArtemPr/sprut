<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller\Api;

use App\Repository\MasterProgramRepository;
use App\Service\ApiService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiProgramController extends AbstractController
{
    use ApiService;

    /**
     * @param MasterProgramRepository $master_programm
     * @param ManagerRegistry         $doctrine
     */
    public function __construct(
        private MasterProgramRepository $master_programm,
        private ManagerRegistry $doctrine
    ) {
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse|Response
     */
    public function getProgramInfo()
    {
        if (false === $this->getAuth('ROLE_API_USER', 'api_get_program_info')) {
            return $this->json(['error' => 'error auth']);
        }

        $result = $this->master_programm->getApiProgramInfo();

        return $this->render('api/index.html.twig', [
            'out' => $this->convertJson($result ?? ['error' => 'no results']),
        ]);
    }

    public function getProgramsList(): Response
    {
        if (false === $this->getAuth('ROLE_API_USER', 'api_get_programs_list')) {
            return $this->json(['error' => 'error auth']);
        }

        $request = new Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);

        $page = (int) ($request->get('page') ?? 1);
        if ($page > 0) {
            $page = $page - 1;
        } else {
            return $this->render('api/index.html.twig', ['out' => $this->convertJson(['error' => 'no results'])]);
        }
        $max_result = $request->get('max_result') ?? null;

        $param = $request->get('param') ?? null;

        $result = $this->master_programm->getProgramList((int) $page, $max_result, $param);

        foreach ($result as $val) {
            $out[] = [
                'program_id' => $val['id'] ?? false,
                'unique_id' => $val['id'] ?? false,
                'program_name' => \html_entity_decode($val['name'] ?? ''),
                'length_hour' => $val['length_hour'] ?? false,
                'length_week' => $val['length_week'] ?? false,
            ];
        }

        return $this->render('api/index.html.twig', [
            'out' => $this->convertJson($out ?? ['error' => 'no results']),
        ]);
    }

    public function getProgram(int $id): Response
    {
        if (false === $this->getAuth('ROLE_API_USER', 'api_get_program')) {
            return $this->json(['error' => 'error auth']);
        }

        $out = $this->master_programm->getProgram($id);

        return $this->render('api/index.html.twig', [
            'out' => $this->convertJson($out ?? ['error' => 'no results']),
        ]);
    }
}
