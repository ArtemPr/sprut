<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller\Administrator;

use App\Entity\Kaferda;
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

        // Сводим ФИО в одно поле
        foreach ($result as $key => $val) {
            $director_name = ($result[$key]['director']['username'] ?? '') . ' ' . ($result[$key]['director']['surname'] ?? '') . ' ' . ($result[$key]['director']['patronymic'] ?? '');
            $result[$key]['director']['username'] = $director_name;
            unset($result[$key]['director']['surname'], $result[$key]['director']['patronymic']);
        }

        // Колонки таблицы (заготовка для последующего расширения функционала для сортировки колонок и изменения их состава)
        $table = [
            ['id', 'ID'],
            ['training_centre.name', 'УЦ'],
            ['name', 'Название'],
            ['director.username', 'ФИО руководителя'],
            ['director.email', 'E-mail руководителя']
        ];

        // Фикс страниц
        $page = $page ?? 1;

        return $this->render('administrator/directory/kafedra.html.twig',
            [
                'data' => $result,
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
}
