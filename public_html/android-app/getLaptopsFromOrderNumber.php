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
$orderNumber = $_GET['orderNumber'];

$finalResult = array();

$sqlSalesIdCheck = "SELECT id FROM sales_orders WHERE order_number = '".$orderNumber."'";
$resultSalesIdCheck = $conn->query($sqlSalesIdCheck);
if($rowResulSalesIdCheck = $resultSalesIdCheck->fetch_assoc()){
  $orderId = $rowResulSalesIdCheck['id'];
  $json;
  $sqlGetSalesRequestedItems = "SELECT requested_sku, requested_qty FROM sales_requested_products WHERE sales_order_id = ".$orderId;
  $resultSalesRequestedItems = $conn->query($sqlGetSalesRequestedItems);
  while($rowResultSalesRequestedItems = $resultSalesRequestedItems->fetch_assoc()) {
  	  $requestedSku = $rowResultSalesRequestedItems['requested_sku'];
  	  if (substr($requestedSku, 0, 4) === "LAP/") {
  	  	  $laptopArr = ['sku' => $rowResultSalesRequestedItems['requested_sku'], 'quantity' => $rowResultSalesRequestedItems['requested_qty']];
      	  $finalResult[] = $laptopArr;
  	  }
  }
  echo json_encode($finalResult);
  $conn->close();
} else {
	echo "error: order not found";
}
?>