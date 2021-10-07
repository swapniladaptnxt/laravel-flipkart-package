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
                $result = $this->_API->call(['URL' => '/sellers/v2/orders/labels', 'METHOD' => "POST", 'DATA' => $data, 'ALLDATA' => true, 'RETURNARRAY' => true]);
            }
        } catch (Exception $e) {
            $result = $e->getMessage();
        }

        return $result;
    }

    // order search
    public function OrderSearch($data)
    {
        try
        {
            $result = array();
            if ($data) {
                $result = $this->_API->call(['URL' => '/sellers/v2/orders/search', 'METHOD' => "POST", 'DATA' => $data, 'ALLDATA' => true, 'RETURNARRAY' => true]);
            }
        } catch (Exception $e) {
            $result = $e->getMessage();
        }

        return $result;
    }
    //   order dispatch

    public function orderDispatch($sku = null, $orderItems)
    {
        try
        {
            $result = array();
            if ($sku) {
                $detail = $this->getSkudetail($orderItems);
                $orders = [['orderItemId' => $orderItems]];
                $data   = array();
                foreach ($detail as $key => $value) {
                    $data[$key] = ['orderItemId' => $value['orderItemId']];
                }
                $result = $this->_API->call(['URL' => '/sellers/v2/orders/dispatch', 'METHOD' => "POST", 'DATA' => $data, 'ALLDATA' => true, 'RETURNARRAY' => true]);
            }
        } catch (Exception $e) {
            $result = $e->getMessage();
        }

        return $result;
    }
    // order shipments
    public function getOrderShipments($orderItemIds)
    {
        try {
            if ($orderItemIds) {
                $result = $this->_API->call(['URL' => '/sellers/v2/orders/shipments?orderItemIds=' . $orderItemIds, 'METHOD' => "GET", 'RETURNARRAY' => true]);
                return $result;
            }
        } catch (Exception $e) {

        }
    }
    //label request
    public function getLabelRequest($requestId)
    {
        try {
            if ($requestId) {
                $result = $this->_API->call(['URL' => '/sellers/v3/orders/labelRequest?requestId=' . $requestId, 'METHOD' => "GET", 'RETURNARRAY' => true]);
                return $result;
            }
        } catch (Exception $e) {

        }
    }

//    Get details of order item
    public function getOrderItemDetails($order_item_id)
    {
        try {
            if ($order_item_id) {
                $result = $this->_API->call(['URL' => '/sellers/v2/orders?order_item_id =' . $order_item_id, 'METHOD' => "GET", 'RETURNARRAY' => true]);
                return $result;
            }
        } catch (Exception $e) {

        }
    }
