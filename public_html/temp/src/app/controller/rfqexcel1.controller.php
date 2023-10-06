<?php
use alhimik1986\PhpExcelTemplator\params\CallbackParam;
use alhimik1986\PhpExcelTemplator\params\ExcelParam;
use alhimik1986\PhpExcelTemplator\PhpExcelTemplator;
use alhimik1986\PhpExcelTemplator\setters\CellSetterArrayValueSpecial;


require_once "vendor/autoload.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'mailer/src/Exception.php';
require 'mailer/src/PHPMailer.php';
require 'mailer/src/SMTP.php';

$db = M::db();

define("ROOTPATH", __DIR__);

define("SPECIAL_ARRAY_TYPE", CellSetterArrayValueSpecial::class);


$rfqQuery = "SELECT rfq_rfq.rfq_date, rfq_rfq.rfq_reference, users.fullname, users.email FROM rfq_rfq LEFT JOIN users ON rfq_rfq.rfq_user = users.user_id WHERE rfq_id=" . $part;
$rfqData = $db->query($rfqQuery)->fetchAll();

$arrayID = array();
$dataSKU = array();
$dataName = array();
$dataQuantity = array();
$typeQuery = "SELECT id, rfq_prodtype, rfq_product, rfq_quantity, rfq_price FROM rfq_items WHERE rfq_id=" . $part;
$typeData = $db->query($typeQuery)->fetchAll();

$index = 0;
$qtySum = 0;
$supplierComments = null;
foreach ($typeData as $type) {
	$supplierCommentsQuery = "SELECT rfq_suppliercomments FROM rfq_items WHERE rfq_product=" . $type['rfq_product'] . " AND rfq_prodtype=" . $type['rfq_prodtype'] . " AND rfq_id=" . $part;

	if ($supplierCommentsIntermediate = $db->query($supplierCommentsQuery)) {
		$supplierCommentsData = $supplierCommentsIntermediate->fetchAll();
		$supplierComments = $supplierCommentsData[0]['rfq_suppliercomments'];
	}

	$qtySum += $type['rfq_quantity'];
	$arrayID[$index] = $index + 1;

	$partQuery = null;

	if ($type['rfq_prodtype'] == 1) {
		$partQuery = "SELECT npr_name, npr_sku FROM nwp_products WHERE npr_id=" . $type['rfq_product'];
		$partData = $db->query($partQuery)->fetchAll();
		$newArr = ['name' => $partData[0]['npr_name'], 'sku' => $partData[0]['npr_sku'], 'quantity' => $type['rfq_quantity'], "suppliercomments" => $supplierComments];

		$dataSKU[$index] = $newArr['sku'];
		if ($supplierComments != "") {
			$dataName[$index] = $newArr['name'] . "\n(" . $supplierComments . ")";
		} else {
			$dataName[$index] = $newArr['name'];
		}
		$dataQuantity[$index] = $newArr['quantity'];
		$index++;
	} else if ($type['rfq_prodtype'] == 2) {
		$partQuery = "SELECT npr2_name, npr2_sku FROM nwp_products2 WHERE npr2_id=" . $type['rfq_product'];
		$partData = $db->query($partQuery)->fetchAll();
		$newArr = ['name' => $partData[0]['npr2_name'], 'sku' => $partData[0]['npr2_sku'], 'quantity' => $type['rfq_quantity'], "suppliercomments" => $supplierComments];

		$dataSKU[$index] = $newArr['sku'];
		if ($supplierComments != "") {
			$dataName[$index] = $newArr['name'] . "\n(" . $supplierComments . ")";
		} else {
			$dataName[$index] = $newArr['name'];
		}
		$dataQuantity[$index] = $newArr['quantity'];
		$index++;
	} else if ($type['rfq_prodtype'] == 3) {
		$partQuery = "SELECT apr_name, apr_sku FROM aproducts WHERE apr_id=" . $type['rfq_product'];
		$partData = $db->query($partQuery)->fetchAll();
		$newArr = ['name' => $partData[0]['apr_name'], 'sku' => $partData[0]['apr_sku'], 'quantity' => $type['rfq_quantity'], "suppliercomments" => $supplierComments];

		$dataSKU[$index] = $newArr['sku'];
		if ($supplierComments != "") {
			$dataName[$index] = $newArr['name'] . "\n(" . $supplierComments . ")";
		} else {
			$dataName[$index] = $newArr['name'];
		}
		$dataQuantity[$index] = $newArr['quantity'];
		$index++;
	} else if ($type['rfq_prodtype'] == 4) {
		$partQuery = "SELECT dp_name, dp_sku FROM dell_part WHERE dp_id=" . $type['rfq_product'];
		$partData = $db->query($partQuery)->fetchAll();
		$newArr = ['name' => $partData[0]['dp_name'], 'sku' => $partData[0]['dp_sku'], 'quantity' => $type['rfq_quantity'], "suppliercomments" => $supplierComments];

		$dataSKU[$index] = $newArr['sku'];
		if ($supplierComments != "") {
			$dataName[$index] = $newArr['name'] . "\n(" . $supplierComments . ")";
		} else {
			$dataName[$index] = $newArr['name'];
		}
		$dataQuantity[$index] = $newArr['quantity'];
		$index++;
	}
}

