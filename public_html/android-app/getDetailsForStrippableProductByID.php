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
$serial = $_GET['serial'];

$sql = "SELECT stock.st_id, products.pr_name FROM stock INNER JOIN products on stock.st_product = products.pr_id WHERE stock.st_status = 8 AND stock.st_servicetag = '".$serial."'";
$result = $conn->query($sql);
if($serialRow = $result->fetch_array()) {
    $newArr = ['id' => $serialRow['st_id'], 'name' => $serialRow['pr_name']];
    echo json_encode($newArr);
}
$conn->close();
?>