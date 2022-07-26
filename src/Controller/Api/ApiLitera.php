<?php
/*
 * Created admhome <admhome@ya.ru> 2022.
 */

namespace App\Controller\Api;

use App\Repository\LiteraRepository;
use App\Service\ApiService;
use App\Service\Litera5API;
use App\Service\LoggerService;
use App\Service\UploadedFilesService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

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
        //
    }

    public function update(): JsonResponse
    {
        //
    }
}
