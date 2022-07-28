<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller\Api;

use App\Entity\DocumentTemplates;
use App\Entity\Loger;
use App\Service\LoggerService;
use App\Service\UploadedFilesService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class apiDocumentsController extends AbstractController
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

        $fs = $this->managerRegistry->getRepository(DocumentTemplates::class)
            ->findOneBy(['template_name' => trim($data['dt-name']), 'delete' => 'true']);
        if (!empty($fs)) {
            return $this->json(['result' => 'error']);
        }

        $fileUploadedPath = $this->uploadFile('dt-formFile', DocumentTemplates::class);

        if (empty($fileUploadedPath)) {
            return $this->json(['result' => 'error']);
        }

        $DocumentTemplates = new DocumentTemplates();
        $DocumentTemplates->setActive((bool) $data['active']);
        $DocumentTemplates->setTemplateName(trim($data['dt-name']));
        $DocumentTemplates->setFileName($_FILES['dt-formFile']['name']);
        $DocumentTemplates->setLink($fileUploadedPath);
        $DocumentTemplates->setFileSize($this->checkedFile['filesize']);
        $DocumentTemplates->setComment(trim($data['dt-comment']));
        $DocumentTemplates->setDateCreate(new \DateTime());
        $DocumentTemplates->setAuthor($this->getUser()->getFullname());
        $DocumentTemplates->setDelete(false);
        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($DocumentTemplates);
        $entityManager->flush();
        $lastId = $DocumentTemplates->getId();

        $logger = new Loger();
        $logger->setTime(new \DateTime());
        $logger->setAction('add_document_templates');
        $logger->setUserLoger($this->getUser());
        $logger->setIp($request->server->get('REMOTE_ADDR'));
        $logger->setChapter('Шаблоны документов');
        $logger->setComment('Добавление шаблона документа  '.$lastId.' '.$data['dt-name']);
        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($logger);
        $entityManager->flush();

        return $this->json(['result' => 'success', 'id' => $lastId]);
    }

    public function update(): Response
    {
        $request = new Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);
        $data = $request->request->all();
        $fs = $this->managerRegistry->getRepository(DocumentTemplates::class)->find((int) $data['id']);
        $fs->setActive($data['active']);
        $fs->setTemplateName(trim($data['dt-name']));
        $fs->setComment(trim($data['dt-comment']));
        $fs->setDateChange(new \DateTime());

        $logger = new Loger();
        $logger->setTime(new \DateTime());
        $logger->setAction('update_document_templates');
        $logger->setUserLoger($this->getUser());
        $logger->setIp($request->server->get('REMOTE_ADDR'));
        $logger->setChapter('Шаблоны документов');
        $logger->setComment($data['id'].' '.$data['dt-name']);
        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($logger);
        $entityManager->flush();

        return $this->json(['result' => 'success', 'id' => $data['id']]);
    }

    public function hide(int $id)
    {
        $request = new Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);

        $fs = $this->doctrine->getRepository(DocumentTemplates::class)->find($id);
        $fs->setDelete(true);

        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($fs);
        $entityManager->flush();

        $data = $this->doctrine->getRepository(DocumentTemplates::class)->find($id);

        $loger = new Loger();
        $loger->setTime(new \DateTime());
        $loger->setAction('delete_document_templates');
        $loger->setUserLoger($this->getUser());
        $loger->setIp($request->server->get('REMOTE_ADDR'));
        $loger->setChapter('Шаблоны документов');
        $loger->setComment($id.' '.$data->getTemplateName());
        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($loger);
        $entityManager->flush();

        return $this->json(['result' => 'success', 'id' => $id]);
    }
}
