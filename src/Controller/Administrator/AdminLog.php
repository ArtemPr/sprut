<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller\Administrator;

use App\Controller\BaseController;
use App\Controller\BaseInterface;
use App\Entity\Loger;
use App\Service\AuthService;
use App\Service\CSVHelper;
use App\Service\LinkService;
use Symfony\Component\HttpFoundation\Response;

class AdminLog extends BaseController implements BaseInterface
{
    use AuthService;
    use LinkService;
    use CSVHelper;

    public function get(bool $full = false)
    {
        $page = $this->request->get('page') ?? null;
        $on_page = $this->request->get('on_page') ?? 25;
        $sort = $this->request->get('sort') ?? null;
        $search = $this->request->get('search') ?? null;

        $data_action = $this->managerRegistry->getRepository(Loger::class)->getActionList();
        $da_out = [];

        foreach ($data_action as $key => $value) {
            $da_out[$value['name']] = $value['value'];
        }

        if (false === $full) {
            $data = $this->managerRegistry->getRepository(Loger::class)->getList($page, $on_page, $sort, $search);
            $count = $this->managerRegistry->getRepository(Loger::class)->getListAll($page, $on_page, $sort, $search);
        } else {
            $data = $this->managerRegistry->getRepository(Loger::class)->getList(0, 9999999999, $sort, $search);
            $count = $this->managerRegistry->getRepository(Loger::class)->getListAll(0, 9999999999, $sort, $search);
        }

        return [
            'data' => $data,
            'data_action' => $da_out,
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

    public function getList(): Response
    {
        $auth = $this->getAuthValue($this->getUser(), 'auth_log', $this->managerRegistry);
        if (!is_array($auth)) {
            return $auth;
        }

        $tpl = !empty($this->request->get('ajax'))
            ? 'administrator/log/log_table.html.twig'
            : 'administrator/log/log.html.twig';

        $result = $this->get();
        $result['auth'] = $auth;

        return $this->render($tpl,
            $result,
        );
    }

    public function getCSV()
    {
        $result = $this->get(true);
        $dataAction = $result['data_action'] ?? [];
        $data = [];

        $dataRow = [];
        foreach ($this->setTable() as $tbl) {
            $dataRow[] = $tbl[1];
        }

        $data[] = $dataRow;

        foreach ($result['data'] as $val) {

//            dd([
//                '$dataRow' => $dataRow,
//                '$val' => $val,
//                '$result' => $result,
//            ]);

            $data[] = [
                $val['chapter'],
                'Администрирование',
                (!empty($val['action']) ? $dataAction[$val['action']] : '-'),
                (!empty($val['user_loger']) ? $val['user_loger']['fullname'] : '-'),
                (!empty($val['user_loger']) ? $val['user_loger']['email'] : '-'),
                (!empty($val['ip']) ? $val['ip'] : '-'),
                (!empty($val['time']) ? date_format($val['time'], 'd/m/Y H:i') : '-'),
                (!empty($val['comment']) ? $val['comment'] : '-'),
            ];
        }

        return $this->processCSV($data, 'log.csv');
    }

    private function setTable(): array
    {
        return [
            ['chapter', 'Раздел', 'string', true],
            ['action', 'Тип события', 'string', true],
            ['', 'Название события', 'string', true],
            ['user_loger.username', 'Инициатор события (ФИО)', 'string', true],
            ['user_loger.email', 'Электронная почта', 'string', true],
            ['ip', 'IP-адрес', 'string', true],
            ['time', 'Дата', 'string', true],
            ['comment', 'Комментарий', 'string', true],
        ];
    }
}
