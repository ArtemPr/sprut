<?php
/*
 * Created admhome <admhome@ya.ru> 2022.
 */

namespace App\Controller\Api;

use App\Entity\Discipline;
use App\Repository\LiteraRepository;
use App\Service\ApiService;
use App\Service\Litera5API;
use App\Service\LoggerService;
use App\Service\UploadedFilesService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ApiLitera extends AbstractController
{
    use ApiService;
    use UploadedFilesService;
    use LoggerService;

    public function __construct(
        private readonly LiteraRepository $literaRepository,
        private readonly ManagerRegistry $doctrine,
        protected Litera5API $litera5API
    ) {
    }

    public function add(): JsonResponse
    {
        $request = new Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);
        $data = $request->request->all();

        $discipline = null;

        if (empty($data['unique_discipline']) && !empty($data['discipline'])) {
            $discipline = $this->doctrine->getRepository(Discipline::class)->find($data['discipline']);
        }

        //

        $this->logAction(
            'add_litera',
            'Литера5',
            'Создание запроса '.$lastId
        );

        return $this->json(['result' => 'success', 'id' => $lastId]);
    }

    public function update(): JsonResponse
    {
        //

        $this->logAction(
            'update_litera',
            'Литера5',
            'Создание запроса '.$lastId
        );

        return $this->json(['result' => 'success', 'id' => $lastId]);
    }
}
