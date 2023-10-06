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
$shippingName = $_GET["carrierName"]." - ".$_GET["serviceName"];
$sql = "UPDATE sales_orders SET is_shipping_changed = 1, shipping_name = '".$shippingName."' WHERE order_id = ".$_GET["orderId"];
error_log($sql);
$conn->query($sql);
$conn->close();

header('Content-Type: application/json; charset=utf-8');
http_response_code(200);
echo json_encode('{ "success" : true }');
?>
