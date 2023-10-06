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
$dst_id = $_POST['dst_id'];
$image = $_POST['image'];
$dst_image = "";

$pos = "";
$sqlFindPos = "SELECT dst_defect_image1, dst_defect_image2, dst_defect_image3 FROM dco_stock WHERE dst_id = " . $dst_id;
$result = $conn->query($sqlFindPos);
if($serialRow = $result->fetch_array()) {
    if(empty($serialRow['dst_defect_image1'])){
        $pos = "1";
    } else if(empty($serialRow['dst_defect_image2'])){
        $pos = "2";
    } else if(empty($serialRow['dst_defect_image3'])){
        $pos = "3";
    }
}

if($pos != ""){
    if (!empty($image)) {
        $ImagePath = "/home/skx/public_html/dst-part-images/". $dst_id ."_" . $pos . ".jpg";
        $dst_image = $ImagePath;
        file_put_contents($ImagePath,base64_decode($image));
    }
}

$sqlUpdate = "UPDATE dco_stock SET dst_defect_image". $pos . " = '" . $dst_image . "' WHERE dst_id = " . $dst_id;
$conn->query($sqlUpdate);

echo $conn->insert_id;
$conn->close();
?>