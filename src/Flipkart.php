<?php

namespace Adaptnxt\Flipkart;

use GuzzleHttp\Client;

class Flipkart
{

    private $access_token;
    private $api_version;
    private $api_base_url;

    public function __construct($access_token)
    {
        $this->access_token = $access_token;
        $this->api_version  = 'v3';
        $this->api_base_url = config('flipkart.api_base_url');
    }

    public function getApiBaseUrl()
    {
        return $this->api_base_url . "/" . $this->api_version;
    }

    public function call(string $endpoint, array $params = [], string $method = 'GET')
    {
        $params['access_token'] = $this->access_token;
        $client                 = new Client();
        return json_decode($client->request($method, $this->getApiBaseUrl() . $endpoint . '?' . http_build_query($params))->getBody()->getContents());

    }

    public function buildQuery(array $filters = [])
    {
        $queryFilters = [];
        foreach ($filters as $key => $value) {
            $queryFilters[] = "$key:$value";
        }
        return join("%20", $queryFilters);
    }

    public function getOrders(string $status = 'all', string $source = 'all', int $page = 1)
    {
        return $this->call("/order/info", [
            'source' => $source,
            'status' => $status,
            'page'   => $page,
        ]);
    }

    public function filter(string $status = 'all', string $source = 'all', int $page = 1)
    {
        return $this->call("/shipments/filter", [

            'source' => $source,
            'status' => $status,
            'page'   => $page,
        ]);
    }
    // order label
    public function getOrderLabelDetails($orderItemIds)
    {
        try {
            if ($orderItemIds) {
                $result = $this->_API->call(['URL' => '/sellers/v2/orders/labels?orderItemIds=' . $orderItemIds, 'METHOD' => "GET", 'RETURNARRAY' => true]);
                return $result;
            }
        } catch (Exception $e) {

        }
    }

    // order label create
    public function addOrderLabel($data)
    {
        try
        {
            $result = array();
            if ($data) {
                $result = $this->_API->call(['URL' => '/sellers/listings/v2/orders/labels', 'METHOD' => "POST", 'DATA' => $data, 'ALLDATA' => true, 'RETURNARRAY' => true]);
            }
        } catch (Exception $e) {
            $result = $e->getMessage();
        }

        return $result;
    }

    // order
    public function getOrderDetails($orderItemIds)
    {
        try {
            if ($orderItemIds) {
                $result = $this->_API->call(['URL' => '/sellers/v2/orders/shipments?orderItemIds=' . $orderItemIds, 'METHOD' => "GET", 'RETURNARRAY' => true]);
                return $result;
            }
        } catch (Exception $e) {

        }
    }
    // Shipment invoice
    public function getshipmentInvoice($shipmentIds)
    {
        try {
            if ($shipmentIds) {
                $result = $this->_API->call(['URL' => '/sellers/v3/shipments?shipmentIds/invoices=' . $shipmentIds, 'METHOD' => "GET", 'RETURNARRAY' => true]);
                return $result;
            }
        } catch (Exception $e) {

        }
    }

    // Shipment Dispatch

