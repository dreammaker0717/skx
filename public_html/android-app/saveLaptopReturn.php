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

$serviceTag = $_POST['serviceTag'];
$reason = $_POST['reason'];
$comment = $_POST['comment'];
if($comment == ""){
	$comment = "No Comment";
} else {
	$comment = addslashes($comment);
}
$user = $_POST['user'];

$stockQuery = "SELECT st_id FROM stock WHERE st_servicetag = '"  . $serviceTag . "'";
$resultStockQuery = $conn->query($stockQuery);

if($rowResultStock = $resultStockQuery->fetch_assoc()){
  	$reasonText = "";
  	switch ($reason) {
	  case "cancelled":
	    $reasonText = "Cancelled Before Shipment";
	    break;
	  case "refunded_not_like":
	    $reasonText = "Refunded - Didn\'t Like it";
	    break;
	  case "refunded_faulty":
	    $reasonText = "Refunded - Issue with Laptop";
	    break;
	  case "refunded_undelivered":
	    $reasonText = "Refunded - Undelivered";
	    break;
	  case "refunded_other":
	    $reasonText = "Refunded - other reason";
	    break;
	  default:
	    $reasonText = "";
	    break;
	}

	
  	$stockUpdateSql = "UPDATE stock SET st_status = 1, st_lastcomment = '" . "Reason is " . $reasonText ."\\n Comment: " . $comment . " Status changed to 1' WHERE st_id = " . $rowResultStock['st_id'];
  	$conn->query($stockUpdateSql);

	$stockHistorySql = "INSERT INTO stock_history SET sh_date = NOW(), sh_user = " . $user . ", sh_status = 1, sh_comment = '" . "Reason is " . $reasonText ."\\n Comment: " . $comment . " Status changed to 1', sh_stock = " . $rowResultStock['st_id'];
	$conn->query($stockHistorySql);


	$laptopSerialsQuery = "SELECT laptop_orderserials.id, laptop_requested_products.laptop_order_id FROM laptop_orderserials LEFT JOIN laptop_requested_products ON laptop_orderserials.laptop_requesteditem_id = laptop_requested_products.id WHERE laptop_orderserials.serial_number = '"  . $serviceTag . "'";
	$resultLaptopSerialsQuery = $conn->query($laptopSerialsQuery);

	if($rowResultLaptopSerials = $resultLaptopSerialsQuery->fetch_assoc()){
		$laptopSerialsUpdateSql = "UPDATE laptop_orderserials SET status = '" . $reason . "' WHERE id = " . $rowResultLaptopSerials['id'];
		$conn->query($laptopSerialsUpdateSql);

		$fullyRefunded = true;
		$refundReasons = array("refunded_not_like", "refunded_faulty", "refunded_undelivered", "refunded_other");

		$fullyCancelled = true;

		$laptopAllSerialsSql = "SELECT laptop_orderserials.status FROM laptop_orderserials LEFT JOIN laptop_requested_products ON laptop_orderserials.laptop_requesteditem_id = laptop_requested_products.id WHERE laptop_requested_products.laptop_order_id = " . $rowResultLaptopSerials['laptop_order_id'];
		$resultLaptopAllSerialsSql = $conn->query($laptopAllSerialsSql);
		while($rowResultLaptopAllSerials = $resultLaptopAllSerialsSql->fetch_assoc()){
			if ($rowResultLaptopAllSerials['status'] != "") {
				if (!in_array($rowResultLaptopAllSerials['status'], $refundReasons)) {
					$fullyRefunded = false;
				}
			} else {
				$fullyRefunded = false;
			}

			if ($rowResultLaptopAllSerials['status'] != "") {
				if ($rowResultLaptopAllSerials['status'] != "cancelled") {
					$fullyCancelled = false;
				}
			} else {
				$fullyCancelled = false;
			}
		}

		$orderStatus = "";

		if ($fullyCancelled) {
			$orderStatus = "calcelled";
		} else{
			if ($fullyRefunded) {
				$orderStatus = "refunded";
			} else {
				$orderStatus = "part_refunded";
			}
		}

		$laptopOrderUpdateSql = "UPDATE laptop_orders SET status = '" . $orderStatus . "' WHERE id = " . $rowResultLaptopSerials['laptop_order_id'];
		$conn->query($laptopOrderUpdateSql);
	}
} 

$conn->close();
?>