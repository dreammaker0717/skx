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

$sql = "SELECT printer_name, printer_ip FROM devices";
$result = $conn->query($sql);
while($printerRow = $result->fetch_assoc()) {
    $newArr = ['printerName' => $printerRow['printer_name'], 'printerIP' => $printerRow['printer_ip']];
    $finalResult[$index] = $newArr;
    $index++;
}
echo json_encode($finalResult);
$conn->close();
?>
