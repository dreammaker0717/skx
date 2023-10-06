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
$item = [];

$sql_acc = "SELECT acc_stock.ast_servicetag, acc_stock.ast_status, aproducts.apr_condition FROM acc_stock INNER JOIN aproducts on acc_stock.ast_product = aproducts.apr_id WHERE acc_stock.ast_servicetag ='".$serial."'";
$result_acc = $conn->query($sql_acc);
if($serialRow_acc = $result_acc->fetch_array()) {
    $item = ['status' => $serialRow_acc['ast_status'], 'condition' => $serialRow_acc['apr_condition'], 'serial' => $serialRow_acc['ast_servicetag']];
} else {
    $sql_dco = "SELECT dco_stock.dst_servicetag, dco_stock.dst_status, dell_part.dp_condition FROM dco_stock INNER JOIN dell_part on dco_stock.dst_product = dell_part.dp_id WHERE dco_stock.dst_servicetag ='".$serial."'";
    $result_dco = $conn->query($sql_dco);
    if($serialRow_dco = $result_dco->fetch_array()) {
        $item = ['status' => $serialRow_dco['dst_status'], 'condition' => $serialRow_dco['dp_condition'], 'serial' => $serialRow_acc['dst_servicetag']];
    }
}

if(count($item) != 0){
    if($item['status'] == 1 || $item['status'] == 2 || $item['status'] == 3 || $item['status'] == 4 || $item['status'] == 6 || $item['status'] == 22 || $item['status'] ==7){
        $str = $item['condition'];
        $pattern = "/new/";
        $isNew = preg_match($pattern, $str);
        if($item['status'] == 6 && $item['condition'] == "Refurbished (Grade B)"){
             echo "error 1";
        } else if($item['status'] == 22 && $item['condition'] == "Refurbished"){
             echo "error 1";
        } else if($item['status'] == 7 && $isNew == 1){
             echo "error 1";
        } else {
            echo json_encode($item);
        }
    } else {
        echo "error 0";
    }
} else {
    echo "error 0";
}
?>