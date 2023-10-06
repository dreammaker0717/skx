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

$sql_acc = "SELECT acc_stock.ast_servicetag, acc_stock.ast_status, aproducts.apr_box_label, aproducts.apr_condition FROM acc_stock INNER JOIN aproducts on acc_stock.ast_product = aproducts.apr_id WHERE acc_stock.ast_servicetag ='".$serial."'";
$result_acc = $conn->query($sql_acc);
if($serialRow_acc = $result_acc->fetch_array()) {
    $newArr_acc = ['serial' => $serialRow_acc['ast_servicetag'], 'sku' => $serialRow_acc['apr_box_label'], 'status' => $serialRow_acc['ast_status'], 'condition' => $serialRow_acc['apr_condition']];
    echo json_encode($newArr_acc);
} else {
    $sql_nwp = "SELECT nwp_stock.nst_servicetag, nwp_stock.nst_status, nwp_products.npr_box_label, nwp_products.npr_condition FROM nwp_stock INNER JOIN nwp_products on nwp_stock.nst_product = nwp_products.npr_id WHERE nwp_stock.nst_servicetag ='".$serial."'";
    $result_nwp = $conn->query($sql_nwp);
    if($serialRow_nwp = $result_nwp->fetch_array()) {
        $newArr_nwp = ['serial' => $serialRow_nwp['nst_servicetag'], 'sku' => $serialRow_nwp['npr_box_label'], 'status' => $serialRow_nwp['nst_status'], 'condition' => $serialRow_nwp['npr_condition']];
        echo json_encode($newArr_nwp);
    } else {
        $sql_dco = "SELECT dco_stock.dst_servicetag, dco_stock.dst_status, dell_part.dp_box_label, dell_part.dp_condition FROM dco_stock INNER JOIN dell_part on dco_stock.dst_product = dell_part.dp_id WHERE dco_stock.dst_servicetag ='".$serial."'";
        $result_dco = $conn->query($sql_dco);
        if($serialRow_dco = $result_dco->fetch_array()) {
            $newArr_dco = ['serial' => $serialRow_dco['dst_servicetag'], 'sku' => $serialRow_dco['dp_box_label'], 'status' => $serialRow_dco['dst_status'], 'condition' => $serialRow_dco['dp_condition']];
            echo json_encode($newArr_dco);
        }
    }
}

$conn->close();
?>
