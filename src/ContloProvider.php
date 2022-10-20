<?php
namespace Flits\Contlo;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7;
use Flits\Contlo\ContloException;

class ContloProvider {
    public $BASE_URL = "https://api.contlo.com/<VERSION>";
    public $HEADERS;
    public $VERSION = 'v1';
    public $EXTRA_CONFIG;
    public $client;

    function __construct($config) {
        $this->HEADERS = $config['headers'] ?? []; // extra headers if you want to pass it in request
        $this->VERSION = $config['version'] ?? 'v1'; // version of the request as per contlo
        $this->EXTRA_CONFIG = $config['EXTRA_CONFIG'] ?? []; // Extra Guzzle/client config for api call
        $this->setupBaseURL();
        $this->setupClient();
    }

    function setupClient() {
        $config = [
            'base_uri' => $this->BASE_URL,
            'timeout' => 2.0,
            'curl' => [
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_2_0,
            ],
            'headers' => $this->HEADERS,
        ];
        $config = array_merge($config, $this->EXTRA_CONFIG);
        $this->client = new Client($config);
    }

    function setupBaseURL() {
        $this->setAPIVersion();
    }

    function setAPIVersion() {
        $this->BASE_URL = str_replace('<VERSION>', $this->VERSION, $this->BASE_URL);
    }

    function POST($payload) {
        try {
            $response = $this->client->request($this->METHOD, $this->URL, [
                'json' => $payload
            ]);
        } catch (RequestException $ex) {
            throw new ContloException($ex->getResponse()->getBody()->getContents(), $ex->getResponse()->getStatusCode());
        }
        if ($response->getStatusCode() != 200) {
            throw new ContloException($response->getBody()->getContents(), $response->getStatusCode());
        }
        return json_decode($response->getBody()->getContents());
    }
}