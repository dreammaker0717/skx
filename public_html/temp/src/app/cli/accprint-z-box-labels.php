<?php
include "phpqrcode/qrlib.php";
require_once("rpdf.php");


$item=1;
$items = explode(",", $_POST['stock_items']);
$db = M::db();
foreach ($items as &$value) {
		


	
	$ts = "acc_stock";
	$tp = "aproducts";
	$p = "a";

	if(isset($_POST["t"])) {
		$ts = $_POST["t"] == "acc_stock" ? "acc_stock" : "nwp_stock";
		$tp = $_POST["t"] == "acc_stock" ? "aproducts" : "nwp_products";
		$p = $_POST["t"] == "acc_stock" ? "a" : "n";
	}

		


	$sto1 = $db->get($ts, [$p."st_servicetag",$p."st_product",$p."st_lastcomment",$p."st_date"], [ $p."st_id" => $value ]);
	$pro1 = $db->get($tp , [$p."pr_name",$p."pr_box_label", $p."pr_box_subtitle"], [ $p."pr_id" => $sto1[ $p."st_product"] ]);	
	$model = $pro1[ $p."pr_name"];

$st_tag = $sto1[ $p."st_servicetag"];
$last_comment = $sto1[ $p."st_lastcomment"];
$st_date1 = $sto1[ $p."st_date"];
$st_date = date('d/m/Y', strtotime($st_date1));
$box_label = $pro1[ $p."pr_box_label"];
$box_subtitle = $pro1[ $p."pr_box_subtitle"];



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



$pdf->Image($stag_f, 40,2,-100);
unlink($stag_f);




$pdf->SetFont('Arial','B',10);
$pdf->TextWithDirection(45,4,'SERIAL','R');

$pdf->Line(0,22,102,22);
$pdf->Line(41,0,41,22);
$pdf->Line(62,0,62,22);
$pdf->Line(0,45,102,45);

$pdf->SetFont('Arial','',8);
$pdf->SetXY(0,42);
$pdf->Cell(102,10,'  www.ndc.co.uk',0,1,'L');
$pdf->SetXY(0,42);
$pdf->Cell(102,10,$st_date.'  ',0,1,'R');
$pdf->SetXY(0,42);
$pdf->Cell(102,10,$st_tag,0,1,'C');


	$pdf->SetFont('Arial','B',30);

	$pdf->SetXY(0,0);	
	$pdf->Cell(41,22,$value,0,1,'C');


	$pdf->SetXY(63,2);	
	$pdf->SetFont('Arial','B',20);
	$pdf->Cell(39,10,$box_label,0,1,'C');

	$pdf->SetFont('Arial','',10);	

	$pdf->SetXY(62,10);
	$pdf->MultiCell(39,4,$box_subtitle,0,'C');

	$pdf->SetXY(0,25);
	$pdf->MultiCell(100,4,$last_comment,0,'C');


	$item++;
	
}

$pdf->Output();

?>