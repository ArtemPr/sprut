<?php
/*
 * Created admhome <admhome@ya.ru> 2022.
 */

namespace App\Service;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class AntiplagiatAPI
{
    protected \SoapClient $client;

    /**
     * Идентификатор реального пользователя во внешней системе.
     */
    protected int $extUserId;

    /**
     * Системный пользователь.
     */
    protected ?UserInterface $internalUser;

    /**
     * @param string $url
     * @param string $login
     * @param string $password
     * @param string $company_name
     * @param string $api_address
     *
     * @throws \SoapFault
     */
    public function __construct(
        protected string $url,
        protected string $login,
        protected string $password,
        protected string $company_name,
        protected string $api_address
    ) {
        $xmlEntityLoader = libxml_disable_entity_loader(false);

        $wdsl = sprintf(
            'https://%s/apiCorp/%s?singleWsdl',
            $this->api_address,
            $this->company_name
        );
        $this->client = new \SoapClient($wdsl, [
            'trace' => 1,
            'login' => $this->login,
            'password' => $this->password,
            'soap_version' => \SOAP_1_1,
            'features' => \SOAP_SINGLE_ELEMENT_ARRAYS,
        ]);

        libxml_disable_entity_loader($xmlEntityLoader);
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
    public function setExtUserId(int $extUserId): void
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

    public function checkFile()
    {
        //
    }

    /**
     * Проверить документ, получить ссылку на отчет на сайте "Antiplagiat"
     *
     * @param string $filename
     * @return array
     */
    public function getFileReport(string $filename): array
    {
        $data = $this->getDocData($filename);
        $uploadResilt = $this->client->UploadDocument([
            'data' => $data,
        ]);
        // Идентификатор документа.
        $docID = $uploadResilt->UploadDocumentResult->Uploaded[0]->Id;
        $this->client->CheckDocument([
            'docId' => $docID,
        ]);
        // Получить текущий статус последней проверки
        $docStatus = $this->client->GetCheckStatus([
            'docId' => $docID,
        ]);

        // Получить текущий статус последней проверки
        while ($docStatus->GetCheckStatusResult->Status === 'InProgress') {
            sleep($docStatus->GetCheckStatusResult->EstimatedWaitTime * 0.1);
            $docStatus = $this->client->GetCheckStatus([
                'docId' => $docID,
            ]);
        }

        $result = [
            'status' => false,
        ];

        if ($docStatus->GetCheckStatusResult->Status === 'Failed') {
            $result = [
                'status' => false,
                'error' => $docStatus->GetCheckStatusResult->FailDetails,
            ];

            return $result;
        }

        // Запросить формирование последнего полного отчета в формат PDF.
        $pdfReport = $this->client->ExportReportToPdf([
            'docId' => $docID,
        ]);

        while ($pdfReport->ExportReportToPdfResult->Status === 'InProgress') {
            sleep(max($pdfReport->ExportReportToPdfResult->EstimatedWaitTime, 10) * 0.1);
            $pdfReport = $this->client->ExportReportToPdf([
                'docId' => $docID,
            ]);
        }

        if ($pdfReport->ExportReportToPdfResult->Status === 'Failed') {
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
}
