<?php

namespace Adaptnxt\Flipkart;

use GuzzleHttp\Client;


use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;

class Flipkart {
    
    private $access_token;
    private $api_version;
    private $api_base_url;

 
    public function __construct($access_token) {
        $this->access_token = $access_token;
        $this->api_version = 'v1';
        $this->api_base_url = config('flipkart.api_base_url');
    }

    public function getApiBaseUrl(  ) {
        return $this->api_base_url . "/" . $this->api_version;
    }

    public function call(string $endpoint, array $params = [], string $method = 'GET'){
        $params['access_token'] = $this->access_token;
        $client = new Client();
        return json_decode($client->request($method, $this->getApiBaseUrl() . $endpoint . '?' . http_build_query($params))->getBody()->getContents());

    }

    public function buildQuery(array $filters = []) {
        $queryFilters = [];
        foreach($filters as $key => $value){
            $queryFilters[] = "$key:$value";
        }
        return join("%20", $queryFilters);
    }

    

    public function getOrders(string $status = 'all', string $source = 'all', int $page = 1){
        return $this->call("/order/info", [
            'source' => $source,
            'status' => $status,
            'page' => $page
        ]);
    }

}