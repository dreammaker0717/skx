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
$sql = "SELECT is_shipping_changed FROM sales_orders WHERE order_id = ".$_GET["orderId"];
$result = $conn->query($sql);
if($changedRow = $result->fetch_array()) {
  $item = ['isChanged' => $changedRow['is_shipping_changed']];
  echo json_encode($item);
} else {
  echo "error 0";
}
$conn->close();
?>
