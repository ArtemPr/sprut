<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller\Services;

use App\Controller\BaseController;
use App\Controller\BaseInterface;

class AntiplagiatController extends BaseController implements BaseInterface
{
    public function getList()
    {
        return $this->render('services/antiplagiat/index.html.twig');
    }

    public function get()
    {
        // TODO: Implement get() method.
    }
}
