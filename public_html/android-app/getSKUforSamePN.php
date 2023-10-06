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
$item;

$sql_acc = "SELECT acc_stock.ast_servicetag, aproducts.apr_box_label, aproducts.apr_mpn FROM acc_stock INNER JOIN aproducts on acc_stock.ast_product = aproducts.apr_id WHERE acc_stock.ast_servicetag ='".$serial."'";
$result_acc = $conn->query($sql_acc);
if($serialRow_acc = $result_acc->fetch_array()) {
    $item = ['serial' => $serialRow_acc['ast_servicetag'], 'sku' => $serialRow_acc['apr_box_label'], 'pn' => $serialRow_acc['apr_mpn']];
} else {
    $sql_dco = "SELECT dco_stock.dst_servicetag, dell_part.dp_box_label, dell_part.dp_mpn FROM dco_stock INNER JOIN dell_part on dco_stock.dst_product = dell_part.dp_id WHERE dco_stock.dst_servicetag ='".$serial."'";
    $result_dco = $conn->query($sql_dco);
    if($serialRow_dco = $result_dco->fetch_array()) {
        $item = ['serial' => $serialRow_dco['dst_servicetag'], 'sku' => $serialRow_dco['dp_box_label'], 'pn' => $serialRow_dco['dp_mpn']];
    }
}
if($item['pn'] == null){
  echo "error 0";
} else {
  $index = 0;
  $tempResult = array();
  $pns = explode(',',$item['pn']);
  for ($i=0; $i < count($pns); $i++) {
    $sql_acc = "SELECT aproducts.apr_id, aproducts.apr_box_label, aproducts.apr_sku, aproducts.apr_name, aproducts.apr_condition, acc_stock.ast_status FROM aproducts LEFT JOIN acc_stock on aproducts.apr_id = acc_stock.ast_product WHERE apr_mpn REGEXP '".trim($pns[$i])."' GROUP BY aproducts.apr_id";
    //echo $sql_acc;
    $result_acc = $conn->query($sql_acc);
    while($serialRow_acc = $result_acc->fetch_assoc()) {
        $newArr_acc = ['id' => $serialRow_acc['apr_id'], 'sku' => $serialRow_acc['apr_box_label'], 'name' => $serialRow_acc['apr_name'], 'specificSKU' => $serialRow_acc['apr_sku'], 'condition' => $serialRow_acc['apr_condition'], 'status' => $serialRow_acc['ast_status']];
        $tempResult[$index] = $newArr_acc;
        $index++;
    }

    $sql_dco = "SELECT dell_part.dp_id, dell_part.dp_box_label, dell_part.dp_sku, dell_part.dp_name, dell_part.dp_condition, dco_stock.dst_status FROM dell_part LEFT JOIN dco_stock on dell_part.dp_id = dco_stock.dst_product WHERE dp_mpn REGEXP '".trim($pns[$i])."' GROUP BY dell_part.dp_id";
    $result_dco = $conn->query($sql_dco);
    while($serialRow_dco = $result_dco->fetch_assoc()) {
        $newArr_dco = ['id' => $serialRow_dco['dp_id'], 'sku' => $serialRow_dco['dp_box_label'], 'name' => $serialRow_dco['dp_name'], 'specificSKU' => $serialRow_dco['dp_sku'], 'condition' => $serialRow_dco['dp_condition'], 'status' => $serialRow_dco['dst_status']];
        $tempResult[$index] = $newArr_dco;
        $index++;
    }
  }
  $result = array();
  $resultIndex = 1;
  $result[0] = $tempResult[0];
  for ($j=0; $j < count($tempResult); $j++) {
    $isDuplicate = false;
    for ($k=0; $k < count($result); $k++) {
      if ($tempResult[$j]['id'] == $result[$k]['id']) {
        $isDuplicate = true;
      }
    }
    if(!$isDuplicate){
      $result[$resultIndex] = $tempResult[$j];
      $resultIndex++;
    }
  }

  echo json_encode($result);
}


$conn->close();
?>
