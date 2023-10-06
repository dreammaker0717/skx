<?php

require_once("fpdf.php");

$pdf = new FPDF('L','mm',array(110,52));
$pdf->SetLeftMargin(5);
$pdf->SetRightMargin(5);
$pdf->SetTopMargin(5);
$pdf->AddFont('barcode','',dirname(__FILE__).'/makefont/archon39.php');

$item=1;
$items = explode(",", $_POST['stock_items']);
$db = M::db();
foreach ($items as &$value) {



	$sto1 = $db->get("stock", ["st_servicetag","st_product"], [ "st_id" => $value ]);
	$st_tag = $sto1["st_servicetag"];
	$pro1 = $db->get("products", ["pr_name"], [ "pr_id" => $sto1["st_product"] ]);	
	$model = $pro1["pr_name"];

	$st_tag = '*' . $st_tag . '*';

	$pdf->AddPage();
	$pdf->SetAutoPageBreak(false);

	$pdf->SetFont('barcode','',30);
	$pdf->Cell(100,12,$st_tag,0,1,'C');

	$pdf->SetFont('Arial','B',72);
	$pdf->Cell(100,26,'Z-'.$value,0,1,'C');

	$pdf->SetFont('Arial','B',18);
	$pdf->Cell(100,5,$model,0,1,'C');

	$item++;
	
}

$pdf->Output();

?>