<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller\Services;

use App\Controller\BaseController;
use App\Controller\BaseInterface;
use App\Entity\Antiplagiat;
use App\Entity\Discipline;
use App\Service\AuthService;
use App\Service\CSVHelper;
use App\Service\LinkService;
use Symfony\Component\Routing\Annotation\Route;

class AntiplagiatController extends BaseController implements BaseInterface
{
    use AuthService;
    use LinkService;
    use CSVHelper;

    public function get(bool $full = false)
    {
        $page = $this->get_data['page'] ?? null;
        $on_page = $this->get_data['on_page'] ?? 25;
        $sort = $this->get_data['sort'] ?? null;
        $search = $this->get_data['search'] ?? null;

        if ($full === false) {
            $data = $this->managerRegistry->getRepository(Antiplagiat::class)->getList($page, $on_page, $sort, $search);
            $count = $this->managerRegistry->getRepository(Antiplagiat::class)->getListAll($page, $on_page, $sort, $search);
        } else {
            $data = $this->managerRegistry->getRepository(Antiplagiat::class)->getList(0, 9999999999, $sort, $search);
            $count = $this->managerRegistry->getRepository(Antiplagiat::class)->getListAll(0, 9999999999, $sort, $search);
        }

        return [
            'data' => $data,
            'disciplines' => $this->managerRegistry->getRepository(Discipline::class)->getList(0, 9999999999),
            'search' => strip_tags($search) ?? '',
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
        ];
    }

    #[Route('/service/antiplagiat', name: 'antiplagiat')]
    public function getList()
    {
        $auth = $this->getAuthValue($this->getUser(), 'auth_litera', $this->managerRegistry);
        if (!is_array($auth)) {
            return $auth;
        }

        $tpl = !empty($this->get_data['ajax'])
            ? 'services/antiplagiat/table.html.twig'
            : 'services/antiplagiat/index.html.twig';

        $result = $this->get();
        $result['auth'] = $auth;

        return $this->render($tpl,
            $result,
        );
    }

    public function getItemForm($id)
    {
        // edit

        $data = '';
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
