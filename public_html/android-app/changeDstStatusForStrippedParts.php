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
$status = $_GET['status'];

$sql = "UPDATE dco_stock SET dst_status=".$status." WHERE dst_id = '".$dst_id."'";
$conn->query($sql);

$conn->close();
?>