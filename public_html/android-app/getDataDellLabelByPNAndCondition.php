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
$pn = $_GET['pn'];
$condition = $_GET['condition'];

$index = 0;
$finalResult = array();

$sql_acc = "SELECT apr_name, apr_box_label, apr_box_subtitle FROM aproducts WHERE apr_mpn REGEXP '".$pn."' AND apr_mpn REGEXP '^[^+]*$' AND apr_condition = '".$condition."'";
$result_acc = $conn->query($sql_acc);
if($serialRow_acc = $result_acc->fetch_array() ){
    $subtitle_acc = preg_replace('/[^A-Za-z0-9 ,\(\)\\\\\/\+\-\_\&]/', '', $serialRow_acc['apr_box_subtitle']);
    $name_acc = preg_replace('/[^A-Za-z0-9 ,\(\)\\\\\/\+\-\_\&]/', '', $serialRow_acc['apr_name']);
    $newArr_acc = ['sku' => $serialRow_acc['apr_box_label'], 'subtitle' => $subtitle_acc, 'name' => $name_acc];
    $finalResult[$index] = $newArr_acc;
    $index++;
} else {
    $sql_dco = "SELECT dp_name, dp_box_label, dp_box_subtitle FROM dell_part WHERE dp_mpn REGEXP '".$pn."' AND dp_mpn REGEXP '^[^+]*$' AND dp_condition = '".$condition."'";
    $result_dco = $conn->query($sql_dco);
    if($serialRow_dco = $result_dco->fetch_array() ){
        $subtitle_dco = preg_replace('/[^A-Za-z0-9 ,\(\)\\\\\/\+\-\_\&]/', '', $serialRow_dco['dp_box_subtitle']);
        $name_dco = preg_replace('/[^A-Za-z0-9 ,\(\)\\\\\/\+\-\_\&]/', '', $serialRow_dco['dp_name']);
        $newArr_dco = ['sku' => $serialRow_dco['dp_box_label'], 'subtitle' => $subtitle_dco, 'name' => $name_dco];
        $finalResult[$index] = $newArr_dco;
        $index++;
    } else {
        $sql_nwp = "SELECT npr_name, npr_box_label, npr_box_subtitle FROM nwp_products WHERE npr_mpn REGEXP '".$pn."' AND npr_mpn REGEXP '^[^+]*$' AND npr_condition = '".$condition."'";
        $result_nwp = $conn->query($sql_nwp);
        if($serialRow_nwp = $result_nwp->fetch_array() ){
            $subtitle_nwp = preg_replace('/[^A-Za-z0-9 ,\(\)\\\\\/\+\-\_\&]/', '', $serialRow_nwp['npr_box_subtitle']);
            $name_nwp = preg_replace('/[^A-Za-z0-9 ,\(\)\\\\\/\+\-\_\&]/', '', $serialRow_nwp['npr_name']);
            $newArr_nwp = ['sku' => $serialRow_nwp['npr_box_label'], 'subtitle' => $subtitle_nwp, 'name' => $name_nwp];
            $finalResult[$index] = $newArr_nwp;
            $index++;
        }
    }
}
if(!$finalResult){
    $sql_acc = "SELECT apr_name, apr_box_label, apr_box_subtitle, apr_condition FROM aproducts WHERE apr_mpn REGEXP '".$pn."' AND apr_mpn REGEXP '^[^+]*$'";
    $result_acc = $conn->query($sql_acc);
    while($serialRow_acc = $result_acc->fetch_assoc()) {
        $subtitle_acc = preg_replace('/[^A-Za-z0-9 ,\(\)\\\\\/\+\-\_\&]/', '', $serialRow_acc['apr_box_subtitle']);
        $name_acc = preg_replace('/[^A-Za-z0-9 ,\(\)\\\\\/\+\-\_\&]/', '', $serialRow_acc['apr_name']);
        $newArr_acc = ['sku' => $serialRow_acc['apr_box_label'], 'subtitle' => $subtitle_acc, 'condition' => $serialRow_acc['apr_condition'], 'name' => $name_acc];
        $finalResult[$index] = $newArr_acc;
        $index++;
    }

    $sql_dco = "SELECT dp_name, dp_box_label, dp_box_subtitle, dp_condition FROM dell_part WHERE dp_mpn REGEXP '".$pn."' AND dp_mpn REGEXP '^[^+]*$'";
    $result_dco = $conn->query($sql_dco);
    while($serialRow_dco = $result_dco->fetch_assoc()) {
        $subtitle_dco = preg_replace('/[^A-Za-z0-9 ,\(\)\\\\\/\+\-\_\&]/', '', $serialRow_dco['dp_box_subtitle']);
        $name_dco = preg_replace('/[^A-Za-z0-9 ,\(\)\\\\\/\+\-\_\&]/', '', $serialRow_dco['dp_name']);
        $newArr_dco = ['sku' => $serialRow_dco['dp_box_label'], 'subtitle' => $subtitle_dco, 'condition' => $serialRow_dco['dp_condition'], 'name' => $name_dco];
        $finalResult[$index] = $newArr_dco;
        $index++;
    }

    $sql_nwp = "SELECT npr_name, npr_box_label, npr_box_subtitle, npr_condition FROM nwp_products WHERE npr_mpn REGEXP '".$pn."' AND npr_mpn REGEXP '^[^+]*$'";
    $result_nwp = $conn->query($sql_nwp);
    while($serialRow_nwp = $result_nwp->fetch_assoc()) {
        $subtitle_nwp = preg_replace('/[^A-Za-z0-9 ,\(\)\\\\\/\+\-\_\&]/', '', $serialRow_nwp['npr_box_subtitle']);
        $name_nwp = preg_replace('/[^A-Za-z0-9 ,\(\)\\\\\/\+\-\_\&]/', '', $serialRow_nwp['npr_name']);
        $newArr_nwp = ['sku' => $serialRow_nwp['npr_box_label'], 'subtitle' => $subtitle_nwp, 'condition' => $serialRow_nwp['npr_condition'], 'name' => $name_nwp];
        $finalResult[$index] = $newArr_nwp;
        $index++;
    }
}
echo json_encode($finalResult);
$conn->close();
?>
