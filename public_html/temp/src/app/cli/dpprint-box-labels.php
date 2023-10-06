<?php
include "phpqrcode/qrlib.php";
require_once("rpdf.php");
$item=1;
$items = explode(",", $_POST['stock_items']);
$db = M::db();
foreach ($items as &$value) {

	$ts = "dco_stock";
	$tp = "dell_part";
	$p = "d";

	
	$sto1 = $db->get($ts, [$p."st_servicetag",$p."st_product",$p."st_status"], [ $p."st_id" => $value ]);
	$pro1 = $db->get($tp, [$p."p_name",$p."p_box_label",$p."p_box_subtitle",$p."p_condition"], [ $p."p_id" => $sto1[$p."st_product"] ]);	

	$model = $pro1[$p."p_name"];
	$box_label = $pro1[$p."p_box_label"];
	$box_subtitle = $pro1[$p."p_box_subtitle"];
	$condition = $pro1[$p."p_condition"];
	
	$st_status=$sto1[$p."st_status"];
	$st_tag = $sto1[$p."st_servicetag"];

	$preBrand = explode(' ', $box_subtitle, 2);
	$pr_manufacturer=$preBrand[0];

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

	/********************* QR Code **********************/
	$pdf->Image($stag_f, 4,22,-100);
	unlink($stag_f);

	
	
/******************* Manufacturer Logo ***************/
switch ($pr_manufacturer){
	case "Dell":
		$pdf->Image(dirname(__FILE__).'/'."dell-mark.jpg", 10,2,-400);
		if ($condition=="Refurbished") {
			$pdf->Image(dirname(__FILE__).'/'."certified-refurbished.jpg", 6,15,-400);}
		break;
	case "Lenovo":
		$pdf->Image(dirname(__FILE__).'/'."lenovo.jpg", 2,5,-400);
		if ($condition=="Refurbished") {
			$pdf->Image(dirname(__FILE__).'/'."certified-refurbished.jpg", 6,12,-400);}
		break;
	default:
		if ($condition=="Refurbished") {
			$pdf->Image(dirname(__FILE__).'/'."certified-refurbished.jpg", 6,10,-400);}
}	
	
	$pdf->Line(0,45,102,45);
	$pdf->SetFont('Arial','',8);
	$pdf->SetXY(0,42);
	$pdf->Cell(102,10,'    www.ndc.co.uk',0,1,'L');
	$pdf->SetXY(0,42);
	$pdf->Cell(102,10,$value.'  ',0,1,'R');
	$pdf->SetXY(0,42);
	$pdf->Cell(102,10,$st_tag,0,1,'C');
	$pdf->Line(30,0,30,45);

	$pdf->Code39(45,3,$box_label,1,5);

		$pdf->SetXY(31,17);	
	switch (strlen($box_label)) {
		case strlen($box_label)==8:
			$pdf->SetFont('Arial','B',40);
			break;

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
		$pdf->MultiCell(70,5,$box_subtitle,0,'C',false);


		$item++;
	
}

$pdf->Output();
?>