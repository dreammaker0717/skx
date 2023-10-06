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
$dor_supplier = $_POST['dor_supplier'];
$dor_state = $_POST['dor_state'];
$dor_total_items = $_POST['dor_total_items'];
$dor_total_delivered = $_POST['dor_total_delivered'];
$dor_fix_rate = $_POST['dor_fix_rate'];
$dor_refernce = $_POST['dor_refernce'];

date_default_timezone_set('Europe/London');
$date = date('Y-m-d', time());
$sql = "INSERT INTO dco_orders (dor_date, dor_supplier, dor_state, dor_total_items, dor_total_delivered, dor_fix_rate, dor_reference, dor_type) VALUES ('".$date."', '".$dor_supplier."','".$dor_state."','".$dor_total_items."','".$dor_total_delivered."','".$dor_fix_rate."','".$dor_refernce."', 'Stripped')";
$conn->query($sql);
echo $conn->insert_id;
$conn->close();
?>
