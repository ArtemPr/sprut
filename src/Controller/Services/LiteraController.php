<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller\Services;

use App\Controller\BaseController;
use App\Controller\BaseInterface;
use App\Entity\Discipline;
use App\Entity\Litera;
use App\Service\AuthService;
use App\Service\CSVHelper;
use App\Service\LinkService;
use Symfony\Component\Routing\Annotation\Route;

class LiteraController extends BaseController implements BaseInterface
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

        if (false === $full) {
            $data = $this->managerRegistry->getRepository(Litera::class)->getList($page, $on_page, $sort, $search);
            $count = $this->managerRegistry->getRepository(Litera::class)->getListAll($page, $on_page, $sort, $search);
        } else {
            $data = $this->managerRegistry->getRepository(Litera::class)->getList(0, 9999999999, $sort, $search);
            $count = $this->managerRegistry->getRepository(Litera::class)->getListAll(0, 9999999999, $sort, $search);
        }

        return [
            'data' => $data,
            'disciplines' => $this->managerRegistry->getRepository(Discipline::class)->getList(0, 9999999999),
            'search' => strip_tags($search) ?? '',
            'pager' => [
                'count_all_position' => $count,
                'current_page' => $page,
                'count_page' => (int) ceil($count / $on_page),
                'paginator_link' => $this->getParinatorLink(),
                'on_page' => $on_page,
            ],
            'sort' => [
                'sort_link' => $this->getSortLink(),
                'current_sort' => $this->get_data['sort'] ?? null,
            ],
            'search_link' => $this->getSearchLink(),
            'table' => $this->setTable(),
            'csv_link' => $this->getCSVLink(),
        ];
    }

    #[Route('/service/litera', name: 'litera')]
    public function getList()
    {
        $auth = $this->getAuthValue($this->getUser(), 'auth_litera', $this->managerRegistry);
        if (!is_array($auth)) {
            return $auth;
        }

        $tpl = !empty($this->request->get('ajax'))
            ? 'services/litera/table.html.twig'
            : 'services/litera/index.html.twig';

        $result = $this->get();
        $result['auth'] = $auth;

        return $this->render($tpl,
            $result,
        );
    }

    #[Route('/service/litera_csv', name: 'litera_csv')]
    public function getCSV()
    {
    }

    #[Route('/form/litera_edit/{id}', name: 'litera_edit')]
    public function getItemForm($id)
    {
        $disciplines = $this->managerRegistry->getRepository(Discipline::class)->getList(0, 9999999999, 'name');
        $data_out = $this->managerRegistry->getRepository(Litera::class)->get($id);

        return $this->render(
            'services/litera/form/update_form.html.twig',
            [
                'data' => $data_out[0] ?? null,
                'controller' => 'Litera',
                'disciplines' => $disciplines,
            ]
        );
    }

    private function setTable(): array
    {
        return [
            [
                'name' => 'status',
                'header' => '',
                'type' => 'string',
                'filter' => true,
                'show' => true,
                'sort' => true,
            ],
            [
                'name' => 'file',
                'header' => '????????',
                'type' => 'string',
                'filter' => true,
                'show' => true,
                'sort' => true,
            ],
            [
                'name' => 'discipline',
                'header' => '????????????????????',
                'type' => 'string',
                'filter' => true,
                'show' => true,
                'sort' => true,
            ],
            [
                'name' => 'size',
                'header' => '????????????',
                'type' => 'string',
                'filter' => true,
                'show' => true,
                'sort' => true,
            ],
            [
                'name' => 'author',
                'header' => '????????????????',
                'type' => 'string',
                'filter' => true,
                'show' => true,
                'sort' => true,
            ],
            [
                'name' => 'data_create',
                'header' => '??????????????',
                'type' => 'string',
                'filter' => true,
                'show' => true,
                'sort' => true,
            ],
            [
                'name' => 'doc_name',
                'header' => '???????????????? ??????????????????',
                'type' => 'string',
                'filter' => true,
                'show' => true,
                'sort' => true,
            ],
            [
                'name' => 'date_update',
                'header' => '??????????????????',
                'type' => 'string',
                'filter' => true,
                'show' => true,
                'sort' => true,
            ],
        ];
    }
}
