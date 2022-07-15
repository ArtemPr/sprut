<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller\Services;

use App\Controller\BaseController;
use App\Controller\BaseInterface;
use App\Entity\Antiplagiat;
use App\Entity\Discipline;
use App\Repository\AntiplagiatRepository;
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

        if (false === $full) {
            $data = $this->managerRegistry->getRepository(Antiplagiat::class)->getList($page, $on_page, $sort, $search);
            $count = $this->managerRegistry->getRepository(Antiplagiat::class)->getListAll($page, $on_page, $sort, $search);
        } else {
            $data = $this->managerRegistry->getRepository(Antiplagiat::class)->getList(0, 9999999999, $sort, $search);
            $count = $this->managerRegistry->getRepository(Antiplagiat::class)->getListAll(0, 9999999999, $sort, $search);
        }

        return [
            'data' => $data,
            'statuses' => $this->getActualStatuses(),
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

    #[Route('/service/antiplagiat', name: 'antiplagiat')]
    public function getList()
    {
        $auth = $this->getAuthValue($this->getUser(), 'auth_antiplagiat', $this->managerRegistry);
        if (!is_array($auth)) {
            return $auth;
        }

        $tpl = !empty($this->request->get('ajax'))
            ? 'services/antiplagiat/table.html.twig'
            : 'services/antiplagiat/index.html.twig';

        $result = $this->get();
        $result['auth'] = $auth;

        return $this->render($tpl,
            $result,
        );
    }

    #[Route('/service/antiplagiat_csv', name: 'antiplagiat_csv')]
    public function getCSV()
    {
        $result = $this->get(true);
        $table = '';

        foreach ($this->setTable() as $tbl) {
            $table .= '"'.$tbl[1].'";';
        }
        $table = substr($table, 0, -1)."\n";

        $data = $result['data'];

//        dd([
//            'table' => $table,
//            'data' => $data,
//        ]);

        foreach ($data as $val) {
            $table .= '"'.$val['id'].'";'.
                '"'.$val['file'].'";'.
                '"'.(!empty($val['discipline']) ? $val['discipline']['name'] : '-').'";'.
                '"'.(!empty($val['size']) ? $val['size'] : '-').'";'.
                '"'.(!empty($val['author']) ? $val['author']['fullname'] : '-').'";'.
                '"'.date_format($val['data_create'], 'd/m/Y H:i').'";'.
                '"'.(!empty($val['comment']) ? $val['comment'] : '-').'";'.
                '"'.(null !== $val['plagiat_percent'] ? $val['plagiat_percent'] : '-').'";'.
                '"'.(!empty($val['result_file']) ? $val['result_file'] : '-').'";'.
                '"'.(!empty($val['result_date']) ? date_format($val['result_date'], 'd/m/Y H:i') : '-').'"'."\n";
        }

        return $this->getCSVFile($table, 'antiplagiat.csv');
    }

    #[Route('/form/antiplagiat_edit/{id}', name: 'antiplagiat_edit')]
    public function getItemForm($id)
    {
        $disciplines = $this->managerRegistry->getRepository(Discipline::class)->getList(0, 9999999999);
        $data_out = $this->managerRegistry->getRepository(Antiplagiat::class)->get($id);

        return $this->render(
            'services/antiplagiat/form/update_form.html.twig',
            [
                'data' => $data_out[0] ?? null,
                'controller' => 'Antiplagiat',
                'disciplines' => $disciplines,
            ]
        );
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
            ['status', 'Статус', 'string', true],
            ['plagiat_percent', 'Заимствования', 'string', true],
            ['result_file', 'PDF', 'string', true],
            ['result_date', 'Проверено', 'string', true],
        ];
    }

    private function getActualStatuses(): array
    {
        return [
            AntiplagiatRepository::CHECK_STATUS_NEW => 'Новый',
            AntiplagiatRepository::CHECK_STATUS_NONE => 'Ожидание',
            AntiplagiatRepository::CHECK_STATUS_INPROGRESS => 'В работе',
            AntiplagiatRepository::CHECK_STATUS_READY => 'Готово',
            AntiplagiatRepository::CHECK_STATUS_FAILED => 'Ошибка',
        ];
    }
}
