<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller\Administrator;

use App\Controller\BaseController;
use App\Controller\BaseInterface;
use App\Service\AuthService;
use App\Service\CSVHelper;
use App\Service\LinkService;
use Symfony\Component\Routing\Annotation\Route;

class DocumentsController extends BaseController implements BaseInterface
{
    use LinkService;
    use AuthService;
    use CSVHelper;

    #[Route('/administrator/document_templates', name: 'document_templates')]
    public function getList()
    {

        $auth = $this->getAuthValue($this->getUser(), 'auth_document_templates', $this->managerRegistry);
        if (!is_array($auth)) {
            return $auth;
        }

        return $this->render('/administrator/document_templates/index.html.twig', [
            'controller' => 'document_templates',
            'auth' => $auth,
            'table' => $this->setTable(),
            // тестовые данные
            'data' => [
                [
                    'active' => true,
                    'id' => '1',
                    'name' => 'Name',
                    'link' => 'http://axiomabio.com/pdf/test.pdf',
                    'comment' => 'lorem ipsum',
                    'date_create' => '20.12.2021',
                    'author' => 'Вася Иванов'
                ],
                [
                    'active' => false,
                    'id' => '2',
                    'name' => 'Name2',
                    'link' => 'https://tablericons.com/',
                    'comment' => 'lorem ipsum dolorem',
                    'date_create' => '15.12.2021',
                    'author' => 'Петя Петров'
                ]]
            // тестовые данные
        ]);
    }

    public function get()
    {
        // TODO: Implement get() method.

    }

    // временно для таблицы
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
                'sort' => false
            ],
            [
                'name' => 'name',
                'header' => 'Название шаблона',
                'type' => 'string',
                'filter' => true,
                'show' => true,
                'sort' => false
            ],
            [
                'name' => 'link',
                'header' => 'Ссылка на скачивание',
                'type' => 'string',
                'filter' => false,
                'show' => true,
                'sort' => false
            ],
            [
                'name' => 'comment',
                'header' => 'Комментарий',
                'type' => 'string',
                'filter' => true,
                'show' => true,
                'sort' => false
            ],
            [
                'name' => 'date_create',
                'header' => 'Дата загрузки',
                'type' => 'date',
                'filter' => true,
                'show' => true,
                'sort' => false
            ],
            [
                'name' => 'author',
                'header' => 'Кто разместил',
                'type' => 'string',
                'filter' => true,
                'show' => true,
                'sort' => false
            ],
        ];
    }
    // временно для таблицы
}
