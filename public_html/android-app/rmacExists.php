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

$rmacID = $_GET['rmacID'];

$sql = "SELECT rmac_ID FROM rmac_items WHERE rmac_ID = ".$rmacID;


$result = $conn->query($sql);
if($rmacRow = $result->fetch_assoc()) {
    echo json_encode('{ "success" : true}');
} else {
	echo json_encode('{ "success" : false}');
}

$conn->close();
?>