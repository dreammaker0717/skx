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

$pn = $_GET['pn'];

$index = 0;
$finalResult = array();

$sql = "SELECT dp_id, dp_box_label, dp_sku, dp_name FROM dell_part WHERE dp_mpn REGEXP '".$pn."'";


$result = $conn->query($sql);
while($serialRow = $result->fetch_assoc()) {
    $newArr = ['id' => $serialRow['dp_id'], 'sku' => $serialRow['dp_box_label'], 'name' => $serialRow['dp_name'], 'specificSKU' => $serialRow['dp_sku']];
    $finalResult[$index] = $newArr;
    $index++;
}
echo json_encode($finalResult);

$conn->close();
?>