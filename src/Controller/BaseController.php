<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class BaseController extends AbstractController
{

    /**
     * @var ManagerRegistry
     */
    protected $managerRegistry;

    /**
     * @var Request
     */
    public $request;

    /**
     * @var array
     */
    protected $get_data;

    /**
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(
        ManagerRegistry $managerRegistry
    )
    {
        $this->managerRegistry = $managerRegistry;

        $this->request = new Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);

        $this->get_data = $this->request->query->all();

//        dd([
//            '$request' => $request ?? '-',
//            '$this->request' => $this->request ?? '-',
//            'get_data' => $this->get_data ?? '-',
//        ]);
    }

}
