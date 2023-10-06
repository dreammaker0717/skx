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
$stID = $_POST['stID'];
$order = $_POST['order'];
$product = $_POST['product'];
$serial = $_POST['serial'];
$model = $_POST['model'];




$sqL_orderprod = "INSERT INTO dco_orderprod (dop_order, dop_product, dop_quantity, dop_delivered, dop_sn) VALUES ('".$order."', '".$product."', 1, 1, '".$serial."')";
//echo $sql_orderprod;
$conn->query($sqL_orderprod);

$date = date('Y-m-d H:i:s', time());
$sqL_dco_stock = "INSERT INTO dco_stock (dst_product, dst_order, dst_servicetag, dst_status, dst_date, dst_addedby, dst_lastcomment) VALUES ('".$product."', '".$order."', '".$serial."', 1, '".$date."', 3, '"."Disassembled from ".$model." - ".$stID."')";
//echo $sqL_dco_stock;
$conn->query($sqL_dco_stock);
//$StockID = $conn->insert_id;;

$conn->close();
?>