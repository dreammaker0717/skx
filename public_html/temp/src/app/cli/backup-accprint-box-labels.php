<?php
include "phpqrcode/qrlib.php";
require_once("rpdf.php");


$item=1;
$items = explode(",", $_POST['stock_items']);
$db = M::db();
foreach ($items as &$value) {
		


	$sto1 = $db->get("acc_stock", ["ast_servicetag","ast_product"], [ "ast_id" => $value ]);
	$pro1 = $db->get("aproducts", ["apr_name","apr_box_label","apr_box_subtitle"], [ "apr_id" => $sto1["ast_product"] ]);	
	$model = $pro1["apr_name"];

	$st_tag = $sto1["ast_servicetag"];

$box_label = $pro1["apr_box_label"];
$box_subtitle = $pro1["apr_box_subtitle"];


/*********************************** Box Label ************************/


	$pdf = new RPDF('L','mm',array(102,51));
	$pdf->SetLeftMargin(10);
	$pdf->SetRightMargin(10);
	/*$pdf->SetTopMargin(5);*/

	$pdf->AddFont('barcode','',dirname(__FILE__).'/makefont/archon39.php');


	$stag_f = dirname(__FILE__).'/'.uniqid(rand(), true) . '.png';
	QRcode::png( $st_tag,$stag_f);


	
	$pdf->AddPage();
	$pdf->SetAutoPageBreak(false);



$pdf->Image($stag_f, 4,12,-100);
unlink($stag_f);

$pdf->SetFont('Arial','B',10);
$pdf->TextWithDirection(5,35,'SCAN SERIAL','U');

$pdf->Line(0,45,102,45);
$pdf->SetFont('Arial','',8);
$pdf->SetXY(0,42);
$pdf->Cell(102,10,'  www.ndc.co.uk',0,1,'L');
$pdf->SetXY(0,42);
$pdf->Cell(102,10,$value.'  ',0,1,'R');
$pdf->SetXY(0,42);
$pdf->Cell(102,10,$st_tag,0,1,'C');
$pdf->Line(30,0,30,45);
	
$pdf->Code39(50,3,$box_label,1,5);

	$pdf->SetXY(31,17);	
switch (strlen($box_label)) {
	case strlen($box_label)==7:
		$pdf->SetFont('Arial','B',45);
		break;

	case strlen($box_label)==6:
		$pdf->SetFont('Arial','B',50);
		break;
	case strlen($box_label)<6:
		$pdf->SetFont('Arial','B',60);
		break;
}
	$pdf->Cell(70,10,$box_label,0,1,'C');

	$pdf->SetXY(31,30);
	$pdf->SetFont('Arial','',12);	
	$pdf->Cell(70,10,$box_subtitle,0,1,'C');


	$item++;
	
}

$pdf->Output();
?>