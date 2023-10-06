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

$sku = $_GET['sku'];
$index = 0;
$finalResult = array();

$sql_apr = "SELECT apr_id FROM aproducts WHERE apr_box_label ='".$sku."'";
$result_apr = $conn->query($sql_apr);
if($row_apr = $result_apr->fetch_array()) {
  $sql_acc = "SELECT ast_id, ast_servicetag FROM acc_stock WHERE ast_product ='".$row_apr['apr_id']."' AND (ast_status = 7 OR ast_status = 22 OR ast_status = 6)";
  $result_acc = $conn->query($sql_acc);
  while($row_acc = $result_acc->fetch_assoc()) {
      $newArr_acc = ['id' => $row_acc['ast_id'], 'serial' => $row_acc['ast_servicetag']];
      $finalResult[$index] = $newArr_acc;
      $index++;
  }
} else {
    $sql_dell = "SELECT dp_id FROM dell_part WHERE dp_box_label ='".$sku."'";
    $result_dell = $conn->query($sql_dell);
    if($row_dell = $result_dell->fetch_array()) {
      $sql_dco = "SELECT dst_id, dst_servicetag FROM dco_stock WHERE dst_product ='".$row_dell['dp_id']."' AND (dst_status = 7 OR dst_status = 22 OR dst_status = 6)";
      $result_dco = $conn->query($sql_dco);
      while($row_dco = $result_dco->fetch_assoc()) {
          $newArr_dco = ['id' => $row_dco['dst_id'], 'serial' => $row_dco['dst_servicetag']];
          $finalResult[$index] = $newArr_dco;
          $index++;
      }
    } else {
      $sql_npr = "SELECT npr_id FROM nwp_products WHERE npr_box_label ='".$sku."'";
      $result_npr = $conn->query($sql_npr);
      if($row_npr = $result_npr->fetch_array()) {
        $sql_nwp = "SELECT nst_id, nst_servicetag FROM nwp_stock WHERE nst_product ='".$row_npr['npr_id']."' AND (nst_status = 7 OR nst_status = 22 OR nst_status = 6)";
        $result_nwp = $conn->query($sql_nwp);
        while($row_nwp = $result_nwp->fetch_assoc()) {
            $newArr_nwp = ['id' => $row_nwp['nst_id'], 'serial' => $row_nwp['nst_servicetag']];
            $finalResult[$index] = $newArr_nwp;
            $index++;
        }
      }
    }
}

echo json_encode($finalResult);
$conn->close();
?>
