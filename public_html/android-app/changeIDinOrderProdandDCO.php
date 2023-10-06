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

$dst_id = $_GET['dst_id'];
$serial = $_GET['dst_serial'];
$product = $_GET['product'];

$sql_stock = "UPDATE dco_stock SET dst_product=".$product." WHERE dst_id = '".$dst_id."'";
$conn->query($sql_stock);

$sql_orderprod = "UPDATE dco_orderprod SET dop_product=".$product." WHERE dop_sn = '".$serial."'";
$conn->query($sql_orderprod);

$conn->close();
?>