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
$reference = $_POST['reference'];
date_default_timezone_set('Europe/London');
$date = date('m/d/Y', time());
$time = date('h:i:s a', time());
$sqlStock = "INSERT INTO stocktake (stocktake_date, stocktake_time, stocktake_reference) VALUES ('".$date."', '".$time."','".$reference."')";
$conn->query($sqlStock);

$StockID = $conn->insert_id;

$json = $_POST['json'];
$data = json_decode($json, TRUE);

foreach($data as $item) {
    $sku = $item['sku'];
    $quantity = $item['quantity'];
    $latitude = $item['latitude'];
    $longitude = $item['longitude'];
    $sqlScan = "INSERT INTO stocktake_scans (StocktakeID, scan_sku, scan_quantity, scan_latitude, scan_longitude) VALUES (".$StockID.", '".$sku."', ".$quantity.",".$latitude.", ".$longitude.")";
    $conn->query($sqlScan);
}

$conn->close();
?>
