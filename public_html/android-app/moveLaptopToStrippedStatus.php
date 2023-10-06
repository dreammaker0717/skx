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

$stID = $_GET['st_id'];

$sql = "UPDATE stock SET st_status = 24 WHERE st_id = ".$stID;
$conn->query($sql);

$conn->close();
?>