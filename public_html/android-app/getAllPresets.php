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

$isInternational = $_GET["isInternational"];
$index = 0;
$finalResult = array();

$sql = "";
if($isInternational == "true"){
  $sql = "SELECT * FROM shipping_presets WHERE international = 1 ORDER BY sort_order ASC";
} else {
  $sql = "SELECT * FROM shipping_presets WHERE international = 0 ORDER BY sort_order ASC";
}
$result = $conn->query($sql);
while($preset = $result->fetch_assoc()) {
    $preset['weight'] = intval($preset['weight']);
    $weight = ["value"=> $preset['weight'], "units"=> "grams"];
    $dimensions = ["length"=> $preset['length'], "width"=> $preset['width'], "height"=> $preset['height'], "units"=> "centimeters"];
    $shipfrom = ["name"=> $preset['name'], "company"=> $preset['company'], "street1"=> $preset['street1'], "street2"=> $preset['street2'], "street3"=> $preset['street3'], "city"=> $preset['city'], "state"=> $preset['state'], "postalCode"=> $preset['postal_code'], "country"=> $preset['country'], "phone"=> $preset['phone'], "residential"=> $preset['residential']];
    $newArr = ["presetName"=> $preset['preset_name'], "colorCode"=> $preset['color'], "carrierCode"=> $preset['carrier_code'], "carrierName"=> $preset['carrier_name'], "serviceCode"=> $preset['service_code'], "serviceName"=> $preset['service_name'], "packageCode"=> $preset['package_code'], "packageName"=> $preset['package_name'], "weight"=> $weight, "dimensions"=> $dimensions, "shipFrom"=> $shipfrom, "testLabel"=> true];
    $finalResult[$index] = $newArr;
    $index++;
}
echo json_encode($finalResult);
$conn->close();
?>
