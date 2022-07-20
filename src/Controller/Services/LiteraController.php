<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller\Services;

use App\Controller\BaseController;
use App\Controller\BaseInterface;
use App\Service\AuthService;
use App\Service\CSVHelper;
use App\Service\LinkService;

class LiteraController extends BaseController implements BaseInterface
{
    use AuthService;
    use LinkService;
    use CSVHelper;

    public function get(bool $full = false)
    {
        // TODO: Implement get() method.
    }

    public function getList()
    {
        $auth = $this->getAuthValue($this->getUser(), 'auth_litera', $this->managerRegistry);
        if (!is_array($auth)) {
            return $auth;
        }

        $page = $this->request->get('page') ?? null;
        $on_page = $this->request->get('on_page') ?? 25;
        $sort = $this->request->get('sort') ?? null;
        $search = $this->request->get('search') ?? null;

        $data = [];

        $count = 0;

        $tpl = !empty($this->get_data['ajax'])
            ?
            'services/litera/table.html.twig'
            :
            'services/litera/ingex.html.twig';

        return $this->render($tpl,
            [
                'data' => $data,
                'auth' => $auth,
            ]
        );
    }

    public function getCSV()
    {
        //
    }

    private function setTable(): array
    {
        return [
        ];
    }
}
