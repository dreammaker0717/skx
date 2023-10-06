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
$orderNumber = $_POST['orderNumber'];
$json = $_POST['json'];
$data = json_decode($json, TRUE);

$user = $_POST['user'];

$sqlSalesOrder = "SELECT id, order_id, order_number, tag_ids, sales_channel, order_total, shipping_amount, shipping_name, recipient, address, country, order_date FROM sales_orders WHERE order_number = '".$orderNumber."'";
$resultSalesOrder = $conn->query($sqlSalesOrder);
$rowResultSalesOrder = $resultSalesOrder->fetch_array();

$laptopOrderExistsCheck = "SELECT id FROM laptop_orders WHERE order_number = '".$orderNumber."'";
$resultLaptopOrderExistsCheck = $conn->query($laptopOrderExistsCheck);
if (!$resultLaptopOrderExistsCheck->fetch_array()) {
	$laptopSalesInsertSQL = "INSERT INTO laptop_orders (order_id, order_number, tag_ids, sales_channel, order_total, shipping_amount, shipping_name, recipient, address, country, status, order_date) VALUES (" . $rowResultSalesOrder['order_id'] . ", '" . $rowResultSalesOrder['order_number'] . "', '" . $rowResultSalesOrder['tag_ids'] . "', '" . $rowResultSalesOrder['sales_channel'] . "', '" . $rowResultSalesOrder['order_total'] . "', '" . $rowResultSalesOrder['shipping_amount'] . "', '" . $rowResultSalesOrder['shipping_name'] . "', '" . $rowResultSalesOrder['recipient'] . "', '" . $rowResultSalesOrder['address'] . "', '" . $rowResultSalesOrder['country'] . "', 'shipped', " . $rowResultSalesOrder['order_date'] . ")";
	$conn->query($laptopSalesInsertSQL);
	$laptopOrderID = $conn->insert_id;

	$salesRequestedSelectSQL = "SELECT id, requested_sku, requested_qty, requested_unit_price FROM sales_requested_products WHERE sales_order_id = " . $rowResultSalesOrder['id'];
	$requestedResult = $conn->query($salesRequestedSelectSQL)->fetch_all(MYSQLI_ASSOC);
	foreach ($requestedResult as $requestedRow) {
		if (substr($requestedRow['requested_sku'], 0, 4) === "LAP/") {
	  	  	$laptopRequestedInsertSQL = "INSERT INTO laptop_requested_products (laptop_order_id, requested_sku, requested_qty) VALUES (" . $laptopOrderID . ", '" . $requestedRow['requested_sku'] . "', " . $requestedRow['requested_qty'] . ")";
	    	$conn->query($laptopRequestedInsertSQL);
			$laptopRequestedID = $conn->insert_id;

			foreach($data as $item) {
			    if ($item['sku'] == $requestedRow['requested_sku']) {
			    	$stockQuery = "select st_id, st_cost, st_vat_type, st_vat_rate from stock where st_servicetag = '" . $item['serial'] . "'";
                    $stockData = $conn->query($stockQuery)->fetch_all(MYSQLI_ASSOC);
			    	$costval = $stockData[0]['st_cost'];
                    $grossval = $requestedRow['requested_unit_price'];
                    $vatval = 0.00;
                    $vattypeval = "Not Set";
                    if ($rowResultSalesOrder['country'] == "GB") {
                        if ($stockData[0]['st_vat_type'] == "Standard" || $stockData[0]['st_vat_type'] == "Import") {
                            $vatval = $grossval-$grossval/(1+($stockData[0]['st_vat_rate']/100));
                            $vattypeval = "Standard";
                        } else if($stockData[0]['st_vat_type'] == "Margin"){
                            $vatval = ($grossval-$costval)/6;
                            $vattypeval = "Margin";
                        }
                    } else{
                        $vattypeval = "Export";
                    }
                    $netval = $grossval - $vatval;
			    	$sqlScan = "INSERT INTO laptop_orderserials (laptop_requesteditem_id, serial_number, status, soldprice, soldnetprice, soldvat, soldvattype) VALUES (".$laptopRequestedID.", '".$item['serial']."', 'shipped', ".$grossval.", ".$netval.", ".$vatval.", '".$vattypeval."')";
			    	$conn->query($sqlScan);

			    	$sqlStockStatus = "UPDATE stock SET st_status = 16, st_lastcomment = 'Sold to " . $rowResultSalesOrder['recipient'] . "' WHERE st_id = " . $stockData[0]['st_id'];
			    	$conn->query($sqlStockStatus);
			    	$stockHistorySql = "INSERT INTO stock_history SET sh_date = NOW(), sh_user = " . $user . ", sh_status = 16, sh_comment = 'Sold to " . $rowResultSalesOrder['recipient'] . "', sh_stock = " . $stockData[0]['st_id'];
					$conn->query($stockHistorySql);
			    }
			}
	  	}
	}

	$conn->close();
} else {
	$conn->close();
	echo "error: order already exists";
}
?>