    public function shipmentDispatch($sku = null, $location_id, $shipmentIds)
    {
        try
        {
            $result = array();
            if ($sku) {
                $detail    = $this->getSkudetail($shipmentIds);
                $locations = [['id' => $location_id, 'shipmentId' => $shipmentIds]];
                $data      = array();
                foreach ($detail as $key => $value) {
                    $data[$key] = ['product_id' => $value['product_id'], 'locations' => $locations];
                }
                $result = $this->_API->call(['URL' => '/sellers/listings/v3/shipments/dispatch', 'METHOD' => "POST", 'DATA' => $data, 'ALLDATA' => true, 'RETURNARRAY' => true]);
            }
        } catch (Exception $e) {
            $result = $e->getMessage();
        }

        return $result;
    }
    // The get vendor details
    public function getVendorDetails($location_id)
    {
        try {
            if ($location_id) {
                $result = $this->_API->call(['URL' => '/sellers/v3/shipments/handover/counts?locationId=' . $location_id, 'METHOD' => "GET", 'RETURNARRAY' => true]);
                return $result;
            }
        } catch (Exception $e) {

        }
    }
    // manifest PDF
    public function manifestPDF($vendorGroupCode, $location_id, $isMps,$pickUpDate_after,$pickUpDate_before)
    {
        try
        {
            $result = array();
            if ($sku) {
                $vendorGroupCode    = $this->getVendordetail($vendorGroupCode);
                $locations = [['id' => $location_id, 'isMps' => $isMps]];
                $data      = array();
                foreach ($vendorGroupCode as $key => $value) {
                    $data[$key] = ['product_id' => $value['product_id'], 'locations' => $locations];
                }
                $result = $this->_API->call(['URL' => '/sellers/listings/v3/shipments/manifest', 'METHOD' => "POST", 'DATA' => $data, 'ALLDATA' => true, 'RETURNARRAY' => true]);
            }
        } catch (Exception $e) {
            $result = $e->getMessage();
        }

        return $result;
    }
    // Self Ship dispatched
    public function selfShipDispatch($tentativeDeliveryDate, $shipmentId, $dispatchDate,$orderItems)
    {
        try
        {
            $result = array();
            if ($sku) {
                $shipments  = $this->getShipmentdetail($shipmentId);
                $orderItems = [['id' => $orderItems]];
                $data      = array();
                foreach ($shipments as $key => $value) {
                    $data[$key] = ['shipmentId' => $value['shipmentId'], 'orderItems' => $orderItems];
                }
                $result = $this->_API->call(['URL' => '/sellers/listings/v2/shipments/dispatch', 'METHOD' => "POST", 'DATA' => $data, 'ALLDATA' => true, 'RETURNARRAY' => true]);
            }
        } catch (Exception $e) {
            $result = $e->getMessage();
        }

        return $result;
    }

    //  Self Ship  delivery
    public function selfShipDelivery($deliveryDate, $shipmentId, $locationId)
    {
        try
        {
            $result = array();
            if ($sku) {
                $shipments  = $this->getShipmentdetail($shipmentId);
                $locationId = [['id' => $locationId]];
                $data      = array();
                foreach ($shipments as $key => $value) {
                    $data[$key] = ['shipmentId' => $value['shipmentId'], 'locationId' => $locationId];
                }
                $result = $this->_API->call(['URL' => '/sellers/listings/v3/shipments/selfShip/delivery', 'METHOD' => "POST", 'DATA' => $data, 'ALLDATA' => true, 'RETURNARRAY' => true]);
            }
        } catch (Exception $e) {
            $result = $e->getMessage();
        }

        return $result;
    }

    // stock
    public function updateInventory($data)
    {
        try
        {
            $result = array();
            if ($data) {
                $result = $this->_API->call(['URL' => '/sellers/listings/v3/update/inventory', 'METHOD' => "POST", 'DATA' => $data, 'ALLDATA' => true, 'RETURNARRAY' => true]);
            }
        } catch (Exception $e) {
            $result = $e->getMessage();
        }

        return $result;
    }

    // update stock

    public function updateApiInventory($sku = null, $location_id, $qty)
    {
        try
        {
            $result = array();
            if ($sku) {
                $detail    = $this->getSkudetail($sku);
                $locations = [['id' => $location_id, 'inventory' => $qty]];
                $data      = array();
                foreach ($detail as $key => $value) {
                    $data[$key] = ['product_id' => $value['product_id'], 'locations' => $locations];
                }
                $result = $this->_API->call(['URL' => '/sellers/listings/v3/update/inventory', 'METHOD' => "POST", 'DATA' => $data, 'ALLDATA' => true, 'RETURNARRAY' => true]);
            }
        } catch (Exception $e) {
            $result = $e->getMessage();
        }

        return $result;
    }

}
