<?php
include PATH_CONFIG . "/constants.php";

$db = M::db();
if ($action == "search") {

    $start = intval($_POST["start"]);
    $length = intval($_POST["length"]);
    if ($length == -1) {
        $length = "100000";
    }

    $stat = intval($_POST["status"]);
    $country = $_POST["country"];
    $marketplace = $_POST["marketplace"];

    $_SF;
    if ($stat == 0) {
        $_SF = "status = 'awaiting_shipment'";
    } else {
        $_SF = "status = 'shipped'";
    }

    if ($country != "") {
        $_SF .= " AND country = '" . $country . "'";
    }

    if ($marketplace != "") {
        $_SF .= " AND sales_channel = '" . $marketplace . "'";
    }

    $_SFC = $_SF;

    $order_field = "order_date desc";
    if (!empty($_POST["order"])) {
        $order_field = $_POST['columns'][$_POST['order']['0']['column']]['data'] . " " . strtoupper($_POST['order']['0']['dir']);
    }

    $_PARA = [];
    $SEARCH = $_POST["search"]["value"];
    if (!empty($SEARCH)) {
        $_SF .= " AND (order_number like :search OR recipient like :search)";
        $_PARA[":search"] = "%" . $SEARCH . "%";
    }

    $query = "select id, order_number, tag_ids, sales_channel, user, order_date, recipient, country, order_total, shipping_name, time_printed, tracking_number
    from sales_orders
    where  $_SF
    order by $order_field
    limit $start,$length";

    $data = $db->query($query, $_PARA)->fetchAll();

    for ($i = 0; $i < count($data); $i++) {
        $itemsQuery = "select id, sku, qty, scanned, time_pick from sales_orderitems where sales_order_id = " . $data[$i]['id'];
        $itemsData = $db->query($itemsQuery)->fetchAll();
        $skuList = array();
        for ($j = 0; $j < count($itemsData); $j++) {
            $skuList[$j]['sku'] = $itemsData[$j]['sku'];

            $skuList[$j]['scanned'] = $itemsData[$j]['scanned'];

            $serialsQuery = "select	serial_number from sales_orderserials where sales_orderitems_id = " . $itemsData[$j]['id'];
            $serialsData = $db->query($serialsQuery)->fetchAll();
            if ($serialsData) {
                $serials = array();
                for ($q = 0; $q < count($serialsData); $q++) {
                    $serials[$q] = $serialsData[$q]['serial_number'];
                }
                $skuList[$j]['serial'] = $serials;
            }
        }
        $data[$i]['skuWithSerial'] = $skuList;
        $qtyList = "";
        for ($k = 0; $k < count($itemsData); $k++) {
            $qtyList .= strval($itemsData[$k]['qty']);
            if ($k < (count($itemsData) - 1)) {
                $qtyList .= "<br/>";
            }
        }
        $data[$i]['qty'] = $qtyList;

        $requestedQuery = "select requested_sku, requested_qty from sales_requested_products where sales_order_id = " . $data[$i]['id'];
        $requestedData = $db->query($requestedQuery)->fetchAll();
        $requestedSKUList = "";
        for ($m = 0; $m < count($requestedData); $m++) {
            $requestedSKUList .= $requestedData[$m]['requested_qty'] . " x " . $requestedData[$m]['requested_sku'];
            if ($m < (count($requestedData) - 1)) {
                $requestedSKUList .= "<br/>";
            }
        }
        $data[$i]['requested_sku'] = $requestedSKUList;

        if ($data[$i]['user'] != 0) {
            $userQuery = "select username from users where user_id = " . $data[$i]['user'];
            $userResult = $db->query($userQuery);
            if ($userResult) {
                $user = $userResult->fetchAll();
                if ($user) {
                    $data[$i]['user'] = $user[0]['username'];
                }
            } else {
                $data[$i]['user'] = '';
            }
        } else {
            $data[$i]['user'] = '';
        }
    }

    for ($n = 0; $n < count($data); $n++) {
        if ($data[$n]['tag_ids'] != "") {
            $data[$n]['order_number'] = $data[$n]['order_number'] . "," . $data[$n]['tag_ids'];
        }
    }

    $numRows = 0;
    $numRowsTotal = 0;

    $query_count = "select count(*) as c from sales_orders where $_SFC ";
    $query_count2 = "select count(*) as c from sales_orders where $_SF ";

    $numRowsTotal = $db->query($query_count2, $_PARA)->fetchAll();
    $numRows = $db->query($query_count)->fetchAll();

    $output = array(
        "draw" => intval($_POST["draw"]),
        "iTotalRecords" => $numRows[0]["c"],
        "iTotalDisplayRecords" => $numRowsTotal[0]["c"] ?? 0,
        "data" => $data,
        "t" => "r0",
    );
    $json = json_encode(utf8ize($output));
    echo $json;

} else if ($action == "all_countries") {
    $queryCountry = "SELECT DISTINCT(country) FROM sales_orders ORDER BY country ASC";
    $output = $db->query($queryCountry)->fetchAll();
    header('Content-Type: application/json; charset=utf-8');
    http_response_code(200);
    echo json_encode(utf8ize($output));
} else if ($action == "all_marketplaces") {
    $queryMarketplaces = "SELECT DISTINCT(sales_channel) FROM sales_orders ORDER BY sales_channel ASC";
    $output = $db->query($queryMarketplaces)->fetchAll();
    header('Content-Type: application/json; charset=utf-8');
    http_response_code(200);
    echo json_encode(utf8ize($output));
} else if ($action == "reload") {
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
    curl_setopt($listCurl, CURLOPT_SSL_VERIFYPEER, true); // 証明書の検証を行わない
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
        curl_setopt($curl[$i], CURLOPT_SSL_VERIFYPEER, true); // 証明書の検証を行わない
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

    $ordersUrl = "https://ssapi.shipstation.com/orders?orderStatus=awaiting_shipment&sortBy=OrderDate&sortDir=DESC&pageSize=500";
    $ordersCurl = curl_init($ordersUrl);
    curl_setopt($ordersCurl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ordersCurl, CURLOPT_SSL_VERIFYPEER, true); // 証明書の検証を行わない
    curl_setopt($ordersCurl, CURLOPT_HTTPHEADER, array('Authorization: Basic MDY5MWEyYTQ0MjdkNDNlNmI5MTYxOGVjZjQ3YzllODk6NDdiYTUyZjliOTVhNDhmOGE3MzAxNzBhYTdjZWJhMjk='));
    $ordersResponse = curl_exec($ordersCurl);
    curl_close($ordersCurl);

    $orders = json_decode($ordersResponse, true);
    //error_log(print_r($ordersJSON, true));
    foreach ($orders['orders'] as $order) {
        $checkSQL = "SELECT	id FROM sales_orders WHERE order_number = '" . $order['orderNumber'] . "'";
        $checkResult = $db->query($checkSQL)->fetchAll();
        if (!$checkResult) {
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
            $dataSalesOrder = array(
                'order_id' => $order['orderId'],
                'order_number' => $order['orderNumber'],
                'tag_ids' => $tagIds,
                'sales_channel' => $storeName,
                'order_total' => $orderTotal,
                'shipping_name' => $shippingName,
                'recipient' => $recipient,
                'address' => $address,
                'country' => $country,
                'status' => $order['orderStatus'],
                'order_date' => $orderDate,
            );
            $db->insert('sales_orders', $dataSalesOrder);
            $orderID = $db->id();
            $listPicks = array();
            $listPicksGrouped = array();
            $index = 0;
            foreach ($order['items'] as $item) {

                $dataSalesRequestedProducts = array(
                    'sales_order_id' => $orderID,
                    'requested_sku' => $item['sku'],
                    'requested_product' => $item['name'],
                    'requested_qty' => $item['quantity'],
                );
                $db->insert('sales_requested_products', $dataSalesRequestedProducts);
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

                $dataSalesOrderItems = array(
                    'sales_order_id' => $orderID,
                    'sku' => $pick['sku'],
                    'qty' => $pick['quantity'],
                );
                $db->insert('sales_orderitems', $dataSalesOrderItems);
            }
        } else {
            $orderID = $checkResult[0]['id'];
            $salesRequestedSelectSQL = "SELECT requested_sku FROM sales_requested_products WHERE sales_order_id = " . $orderID;
            $requestedResultInt = $db->query($salesRequestedSelectSQL);
            if ($requestedResultInt) {
                $requestedResult = $requestedResultInt->fetchAll();
                foreach ($order['items'] as $item) {
                    $found = false;
                    foreach ($requestedResult as $requestedRow) {
                        if ($requestedRow['requested_sku'] == $item['sku']) {
                            $found = true;
                        }
                    }

                    if (!$found) {

                        $dataSalesRequestedProduct = array(
                            'sales_order_id' => $orderID,
                            'requested_sku' => $item['sku'],
                            'requested_product' => $item['name'],
                            'requested_qty' => $item['quantity'],
                        );
                        $db->insert('sales_requested_products', $dataSalesRequestedProduct);
                    }
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

                $saleSKUSQL = "SELECT sku FROM sales_orderitems WHERE sales_order_id = " . $orderID;
                $saleSKUResultInt = $db->query($saleSKUSQL);

                if ($saleSKUResultInt) {
                    $saleSKUResult = $saleSKUResultInt->fetchAll();
                    foreach ($listPicksGrouped as $pick) {
                        $found = false;
                        foreach ($saleSKUResult as $SKURow) {
                            if ($SKURow['sku'] == $pick['sku']) {
                                $found = true;
                            }
                        }

                        if (!$found) {

                            $dataSalesNewPick = array(
                                'sales_order_id' => $orderID,
                                'sku' => $pick['sku'],
                                'qty' => $pick['name'],
                            );
                            $db->insert('sales_orderitems', $dataSalesNewPick);
                        }
                    }
                }

                $mergedIds = $order['advancedOptions']['mergedIds'];
                if ($mergedIds) {
                    for ($m = 0; $m < count($mergedIds); $m++) {
                        $idSQL = "SELECT id FROM sales_orders WHERE order_id = " . $mergedIds[$m];
                        $idResultInt = $db->query($idSQL);
                        if ($idResultInt) {
                            $idResult = $idResultInt->fetchAll();
                            $orderTableID = $idResult[0]['id'];
                            $idSKUSQL = "SELECT id FROM sales_orderitems WHERE sales_order_id = " . $orderTableID;
                            $idSKUResultInt = $db->query($idSKUSQL);
                            if ($idSKUResultInt) {
                                $idSKUResult = $idSKUResultInt->fetchAll();
                                if ($idSKUResult) {
                                    foreach ($idSKUResult as $idSKU) {
                                        $db->delete("sales_orderserials", ["sales_orderitems_id" => $idSKU['id']]);
                                    }
                                }
                                $db->delete("sales_orderitems", ["sales_order_id" => $orderTableID]);
                                $db->delete("sales_orders", ["order_id" => $mergedIds[$m]]);
                            }
                        }
                    }
                }
            }
        }
    }
    $orderDBSQL = "SELECT order_id FROM sales_orders WHERE status = 'awaiting_shipment'";
    $orderDBResult = $db->query($orderDBSQL)->fetchAll();
    if ($orderDBResult) {
        foreach ($orderDBResult as $orderDB) {
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
                $db->update('sales_orders', ['status' => $singleOrder['orderStatus']], ['order_id' => $orderDB['order_id']]);
            }
        }
    }
    header('Content-Type: application/json; charset=utf-8');
    http_response_code(200);
    echo json_encode('{ "success" : true }');
} else {
    exit();
}

function utf8ize($d)
{
    if (is_array($d)) {
        foreach ($d as $k => $v) {
            $d[$k] = utf8ize($v);
        }
    } else if (is_string($d)) {
        return utf8_encode($d);
    }
    return $d;
}
