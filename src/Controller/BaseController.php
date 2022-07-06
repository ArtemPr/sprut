<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BaseController extends AbstractController
{
    protected $managerRegistry;
    public function __construct(
        ManagerRegistry $managerRegistry
    )
    {
        $this->managerRegistry = $managerRegistry;
    }
}