$params = [
	"[id]" => new ExcelParam(SPECIAL_ARRAY_TYPE, $arrayID),
	"[SKU]" => new ExcelParam(SPECIAL_ARRAY_TYPE, $dataSKU),
	"[product_description]" => new ExcelParam(SPECIAL_ARRAY_TYPE, $dataName),
	"[rfq_qty]" => new ExcelParam(SPECIAL_ARRAY_TYPE, $dataQuantity),
	"[rfq_price]" => new ExcelParam(SPECIAL_ARRAY_TYPE, [
		"",
		"",
		"",
		"",
		"",
	]),
	"[rfq_total]" => new ExcelParam(SPECIAL_ARRAY_TYPE, [
		"",
		"",
		"",
		"",
		"",
	]),
	"{rfq_id}" => $part,
	"{rfq_date}" => $rfqData[0]['rfq_date'],
	"{rfq_reference}" => $rfqData[0]['rfq_reference'],
	"{user}" => $rfqData[0]['fullname'],
	"{user_email}" => $rfqData[0]['email'],
	"{qty_sum}" => $qtySum,
];

fopen(ROOTPATH ."/rfq_".$part.".xlsx", "w");
PhpExcelTemplator::saveToFile(ROOTPATH . "/rfq_excel_template.xlsx", ROOTPATH ."/rfq_".$part.".xlsx", $params);
$imap = "SELECT * from imap";
$imapSetting = $db->query($imap)->fetchAll();

$mail = new PHPMailer;
$mail->isSMTP();
$mail->SMTPDebug = 0;
$mail->Host = $imapSetting[0]['host'];
$mail->Port = $imapSetting[0]['port'];
$mail->SMTPSecure = 'tls';
$mail->SMTPAuth = true;
$mail->Username = $imapSetting[0]['username'];
$mail->Password = $imapSetting[0]['password'];
$mail->setFrom('info@service-x.uk','ServiceX');

if($_POST['data']['email']){
	$mail->addAddress($_POST['data']['email'], 'Name');
}

if($_POST['data']['groups']){

	for($m=0;$m<count($_POST['data']['groups']);$m++){
		$groupSupplierQuery = "SELECT * from supplier_groups_new where g_id=".$_POST['data']['groups'][$m];
		$groupSuppliers = $db->query($groupSupplierQuery)->fetchAll();
		foreach($groupSuppliers as $sup){

			$SupplierQuery = "SELECT * from suppliers where sp_id=".$sup['sg_supplier_id'];
			$Suppliers = $db->query($SupplierQuery)->fetchAll();
			foreach($Suppliers as $sp){
				if($sp['sp_email']){
					$mail->addAddress($sp['sp_email'], '');
				}
			}
		}
	}
}
$mail->AllowEmpty = true;
$mail->Subject = $_POST['data']['subject'];
$mail->msgHTML($_POST['data']['body']);
$mail->AltBody = 'HTML messaging not supported';
$mail->addAttachment(ROOTPATH ."/rfq_".$part.".xlsx");
if($mail->send()){
	echo json_encode(array('success' => true,'id'=> $part));
}else{
	echo json_encode(array('success' => false,'id'=> $part,'error'=>'Email not sent.'));
}

exit();
?>
