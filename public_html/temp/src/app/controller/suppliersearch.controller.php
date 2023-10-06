<?php

$db = M::db();
//print_r($_POST);exit;
 $supplierQuery = "SELECT * FROM suppliers WHERE sp_name LIKE '%".$_POST['data']['keyword']."' OR sp_contact LIKE  '%".$_POST['data']['keyword']."%'";
$supplierData = $db->query($supplierQuery)->fetchAll();



$msg = "";
if($supplierData){

$msg.='<ul id="supplier-list">';
$cnt =0;
foreach ($supplierData as $supp) {
	$email = $supp["sp_email"];
	$msg.='<li onClick=selectSupplier("'.$email.'")>
      '.$supp["sp_name"].'-'.$supp["sp_contact"].'
    </li>';
		$cnt++;
}
$msg.='</ul>';
echo $msg;

}
exit();

?>
