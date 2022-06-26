<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller\Administrator;

use App\Entity\Kaferda;
use App\Entity\TrainingCenters;
use App\Entity\User;
use \App\Service\LinkService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminDirectoryKafedra extends AbstractController
{
    use LinkService;

    public function __construct(
        private ManagerRegistry $managerRegistry
    )
    {
    }

    public function getList(): Response
    {
        $request = new Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);
        $page = $request->get('page') ?? null;
        $on_page = $request->get('on_page') ?? 25;
        $sort = $request->get('sort') ?? null;

        $result = $this->managerRegistry->getRepository(Kaferda::class)->getList($page, $on_page, $sort);

        // Считаем кол-во позиций всего
        // @TODO перенести findAll в метод репозитория
        $count = $this->managerRegistry->getRepository(Kaferda::class)->findAll();
        $count = count($count);

        $training_centre = $this->managerRegistry->getRepository(TrainingCenters::class)->getList();
        $user = $this->managerRegistry->getRepository(User::class)->getList();

        // Сводим ФИО в одно поле
        foreach ($result as $key => $val) {
            $director_name = ($result[$key]['director']['username'] ?? '') . ' ' . ($result[$key]['director']['surname'] ?? '') . ' ' . ($result[$key]['director']['patronymic'] ?? '');
            $result[$key]['director']['username'] = $director_name;
            unset($result[$key]['director']['surname'], $result[$key]['director']['patronymic']);
        }

        // Колонки таблицы (заготовка для последующего расширения функционала для сортировки колонок и изменения их состава)
        /**
         * Порядок элементов в массиве = порядок в таблице
         * [
         *  'name', - имя поля в таблице
         *  'имя поля', - имя поля для таблицы
         *  'string|list|bool', - тип поля (строка|список если используется ассоциации|булево значение (красная/зеленая точка))
         *  'true|false', - показывать/не показывать поле
         * ]
         */
        $table = [
            ['id', 'ID', 'string', true],
            ['training_centre.name', 'УЦ', 'string', true],
            ['name', 'Название', 'string', true],
            ['director.username', 'ФИО руководителя', 'string', true],
            ['director.email', 'E-mail руководителя', 'string', true]
        ];

        // Фикс страниц
        $page = $page ?? 1;

        $tpl = $request->get('ajax') ? 'administrator/directory/kafedra_table.html.twig' : 'administrator/directory/kafedra.html.twig' ;

        return $this->render($tpl,
            [
                'data' => $result,
                'traning_centre' => $training_centre,
                'user'  => $user,
                'pager' => [
                    'count_all_position' => $count,
                    'current_page' => $page,
                    'count_page' => (int)ceil($count / $on_page),
                    'paginator_link' => $this->getParinatorLink(),
                    'on_page' => $on_page
                ],
                'sort' => [
                    'sort_link' => $this->getSortLink(),
                    'current_sort' => $request->get('sort') ?? null,
                ],
                'table' => $table

            ]
        );
    }


    public function getKafedraForm($id)
    {
        $training_centre = $this->managerRegistry->getRepository(TrainingCenters::class)->getList();
        $user = $this->managerRegistry->getRepository(User::class)->getList();

        $data_out = $this->managerRegistry->getRepository(Kaferda::class)->find($id);

        return $this->render(
            'administrator/directory/form/kafedra_update.html.twig',
            [
                'data' => $data_out,
                'controller' => 'AdminKafedra',
                'traning_centre' => $training_centre,
                'user'  => $user,
            ]
        );
    }
}
