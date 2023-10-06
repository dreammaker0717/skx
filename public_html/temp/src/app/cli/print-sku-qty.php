<?php
	include "phpqrcode/qrlib.php";
	
	$sku =  strtoupper( $_POST["sku"] );
	$qty =  str_pad( intval($_POST["qty"]),3,"0",STR_PAD_LEFT);

	$sku_f = dirname(__FILE__).'/'.uniqid(rand(), true) . '.png';
	$qty_f =  dirname(__FILE__).'/'.uniqid(rand(), true) . '.png';

	
	
	QRcode::png($sku,$sku_f);	
	QRcode::png($qty,$qty_f);




		
	require_once("fpdf.php");

	$pdf = new FPDF('L','mm',array(110,52));
	$pdf->SetLeftMargin(5);
	$pdf->SetRightMargin(5);
	$pdf->SetTopMargin(5);
	
	$pdf->AddPage();
	$pdf->SetAutoPageBreak(false);

	$pdf->Image($sku_f, 20,5,-100);

	$pdf->Image($qty_f, 75,5,-100);

	$pdf->SetFont('Arial','B',32);
	$pdf->Cell(55,60,$sku,0,0,'C');

	$pdf->SetFont('Arial','',72);
	$pdf->Cell(10,40,"|",0,0,'R');

	$pdf->SetFont('Arial','',32);
	$pdf->Cell(27,60,$qty,0,0,'R');

	


	$pdf->Output();

	
	

	unlink($sku_f);
	unlink($qty_f);
	
	
?>