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
}
