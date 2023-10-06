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

$listPicks = json_decode($_POST['listPicks'], true);
$openingTime = $_POST['openingTime'];
$printTime = $_POST['printTime'];
$orderNumber = $_POST['orderNumber'];
$salesChannel = $_POST['salesChannel'];

$user = $_POST['user'];

$orderID;
$country;
$searchSql = "SELECT id, country FROM sales_orders WHERE order_number = '".$orderNumber."'";
$searchResult = $conn->query($searchSql);
if ($idRow = $searchResult->fetch_array()) {
    $orderID = $idRow['id'];
    $country = $idRow['country'];
}

$updateTimeSql = "UPDATE sales_orders SET user =".$user.", time_opened =".intval($openingTime).", time_printed = ".intval($printTime)." WHERE id = ".$orderID;
$conn->query($updateTimeSql);

foreach ($listPicks as $pick) {
    $orderItemID;
    $searchItemsSql = "SELECT id FROM sales_orderitems WHERE sales_order_id = ".$orderID." AND sku = '".$pick['sku']."'";
    $searchItemsResult = $conn->query($searchItemsSql);
    if ($idItemsRow = $searchItemsResult->fetch_array()) {
        $orderItemID = $idItemsRow['id'];
    }
    $updateSalesItems = "UPDATE sales_orderitems SET scanned =".$pick['isScanned'].", has_serial = ".$pick['hasSerial'].", time_pick = '".$pick['manualPickTime']."' WHERE id = ".$orderItemID;
    $conn->query($updateSalesItems);

    $sqlDelete = "DELETE FROM sales_orderserials WHERE sales_orderitems_id = ".$orderItemID;
    $conn->query($sqlDelete);

    foreach ($pick['serialNumbers'] as $serial) {
        $cost = 0;
        $vattype = "";
        $vatrate = 0;
        $lastComment = "Sold to Order ".$orderNumber." / ".$salesChannel." / ".$printTime;

        $sql_acc = "SELECT ast_id, ast_cost, ast_vat_type, ast_vat_rate FROM acc_stock WHERE ast_servicetag ='".$serial['serialNumber']."'";
        $result_acc = $conn->query($sql_acc);
        if ($serialRow_acc = $result_acc->fetch_array()) {
            $cost = $serialRow_acc['ast_cost'];
            $vattype = $serialRow_acc['ast_vat_type'];
            $vatrate = $serialRow_acc['ast_vat_rate'];
            $updateSql_acc = "UPDATE acc_stock SET ast_status = 16, ast_lastcomment = '".$lastComment."' WHERE ast_id = ".$serialRow_acc['ast_id'];
            $conn->query($updateSql_acc);
        } else {
            $sql_nwp = "SELECT nst_id, nst_cost, nst_vat_type, nst_vat_rate FROM nwp_stock WHERE nst_servicetag ='".$serial['serialNumber']."'";
            $result_nwp = $conn->query($sql_nwp);
            if ($serialRow_nwp = $result_nwp->fetch_array()) {
                $cost = $serialRow_nwp['nst_cost'];
                $vattype = $serialRow_nwp['nst_vat_type'];
                $vatrate = $serialRow_nwp['nst_vat_rate'];
                $updateSql_nwp = "UPDATE nwp_stock SET nst_status = 16, nst_lastcomment = '".$lastComment."' WHERE nst_id = ".$serialRow_nwp['nst_id'];
                $conn->query($updateSql_nwp);
            } else {
                $sql_dco = "SELECT dst_id, dst_cost, dst_vat_type, dst_vat_rate FROM dco_stock WHERE dst_servicetag ='".$serial['serialNumber']."'";
                $result_dco = $conn->query($sql_dco);
                if ($serialRow_dco = $result_dco->fetch_array()) {
                    $cost = $serialRow_dco['dst_cost'];
                    $vattype = $serialRow_dco['dst_vat_type'];
                    $vatrate = $serialRow_dco['dst_vat_rate'];
                    $updateSql_dco = "UPDATE dco_stock SET dst_status = 16, dst_lastcomment = '".$lastComment."' WHERE dst_id = ".$serialRow_dco['dst_id'];
                    $conn->query($updateSql_dco);
                }
            }
        }

        $soldprice = 0;
        $soldnetprice = 0;
        $soldvat = 0;
        $soldvattype = "Not Set";
        $requestedItemsSql = "SELECT id, requested_sku, requested_unit_price FROM sales_requested_products WHERE sales_order_id = ".$orderID;
        $requestedItemsResult = $conn->query($requestedItemsSql);
        while ($requestedItemsRow = $requestedItemsResult->fetch_assoc()) {
            $fullSku = $requestedItemsRow['requested_sku'];
            $sku = "";
            $whole = "";
            if (!empty(strrpos($fullSku, '/'))) {
                $whole = substr($fullSku, strrpos($fullSku, '/') + 1);
            } else {
                $whole = $fullSku;
            }
            if (!empty(strrpos($whole, '_'))) {
                $secondPart = explode("_", $whole)[1];
                if (empty(strpos($secondPart, '+'))) {
                    $sku = explode("_", $whole)[0];
                }
            } else {
                if (empty(strpos($whole, '+'))) {
                    $sku = $whole;
                }
            }

            if ($sku != "" && $sku == $pick['sku']) {
                $soldprice = $requestedItemsRow['requested_unit_price'];
                if ($country == "GB") {
                    if ($vattype == "Standard" || $vattype == "Import") {
                        $soldvat = $soldprice-$soldprice/(1+($vatrate/100));
                        $soldvattype = "Standard";
                    } else if($vattype == "Margin"){
                        $soldvat = ($soldprice-$cost)/6;
                        $soldvattype = "Margin";
                    }
                } else{
                    $soldvattype = "Export";
                }
                $soldnetprice = $soldprice - $soldvat;

                break;
            }
        }

        $sqlSerial = "INSERT INTO sales_orderserials (sales_orderitems_id, serial_number, time_scanned, soldprice, soldnetprice, soldvat, soldvattype) VALUES (".$orderItemID.", '".$serial['serialNumber']."', '".$serial['scanTime']."', ".$soldprice.", ".$soldnetprice.", ".$soldvat.", '".$soldvattype."')";
        $conn->query($sqlSerial);
    }
}
$conn->close();
?>
