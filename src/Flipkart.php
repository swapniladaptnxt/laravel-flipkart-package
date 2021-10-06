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
        $this->api_version = 'v3';
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

    public function filter(string $status = 'all', string $source = 'all', int $page = 1){
        return $this->call("/shipments/filter", [
            
            'source' => $source,
            'status' => $status,
            'page' => $page
        ]);
    }
    // order
    public function getOrderDetails($orderItemIds){
        try {
                if($orderItemIds){
                    $result=$this->_API->call(['URL'=>'/sellers/v2/orders/shipments?orderItemIds='.$orderItemIds, 'METHOD' => "GET",'RETURNARRAY'=>true]);
                    return $result;
                }
        } catch (Exception $e) {
            
        }
    }

    // stock
    public function updateInventory($data){
        try
        {
            $result=array();
            if($data){
                $result=$this->_API->call(['URL'=>'/sellers/listings/v3/update/inventory', 'METHOD' => "POST",'DATA'=>$data,'ALLDATA'=>true,'RETURNARRAY'=>true]);
            }
        }
        catch (Exception $e)
        {
            $result = $e->getMessage();
        }

        return $result;
    }

    // update stock

    public function updateApiInventory($sku=NULL,$location_id,$qty){
        try
        {
            $result=array();
            if($sku){
                $detail=$this->getSkudetail($sku);
                $locations=[['id'=>$location_id,'inventory'=>$qty]];
                $data=array();
                foreach ($detail as $key => $value) {
                    $data[$key]=['product_id'=>$value['product_id'],'locations'=>$locations];
                }
                $result=$this->_API->call(['URL'=>'/sellers/listings/v3/update/inventory', 'METHOD' => "POST",'DATA'=>$data,'ALLDATA'=>true,'RETURNARRAY'=>true]);
            }
        }
        catch (Exception $e)
        {
            $result = $e->getMessage();
        }

        return $result;
    }



}