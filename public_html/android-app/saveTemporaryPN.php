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
$dp_sku = $_POST['dp_sku'];
$dp_name = $_POST['dp_name'];
$dp_image = $_POST['dp_image'];
$dp_supplier_stock_code = $_POST['dp_supplier_stock_code'];
$dp_condition = $_POST['dp_condition'];
$dp_mpn = $_POST['dp_mpn'];
$image = $_POST['image'];

$sqL = "INSERT INTO dell_part (dp_sku, dp_name, dp_box_label, dp_box_subtitle, dp_supplier_stock_code, dp_condition, dp_keyboard_language, dp_image, dp_mpn) VALUES ('".$dp_sku."', '".$dp_name."', 'XXXX', 'XXXX', '".$dp_supplier_stock_code."', '".$dp_condition."','XXXX', '".$dp_image."', '".$dp_mpn."')";

$conn->query($sqL);

$insert_id = $conn->insert_id;
$ImagePath = "";
if (!empty($image)) {
    $ImagePath = "/home/skx/public_html/new-part-images/". $insert_id . "-" . $dp_mpn .".jpg";
    file_put_contents($ImagePath,base64_decode($image));
}
$sqlImg = "UPDATE dell_part SET dp_image = '" .$ImagePath. "' WHERE dp_id = " . $insert_id;
$conn->query($sqlImg);

echo $insert_id;
$conn->close();
?>