<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller\Administrator;

use App\Controller\BaseController;
use App\Controller\BaseInterface;
use App\Service\AuthService;
use App\Service\CSVHelper;
use App\Service\LinkService;
use Symfony\Component\Routing\Annotation\Route;

class DocumentsController extends BaseController implements BaseInterface
{
    use LinkService;
    use AuthService;
    use CSVHelper;

    #[Route('/administrator/document_templates', name: 'document_templates')]
    public function getList()
    {

        $auth = $this->getAuthValue($this->getUser(), 'auth_document_templates', $this->managerRegistry);
        if(!is_array($auth)) {
            return $auth;
        }

        return $this->render('/administrator/document_templates/index.html.twig', [
            'controller' => 'document_templates',
            'auth' => $auth
        ]);
    }

    public function get()
    {
        // TODO: Implement get() method.
    }

}
