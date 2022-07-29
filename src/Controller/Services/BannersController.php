<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller\Services;

use App\Controller\BaseController;
use App\Controller\BaseInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BannersController extends BaseController implements BaseInterface
{

    #[Route('/service/banner', name: 'service_banners')]
    public function getList(): Response
    {
        return $this->render(
            'services/banners/index.html.twig',
            [
                'controller' => 'banners',
                'csv_link' => 'dfghjkl',
                'table' => $this->setTable()
            ]
        );
    }

    public function get()
    {
        // TODO: Implement get() method.
    }

    private function setTable(): array
    {
        return [];
    }
}
