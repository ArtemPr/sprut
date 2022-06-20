<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller\Administrator;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class FormController extends AbstractController
{
    #[Route('/form/edit_user', name: 'edit_user')]
    public function getUserForm()
    {
        return $this->render(
            'administrator/user/form/update_form.html.twig',
            [
                'controller' => 'AdminUser',
            ]
        );
    }
}
