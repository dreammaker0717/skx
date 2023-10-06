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

$palmrestpn = $_GET['palmrestpn'];
$keyboardpn = $_GET['keyboardpn'];

$index = 0;
$finalResult = array();

$sql_dco = "SELECT dp_box_label, dp_box_subtitle, dp_condition, dp_name, dp_sku FROM dell_part WHERE dp_mpn REGEXP '".$palmrestpn."' AND dp_mpn REGEXP '".$keyboardpn."' AND dp_sku REGEXP '^PLM\/DELL\/P'";
$result_dco = $conn->query($sql_dco);
while($serialRow_dco = $result_dco->fetch_assoc()) {
    $subtitle_dco = preg_replace('/[^A-Za-z0-9 ,\(\)\\\\\/\+\-\_\&]/', '', $serialRow_dco['dp_box_subtitle']);
    $newArr_dco = ['sku' => $serialRow_dco['dp_box_label'], 'subtitle' => $subtitle_dco, 'condition' => $serialRow_dco['dp_condition'], 'name' => $serialRow_dco['dp_name'], 'specificSKU' => $serialRow_dco['dp_sku']];
    $finalResult[$index] = $newArr_dco;
    $index++;
}
//echo "index ".$index;
echo json_encode($finalResult);
$conn->close();
?>
