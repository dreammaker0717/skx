<?php

$db = M::db();
//print_r($_POST);exit;
 $customerQuery = "SELECT * FROM customers WHERE c_name LIKE '%".$_POST['data']['keyword']."' OR c_email LIKE  '%".$_POST['data']['keyword']."%'";
$customerData = $db->query($customerQuery)->fetchAll();



$msg = "";
if($customerData){

$msg.='<ul id="supplier-list">';
$cnt =0;
foreach ($customerData as $supp) {
	$email = $supp["c_name"];
  $id = $supp["customer_id"];
	$msg.='<li class="suppclick" onClick=selectCustomer("'.$email.'","'.$id.'")>
      '.$supp["c_name"].'
    </li>';
		$cnt++;
}
$msg.='</ul>';
echo $msg;

}
exit();

?>
