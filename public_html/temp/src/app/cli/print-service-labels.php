<?php

require_once("fpdf.php");

$pdf = new FPDF('L','mm',array(110,52));
$pdf->SetLeftMargin(5);
$pdf->SetRightMargin(5);
$pdf->SetTopMargin(5);
$pdf->AddFont('barcode','','archon39.php');

$item=1;
$items = explode(",", $_POST['stock_items']);
$db=M::db();
foreach ($items as &$value) {
	
	

	$sto1 = $db->get("stock", ["st_servicetag","st_product","st_lastcomment"], [ "st_id" => $value ]);
	$st_tag = $sto1["st_servicetag"];
	$pro1 = $db->get("products", ["pr_name","pr_manufacturer"], [ "pr_id" => $sto1["st_product"] ]);	
	$model = $pro1["pr_name"];	
	$comment=  $sto1["st_lastcomment"];
	$man1 = $db->get("manufacturers", ["mf_name"], [ "mf_id" => $pro1["pr_manufacturer"] ]);	
	$manufacturer= $man1["mf_name"];

	$pdf->AddPage();
	$pdf->SetAutoPageBreak(false);

	$pdf->SetFont('Arial','',14);
	$pdf->Cell(50,5,'NDC ID#: '.$value,0,0,'L');
	$pdf->Cell(50,5,'S/N: '.$st_tag,0,1,'R');

	$pdf->SetFont('Arial','B',20);
	$pdf->Cell(100,10,$manufacturer.' '.$model,0,1,'C');

	$pdf->SetFont('Arial','',12);
	$pdf->MultiCell(100,5,'Comments: '.$comment,0,'C');

	$item++;
	
}

$pdf->Output();

?>