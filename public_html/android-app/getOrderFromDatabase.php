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
if($rowResulSalesIdCheck = $resultSalesIdCheck->fetch_array() ){
  $orderId = $rowResulSalesIdCheck['id'];
  $json;
  $sqlGetSalesItems = "SELECT id, sku, qty, scanned, has_serial, time_pick FROM sales_orderitems WHERE sales_order_id = ".$orderId;
  $resultSalesItems = $conn->query($sqlGetSalesItems);
  while($rowResultSalesItems = $resultSalesItems->fetch_assoc()) {
      $salesItemId = $rowResultSalesItems['id'];
      $skuArr = array();
      $sqlSalesItemSerials = "SELECT serial_number, time_scanned FROM sales_orderserials WHERE sales_orderitems_id = ".$salesItemId;
      $resultSalesItemSerials = $conn->query($sqlSalesItemSerials);
      $serialArr = array();
      while($rowResultSalesItemSerials = $resultSalesItemSerials->fetch_assoc()) {
        $serialArr[] = ['serialNumber' => $rowResultSalesItemSerials['serial_number'], 'scanTime' => $rowResultSalesItemSerials['time_scanned']];
      }
      $skuArr = ['quantity' => $rowResultSalesItems['qty'],'sku' => $rowResultSalesItems['sku'],'picked' => count($serialArr),'serialNumbers' => $serialArr,'isScanned' => $rowResultSalesItems['scanned'],'hasSerial' => $rowResultSalesItems['has_serial'],'manualPickTime' => $rowResultSalesItems['time_pick']];
      $finalResult[] = $skuArr;
  }
  echo json_encode($finalResult);
  $conn->close();
}
?>
