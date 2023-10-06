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
$serviceTag = $_GET['serviceTag'];

$sqlServiceTagCheck = "SELECT l.order_date, l.order_number, l.recipient, l.country, r.requested_sku FROM laptop_orders l left join laptop_requested_products r on r.laptop_order_id = l.id left join laptop_orderserials t on t.laptop_requesteditem_id = r.id WHERE t.serial_number = '".$serviceTag."'";

$resultServiceTagCheck = $conn->query($sqlServiceTagCheck);

if($rowResulServiceTagCheck = $resultServiceTagCheck->fetch_assoc()){
  	echo json_encode($rowResulServiceTagCheck);
} else {
	echo "error: servicetag not found";
}
$conn->close();
?>