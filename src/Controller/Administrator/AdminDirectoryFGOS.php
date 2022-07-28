<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller\Administrator;

use _PHPStan_59fb0a3b2\Nette\Utils\DateTime;
use App\Controller\BaseController;
use App\Entity\FederalStandart;
use App\Entity\FederalStandartCompetencies;
use App\Service\AuthService;
use App\Service\CSVHelper;
use App\Service\LinkService;
use Symfony\Component\HttpFoundation\Response;

class AdminDirectoryFGOS extends BaseController
{
    use LinkService;
    use AuthService;
    use CSVHelper;

    public function getList(): Response
    {
        $auth = $this->getAuthValue($this->getUser(), 'auth_directory', $this->managerRegistry);
        if(!is_array($auth)) {
            return $auth;
        }

        $tpl = $this->request->get('ajax') ? 'administrator/directory/fgos/fgos_table.html.twig' : 'administrator/directory/fgos/fgos.html.twig';
        $result = $this->get();
        $result['auth'] = $auth;

        return $this->render($tpl,
            $result
        );
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
                ($val['active'] ? 'да' : 'нет'),
                $val['short_name'],
                $val['name'],
            ];
        }

        return $this->processCSV($data, 'fgos.csv');
    }

    public function getFgosForm($id): Response
    {
        $data = $this->managerRegistry->getRepository(FederalStandart::class)->get($id);
        if(!empty($data)) {
            $data_competensions = $this->managerRegistry->getRepository(FederalStandartCompetencies::class)->findBy(
                ['federal_standart' => $id, 'delete' => false], ['id' => 'ASC']
            );
            $data['compenencions'] = $data_competensions;
        }
        if (empty($data['old_name']) && strstr($data['name'], 'утв.')) {
            $data['old_name'] = $data['name'];

            $str = $data['name'];
            $str = str_replace('Федеральный государственный образовательный стандарт', '', $str);
            if (strstr($str,'высшего образования по специальности')) {
                $data['type'] = 1;
            } elseif (strstr($str,'высшего образования - бакалавриат по направлению подготовки')) {
                $data['type'] = 2;
            } elseif (strstr($str,'высшего образования - специалитет по специальности')) {
                $data['type'] = 3;
            } elseif (strstr($str,'среднего профессионального образования по специальности')) {
                $data['type'] = 4;
            }

            $tmp = explode(' ', $data['short_name']);
            $data['code'] = end($tmp);

            preg_match('/N(.*?)\)$/i', $data['name'], $out);
            if(!empty($out[1])){
                $data['pr_num'] = trim($out[1]);
            }


            preg_match('/(\d)(\d).(\d)(\d).(\d)(\d)(\d)(\d)/i', $str, $out2);
            if(empty($out2)) {
                preg_match('/(\d).(\d)(\d).(\d)(\d)(\d)(\d)/i', $str, $out2);
                $date_create = explode('.', $out2[0]);
                $date_create[0] = '0' . $date_create[0];
            } else {
                $date_create = explode('.', $out2[0]);
            }
            $date_create = mktime(1,1,1, $date_create[1], $date_create[0], $date_create[2]);
            $data['date_create'] = new DateTime(date('r', $date_create));

            $str = str_replace($data['code'], '', $str);
            $str = str_replace('высшего образования по специальности', '', $str);
            $str = str_replace('высшего образования - бакалавриат по направлению подготовки', '', $str);
            $str = str_replace('высшего образования - специалитет по специальности', '', $str);
            $str = str_replace('среднего профессионального образования по специальности', '', $str);
            $data['name'] = trim(preg_replace('/\(утв.(.*?)$/','', $str));


        } else {
            $str = 'Федеральный государственный образовательный стандарт ';
            $type = [
                1 => 'высшего образования по специальности ',
                2 => 'высшего образования - бакалавриат по направлению подготовки ',
                3 => 'высшего образования - специалитет по специальности ',
                4 => 'среднего профессионального образования по специальности '
            ];
            $data['old_name'] = $str . $type[$data['type']] . $data['code'] . ' ' . $data['name'] . ' (утв. Приказом Минобрнауки России от ' . $data['date_create']->format('d.m.Y') . ' №' . $data['pr_num'] . ')';
        }

        if (!empty($data['date_create']))
        {
            $data['date_create'] = $data['date_create']->format(
                'Y-m-d'
            );
        }

        return $this->render(
            'administrator/directory/fgos/form/update.html.twig',
            [
                'data' => $data
            ]
        );
    }

    private function get(bool $full = false)
    {
        $page = $this->request->get('page') ?? null;
        $on_page = $this->request->get('on_page') ?? 25;
        $sort = $this->request->get('sort') ?? null;
        $search = $this->request->get('search') ?? null;

        if ($full === false) {
            $result = $this->managerRegistry->getRepository(FederalStandart::class)->getList($page, $on_page, $sort, $search);
            $count = $this->managerRegistry->getRepository(FederalStandart::class)->getListAll($page, $on_page, $sort, $search);
        } else {
            $result = $this->managerRegistry->getRepository(FederalStandart::class)->getList(0, 9999999999, $sort, $search);
            $count = $this->managerRegistry->getRepository(FederalStandart::class)->getListAll(0, 9999999999, $sort, $search);
        }

        $page = $page ?? 1;

        return [
            'data' => $result,
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
                'current_sort' => $this->request->get('sort') ?? null,
            ],
            'search_link' => $this->getSearchLink(),
            'table' => $this->setTable(),
            'csv_link' => $this->getCSVLink()
        ];
    }



    private function setTable()
    {
        return [
            [
                'name' => 'active',
                'header' => '',
                'type' => 'bool',
                'filter' => false,
                'show' => true,
                'sort' => false
            ],
            [
                'name' => 'id',
                'header' => 'ID',
                'type' => 'int',
                'filter' => true,
                'show' => true,
                'sort' => true
            ],
            [
                'name' => 'short_name',
                'header' => 'Короткое название',
                'type' => 'string',
                'filter' => true,
                'show' => false,
                'sort' => true
            ],
            [
                'name' => 'name',
                'header' => 'Название',
                'type' => 'string',
                'filter' => true,
                'show' => true,
                'sort' => true
            ],
            [
                'name' => 'code',
                'header' => 'Код',
                'type' => 'string',
                'filter' => true,
                'show' => true,
                'sort' => true
            ],
            [
                'name' => 'date_create',
                'header' => 'Дата утверждения',
                'type' => 'date',
                'filter' => true,
                'show' => false,
                'sort' => true
            ],
            [
                'name' => 'pr_num',
                'header' => 'Номер приказа',
                'type' => 'int',
                'filter' => true,
                'show' => false,
                'sort' => true
            ],
        ];
    }
}
