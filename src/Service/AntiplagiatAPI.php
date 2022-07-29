<?php
/*
 * Created admhome <admhome@ya.ru> 2022.
 */

namespace App\Service;

use App\Repository\AntiplagiatRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class AntiplagiatAPI
{
    protected \SoapClient $client;

    /**
     * Идентификатор реального пользователя во внешней системе.
     */
    protected string $extUserId;

    /**
     * Системный пользователь.
     */
    protected ?UserInterface $internalUser;

    /**
     * @throws \SoapFault
     */
    public function __construct(
        protected string $url,
        protected string $login,
        protected string $password,
        protected string $company_name,
        protected string $api_address,
        protected string $ext_user
    ) {
        $xmlEntityLoader = libxml_disable_entity_loader(false);

        $wdsl = sprintf(
            'https://%s/apiCorp/%s?singleWsdl',
            $this->api_address,
            $this->company_name
        );

        try {
            $this->client = new \SoapClient($wdsl, [
                'trace' => 1,
                'login' => $this->login,
                'password' => $this->password,
                'soap_version' => \SOAP_1_1,
                'features' => \SOAP_SINGLE_ELEMENT_ARRAYS,
            ]);
        } catch (\SoapFault $exception) {
            throw new \Exception('[ ] Antiplagiat connection error: '.$exception->getMessage());
        }

        libxml_disable_entity_loader($xmlEntityLoader);

        $this->setExtUserId($this->ext_user);
    }

    /**
     * Отладочные данные (предполагается использовать с dump() или dd()).
     *
     * @return array
     */
    public function soapDebug()
    {
        return [
            'requestHeaders' => $this->client->__getLastRequestHeaders() ?? '-',
            'request' => $this->client->__getLastRequest() ?? '-',
            'responseHeaders' => $this->client->__getLastResponseHeaders() ?? '-',
            'response' => $this->client->__getLastResponse() ?? '-',
        ];
    }

    public function getExtUserId(): int
    {
        return $this->extUserId;
    }

    /**
     * @param mixed $extUserId
     */
    public function setExtUserId(string $extUserId): void
    {
        $this->extUserId = $extUserId;
    }

    public function getInternalUser(): ?UserInterface
    {
        return $this->internalUser;
    }

    /**
     * @param mixed $internalUser
     */
    public function setInternalUser(?UserInterface $internalUser): void
    {
        $this->internalUser = $internalUser;
    }

    /**
     * Подготовка описания загружаемого файла.
     *
     * @return string[]
     */
    protected function getDocData(string $filename): array
    {
        if (!is_readable($filename)) {
            throw new NotFoundResourceException('[ERROR] Unreadable file "'.$filename.'"!');
        }

        return [
            'Data' => file_get_contents($filename),
            'FileName' => basename($filename),
            'FileType' => '.'.substr(strrchr($filename, '.'), 1),
            'ExternalUserID' => $this->extUserId,
        ];
    }

    /**
     * Подготовка атрибутов загружаемого файла.
     *
     * @return \array[][][]
     */
    protected function getDocAttributes(): array
    {
        $otherName = $this->internalUser->getUsername();

        if (!empty($this->internalUser->getPatronymic())) {
            $otherName .= ' '.$this->internalUser->getPatronymic();
        }

        return [
            'DocumentDescription' => [
                'Authors' => [
                    'AuthorName' => [
                        'OtherNames' => $otherName,
                        'Surname' => $this->internalUser->getSurname(),
                        'PersonIDs' => [
                            'CustomID' => 'original',
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Преобразовать int $docId из таблицы антиплагиата в объект для использования в сервисе антиплагиата.
     */
    public function getDocIdObject(int $docId): \stdClass
    {
        $docIdClass = new \stdClass();

        $docIdClass->Id = $docId;
        $docIdClass->External = null;

        return $docIdClass;
    }

    /**
     * Преобразовать статус для записи его в таблицу на нашей стороне.
     */
    public function getNumericStatus(string $status): int
    {
        return match ($status) {
            'None' => AntiplagiatRepository::CHECK_STATUS_NONE,
            'InProgress' => AntiplagiatRepository::CHECK_STATUS_INPROGRESS,
            'Ready' => AntiplagiatRepository::CHECK_STATUS_READY,
            'Failed' => AntiplagiatRepository::CHECK_STATUS_FAILED,
            default => 0,
        };
    }

    /**
     * Получить информацию о тарифе.
     */
    public function getTariffInfo(): array
    {
        $tarifInfo = $this->client->GetTariffInfo()->GetTariffInfoResult;

        $available_services = [];

        if (!empty($tarifInfo->CheckServices) && !empty($tarifInfo->CheckServices->CheckServiceInfo)) {
            foreach ($tarifInfo->CheckServices->CheckServiceInfo as $check_service) {
                $available_services[] = $check_service->Description.' (#'.$check_service->Code.')';
            }
        }

        return [
            'name' => $tarifInfo->Name,
            'subscriptionDate' => $tarifInfo->SubscriptionDate,
            'expiration_date' => $tarifInfo->ExpirationDate,
            'total_checks_count' => $tarifInfo->TotalChecksCount,
            'remained_checks_count' => $tarifInfo->RemainedChecksCount,
            'available_services' => $available_services,
        ];
    }

    /**
     * Загрузить файл в сервис антиплагиат.
     */
    public function uploadFile(string $filename): \stdClass
    {
        $data = $this->getDocData($filename);
        $uploadResult = $this->client->UploadDocument([
            'data' => $data,
        ]);
        // Идентификатор документа.
        $docID = $uploadResult->UploadDocumentResult->Uploaded[0]->Id;

        return $docID;
    }

    /**
     * Проверить документ в сервисе антиплагиат
     */
    public function checkDocument(\stdClass $docID): array
    {
        $this->client->CheckDocument([
            'docId' => $docID,
        ]);
        // Получить текущий статус последней проверки
        $docStatus = $this->client->GetCheckStatus([
            'docId' => $docID,
        ]);

        // Получить текущий статус последней проверки
        while ('InProgress' === $docStatus->GetCheckStatusResult->Status) {
            sleep($docStatus->GetCheckStatusResult->EstimatedWaitTime * 0.1);
            $docStatus = $this->client->GetCheckStatus([
                'docId' => $docID,
            ]);
        }

        $result = [
            'status' => $docStatus->GetCheckStatusResult->Status,
        ];

        if ('Failed' === $docStatus->GetCheckStatusResult->Status) {
            $result['error'] = $docStatus->GetCheckStatusResult->FailDetails;
        }

        return $result;
    }

    public function getShortReport(\stdClass $docID): \stdClass
    {
        $shortReport = $this->client->GetReportView([
            'docId' => $docID,
        ]);

        return $shortReport->GetReportViewResult->Summary;
    }

    /**
     * Проверить документ, получить ссылку на отчет на сайте "Antiplagiat".
     */
    public function getFileReport(\stdClass $docID): array
    {
        $pdfReport = $this->client->ExportReportToPdf([
            'docId' => $docID,
        ]);

        while ('InProgress' === $pdfReport->ExportReportToPdfResult->Status) {
            sleep(max($pdfReport->ExportReportToPdfResult->EstimatedWaitTime, 10) * 0.1);
            $pdfReport = $this->client->ExportReportToPdf([
                'docId' => $docID,
            ]);
        }

        if ('Failed' === $pdfReport->ExportReportToPdfResult->Status) {
            $result = [
                'status' => false,
                'error' => $pdfReport->ExportReportToPdfResult->FailDetails,
            ];

            return $result;
        }

        // Из образца кода, представленного системой "Антиплагиат":
        //
        // Получить ссылку на отчет на сайте "Антиплагиат"
        // ВНИМАНИЕ! Не гарантируется что данная ссылка будет работать вечно, она может перестать работать в любой момент,
        // поэтому нельзя давать ее пользователю. Нужно скачивать pdf себе и дальше уже управлять его временем жизни
        $result = [
            'status' => true,
            'reports' => [
                'pdf' => [
                    'number' => $pdfReport->ExportReportToPdfResult->ReportNum,
                    'link' => $this->url.$pdfReport->ExportReportToPdfResult->DownloadLink,
                ],
            ],
        ];

        return $result;
    }

    /**
     * Получить содержимое pdf-файла с отчётом.
     */
    public function downloadPdfReport(string $remoteFilename): string
    {
        $ch = curl_init($remoteFilename);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        return curl_exec($ch);
    }
}
