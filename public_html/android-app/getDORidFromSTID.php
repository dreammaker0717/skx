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
$st_id = $_GET['st_id'];

$sql = "SELECT dor_id FROM dco_orders WHERE dor_reference REGEXP '".$st_id."$'";
$result = $conn->query($sql);
if($serialRow = $result->fetch_array()) {
    echo $serialRow['dor_id'];
}
$conn->close();
?>