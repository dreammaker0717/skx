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
$pin = $_GET['pin'];

$sql = "SELECT user_id FROM users WHERE pin = ".$pin;
$result = $conn->query($sql);
if($row = $result->fetch_array()) {
    echo $row['user_id'];
} else {
  echo "error 0";
}

$conn->close();
?>
