<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller\Api;

use App\Entity\DocumentsVariables;
use App\Entity\Loger;
use App\Service\LoggerService;
use App\Service\UploadedFilesService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiDocumentVariablesController extends AbstractController
{
    use UploadedFilesService;
    use LoggerService;

    public function __construct(private ManagerRegistry $managerRegistry, private readonly ManagerRegistry $doctrine)
    {
    }

    public function add(): Response
    {
        $request = new Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);
        $data = $request->request->all();

        $fs = $this->managerRegistry->getRepository(DocumentsVariables::class)
            ->findOneBy(['name' => trim($data['name'])]);
        if (!empty($fs)) {
            return $this->json(['result' => 'error']);
        }

        $DV = new DocumentsVariables();
        $DV->setActive(true);
        $DV->setVariableValue(trim($data['variable_value']));
        $DV->setName($data['name']);
        $DV->setLinkedField($data['linked_field']);
        $DV->setComment(trim($data['comment']));
        $DV->setUsage($data['usage']);
        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($DV);
        $entityManager->flush();
        $lastId = $DV->getId();

        $this->logAction('add_document_variables', 'Переменные', 'Добавление переменной  '.$lastId.' '.$data['name']);

        return $this->json(['result' => 'success', 'id' => $lastId]);
    }

    public function update(): Response
    {
        $request = new Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);
        $data = $request->request->all();
        $fs = $this->managerRegistry->getRepository(DocumentsVariables::class)->find((int) $data['id']);
        $fs->setVariableValue(trim($data['variable_value']));
        $fs->setName($data['name']);
        $fs->setLinkedField($data['linked_field']);
        $fs->setComment(trim($data['comment']));
        $fs->setUsage($data['usage']);

        $this->logAction('update_document_variables', 'Переменные', $data['id'].' '.$data['name']);

        return $this->json(['result' => 'success', 'id' => $data['id']]);
    }

    public function hide(int $id)
    {
        $request = new Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);

        $fs = $this->doctrine->getRepository(DocumentsVariables::class)->find($id);
        $fs->setActive(false);

        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($fs);
        $entityManager->flush();

        $data = $this->doctrine->getRepository(DocumentsVariables::class)->find($id);

        $this->logAction('delete_document_variables', 'Переменные', $id.' '.$data->getName());

        return $this->json(['result' => 'success', 'id' => $id]);
    }
}
