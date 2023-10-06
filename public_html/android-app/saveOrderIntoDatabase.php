<?php

$servername = "127.0.0.1";
$username = "skx_farhad";
$password = "ferrari488P";
$dbname = "skx_ndc3";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$results = array();

$storesUrl = "https://ssapi.shipstation.com/stores";
$storesCurl = curl_init($storesUrl);
curl_setopt($storesCurl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($storesCurl, CURLOPT_SSL_VERIFYPEER, true);
curl_setopt($storesCurl, CURLOPT_HTTPHEADER, array('Authorization: Basic MDY5MWEyYTQ0MjdkNDNlNmI5MTYxOGVjZjQ3YzllODk6NDdiYTUyZjliOTVhNDhmOGE3MzAxNzBhYTdjZWJhMjk='));
$storesResponse = curl_exec($storesCurl);
curl_close($storesCurl);
$storesList = json_decode($storesResponse, true);

$listUrl = "https://ssapi.shipstation.com/carriers";
$listCurl = curl_init($listUrl);
curl_setopt($listCurl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($listCurl, CURLOPT_SSL_VERIFYPEER, true);
curl_setopt($listCurl, CURLOPT_HTTPHEADER, array('Authorization: Basic MDY5MWEyYTQ0MjdkNDNlNmI5MTYxOGVjZjQ3YzllODk6NDdiYTUyZjliOTVhNDhmOGE3MzAxNzBhYTdjZWJhMjk='));
$listResponse = curl_exec($listCurl);
curl_close($listCurl);
$carrierCodes = json_decode($listResponse, true);
$mh = curl_multi_init();
$i = 0;
foreach ($carrierCodes as $code) {
    $url = "https://ssapi.shipstation.com/carriers/listservices?carrierCode=" . $code['code'];
    $curl[$i] = curl_init("$url");
    curl_setopt($curl[$i], CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl[$i], CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($curl[$i], CURLOPT_HTTPHEADER, array('Authorization: Basic MDY5MWEyYTQ0MjdkNDNlNmI5MTYxOGVjZjQ3YzllODk6NDdiYTUyZjliOTVhNDhmOGE3MzAxNzBhYTdjZWJhMjk='));
    curl_multi_add_handle($mh, $curl[$i]);
    $i++;
}
$running = null; // execute the handles
do {
    curl_multi_exec($mh, $running);
    curl_multi_select($mh);
} while ($running > 0);
for ($j = 0; $j < $i; $j++) {
    $results[$j] = curl_multi_getcontent($curl[$j]);
    curl_multi_remove_handle($mh, $curl[$j]);
}
curl_multi_close($mh);
$orders = json_decode($_POST['orders'], true);
foreach ($orders['orders'] as $order) {
    $checkSQL = "SELECT	id FROM sales_orders WHERE order_number = '" . $order['orderNumber'] . "'";
    $checkResult = $conn->query($checkSQL);
    $orderIDRow = $checkResult->fetch_array();
    if (!$orderIDRow) {
        $storeName = '';
        $storeID = $order['advancedOptions']['storeId'];
        foreach ($storesList as $store) {
            if ($store['storeId'] == $storeID) {
                $storeName = $store['storeName'];
            }
        }
        $shippingName = '';
        $carrierName = '';
        foreach ($carrierCodes as $carrierCode) {
            if ($carrierCode['code'] == $order['carrierCode']) {
                $carrierName = $carrierCode['name'];
            }
        }

        $serviceName = '';
        foreach ($results as $result) {
            $arr = json_decode($result, true);
            foreach ($arr as $entry) {
                if ($entry['carrierCode'] == $order['carrierCode'] && $entry['code'] == $order['serviceCode']) {
                    $serviceName = $entry['name'];
                }
            }
        }
        $shippingName = $carrierName . " - " . $serviceName;

        $address = '';

        if ($order['shipTo']['company']) {
            $address = $address . $order['shipTo']['company'] . ", ";
        }
        if ($order['shipTo']['street1']) {
            $address = $address . $order['shipTo']['street1'] . ", ";
        }
        if ($order['shipTo']['street2']) {
            $address = $address . $order['shipTo']['street2'] . ", ";
        }
        if ($order['shipTo']['street3']) {
            $address = $address . $order['shipTo']['street3'] . ", ";
        }
        if ($order['shipTo']['city']) {
            $address = $address . $order['shipTo']['city'] . ", ";
        }
        if ($order['shipTo']['state']) {
            $address = $address . $order['shipTo']['state'] . ", ";
        }
        if ($order['shipTo']['postalCode']) {
            $address = $address . $order['shipTo']['postalCode'] . ", ";
        }
        if ($order['shipTo']['country']) {
            $address = $address . $order['shipTo']['country'] . ", ";
        }
        if ($order['shipTo']['phone']) {
            $address = $address . $order['shipTo']['phone'] . ", ";
        }
        $address = rtrim($address, ', ');
        $address = preg_replace('/[^A-Za-z0-9 ,\(\)\\\\\/\+\-\_\&]/', '', $address);
        $recipient = preg_replace('/[^A-Za-z0-9 ,\(\)\\\\\/\+\-\_\&]/', '', $order['shipTo']['name']);
        //$orderDate = strtotime($order['orderDate']);
        $date = new DateTime($order['orderDate'], new DateTimeZone("America/Los_Angeles"));
        $orderDate = $date->format('U');

        $tagIds = "";
        for ($k = 0; $k < count($order['tagIds']); $k++) {
            $tagIds .= strval($order['tagIds'][$k]);
            if ($k < (count($order['tagIds']) - 1)) {
                $tagIds .= ",";
            }
        }
        $orderTotal = 0;
        if ($order['advancedOptions']['storeId']) {
            if ($order['advancedOptions']['storeId'] == 24076) {
                if ($order['amountPaid']) {
                    $orderTotal = $order['amountPaid'];
                }
            } else {
                if ($order['orderTotal']) {
                    $orderTotal = $order['orderTotal'];
                }
            }
        } else {
            if ($order['orderTotal']) {
                $orderTotal = $order['orderTotal'];
            }
        }

        $country = "";
        if ($order['shipTo']['country']) {
            $country = $order['shipTo']['country'];
        }

        $multipiler = 1;
        $itemsummed = $order['shippingAmount'];
        foreach ($order['items'] as $item) {
            $itemsummed += $item['unitPrice']*$item['quantity'];
        }
        if ($itemsummed != $orderTotal) {
            $multipiler = $orderTotal/$itemsummed;
        }
            
        $salesInsertSQL = "INSERT INTO sales_orders (order_id, order_number, tag_ids, sales_channel, order_total, shipping_amount, shipping_name, recipient, address, country, status, order_date) VALUES (" . $order['orderId'] . ", '" . $order['orderNumber'] . "', '" . $tagIds . "', '" . $storeName . "', '" . $orderTotal . "', " . $order['shippingAmount']*$multipiler . ", '" . $shippingName . "', '" . $recipient . "', '" . $address . "', '" . $country . "', '" . $order['orderStatus'] . "', " . $orderDate . ")";
        //error_log($salesInsertSQL);
        $conn->query($salesInsertSQL);
        $orderID = $conn->insert_id;

        $listPicks = array();
        $listPicksGrouped = array();

        $index = 0;
        foreach ($order['items'] as $item) {
            $salesRequestedSQL = "INSERT INTO sales_requested_products (sales_order_id, requested_sku, requested_product, requested_qty, requested_unit_price) VALUES (" . $orderID . ",'" . $item['sku'] . "','" . $item['name'] . "'," . $item['quantity'] . "," . $item['unitPrice']*$multipiler . ")";
            $conn->query($salesRequestedSQL);
            $fullSku = $item['sku'];
            $quantity = $item['quantity'];
            $sku = "";
            $whole = "";
            if (!empty(strrpos($fullSku, '/'))) {
                $whole = substr($fullSku, strrpos($fullSku, '/') + 1);
            } else {
                $whole = $fullSku;
            }
            if (!empty(strrpos($whole, '_'))) {
                $sku = explode("_", $whole)[0];
                $secondPart = explode("_", $whole)[1];
                if (!empty(strpos($secondPart, '+'))) {
                    $auxiliary = explode("+", $secondPart);
                    for ($i = 1; $i < count($auxiliary); $i++) {
                        $newArr = ['sku' => $auxiliary[$i], 'quantity' => $quantity];
                        $listPicks[$index] = $newArr;
                        $index++;
                    }
                    if (strpos($auxiliary[0], 'PK') === 0) {
                        $quantity = $quantity * ((int) substr($auxiliary[0], 2));
                    }
                } else {
                    if (strpos($secondPart, 'PK') === 0) {
                        $quantity = $quantity * ((int) substr($secondPart, 2));
                    }
                }
                $newArr = ['sku' => $sku, 'quantity' => $quantity];
                $listPicks[$index] = $newArr;
                $index++;
            } else {
                if (!empty(strpos($whole, '+'))) {
                    $auxiliary = explode("+", $whole);
                    for ($j = 0; $j < count($auxiliary); $j++) {
                        $newArr = ['sku' => $auxiliary[$j], 'quantity' => $quantity];
                        $listPicks[$index] = $newArr;
                        $index++;
                    }
                } else {
                    $sku = $whole;
                    $newArr = ['sku' => $sku, 'quantity' => $quantity];
                    $listPicks[$index] = $newArr;
                    $index++;
                }
            }

        }
        $listPicksGrouped[0] = $listPicks[0];
        for ($k = 1; $k < count($listPicks); $k++) {
            $isPresent = false;
            $position;
            for ($l = 0; $l < count($listPicksGrouped); $l++) {
                if ($listPicksGrouped[$l]['sku'] === $listPicks[$k]['sku']) {
                    $isPresent = true;
                    $position = $l;
                }
            }
            if (!$isPresent) {
                $listPicksGrouped[] = $listPicks[$k];
            } else {
                $listPicksGrouped[$position]['quantity'] += $listPicks[$k]['quantity'];
            }
        }
        foreach ($listPicksGrouped as $pick) {
            $salesItemsSql = "INSERT INTO sales_orderitems (sales_order_id, sku, qty) VALUES ('" . $orderID . "', '" . $pick['sku'] . "'," . $pick['quantity'] . ")";
            $conn->query($salesItemsSql);
        }
    } else {
        $orderID = $orderIDRow['id'];
        $salesRequestedSelectSQL = "SELECT id, requested_sku, requested_qty FROM sales_requested_products WHERE sales_order_id = " . $orderID;
        $requestedResult = $conn->query($salesRequestedSelectSQL)->fetch_all(MYSQLI_ASSOC);

        $orderTotal = 0;
        if ($order['advancedOptions']['storeId']) {
            if ($order['advancedOptions']['storeId'] == 24076) {
                if ($order['amountPaid']) {
                    $orderTotal = $order['amountPaid'];
                }
            } else {
                if ($order['orderTotal']) {
                    $orderTotal = $order['orderTotal'];
                }
            }
        } else {
            if ($order['orderTotal']) {
                $orderTotal = $order['orderTotal'];
            }
        }

        $multipiler = 1;
        $itemsummed = $order['shippingAmount'];
        foreach ($order['items'] as $item) {
            $itemsummed += $item['unitPrice']*$item['quantity'];
        }
        if ($itemsummed != $orderTotal) {
            $multipiler = $orderTotal/$itemsummed;
        }

        foreach ($order['items'] as $item) {
            $found = false;
            foreach ($requestedResult as $requestedRow) {
                if ($requestedRow['requested_sku'] == $item['sku']) {
                    $found = true;
                }
            }

            if (!$found) {
                $salesNewRequestedSQL = "INSERT INTO sales_requested_products (sales_order_id, requested_sku, requested_product, requested_qty, requested_unit_price) VALUES (" . $orderID . ",'" . $item['sku'] . "','" . $item['name'] . "'," . $item['quantity'] . "," . $item['unitPrice']*$multipiler . ")";
                //error_log($salesNewRequestedSQL);
                $conn->query($salesNewRequestedSQL);
            }
        }

        $itemCoin = 0;
        $listCoin = array();
        foreach ($order['items'] as $item) {
            if (!in_array($item['sku'], $listCoin)) {
                foreach ($order['items'] as $newItem) {
                    if ($item['sku'] == $newItem['sku']) {
                        $itemCoin += 1;
                    }
                }

                if ($itemCoin > 1) {
                    $quantityID;
                    //$listCoin[] = ['sku' => $item['sku'], 'qty' => $$itemCoin];
                    foreach ($requestedResult as $requestedRow) {
                        if ($requestedRow['requested_sku'] == $item['sku']) {
                            $quantityID = $requestedRow['id'];
                        }
                    }
                    $salesNewRequestedSQL = "UPDATE sales_requested_products SET requested_qty = " . $itemCoin . " WHERE id = " . $quantityID;
                    //error_log($salesNewRequestedSQL);
                    $conn->query($salesNewRequestedSQL);
                }
                $itemCoin = 0;
            }
            $listCoin[] = $item['sku'];
        }

        $listPicks = array();
        $listPicksGrouped = array();
        $index = 0;
        foreach ($order['items'] as $item) {
            $fullSku = $item['sku'];
            $quantity = $item['quantity'];
            $sku = "";
            $whole = "";
            if (!empty(strrpos($fullSku, '/'))) {
                $whole = substr($fullSku, strrpos($fullSku, '/') + 1);
            } else {
                $whole = $fullSku;
            }
            if (!empty(strrpos($whole, '_'))) {
                $sku = explode("_", $whole)[0];
                $secondPart = explode("_", $whole)[1];
                if (!empty(strpos($secondPart, '+'))) {
                    $auxiliary = explode("+", $secondPart);
                    for ($i = 1; $i < count($auxiliary); $i++) {
                        $newArr = ['sku' => $auxiliary[$i], 'quantity' => $quantity];
                        $listPicks[$index] = $newArr;
                        $index++;
                    }
                    if (strpos($auxiliary[0], 'PK') === 0) {
                        $quantity = $quantity * ((int) substr($auxiliary[0], 2));
                    }
                } else {
                    if (strpos($secondPart, 'PK') === 0) {
                        $quantity = $quantity * ((int) substr($secondPart, 2));
                    }
                }
                $newArr = ['sku' => $sku, 'quantity' => $quantity];
                $listPicks[$index] = $newArr;
                $index++;
            } else {
                if (!empty(strpos($whole, '+'))) {
                    $auxiliary = explode("+", $whole);
                    for ($j = 0; $j < count($auxiliary); $j++) {
                        $newArr = ['sku' => $auxiliary[$j], 'quantity' => $quantity];
                        $listPicks[$index] = $newArr;
                        $index++;
                    }
                } else {
                    $sku = $whole;
                    $newArr = ['sku' => $sku, 'quantity' => $quantity];
                    $listPicks[$index] = $newArr;
                    $index++;
                }
            }

        }

        $listPicksGrouped[0] = $listPicks[0];
        for ($k = 1; $k < count($listPicks); $k++) {
            $isPresent = false;
            $position;
            for ($l = 0; $l < count($listPicksGrouped); $l++) {
                if ($listPicksGrouped[$l]['sku'] === $listPicks[$k]['sku']) {
                    $isPresent = true;
                    $position = $l;
                }
            }
            if (!$isPresent) {
                $listPicksGrouped[] = $listPicks[$k];
            } else {
                $listPicksGrouped[$position]['quantity'] += $listPicks[$k]['quantity'];
            }
        }
        // starts here
        $saleSKUSQL = "SELECT sku, qty FROM sales_orderitems WHERE sales_order_id = " . $orderID;
        $saleSKUResult = $conn->query($saleSKUSQL)->fetch_all(MYSQLI_ASSOC);

        foreach ($listPicksGrouped as $pick) {
            $found = false;
            foreach ($saleSKUResult as $SKURow) {
                if ($SKURow['sku'] == $pick['sku']) {
                    $found = true;
                }
            }

            if (!$found) {
                $salesNewPickSQL = "INSERT INTO sales_orderitems (sales_order_id, sku, qty) VALUES ('" . $orderID . "', '" . $pick['sku'] . "'," . $pick['quantity'] . ")";
                //error_log($salesNewPickSQL);
                $conn->query($salesNewPickSQL);
            }
        }

        foreach ($listPicksGrouped as $pick) {
            foreach ($saleSKUResult as $SKURow) {
                if ($SKURow['sku'] == $pick['sku']) {
                    if ($SKURow['qty'] != $pick['quantity']) {
                        $salesUpdatePickSQL = "UPDATE sales_orderitems SET qty = " . $pick['quantity'] . " WHERE sales_order_id = " . $orderID . " AND sku = '" . $pick['sku'] . "'";
                        //error_log($salesUpdatePickSQL);
                        $conn->query($salesUpdatePickSQL);
                    }
                }
            }
        }

        $mergedIds = $order['advancedOptions']['mergedIds'];
        if ($mergedIds) {
            for ($m = 0; $m < count($mergedIds); $m++) {
                $idSQL = "SELECT id FROM sales_orders WHERE order_id = " . $mergedIds[$m];
                $idResult = $conn->query($idSQL)->fetch_all(MYSQLI_ASSOC);
                if ($idResult) {
                    $orderTableID = $idResult[0]['id'];
                    $idSKUSQL = "SELECT id FROM sales_orderitems WHERE sales_order_id = " . $orderTableID;
                    $idSKUResult = $conn->query($idSKUSQL)->fetch_all(MYSQLI_ASSOC);
                    if ($idSKUResult) {
                        foreach ($idSKUResult as $idSKU) {
                            $deleteSerialSQL = "DELETE FROM sales_orderserials WHERE sales_orderitems_id = " . $idSKU['id'];
                            //error_log($deleteSerialSQL);
                            $conn->query($deleteSerialSQL);
                        }
                    }
                    $deleteItemsSQL = "DELETE FROM sales_orderitems WHERE sales_order_id = " . $orderTableID;
                    //error_log($deleteItemsSQL);
                    $conn->query($deleteSerialSQL);
                    $deleteSQL = "DELETE FROM sales_orders WHERE order_id = " . $mergedIds[$m];
                    //error_log($deleteSQL);
                    $conn->query($deleteSQL);
                }
            }
        }

    }
}
$orderDBSQL = "SELECT order_id FROM sales_orders WHERE status = 'awaiting_shipment'";
$orderDBResult = $conn->query($orderDBSQL);
while ($orderDB = $orderDBResult->fetch_assoc()) {
    $found = false;
    foreach ($orders['orders'] as $order) {
        if ($order['orderId'] == $orderDB['order_id']) {
            $found = true;
        }
    }
    if (!$found) {
        $singleOrderUrl = "https://ssapi.shipstation.com//orders/" . $orderDB['order_id'];
        $singleOrderCurl = curl_init($singleOrderUrl);
        curl_setopt($singleOrderCurl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($singleOrderCurl, CURLOPT_SSL_VERIFYPEER, true); // 証明書の検証を行わない
        curl_setopt($singleOrderCurl, CURLOPT_HTTPHEADER, array('Authorization: Basic MDY5MWEyYTQ0MjdkNDNlNmI5MTYxOGVjZjQ3YzllODk6NDdiYTUyZjliOTVhNDhmOGE3MzAxNzBhYTdjZWJhMjk='));
        $singleOrderResponse = curl_exec($singleOrderCurl);
        curl_close($singleOrderCurl);
        $singleOrder = json_decode($singleOrderResponse, true);
        $updateSQL = "UPDATE sales_orders SET status = '" . $singleOrder['orderStatus'] . "' WHERE order_id = " . $orderDB['order_id'];
        $conn->query($updateSQL);
    }
}
$conn->close();
