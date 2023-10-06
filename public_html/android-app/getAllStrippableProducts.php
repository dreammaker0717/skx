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

$index = 0;
$finalResult = array();

$sql = "SELECT stock.st_id, stock.st_servicetag, products.pr_name, manufacturers.mf_name FROM stock INNER JOIN products on stock.st_product = products.pr_id INNER JOIN manufacturers ON products.pr_manufacturer = manufacturers.mf_id WHERE stock.st_status = 8";
$result = $conn->query($sql);
while($serialRow = $result->fetch_assoc()) {
    $newArr = ['id' => $serialRow['st_id'], 'serial' => $serialRow['st_servicetag'], 'model' => $serialRow['pr_name'], 'brand' => $serialRow['mf_name']];
    $finalResult[$index] = $newArr;
    $index++;
}
echo json_encode($finalResult);
$conn->close();
?>