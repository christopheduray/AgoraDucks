<?php

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\RequestOptions;
use Composer\CaBundle\CaBundle;


class Payconiq {

    public const  API_VERSION      = 'v3';
    public const  API_ENDPOINT     = 'https://api.payconiq.com/';
    public const  API_EXT_ENDPOINT = 'https://api.ext.payconiq.com/';
    private const TIMEOUT          = 10;
    private const CONNECT_TIMEOUT  = 2;

    private $apiKey;
    private $httpClient;
    private $useProd;

    public function __construct($key, $isProd){

        $this->httpClient = new Client([
            RequestOptions::TIMEOUT => self::TIMEOUT,
            RequestOptions::CONNECT_TIMEOUT => self::CONNECT_TIMEOUT,
            RequestOptions::VERIFY => CaBundle::getBundledCaBundlePath(),
        ]);

        $this->useProd=$isProd;

        $this->apiKey=$key;
    }

    public function requestPayment(array $ar)
    {
        try {
            $uri      = $this->getApiEndpointBase() . '/payments';
            $response = $this->httpClient->post(
                $uri,
                [
                    RequestOptions::HEADERS => [
                        'Authorization' => 'Bearer ' . $this->apiKey,
                    ],
                    RequestOptions::JSON => $ar,
                ]
            );
            return json_decode($response->getBody()->getContents());

        } catch (ClientException $e) {
            throw $e;
        }
    }

    public function getPaymentDetail(string $paymentId)
    {
        try {
            $uri      = $this->getApiEndpointBase() . '/payments/'.$paymentId;
            $response = $this->httpClient->get(
                $uri,
                [
                    RequestOptions::HEADERS => [
                        'Authorization' => 'Bearer ' . $this->apiKey,
                    ],
                ]
            );
            return json_decode($response->getBody()->getContents());

        } catch (ClientException $e) {
            throw $e;
        }

    }


    public function getApiEndpointBase(): string
    {
        return ($this->useProd ? self::API_ENDPOINT : self::API_EXT_ENDPOINT) . self::API_VERSION;
    }

}