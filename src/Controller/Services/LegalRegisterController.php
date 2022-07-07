<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller\Services;

use App\Controller\BaseController;
use App\Controller\BaseInterface;
use App\Service\AuthService;

class LegalRegisterController extends BaseController implements BaseInterface
{
    use AuthService;

    public function getList()
    {
        $auth = $this->getAuthValue($this->getUser(), 'auth_legalregister', $this->managerRegistry);
        if(!is_array($auth)) {
            return $auth;
        }

        $tpl = !empty($this->get_data['ajax'])
            ?
            'services/legal_register/table.html.twig'
            :
            'services/legal_register/index.html.twig';

        return $this->render($tpl,
            [
                'auth' => $auth,
            ]
        );
    }

    public function get()
    {
        // TODO: Implement get() method.
    }
}
