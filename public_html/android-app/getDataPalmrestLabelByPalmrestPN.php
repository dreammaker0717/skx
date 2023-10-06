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

$index = 0;
$finalResult = array();

$sql_dco = "SELECT dp_box_label, dp_box_subtitle, dp_condition, dp_name FROM dell_part WHERE dp_mpn REGEXP '".$palmrestpn."' AND dp_sku REGEXP '^PLM\/DELL\/XP' AND dp_condition = 'Removed from New Laptop'";


$result_dco = $conn->query($sql_dco);
if($serialRow_dco = $result_dco->fetch_array()) {
    $subtitle_dco = preg_replace('/[^A-Za-z0-9 ,\(\)\\\\\/\+\-\_\&]/', '', $serialRow_dco['dp_box_subtitle']);
    $newArr_dco = ['sku' => $serialRow_dco['dp_box_label'], 'subtitle' => $subtitle_dco, 'condition' => $serialRow_dco['dp_condition'], 'name' => $serialRow_dco['dp_name']];
    echo json_encode($newArr_dco);
}
//echo "index ".$index;

$conn->close();
?>
