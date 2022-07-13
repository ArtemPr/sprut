<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller\Api;

use App\Entity\Antiplagiat;
use App\Entity\Discipline;
use App\Entity\Loger;
use App\Repository\AntiplagiatRepository;
use App\Service\AntiplagiatAPI;
use App\Service\ApiService;
use App\Service\UploadedFilesService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ApiAntiplagiat extends AbstractController
{
    use ApiService;
    use UploadedFilesService;

    protected $checkedFile;

    public function __construct(
        private readonly AntiplagiatRepository $antiplagiatRepository,
        private readonly ManagerRegistry $doctrine,
        protected AntiplagiatAPI $antiplagiatAPI
    ) {
    }

    public function add(): JsonResponse
    {
        $request = new Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);
        $data = $request->request->all();
//        $files = $request->files->all();

        $discipline = null;
        $intDocId = 0;
        $intStatus = AntiplagiatRepository::CHECK_STATUS_NEW;
        $pdfReport = null;
        $plagiatPercents = null;

        if (!empty($data['discipline'])) {
            $discipline = $this->doctrine->getRepository(Discipline::class)->find($data['discipline']);
        }

        $fileUploadedPath = $this->uploadFile('file', Antiplagiat::class);

        // документ загружен к нам в хранилище
        $antiplagiat = new Antiplagiat();
        $antiplagiat->setAuthor($this->getUser());
        $antiplagiat->setDiscipline($discipline);
        $antiplagiat->setComment($data['comment']);
        $antiplagiat->setDataCreate(new \DateTime());
        $antiplagiat->setStatus($intStatus);
        $antiplagiat->setFile($fileUploadedPath);
        $antiplagiat->setSize($this->checkedFile['filesize']);

        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($antiplagiat);
        $entityManager->flush();
        $lastId = $antiplagiat->getId();

        $loger = new Loger();
        $loger->setTime(new \DateTime());
        $loger->setAction('add_antiplagiat');
        $loger->setUserLoger($this->getUser());
        $loger->setIp($request->server->get('REMOTE_ADDR'));
        $loger->setChapter('Антиплагиат');
        $loger->setComment('Создание запроса '.$lastId);
        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($loger);
        $entityManager->flush();

        // попробуем отдать документ антиплагиату и посмотреть, что из этого таки получится
        $docID = $this->antiplagiatAPI->uploadFile($this->checkedFile['dirname'].'/'.$this->checkedFile['basename']);
        $intDocId = $docID->Id ?? 0;

        if (!empty($docID)) {
            $checkResult = $this->antiplagiatAPI->checkDocument($docID);
            $intStatus = $this->antiplagiatAPI->getNumericStatus($checkResult['status']);
        }

        if (AntiplagiatRepository::CHECK_STATUS_NEW != $intStatus
            ?? AntiplagiatRepository::CHECK_STATUS_FAILED != $intStatus
        ) {
            $shortReport = $this->antiplagiatAPI->getShortReport($docID);
            $plagiatPercents = $shortReport->DetailedScore->Plagiarism;

            $pdfReportProcess = $this->antiplagiatAPI->getFileReport($docID);

            if (true == $pdfReportProcess['status']) {
                $pdfReportFileName = $pdfReportProcess['reports']['pdf']['link'];

                $pdfReport = $this->downloadFile(
                    $this->antiplagiatAPI->downloadPdfReport($pdfReportProcess['reports']['pdf']['link']),
                    Antiplagiat::class,
                    $intDocId
                );
            }
        }

        $antiplagiat = $this->doctrine->getRepository(Antiplagiat::class)->find($lastId);
        $antiplagiat->setStatus($intStatus);
        $antiplagiat->setDocId($intDocId);

        if (!empty($pdfReport)) {
            $antiplagiat->setResultFile($pdfReport);
        }

        if (null !== $plagiatPercents) {
            $antiplagiat->setPlagiatPercent($plagiatPercents);
        }

        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($antiplagiat);
        $entityManager->flush();

        // logger? add_antiplagiat update_antiplagiat

        return $this->json(['result' => 'success', 'id' => $lastId]);
    }

    public function update(): JsonResponse
    {
        //
        // файл не обновляем
        //
        $request = new Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);
        $data = $request->request->all();

        $discipline = null;

        if (!empty($data['discipline'])) {
            $discipline = $this->doctrine->getRepository(Discipline::class)->find($data['discipline']);
        }

//        dd($this->json([
//            '$data' => $data,
//        ]));

        $antiplagiat = $this->doctrine->getRepository(Antiplagiat::class)->find($data['id']);

        $antiplagiat->setAuthor($this->getUser());
        $antiplagiat->setDiscipline($discipline);
        $antiplagiat->setComment($data['comment']);

        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($antiplagiat);
        $entityManager->flush();

        $loger = new Loger();
        $loger->setTime(new \DateTime());
        $loger->setAction('update_antiplagiat');
        $loger->setUserLoger($this->getUser());
        $loger->setIp($request->server->get('REMOTE_ADDR'));
        $loger->setChapter('Антиплагиат');
        $loger->setComment('Обновление запроса '.$data['id']);
        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($loger);
        $entityManager->flush();

        return $this->json(['result' => 'success', 'id' => $data['id']]);
    }
}
