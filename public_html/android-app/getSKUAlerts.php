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

$skuList = json_decode($_POST['payload'], true);
$index = 0;
$finalResult = array();

for ($i = 0; $i < count($skuList); $i++) {
    $sql = "select skualerts.sku, skualerts_messages.message from skualerts left join skualerts_messages on skualerts.message_id = skualerts_messages.id where skualerts.sku = '" . $skuList[$i] . "'";
    //error_log($sql);
    $result = $conn->query($sql);
    if ($result) {
        while ($messageRow = $result->fetch_assoc()) {
            $newArr = ['sku' => $messageRow['sku'], 'message' => $messageRow['message']];
            $finalResult[$index] = $newArr;
            $index++;
        }
    }
}
echo json_encode($finalResult);
$conn->close();