//    Get details of order items
    public function getDetailsOrderItems($orderItemIds)
    {
        try {
            if ($order_item_id) {
                $result = $this->_API->call(['URL' => '/sellers/v2/orders?orderItemIds =' . $orderItemIds, 'METHOD' => "GET", 'RETURNARRAY' => true]);
                return $result;
            }
        } catch (Exception $e) {

        }
    }
    // Download manifest PDF
    public function getDownloadManifestPdf($data)
    {
        try {
            if ($data) {
                $result = $this->_API->call(['URL' => '/sellers/v2/orders/manifest', 'METHOD' => "GET", 'RETURNARRAY' => true]);
                return $result;
            }
        } catch (Exception $e) {

        }
    }

    // Cancel order items
    public function cancelOrderItems($sku = null, $orderItems)
    {
        try
        {
            $result = array();
            if ($sku) {
                $detail = $this->getSkudetail($orderItems);
                $orders = [['orderItemId' => $orderItems]];
                $data   = array();
                foreach ($detail as $key => $value) {
                    $data[$key] = ['orderItemId' => $value['orderItemId']];
                }
                $result = $this->_API->call(['URL' => '/sellers/v2/orders/cancel', 'METHOD' => "POST", 'DATA' => $data, 'ALLDATA' => true, 'RETURNARRAY' => true]);
            }
        } catch (Exception $e) {
            $result = $e->getMessage();
        }

        return $result;
    }

    // Get Invoice Details for order items
    public function getshipmentInvoice($orderItemIds)
    {
        try {
            if ($orderItemIds) {
                $result = $this->_API->call(['URL' => '/sellers/v2/orders/invoices?orderItemIds=' . $orderItemIds, 'METHOD' => "GET", 'RETURNARRAY' => true]);
                return $result;
            }
        } catch (Exception $e) {

        }
    }

    //  Update Service as complete
    public function updateServiceComplete($sku = null, $shipmentId, $locationId)
    {
        try
        {
            $result = array();
            if ($sku) {
                $detail    = $this->getSkudetail($shipmentId);
                $locations = [['id' => $location_id, 'shipmentId' => $shipmentId]];
                $data      = array();
                foreach ($detail as $key => $value) {
                    $data[$key] = ['shipmentId' => $value['shipmentId'], 'locations' => $locations];
                }
                $result = $this->_API->call(['URL' => '/sellers/v2/services/complete', 'METHOD' => "POST", 'DATA' => $data, 'ALLDATA' => true, 'RETURNARRAY' => true]);
            }
        } catch (Exception $e) {
            $result = $e->getMessage();
        }

        return $result;
    }
    // Update Service attempts
    public function updateServiceAttempts($sku = null, $shipmentId, $locationId)
    {
        try
        {
            $result = array();
            if ($sku) {
                $detail    = $this->getSkudetail($shipmentId);
                $locations = [['id' => $location_id, 'shipmentId' => $shipmentId]];
                $data      = array();
                foreach ($detail as $key => $value) {
                    $data[$key] = ['shipmentId' => $value['shipmentId'], 'locations' => $locations];
                }
                $result = $this->_API->call(['URL' => '/sellers/v2/services/attempt', 'METHOD' => "POST", 'DATA' => $data, 'ALLDATA' => true, 'RETURNARRAY' => true]);
            }
        } catch (Exception $e) {
            $result = $e->getMessage();
        }

        return $result;
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
                $result = $this->_API->call(['URL' => '/sellers/v2/shipments/dispatch', 'METHOD' => "POST", 'DATA' => $data, 'ALLDATA' => true, 'RETURNARRAY' => true]);
            }
        } catch (Exception $e) {
            $result = $e->getMessage();
        }

        return $result;
    }
    //  Update delivery attempt for self-ship order items
    public function updateDeliveryAttempt($sku = null, $location_id, $shipmentIds)
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
                $result = $this->_API->call(['URL' => '/sellers/v2/shipments/deliveryAttempt', 'METHOD' => "POST", 'DATA' => $data, 'ALLDATA' => true, 'RETURNARRAY' => true]);
            }
        } catch (Exception $e) {
            $result = $e->getMessage();
        }

        return $result;
    }
    // update Delivery date for self-ship order items
    public function updateDeliveryDate($sku = null, $location_id, $shipmentIds)
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
                $result = $this->_API->call(['URL' => '/sellers/v2/shipments/delivery', 'METHOD' => "POST", 'DATA' => $data, 'ALLDATA' => true, 'RETURNARRAY' => true]);
            }
        } catch (Exception $e) {
            $result = $e->getMessage();
        }

        return $result;
    }
    // Download labels and invoices in PDF format for shipments
    public function geDownloadLabelAndInvoice($shipmentIds)
    {
        try {
            if ($shipmentIds) {
                $result = $this->_API->call(['URL' => '/sellers/v3/shipments/shipmentIds=' . $shipmentIds . '/labels', 'METHOD' => "GET", 'RETURNARRAY' => true]);
                return $result;
            }
        } catch (Exception $e) {

        }
    }

    // order info
    public function getOrders(string $status = 'all', string $source = 'all', int $page = 1)
    {
        return $this->call("/order/info", [
            'source' => $source,
            'status' => $status,
            'page'   => $page,
        ]);
    }
    // filter
    public function filter(string $status = 'all', string $source = 'all', int $page = 1)
    {
        return $this->call("/shipments/filter", [

            'source' => $source,
            'status' => $status,
            'page'   => $page,
        ]);
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
    public function manifestPDF($vendorGroupCode, $location_id, $isMps, $pickUpDate_after, $pickUpDate_before)
    {
        try
        {
            $result = array();
            if ($sku) {
                $vendorGroupCode = $this->getVendordetail($vendorGroupCode);
                $locations       = [['id' => $location_id, 'isMps' => $isMps]];
                $data            = array();
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
    public function selfShipDispatch($tentativeDeliveryDate, $shipmentId, $dispatchDate, $orderItems)
    {
        try
        {
            $result = array();
            if ($sku) {
                $shipments  = $this->getShipmentdetail($shipmentId);
                $orderItems = [['id' => $orderItems]];
                $data       = array();
                foreach ($shipments as $key => $value) {
                    $data[$key] = ['shipmentId' => $value['shipmentId'], 'orderItems' => $orderItems];
                }
                $result = $this->_API->call(['URL' => '/sellers/v2/shipments/dispatch', 'METHOD' => "POST", 'DATA' => $data, 'ALLDATA' => true, 'RETURNARRAY' => true]);
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
                $data       = array();
                foreach ($shipments as $key => $value) {
                    $data[$key] = ['shipmentId' => $value['shipmentId'], 'locationId' => $locationId];
                }
                $result = $this->_API->call(['URL' => '/sellers/listings/v3/shipments/selfShip/delivery
                "', 'METHOD' => "POST", 'DATA' => $data, 'ALLDATA' => true, 'RETURNARRAY' => true]);
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
