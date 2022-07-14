<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Controller\Api;

use App\Entity\Antiplagiat;
use App\Entity\Discipline;
use App\Entity\User;
use App\Repository\AntiplagiatRepository;
use App\Service\AntiplagiatAPI;
use App\Service\ApiService;
use App\Service\LoggerService;
use App\Service\UploadedFilesService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ApiAntiplagiat extends AbstractController
{
    use ApiService;
    use UploadedFilesService;
    use LoggerService;

    protected $checkedFile;

    public function __construct(
        private readonly AntiplagiatRepository $antiplagiatRepository,
        private readonly ManagerRegistry $doctrine,
        protected AntiplagiatAPI $antiplagiatAPI
    ) {
    }

    public function processItem(Antiplagiat $antiplagiatEntity, string $rootDir): bool
    {
        $intStatus = AntiplagiatRepository::CHECK_STATUS_NEW;
        $pdfReport = null;
        $plagiatPercents = null;

        // попробуем отдать документ антиплагиату и посмотреть, что из этого таки получится
        $docID = $this->antiplagiatAPI->uploadFile($rootDir.$antiplagiatEntity->getFile());
        $intDocId = $docID->Id ?? 0;

        if (!empty($docID)) {
            $checkResult = $this->antiplagiatAPI->checkDocument($docID);
            $intStatus = $this->antiplagiatAPI->getNumericStatus($checkResult['status']);
        }

        if (AntiplagiatRepository::CHECK_STATUS_NEW != $intStatus
            && AntiplagiatRepository::CHECK_STATUS_FAILED != $intStatus
        ) {
            $shortReport = $this->antiplagiatAPI->getShortReport($docID);
            $plagiatPercents = $shortReport->DetailedScore->Plagiarism;

            $pdfReportProcess = $this->antiplagiatAPI->getFileReport($docID);

            if (true == $pdfReportProcess['status']) {
                $pdfReport = $this->downloadFile(
                    $this->antiplagiatAPI->downloadPdfReport($pdfReportProcess['reports']['pdf']['link']),
                    Antiplagiat::class,
                    $intDocId,
                    $rootDir
                );
            }
        }

        $antiplagiatEntity->setStatus($intStatus);
        $antiplagiatEntity->setDocId($intDocId);

        if (AntiplagiatRepository::CHECK_STATUS_NEW != $intStatus
            && AntiplagiatRepository::CHECK_STATUS_FAILED != $intStatus
        ) {
            $antiplagiatEntity->setResultDate(new \DateTime());
        }

        if (!empty($pdfReport)) {
            $antiplagiatEntity->setResultFile($pdfReport);
        }

        if (null !== $plagiatPercents) {
            $antiplagiatEntity->setPlagiatPercent($plagiatPercents);
        }

        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($antiplagiatEntity);
        $entityManager->flush();

        $this->logAction(
            'update_data_into_antiplagiat',
            $this->doctrine->getRepository(User::class)->find(['id' => 54]),
            'Антиплагиат',
            'Отправка данных в Антиплагиат, id: '.$antiplagiatEntity->getId()
        );

        return true;
    }

    public function add(): JsonResponse
    {
        $request = new Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);
        $data = $request->request->all();

        $discipline = null;

        if (empty($data['unique_discipline']) && !empty($data['discipline'])) {
            $discipline = $this->doctrine->getRepository(Discipline::class)->find($data['discipline']);
        }

        $fileUploadedPath = $this->uploadFile('file', Antiplagiat::class);

        // документ загружен к нам в хранилище
        $antiplagiat = new Antiplagiat();
        $antiplagiat->setAuthor($this->getUser());
        $antiplagiat->setDiscipline($discipline);
        $antiplagiat->setComment($data['comment']);
        $antiplagiat->setDataCreate(new \DateTime());
        $antiplagiat->setStatus(AntiplagiatRepository::CHECK_STATUS_NEW);
        $antiplagiat->setFile($fileUploadedPath);
        $antiplagiat->setSize($this->checkedFile['filesize']);

        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($antiplagiat);
        $entityManager->flush();
        $lastId = $antiplagiat->getId();

        $this->logAction(
            'add_antiplagiat',
            $this->getUser(),
            'Антиплагиат',
            'Создание запроса '.$lastId
        );

        // сразу обработать файлы для Антиплагиата
//        $this->processItem($antiplagiat, $_SERVER['DOCUMENT_ROOT']);

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

        if (empty($data['unique_discipline']) && !empty($data['discipline'])) {
            $discipline = $this->doctrine->getRepository(Discipline::class)->find($data['discipline']);
        }

        $antiplagiat = $this->doctrine->getRepository(Antiplagiat::class)->find($data['id']);

        $antiplagiat->setAuthor($this->getUser());
        $antiplagiat->setDiscipline($discipline);
        $antiplagiat->setComment($data['comment']);

        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($antiplagiat);
        $entityManager->flush();

        $this->logAction(
            'update_antiplagiat',
            $this->getUser(),
            'Антиплагиат',
            'Обновление запроса '.$data['id']
        );

        return $this->json(['result' => 'success', 'id' => $data['id']]);
    }
}
