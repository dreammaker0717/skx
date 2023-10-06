<?php
use alhimik1986\PhpExcelTemplator\params\CallbackParam;
use alhimik1986\PhpExcelTemplator\params\ExcelParam;
use alhimik1986\PhpExcelTemplator\PhpExcelTemplator;
use alhimik1986\PhpExcelTemplator\setters\CellSetterArrayValueSpecial;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once("vendor/autoload.php");
require_once(PATH_APP."/cli/inv_table.php");

require_once("mailer/src/Exception.php");
require_once("mailer/src/PHPMailer.php");
require_once("mailer/src/SMTP.php");

$db=M::db();

if ($action == "rfq_excel" || $action == "email_rfq_excel") {

	define("SPECIAL_ARRAY_TYPE", CellSetterArrayValueSpecial::class);

	$rfqQuery = "SELECT rfq_rfq.rfq_date, rfq_rfq.rfq_reference, users.fullname, users.email FROM rfq_rfq LEFT JOIN users ON rfq_rfq.rfq_user = users.user_id WHERE rfq_id=" . $part;
	$rfqData = $db->query($rfqQuery)->fetchAll();

	$arrayID = array();
	$emptyRow = array();
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
		$emptyRow[$index] = "";

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
		"[rfq_price]" => new ExcelParam(SPECIAL_ARRAY_TYPE, $emptyRow),
		"[rfq_total]" => new ExcelParam(SPECIAL_ARRAY_TYPE, $emptyRow),
		"{rfq_id}" => $part,
		"{rfq_date}" => $rfqData[0]['rfq_date'],
		"{rfq_reference}" => $rfqData[0]['rfq_reference'],
		"{user}" => $rfqData[0]['fullname'],
		"{user_email}" => $rfqData[0]['email'],
		"{qty_sum}" => $qtySum,
	];
	if ($action == "rfq_excel") {
		PhpExcelTemplator::outputToFile(__DIR__ . "/rfq_excel_template.xlsx", "./rfq_" . $part . ".xlsx", $params);
	} else if($action == "email_rfq_excel"){
		PhpExcelTemplator::saveToFile(__DIR__ . "/rfq_excel_template.xlsx", "./rfq_temp.xlsx", $params);
		$userInfo = $db->get("users","*",["user_id"=>$_SESSION["user_id"]]);
		$imap = "SELECT * from imap";
		$imapSetting = $db->query($imap)->fetchAll();

		$mail = new PHPMailer(true);
		$mail->isSMTP();
		$mail->SMTPDebug = 0;
		$mail->Host = $imapSetting[0]['host'];
		$mail->Port = $imapSetting[0]['port'];
		$mail->SMTPSecure = 'tls';
		$mail->SMTPAuth = true;

		$mail->Username = $userInfo['email'];
		$mail->Password = $userInfo['email_password'];
		$mail->setFrom($userInfo['email'],'ServiceX');
		$mail->addReplyTo($userInfo['email'], $userInfo['username']);

		if($_POST['email']){
			$mail->addAddress($_POST['email'], 'Name');
		}

		if($_POST['groups']){
			for($m=0;$m<count($_POST['groups']);$m++){
				$groupSupplierQuery = "SELECT * from supplier_groups_new where g_id=".$_POST['groups'][$m];
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
		$mail->Subject = $_POST['subject'];
		$mail->msgHTML($_POST['body']);
		$mail->AltBody = 'HTML messaging not supported';
		$mail->addAttachment("./rfq_temp.xlsx", "Request_For_Quote_ID- ".$part.".xlsx");
		if($mail->send()){
			unlink("./rfq_temp.xlsx");
			echo json_encode(array('success' => true,'id'=> $part));
		}else{
			unlink("./rfq_temp.xlsx");
			echo json_encode(array('success' => false,'id'=> $part,'error'=>'Email not sent.'));
		}
	} else{
		exit();
	}
} else if($action == "rfq_pdf"){
	$rfqQuery = "SELECT rfq_date, rfq_reference, rfq_currency FROM rfq_rfq WHERE rfq_id=" . $part;
	$rfqData = $db->query($rfqQuery)->fetchAll();
	$pdf = new PDF_INV_Table();
    $pdf->SetMargins(6,6);
    $pdf->AddPage();
    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(40,4,"ServiceX LTD",0,1);
    $pdf->SetFont('Arial','',8);
    $pdf->Cell(40,4,"4 Redgate Lane",0,1);
    $pdf->Cell(40,4,"Manchester",0,1);
    $pdf->Cell(40,4,"M12 4RY",0,1);
    $pdf->Cell(40,4,"United Kingdom",0,1);

	$pdf->Ln(4);
    $pdf->SetFont('Arial','B',10);
	$pdf->Cell(36,6,'Order Date',0,0,'L');
	$pdf->Cell(96,6,'Reference',0,0,'L');
	$pdf->Cell(66,6,'Currency',0,1,'L');
    $pdf->SetFont('Arial','',10);
	$pdf->Cell(36,6,$rfqData[0]['rfq_date'],0,0,'L');
    $nbr = $pdf->NbLines(96,$rfqData[0]['rfq_reference']);
    $hr = $nbr*6;
    $xr = $pdf->GetX();
    $yr = $pdf->GetY();
	$pdf->MultiCell(96,$hr,$rfqData[0]['rfq_reference'],0,'L');
	$pdf->SetXY($xr+96,$yr);
	$pdf->Cell(66,6,$rfqData[0]['rfq_currency'],0,1,'L');

	$typeQuery = "SELECT id, rfq_prodtype, rfq_product, rfq_quantity, rfq_price FROM rfq_items WHERE rfq_id=" . $part;
    $typeData = $db->query($typeQuery)->fetchAll();
    $price = 0;
    $index = 0;
    $supplierComments = null;
    foreach ($typeData as $type) {
        $lastRfqQuery = "SELECT rfq_items.rfq_price, rfq_items.rfq_suppliercomments FROM rfq_items LEFT JOIN rfq_rfq ON rfq_items.rfq_id = rfq_rfq.rfq_id WHERE rfq_product=" . $type['rfq_product'] . " AND rfq_prodtype=" . $type['rfq_prodtype'] . " ORDER BY rfq_rfq.rfq_date DESC";

	    $lastRfqIntermediate = $db->query($lastRfqQuery);
	    if ($lastRfqIntermediate) {
	        $lastRfqData = $lastRfqIntermediate->fetchAll();
	        $price = $lastRfqData[0]['rfq_price'];
	        $supplierComments = $lastRfqData[0]['rfq_suppliercomments'];
	    }
        $partQuery = null;
        if ($type['rfq_prodtype'] == 1) {
            $partQuery = "SELECT npr_name, npr_condition, npr_sku, npr_magqty, npr_suppliercomments FROM nwp_products WHERE npr_id=" . $type['rfq_product'];
            $partData = $db->query($partQuery)->fetchAll();
            $nwpStockQuery = "SELECT COUNT(*) as c FROM nwp_stock WHERE (nst_status = 7 OR nst_status = 22 OR nst_status = 6) AND nst_product = " . $type['rfq_product'];
            $nwpStockData = $db->query($nwpStockQuery)->fetchAll();
            $quantity = 0;
            $arrived = 0;
            $nwpOrderQuery = "SELECT rfq_orderproducts.rfqop_quantity, rfq_orderproducts.rfqop_arrived, rfq_orders.rfqo_state FROM rfq_orderproducts LEFT JOIN rfq_orders ON rfq_orderproducts.rfqo_id = rfq_orders.rfqo_id WHERE rfq_orderproducts.rfqop_prodtype = 1 AND rfq_orderproducts.rfqop_product = " . $type['rfq_product'];
            if ($nwpOrderData = $db->query($nwpOrderQuery)->fetchAll()) {
                foreach ($nwpOrderData as $entry) {
                    if ($entry['rfqo_state'] == "On Order" || $entry['rfqo_state'] == "Part Arrived") {
                        $quantity+= $entry['rfqop_quantity'];
                        $arrived+= $entry['rfqop_arrived'];
                    }
                }
            }
            $newArr = ['name' => $partData[0]['npr_name'], 'condition' => $partData[0]['npr_condition'], 'sku' => $partData[0]['npr_sku'], 'quantity' => $type['rfq_quantity'], 'invqty' => $nwpStockData[0]['c'], 'magqty' => $partData[0]['npr_magqty'], 'orderqty' => ($quantity - $arrived), 'price' => $price, "prodtype" => 1, "id" => $type['rfq_product'], "suppliercomments" => $supplierComments, "product_suppliercomments" => $partData[0]['npr_suppliercomments']];
            $data[$index] = $newArr;
            $index++;
        } else if ($type['rfq_prodtype'] == 2) {
            $partQuery = "SELECT npr2_name, npr2_condition, npr2_sku, npr2_magqty, npr2_suppliercomments FROM nwp_products2 WHERE npr2_id=" . $type['rfq_product'];
            $partData = $db->query($partQuery)->fetchAll();
            $quantity = 0;
            $arrived = 0;
            $nwp2OrderQuery = "SELECT rfq_orderproducts.rfqop_quantity, rfq_orderproducts.rfqop_arrived, rfq_orders.rfqo_state FROM rfq_orderproducts LEFT JOIN rfq_orders ON rfq_orderproducts.rfqo_id = rfq_orders.rfqo_id WHERE rfq_orderproducts.rfqop_prodtype = 2 AND rfq_orderproducts.rfqop_product = " . $type['rfq_product'];
            if ($nwp2OrderData = $db->query($nwp2OrderQuery)->fetchAll()) {
                foreach ($nwp2OrderData as $entry) {
                    if ($entry['rfqo_state'] == "On Order" || $entry['rfqo_state'] == "Part Arrived") {
                        $quantity+= $entry['rfqop_quantity'];
                        $arrived+= $entry['rfqop_arrived'];
                    }
                }
            }
            $newArr = ['name' => $partData[0]['npr2_name'], 'condition' => $partData[0]['npr2_condition'], 'sku' => $partData[0]['npr2_sku'], 'quantity' => $type['rfq_quantity'], 'invqty' => 0, 'magqty' => $partData[0]['npr2_magqty'], 'orderqty' => ($quantity - $arrived), 'price' => $price, "prodtype" => 2, "id" => $type['rfq_product'], "suppliercomments" => $supplierComments, "product_suppliercomments" => $partData[0]['npr2_suppliercomments']];
            $data[$index] = $newArr;
            $index++;
        } else if ($type['rfq_prodtype'] == 3) {
            $partQuery = "SELECT apr_name, apr_condition, apr_sku, apr_magqty, apr_suppliercomments FROM aproducts WHERE apr_id=" . $type['rfq_product'];
            $partData = $db->query($partQuery)->fetchAll();
            $aprStockQuery = "SELECT COUNT(*) as c FROM acc_stock WHERE (ast_status = 7 OR ast_status = 22 OR ast_status = 6) AND ast_product = " . $type['rfq_product'];
            $aprStockData = $db->query($aprStockQuery)->fetchAll();
            $quantity = 0;
            $arrived = 0;
            $aprOrderQuery = "SELECT rfq_orderproducts.rfqop_quantity, rfq_orderproducts.rfqop_arrived, rfq_orders.rfqo_state FROM rfq_orderproducts LEFT JOIN rfq_orders ON rfq_orderproducts.rfqo_id = rfq_orders.rfqo_id WHERE rfq_orderproducts.rfqop_prodtype = 3 AND rfq_orderproducts.rfqop_product = " . $type['rfq_product'];
            if ($aprOrderData = $db->query($aprOrderQuery)->fetchAll()) {
                foreach ($aprOrderData as $entry) {
                    if ($entry['rfqo_state'] == "On Order" || $entry['rfqo_state'] == "Part Arrived") {
                        $quantity+= $entry['rfqop_quantity'];
                        $arrived+= $entry['rfqop_arrived'];
                    }
                }
            }
            $newArr = ['name' => $partData[0]['apr_name'], 'condition' => $partData[0]['apr_condition'], 'sku' => $partData[0]['apr_sku'], 'quantity' => $type['rfq_quantity'], 'invqty' => $aprStockData[0]['c'], 'magqty' => $partData[0]['apr_magqty'], 'orderqty' => ($quantity - $arrived), 'price' => $price, "prodtype" => 3, "id" => $type['rfq_product'], "suppliercomments" => $supplierComments, "product_suppliercomments" => $partData[0]['apr_suppliercomments']];
            $data[$index] = $newArr;
            $index++;
        } else if ($type['rfq_prodtype'] == 4) {
            $partQuery = "SELECT dp_name, dp_condition, dp_sku, dp_magqty, dp_suppliercomments FROM dell_part WHERE dp_id=" . $type['rfq_product'];
            $partData = $db->query($partQuery)->fetchAll();
            $dpStockQuery = "SELECT COUNT(*) as c FROM dco_stock WHERE (dst_status = 7 OR dst_status = 22 OR dst_status = 6) AND dst_product = " . $type['rfq_product'];
            $dpStockData = $db->query($dpStockQuery)->fetchAll();
            $quantity = 0;
            $arrived = 0;
            $dpOrderQuery = "SELECT rfq_orderproducts.rfqop_quantity, rfq_orderproducts.rfqop_arrived, rfq_orders.rfqo_state FROM rfq_orderproducts LEFT JOIN rfq_orders ON rfq_orderproducts.rfqo_id = rfq_orders.rfqo_id WHERE rfq_orderproducts.rfqop_prodtype = 4 AND rfq_orderproducts.rfqop_product = " . $type['rfq_product'];
            if ($dpOrderData = $db->query($dpOrderQuery)->fetchAll()) {
                foreach ($dpOrderData as $entry) {
                    if ($entry['rfqo_state'] == "On Order" || $entry['rfqo_state'] == "Part Arrived") {
                        $quantity+= $entry['rfqop_quantity'];
                        $arrived+= $entry['rfqop_arrived'];
                    }
                }
            }
            $newArr = ['name' => $partData[0]['dp_name'], 'condition' => $partData[0]['dp_condition'], 'sku' => $partData[0]['dp_sku'], 'quantity' => $type['rfq_quantity'], 'invqty' => $dpStockData[0]['c'], 'magqty' => $partData[0]['dp_magqty'], 'orderqty' => ($quantity - $arrived), 'price' => $price, "prodtype" => 4, "id" => $type['rfq_product'], "suppliercomments" => $supplierComments, "product_suppliercomments" => $partData[0]['dp_suppliercomments']];
            $data[$index] = $newArr;
            $index++;
        }
    }

    $celldata = array();
    for ($i=0; $i < count($data); $i++) {
	    for ($j=0; $j < count($data[$i]); $j++) {
	        switch ($j) {
	            case 0:
	                $celldata[$i][$j] = $data[$i]['sku'];
	                break;
	            
	            case 1:
	                if ($data[$i]['suppliercomments'] != "") {
	                    $celldata[$i][$j] = $data[$i]['name'] . "\n(" . $data[$i]['suppliercomments'] . ")";
	                } else {
	                    if ($data[$i]['product_suppliercomments'] != "") {
	                        $celldata[$i][$j] = $data[$i]['name'] . "\n(" . $data[$i]['product_suppliercomments'] . ")";
	                    } else {
	                        $celldata[$i][$j] = $data[$i]['name'];
	                    }
	                }
	                
	                break;
	            
	            case 2:
	                $celldata[$i][$j] = $data[$i]['condition'];
	                break;
	            
	            case 3:
	                $celldata[$i][$j] = $data[$i]['quantity'];
	                break;
	            
	            case 4:
	                $celldata[$i][$j] = $data[$i]['price'];
	                break;
	            
	            case 5:
	                $celldata[$i][$j] = $data[$i]['invqty'];
	                break;
	            
	            case 6:
	                $celldata[$i][$j] = $data[$i]['magqty'];
	                break;
	            
	            case 7:
	                $celldata[$i][$j] = $data[$i]['orderqty'];
	                break;
	            
	            default:
	                $celldata[$i][$j] = "";
	                break;
	        }
	    }
	}

    $pdf->Ln(6);
    $pdf->SetLineWidth(.15);
    $width = array(23, 57, 19, 16, 21, 21, 21, 21);
    $pdf->SetWidths($width);
	$pdf->AddHeader(array("SKU", "Description", "Condition", "Qty", "Last Price", "SKX STOCK", "MAGENTO STOCK", "ON ORDER"), array('L', 'L', 'L', '', '', '', '', ''));

    $aligns = array('L', 'L', 'L', 'C', 'R', 'C', 'C', 'C');
    $pdf->SetAligns($aligns);
    $pdf->SetCellMargin(2);
	for($i=0; $i<count($celldata); $i++){
		$pdf->Row($celldata[$i], $i);
    }
	$pdf->Output("D", "order_" . $part . ".pdf");
} else if($action == "rfq_order_pdf"){
	$rfqQuery = "SELECT rfq_orders.rfqo_date, rfq_orders.rfqo_reference, rfq_orders.rfqo_currency, suppliers.sp_name FROM rfq_orders LEFT JOIN suppliers ON rfq_orders.rfqo_supplier = suppliers.sp_id WHERE rfq_orders.rfqo_id = " . $part;
	$rfqData = $db->query($rfqQuery)->fetchAll();
	$pdf = new PDF_INV_Table();
    $pdf->SetMargins(6,6);
    $pdf->AddPage();
    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(40,4,"ServiceX LTD",0,1);
    $pdf->SetFont('Arial','',8);
    $pdf->Cell(40,4,"4 Redgate Lane",0,1);
    $pdf->Cell(40,4,"Manchester",0,1);
    $pdf->Cell(40,4,"M12 4RY",0,1);
    $pdf->Cell(40,4,"United Kingdom",0,1);

	$pdf->Ln(4);
    $pdf->SetFont('Arial','B',10);
	$pdf->Cell(30,6,'Order Date',0,0,'L');
	$pdf->Cell(64,6,'Reference',0,0,'L');
	$pdf->Cell(30,6,'Currency',0,0,'L');
	$pdf->Cell(50,6,'Supplier',0,1,'L');
    $pdf->SetFont('Arial','',10);
	$pdf->Cell(30,6,$rfqData[0]['rfqo_date'],0,0,'L');
    $nbr = $pdf->NbLines(64,$rfqData[0]['rfqo_reference']);
    $hr = $nbr*6;
    $xr = $pdf->GetX();
    $yr = $pdf->GetY();
	$pdf->MultiCell(64,$hr,$rfqData[0]['rfqo_reference'],0,'L');
	$pdf->SetXY($xr+64,$yr);
	$pdf->Cell(30,6,$rfqData[0]['rfqo_currency'],0,0,'L');
	$pdf->Cell(50,6,$rfqData[0]['sp_name'],0,1,'L');

	$opQuery = "SELECT id, rfqop_prodtype, rfqop_product, rfqop_quantity, rfqop_price, rfqop_arrived, rfqop_suppliercomments FROM rfq_orderproducts WHERE rfqo_id=" . $part;
	$opData = $db->query($opQuery)->fetchAll();
	$index = 0;
	foreach ($opData as $type) {
		$partQuery = null;
		if ($type['rfqop_prodtype'] == 1) {
			$partQuery = "SELECT npr_name, npr_sku, npr_suppliercomments FROM nwp_products WHERE npr_id=" . $type['rfqop_product'];
			$partData = $db->query($partQuery)->fetchAll();
			$newArr = ["id" => $type['id'], "name" => $partData[0]['npr_name'], "sku" => $partData[0]['npr_sku'], "quantity" => $type['rfqop_quantity'], "price" => $type['rfqop_price'], "arrived" => $type['rfqop_arrived'], "suppliercomments" => $type['rfqop_suppliercomments'], "product_suppliercomments" => $partData[0]['npr_suppliercomments'], "prodtype" => 1];
			$data[$index] = $newArr;
			$index++;
		} else if ($type['rfqop_prodtype'] == 2) {
			$partQuery = "SELECT npr2_name, npr2_sku, npr2_suppliercomments FROM nwp_products2 WHERE npr2_id=" . $type['rfqop_product'];
			$partData = $db->query($partQuery)->fetchAll();
			$newArr = ["id" => $type['id'], "name" => $partData[0]['npr2_name'], "sku" => $partData[0]['npr2_sku'], "quantity" => $type['rfqop_quantity'], "price" => $type['rfqop_price'], "arrived" => $type['rfqop_arrived'], "suppliercomments" => $type['rfqop_suppliercomments'], "product_suppliercomments" => $partData[0]['npr2_suppliercomments'], "prodtype" => 2];
			$data[$index] = $newArr;
			$index++;
		} else if ($type['rfqop_prodtype'] == 3) {
			$partQuery = "SELECT apr_name, apr_sku, apr_suppliercomments FROM aproducts WHERE apr_id=" . $type['rfqop_product'];
			$partData = $db->query($partQuery)->fetchAll();
			$newArr = ["id" => $type['id'], "name" => $partData[0]['apr_name'], "sku" => $partData[0]['apr_sku'], "quantity" => $type['rfqop_quantity'], "price" => $type['rfqop_price'], "arrived" => $type['rfqop_arrived'], "suppliercomments" => $type['rfqop_suppliercomments'], "product_suppliercomments" => $partData[0]['apr_suppliercomments'], "prodtype" => 3];
			$data[$index] = $newArr;
			$index++;
		} else if ($type['rfqop_prodtype'] == 4) {
			$partQuery = "SELECT dp_name, dp_sku, dp_suppliercomments FROM dell_part WHERE dp_id=" . $type['rfqop_product'];
			$partData = $db->query($partQuery)->fetchAll();
			$newArr = ["id" => $type['id'], "name" => $partData[0]['dp_name'], "sku" => $partData[0]['dp_sku'], "quantity" => $type['rfqop_quantity'], "price" => $type['rfqop_price'], "arrived" => $type['rfqop_arrived'], "suppliercomments" => $type['rfqop_suppliercomments'], "product_suppliercomments" => $partData[0]['dp_suppliercomments'], "prodtype" => 4];
			$data[$index] = $newArr;
			$index++;
		}
	}

    $celldata = array();
    for ($i=0; $i < count($data); $i++) {
	    for ($j=0; $j < count($data[$i]); $j++) {
	        switch ($j) {
	            case 0:
	                $celldata[$i][$j] = $data[$i]['sku'];
	                break;
	            
	            case 1:
	                if ($data[$i]['suppliercomments'] != "") {
	                    $celldata[$i][$j] = $data[$i]['name'] . "\n(" . $data[$i]['suppliercomments'] . ")";
	                } else {
	                    if ($data[$i]['product_suppliercomments'] != "") {
	                        $celldata[$i][$j] = $data[$i]['name'] . "\n(" . $data[$i]['product_suppliercomments'] . ")";
	                    } else {
	                        $celldata[$i][$j] = $data[$i]['name'];
	                    }
	                }
	                
	                break;
	            
	            case 2:
	            	if ($data[$i]['arrived'] == 0) {
						$celldata[$i][$j] = "ON Order";
					} else if ($data[$i]['arrived'] > 0 && $data[$i]['arrived'] < $data[$i]['quantity']){
						$celldata[$i][$j] = "Part Arrived";
					} else {
						$celldata[$i][$j] = "Complete";
					}
	                break;
	            
	            case 3:
	                $celldata[$i][$j] = $data[$i]['price'];
	                break;
	            
	            case 4:
	                $celldata[$i][$j] = $data[$i]['quantity'];
	                break;
	            
	            case 5:
	                $celldata[$i][$j] = number_format($data[$i]['price']*$data[$i]['quantity'], 2);
	                break;
	            
	            case 6:
	                $celldata[$i][$j] = $data[$i]['arrived'];
	                break;
	            
	            default:
	                $celldata[$i][$j] = "";
	                break;
	        }
	    }
	}

    $pdf->Ln(6);
    $pdf->SetLineWidth(.15);
    $width = array(26, 74, 20, 16, 21, 21, 21);
    $pdf->SetWidths($width);
	$pdf->AddHeader(array("SKU", "Description", "Status", "Price", "Qty", "Total", "Arrived"), array('L', 'L', 'L', 'C', 'C', 'C', 'C'));

    $aligns = array('L', 'L', 'L', 'R', 'C', 'R', 'C');
    $pdf->SetAligns($aligns);
    $pdf->SetCellMargin(2);
	for($i=0; $i<count($celldata); $i++){
		$pdf->Row($celldata[$i], $i);
    }
	$pdf->Output("D", "order_" . $part . ".pdf");
} else {
	exit();
}
?>
