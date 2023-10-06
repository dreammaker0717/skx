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
$json = $_POST['json'];
$obj = json_decode($json, true);

for($i = 0; $i < count($obj); $i++){
  $sql_acc = "SELECT ast_id FROM acc_stock WHERE ast_servicetag = '" . $obj[$i]["serial"]."'";
  $result_acc = $conn->query($sql_acc);
  if($serialRow_acc = $result_acc->fetch_array() ){
      $updateSQL_acc = "UPDATE acc_stock SET ast_status = " . $obj[$i]["status"] . " WHERE ast_servicetag = '" . $obj[$i]["serial"] . "'";
      $conn->query($updateSQL_acc);
  } else {
      $sql_nwp = "SELECT nst_id FROM nwp_stock WHERE nst_servicetag = '" . $obj[$i]["serial"]."'";
      $result_nwp = $conn->query($sql_nwp);
      if($serialRow_nwp = $result_nwp->fetch_array() ){
          $updateSQL_nwp = "UPDATE nwp_stock SET nst_status = " . $obj[$i]["status"]. " WHERE ast_servicetag = '" . $obj[$i]["serial"] . "'";
          $conn->query($updateSQL_nwp);
      } else {
          $sql_dco = "SELECT dst_id FROM dco_stock WHERE dst_servicetag = '" . $obj[$i]["serial"]."'";
          $result_dco = $conn->query($sql_dco);
          if($serialRow_dco = $result_dco->fetch_array() ){
              $updateSQL_dco = "UPDATE dco_stock SET dst_status = " . $obj[$i]["status"]. " WHERE ast_servicetag = '" . $obj[$i]["serial"] . "'";
              $conn->query($updateSQL_dco);
          }
      }
  }
}

$conn->close();
?>
