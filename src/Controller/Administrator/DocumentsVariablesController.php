<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller\Administrator;

use App\Entity\DocumentsVariables;
use App\Service\AuthService;
use App\Service\CSVHelper;
use App\Service\LinkService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DocumentsVariablesController extends AbstractController
{
    use LinkService;
    use AuthService;
    use CSVHelper;

    public function __construct(private ManagerRegistry $managerRegistry)
    {
        $this->request = new Request($_GET);
    }

    public function getList(): Response
    {
        $auth = $this->getAuthValue($this->getUser(), 'auth_directory', $this->managerRegistry);
        if (!is_array($auth)) {
            return $auth;
        }

        $tpl = $this->request->get('ajax') ? 'administrator/document_variables/table.html.twig' : 'administrator/document_variables/index.html.twig';
        $result = $this->get();
        $result['auth'] = $auth;

        return $this->render($tpl,
            $result
        );
    }

    public function get(bool $full = false)
    {
        $page = $this->request->get('page') ?? null;
        $on_page = $this->request->get('on_page') ?? 25;
        $sort = $this->request->get('sort') ?? null;
        $search = $this->request->get('search') ?? null;

        if (false === $full) {
            $result = $this->managerRegistry->getRepository(DocumentsVariables::class)->getList($page, $on_page, $sort, $search);
            $count = $this->managerRegistry->getRepository(DocumentsVariables::class)->getListAll($page, $on_page, $sort, $search);
        } else {
            $result = $this->managerRegistry->getRepository(DocumentsVariables::class)->getList(0, 9999999999, $sort, $search);
            $count = $this->managerRegistry->getRepository(DocumentsVariables::class)->getListAll(0, 9999999999, $sort, $search);
        }

        $page = $page ?? 1;

        return [
            'data' => $result,
            'search' => strip_tags($search) ?? '',
            'controller' => 'document_variables',
            'pager' => [
                'count_all_position' => $count,
                'current_page' => $page,
                'count_page' => (int) ceil($count / $on_page),
                'paginator_link' => $this->getParinatorLink(),
                'on_page' => $on_page,
            ],
            'sort' => [
                'sort_link' => $this->getSortLink(),
                'current_sort' => $this->request->get('sort') ?? null,
            ],
            'search_link' => $this->getSearchLink(),
            'table' => $this->setTable(),
            'csv_link' => $this->getCSVLink(),
        ];
    }

    public function getCSV()
    {
        $result = $this->get(true);
        $data = [];

        $dataRow = [];
        foreach ($this->setTable() as $tbl) {
            $dataRow[] = $tbl['header'];
        }

        $data[] = $dataRow;

        foreach ($result['data'] as $val) {
            $data[] = [
                $val['active'] ? 'да' : 'нет',
                $val['id'],
                $val['template_name'],
                $val['file_name'],
                round($val['file_size'] / 1027, 2),
                $val['link'],
                $val['comment'],
                $val['date_create'] ? date_format($val['date_create'], 'd.m.Y H:i') : '-',
                $val['author'],
                $val['date_change'] ? date_format($val['date_create'], 'd.m.Y H:i') : '-',
            ];
        }

        return $this->processCSV($data, 'document_templates.csv');
    }

    public function getDocumentTemplatesForm($id): Response
    {
        $data_out = $this->managerRegistry->getRepository(DocumentsVariables::class)->get($id);

        return $this->render(
            'administrator/document_variables/form/update.html.twig',
            [
                'data' => $data_out[0] ?? null,
            ]
        );
    }

    private function setTable()
    {
        return [
            [
                'name' => 'active',
                'header' => 'Статус',
                'type' => 'bool',
                'filter' => false,
                'show' => true,
                'sort' => false,
            ],
            [
                'name' => 'id',
                'header' => 'ID',
                'type' => 'int',
                'filter' => true,
                'show' => true,
                'sort' => true,
            ],
            [
                'name' => 'variable_value',
                'header' => 'Значение переменной',
                'type' => 'string',
                'filter' => true,
                'show' => true,
                'sort' => true,
            ],
            [
                'name' => 'name',
                'header' => 'Название переменной',
                'type' => 'string',
                'filter' => true,
                'show' => true,
                'sort' => true,
            ],
            [
                'name' => 'linked_field',
                'header' => 'Привязанное поле',
                'type' => 'string',
                'filter' => true,
                'show' => true,
                'sort' => true,
            ],
            [
                'name' => 'comment',
                'header' => 'Комментарий',
                'type' => 'string',
                'filter' => true,
                'show' => true,
                'sort' => true,
            ],
            [
                'name' => 'usage',
                'header' => 'Использование',
                'type' => 'string',
                'filter' => true,
                'show' => true,
                'sort' => true,
            ],
        ];
    }
}
