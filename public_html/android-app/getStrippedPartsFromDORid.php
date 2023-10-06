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

$dor_id = $_GET['dor_id'];

$sql = "SELECT dco_stock.dst_id, dco_stock.dst_servicetag, dco_stock.dst_status, dell_part.dp_mpn, dell_part.dp_sku, dell_part.dp_name, dell_part.dp_box_label, dell_part.dp_box_subtitle, dell_part.dp_condition FROM dco_orderprod INNER JOIN dell_part on dco_orderprod.dop_product = dell_part.dp_id INNER JOIN dco_stock on dell_part.dp_id = dco_stock.dst_product WHERE dco_orderprod.dop_order = '".$dor_id."' AND dco_stock.dst_order = '".$dor_id."' AND dco_orderprod.dop_sn = dco_stock.dst_servicetag";
$result = $conn->query($sql);
while($serialRow = $result->fetch_assoc()) {
    $newArr = ['id' => $serialRow['dst_id'], 'pn' => $serialRow['dp_mpn'], 'sku' => $serialRow['dp_sku'], 'name' => $serialRow['dp_name'], 'dstSerial' => $serialRow['dst_servicetag'], 'status' => $serialRow['dst_status'], 'subtitle' => $serialRow['dp_box_subtitle'], 'condition' => $serialRow['dp_condition'], 'boxLabel' => $serialRow['dp_box_label']];
    $finalResult[$index] = $newArr;
    $index++;
}
echo json_encode($finalResult);
$conn->close();
?>