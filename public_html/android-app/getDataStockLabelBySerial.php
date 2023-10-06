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
$sql_acc = "SELECT acc_stock.ast_servicetag, acc_stock.ast_id, acc_stock.ast_status, acc_stock.ast_lastcomment, acc_stock.ast_date, aproducts.apr_name, aproducts.apr_box_label, aproducts.apr_box_subtitle, aproducts.apr_condition FROM acc_stock INNER JOIN aproducts on acc_stock.ast_product = aproducts.apr_id WHERE acc_stock.ast_servicetag = '" . $serial."'";
$result_acc = $conn->query($sql_acc);
if($serialRow_acc = $result_acc->fetch_array() ){
    $subtitle_acc = preg_replace('/[^A-Za-z0-9 ,\(\)\\\\\/\+\-\_\&]/', '', $serialRow_acc['apr_box_subtitle']);
    $name_acc = preg_replace('/[^A-Za-z0-9 ,\(\)\\\\\/\+\-\_\&]/', '', $serialRow_acc['apr_name']);
    $newArr_acc = ['serial' => $serialRow_acc['ast_servicetag'], 'st_id' => $serialRow_acc['ast_id'], 'status' => $serialRow_acc['ast_status'], 'lastcomment' => $serialRow_acc['ast_lastcomment'], 'date' => $serialRow_acc['ast_date'], 'name' => $name_acc, 'sku' => $serialRow_acc['apr_box_label'], 'subtitle' => $subtitle_acc, 'condition' => $serialRow_acc['apr_condition']];
    echo json_encode($newArr_acc);
} else {
    $sql_nwp = "SELECT nwp_stock.nst_servicetag, nwp_stock.nst_id, nwp_stock.nst_status, nwp_stock.nst_lastcomment, nwp_stock.nst_date, nwp_products.npr_name, nwp_products.npr_box_label, nwp_products.npr_box_subtitle, nwp_products.npr_condition FROM nwp_stock INNER JOIN nwp_products on nwp_stock.nst_product = nwp_products.npr_id WHERE nwp_stock.nst_servicetag = '" . $serial."'";
    $result_nwp = $conn->query($sql_nwp);
    if($serialRow_nwp = $result_nwp->fetch_array() ){
        $subtitle_nwp = preg_replace('/[^A-Za-z0-9 ,\(\)\\\\\/\+\-\_\&]/', '', $serialRow_nwp['npr_box_subtitle']);
        $name_nwp = preg_replace('/[^A-Za-z0-9 ,\(\)\\\\\/\+\-\_\&]/', '', $serialRow_nwp['npr_name']);
        $newArr_nwp = ['serial' => $serialRow_nwp['nst_servicetag'], 'st_id' => $serialRow_nwp['nst_id'], 'status' => $serialRow_nwp['nst_status'], 'lastcomment' => $serialRow_nwp['nst_lastcomment'], 'date' => $serialRow_nwp['nst_date'], 'name' => $name_nwp, 'sku' => $serialRow_nwp['npr_box_label'], 'subtitle' => $subtitle_nwp, 'condition' => $serialRow_nwp['npr_condition']];
        echo json_encode($newArr_nwp);
    } else {
        $sql_dco = "SELECT dco_stock.dst_servicetag, dco_stock.dst_id, dco_stock.dst_status, dco_stock.dst_lastcomment, dco_stock.dst_date, dell_part.dp_name, dell_part.dp_box_label, dell_part.dp_box_subtitle, dell_part.dp_condition FROM dco_stock INNER JOIN dell_part on dco_stock.dst_product = dell_part.dp_id WHERE dco_stock.dst_servicetag = '" . $serial."'";
        $result_dco = $conn->query($sql_dco);
        if($serialRow_dco = $result_dco->fetch_array() ){
            $subtitle_dco = preg_replace('/[^A-Za-z0-9 ,\(\)\\\\\/\+\-\_\&]/', '', $serialRow_dco['dp_box_subtitle']);
            $name_dco = preg_replace('/[^A-Za-z0-9 ,\(\)\\\\\/\+\-\_\&]/', '', $serialRow_dco['dp_name']);
            $newArr_dco = ['serial' => $serialRow_dco['dst_servicetag'], 'st_id' => $serialRow_dco['dst_id'], 'status' => $serialRow_dco['dst_status'], 'lastcomment' => $serialRow_dco['dst_lastcomment'], 'date' => $serialRow_dco['dst_date'], 'name' => $name_dco, 'sku' => $serialRow_dco['dp_box_label'], 'subtitle' => $subtitle_dco, 'condition' => $serialRow_dco['dp_condition']];
            echo json_encode($newArr_dco);
        }
    }
}

$conn->close();
?>
