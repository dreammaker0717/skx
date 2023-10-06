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
$trackingNumber = $_POST['trackingNumber'];
$sqlSalesIdCheck = "SELECT id FROM sales_orders WHERE order_number = '".$orderNumber."'";
$resultSalesIdCheck = $conn->query($sqlSalesIdCheck);
if($rowResulSalesIdCheck = $resultSalesIdCheck->fetch_array() ){
  $orderId = $rowResulSalesIdCheck['id'];
  $updateSQL = "UPDATE sales_orders SET tracking_number = '" . $trackingNumber . "', status = 'shipped' WHERE id = '" . $orderId . "'";
  $conn->query($updateSQL);
}
