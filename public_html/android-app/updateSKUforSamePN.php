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
$newID = $_GET['newID'];
$item;

$sql_acc = "SELECT acc_stock.ast_servicetag, aproducts.apr_box_label, aproducts.apr_mpn FROM acc_stock INNER JOIN aproducts on acc_stock.ast_product = aproducts.apr_id WHERE acc_stock.ast_servicetag ='".$serial."'";
$result_acc = $conn->query($sql_acc);
if($serialRow_acc = $result_acc->fetch_array()) {
    $updateSQL_acc = "UPDATE acc_stock SET ast_product = " . $newID . " WHERE ast_servicetag = '" . $serial . "'";
    $conn->query($updateSQL_acc);
} else {
    $sql_dco = "SELECT dco_stock.dst_servicetag, dell_part.dp_box_label, dell_part.dp_mpn FROM dco_stock INNER JOIN dell_part on dco_stock.dst_product = dell_part.dp_id WHERE dco_stock.dst_servicetag ='".$serial."'";
    $result_dco = $conn->query($sql_dco);
    if($serialRow_dco = $result_dco->fetch_array()) {
        $updateSQL_dco = "UPDATE dco_stock SET dst_product = " . $newID. " WHERE ast_servicetag = '" . $serial . "'";
        $conn->query($updateSQL_dco);
    }
}

$conn->close();
?>