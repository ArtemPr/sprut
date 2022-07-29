<?php
/*
 * Created admhome <admhome@ya.ru> 2022.
 */

namespace App\Service;

// https://symfony.com/doc/current/http_client.html#basic-usage
use Symfony\Contracts\HttpClient\HttpClientInterface;

class Litera5API
{
    public function __construct(
        protected string $apiurl,
        protected string $apikey,
        protected string $company,
        protected string $login,
        protected HttpClientInterface $httpClient
    ) {
        $this->httpClient = $httpClient;
    }

    protected function prepareData(array $data): array
    {
        $currTs = time();

        $authData = [
            'time' => $currTs,
            'company' => $this->company,
            'login' => $this->login,
            'token' => $this->apikey,
        ];

        $data = array_merge($authData, $data, [
            'signature' => $this->getSignature($data),
        ]);

        return $data;
    }

    protected function getSignature(array $data): string
    {
        $strForHash = implode('', $data);

        return md5($strForHash);
    }

    protected function processedRequest(string $url, array $data): mixed
    {
        $data = $this->prepareData($data);

        $requestJson = json_encode($data, JSON_THROW_ON_ERROR);

        $response = $this->httpClient->request('POST', $this->apiurl.$url, [
            'headers' => [
                'Content-Type: application/json; charset=utf-8',
                'Accept: application/json',
            ],
            'body' => $requestJson,
        ]);

        if (201 !== $response->getStatusCode()) {
            throw new \Exception('Response status code is: '.$response->getStatusCode());
        }

        $responseJson = $response->getContent();

        return json_decode($responseJson, true, 512, JSON_THROW_ON_ERROR);
    }

    public function setCheck(array $data): mixed
    {
        return $this->processedRequest('/api/pub/check/', $data);
    }

    public function getResult(array $data): mixed
    {
        return $this->processedRequest('/api/pub/check-ogxt-results/', $data);
    }
}
