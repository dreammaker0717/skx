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

$sql = "SELECT dst_id FROM dco_stock WHERE dst_servicetag = '".$serial."'";
$result = $conn->query($sql);
//$count=$result->num_rows;
if($result->num_rows > 0){
    echo "yes";
} else {
    echo "no";
}
$conn->close();
?>