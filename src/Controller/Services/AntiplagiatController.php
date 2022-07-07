<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller\Services;

use App\Controller\BaseController;
use App\Controller\BaseInterface;
use App\Service\AuthService;
use App\Service\LinkService;

class AntiplagiatController extends BaseController implements BaseInterface
{
    use AuthService;
    use LinkService;

    public function getList()
    {
        $auth = $this->getAuthValue($this->getUser(), 'auth_litera', $this->managerRegistry);
        if (!is_array($auth)) {
            return $auth;
        }

        $page = $this->get_data['page'] ?? null;
        $on_page = $this->get_data['on_page'] ?? 25;
        $sort = $this->get_data['sort'] ?? null;
        $search = $this->get_data['search'] ?? null;

        $data = [];

        $count = 0;

        $tpl = !empty($this->get_data['ajax'])
            ?
            'services/antiplagiat/table.html.twig'
            :
            'services/antiplagiat/index.html.twig';

        return $this->render($tpl,
            [
                'data' => $data,
                'search' => strip_tags($search) ?? '',
                'auth' => $auth,
                'pager' => [
                    'count_all_position' => $count,
                    'current_page' => $page,
                    'count_page' => (int)ceil($count / $on_page),
                    'paginator_link' => $this->getParinatorLink(),
                    'on_page' => $on_page
                ],
                'sort' => [
                    'sort_link' => $this->getSortLink(),
                    'current_sort' => $this->get_data['sort'] ?? null,
                ],
                'search_link' => $this->getSearchLink(),
                'table' => $this->setTable(),
                'csv_link' => $this->getCSVLink()
            ]
        );
    }

    public function get()
    {
        // TODO: Implement get() method.
    }

    private function setTable(): array
    {
        return [
            ['status', '', 'bool', true],
            ['file', 'Файл', 'string', true],
            ['discipline', 'Дисциплина', 'string', true],
            ['size', 'Размер', 'string', true],
            ['author', 'Загрузил', 'string', true],
            ['data_create', 'Создано', 'string', true],
            ['comment', 'Комментарий', 'string', true],
            ['plagiat_percent', 'Заимствования', 'string', true],
            ['result_file', 'PDF', 'string', true],
            ['result_date', 'Проверено', 'string', true]
        ];
    }
}
