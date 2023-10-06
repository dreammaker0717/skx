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
$boxlabel = $_GET['boxlabel'];
$sql_acc = "SELECT apr_name FROM aproducts WHERE apr_box_label = '".$boxlabel."'";
$result_acc = $conn->query($sql_acc);
if($serialRow_acc = $result_acc->fetch_array() ){
    $name_acc = preg_replace('/[^A-Za-z0-9 ,\(\)\\\\\/\+\-\_\&]/', '', $serialRow_acc['apr_name']);
    $newArr_acc = ['name' => $name_acc];
    echo json_encode($newArr_acc);
} else {
    $sql_nwp = "SELECT npr_name FROM nwp_products WHERE npr_box_label = '".$boxlabel."'";
    $result_nwp = $conn->query($sql_nwp);
    if($serialRow_nwp = $result_nwp->fetch_array() ){
        $name_nwp = preg_replace('/[^A-Za-z0-9 ,\(\)\\\\\/\+\-\_\&]/', '', $serialRow_nwp['npr_name']);
        $newArr_nwp = ['name' => $name_nwp];
        echo json_encode($newArr_nwp);
    } else {
        $sql_dco = "SELECT dp_name FROM dell_part WHERE dp_box_label = '".$boxlabel."'";
        $result_dco = $conn->query($sql_dco);
        if($serialRow_dco = $result_dco->fetch_array() ){
            $name_dco = preg_replace('/[^A-Za-z0-9 ,\(\)\\\\\/\+\-\_\&]/', '', $serialRow_dco['dp_name']);
            $newArr_dco = ['name' => $name_dco];
            echo json_encode($newArr_dco);
        }
    }
}
$conn->close();
?>
