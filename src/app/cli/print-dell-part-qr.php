<?php
	include "phpqrcode/qrlib.php";
	

	$id =  intval(  isset($_GET["id"]) ? $_GET["id"] :  str_replace("id=","",$_SERVER["REDIRECT_QUERY_STRING"] ));
	
	$db = M::db();
	
	$rec = $db->get("dell_part", "*", [ "dp_id" => $id ]);

	

	$sku_f = dirname(__FILE__).'/'.uniqid(rand(), true) . '.png';
	QRcode::png( $rec["dp_box_label"],$sku_f);	
	
	require_once("fpdf.php");


	$pdf = new FPDF('L','mm',array(102,51));
	$pdf->SetLeftMargin(5);
	$pdf->SetRightMargin(5);
	$pdf->SetTopMargin(1);
	
	$pdf->AddPage();
	$pdf->SetAutoPageBreak(false);

	$pdf->SetXY(5,2);
	$pdf->SetFont('Arial','',12);
	$pdf->Cell(70,10,$rec["dp_sku"],0,1,'C');

	$pdf->SetXY(5,17);	
	$pdf->SetFont('Arial','B',60);	
	$pdf->Cell(70,10,$rec["dp_box_label"],0,1,'C');

	$pdf->SetXY(5,30);
	$pdf->SetFont('Arial','',12);	
	$pdf->Cell(70,10,$rec["dp_box_subtitle"],0,1,'C');

	$pdf->SetXY(2,42);	
	$pdf->SetFont('Arial','',12);	
	$pdf->Cell(102,5,"www.ndc.co.uk",0,1,'L');

	$pdf->Image($sku_f, 75,10,-100);
	$pdf->Image(dirname(__FILE__).'/'."dell-mark.jpg", 86,35,-400);


	$pdf->Output('',$rec["dp_box_label"].".pdf");

	
	

	unlink($sku_f);
	
	
	
?>