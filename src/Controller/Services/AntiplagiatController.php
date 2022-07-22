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
use App\Service\AntiplagiatAPI;
use App\Service\AuthService;
use App\Service\CSVHelper;
use App\Service\LinkService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class AntiplagiatController extends BaseController implements BaseInterface
{
    use AuthService;
    use LinkService;
    use CSVHelper;

    public function __construct(ManagerRegistry $managerRegistry, Security $security, AntiplagiatAPI $antiplagiatAPI)
    {
        parent::__construct($managerRegistry, $security);
        $this->security = $security;
        $this->antiplagiatAPI = $antiplagiatAPI;
    }

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
            'statuses_colors' => $this->getStatusesColors(),
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

        $tarif = $this->antiplagiatAPI->getTariffInfo();
        $result['tarif'] = [
            'check_left' => null == $tarif['remained_checks_count'] ? '&infin;' : $tarif['remained_checks_count'],
            'check_total' => null == $tarif['total_checks_count'] ? '&infin;' : $tarif['total_checks_count'],
            'check_expired' => $tarif['expiration_date'],
        ];

        return $this->render($tpl,
            $result,
        );
    }

    #[Route('/service/antiplagiat_csv', name: 'antiplagiat_csv')]
    public function getCSV()
    {
        $result = $this->get(true);
        $data = [];

        $dataRow = [];
        foreach ($this->setTable() as $tbl) {
            $dataRow[] = $tbl['name'];
        }

        $data[] = $dataRow;

        if (!empty($result['data'])) {
            foreach ($result['data'] as $val) {
                $data[] = [
                    $this->getActualStatuses()[$val['status']],
                    $val['file'],
                    (!empty($val['discipline']) ? $val['discipline']['name'] : '-'),
                    (!empty($val['size']) ? number_format($val['size'], 2, ',', ' ') : '-'),
                    (!empty($val['author']) ? $val['author']['fullname'] : '-'),
                    date_format($val['data_create'], 'd/m/Y H:i'),
                    (!empty($val['comment']) ? $val['comment'] : '-'),
                    (null !== $val['plagiat_percent'] ? number_format($val['plagiat_percent'], 2, ',', ' ') : '-'),
                    (!empty($val['result_file']) ? $val['result_file'] : '-'),
                    (!empty($val['result_date']) ? date_format($val['result_date'], 'd/m/Y H:i') : '-'),
                ];
            }
        }

        return $this->processCSV($data, 'antiplagiat.csv');
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
                'header' => 'Название файла',
                'type' => 'string',
                'filter' => true,
                'show' => true,
                'sort' => true,
            ],
            [
                'name' => 'discipline',
                'header' => 'Дисциплина',
                'type' => 'string',
                'filter' => true,
                'show' => true,
                'sort' => true,
            ],
            [
                'name' => 'size',
                'header' => 'Размер, кб',
                'type' => 'string',
                'filter' => true,
                'show' => true,
                'sort' => true,
            ],
            [
                'name' => 'author',
                'header' => 'Загрузил',
                'type' => 'string',
                'filter' => true,
                'show' => true,
                'sort' => true,
            ],
            [
                'name' => 'data_create',
                'header' => 'Создано',
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
                'name' => 'plagiat_percent',
                'header' => 'Заимствования, %',
                'type' => 'string',
                'filter' => true,
                'show' => true,
                'sort' => true,
            ],
            [
                'name' => 'result_file',
                'header' => 'PDF',
                'type' => 'string',
                'filter' => true,
                'show' => true,
                'sort' => true,
            ],
            [
                'name' => 'result_date',
                'header' => 'Проверено',
                'type' => 'string',
                'filter' => true,
                'show' => true,
                'sort' => true,
            ],
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

    private function getStatusesColors(): array
    {
        return [
            AntiplagiatRepository::CHECK_STATUS_NEW => 'bg-muted',
            AntiplagiatRepository::CHECK_STATUS_NONE => 'bg-muted',
            AntiplagiatRepository::CHECK_STATUS_INPROGRESS => 'bg-yellow',
            AntiplagiatRepository::CHECK_STATUS_READY => 'bg-green',
            AntiplagiatRepository::CHECK_STATUS_FAILED => 'bg-red',
        ];
    }
}
