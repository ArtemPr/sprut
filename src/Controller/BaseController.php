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
    protected $request;

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

        $this->request = new Request($_GET);

        $this->get_data = $this->request->query->all();
    }

}
