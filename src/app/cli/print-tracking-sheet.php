<?php

require_once("polygon.php");

$pdf = new PDF_Polygon('P','mm',array(148,210));
$pdf->SetLeftMargin(10);
$pdf->SetRightMargin(10);
$pdf->SetTopMargin(10);


$item=1;
$items = explode(",", $_POST['stock_items']);
$db=M::db();
foreach ($items as &$value) {
	
	$sto1 = $db->get("stock", ["st_servicetag","st_product"], [ "st_id" => $value ]);
	$st_tag = $sto1["st_servicetag"];
	$pro1 = $db->get("products", ["pr_name"], [ "pr_id" => $sto1["st_product"] ]);	
	$model = $pro1["pr_name"];

	

$pdf->AddPage();
$pdf->AddFont('barcode','',dirname(__FILE__).'/makefont/archon39.php');
$pdf->SetAutoPageBreak(false);

$pdf->SetFont('Arial','B',12);
$pdf->SetDrawColor(150,150,150);
$pdf->SetLineWidth(0.5);
$pdf->Rect(10,9,130,6,'D');

$pdf->Cell(60,5,'LAPTOP DIAGNOSTICS SHEET',0,0,'L');
$pdf->Cell(70,5,$model,0,1,'R');

$pdf->SetFont('Arial','',6);
$pdf->Cell(70,5,'Order ID:'.$model.' / Order Date:'.$model.' / Supplier:'.$model,0,1,'L');

$pdf->Cell(30,3,'',0,1,'C');
$pdf->SetFont('Arial','B',12);
$pdf->Cell(130,6,'TECHNICIAN TESTS & CHECKS',1,1,'L');
$pdf->Cell(30,3,'',0,1,'C');

$pdf->SetFont('Arial','',8);
$pdf->SetLineWidth(0);
$pdf->SetDrawColor(0,0,0);

$pdf->Cell(32,4,'Program Service Tag',0,0,'L');
$pdf->Cell(32,4,'',1,0,'L');
$pdf->Cell(2,4,'',0,0,'L');
$pdf->Cell(32,4,'Bluetooth Test',0,0,'L');
$pdf->Cell(32,4,'',1,1,'L');
$pdf->Cell(100,1,'',0,1,'L');

$pdf->Cell(32,4,'HD Detection',0,0,'L');
$pdf->Cell(32,4,'',1,0,'L');
$pdf->Cell(2,4,'',0,0,'L');
$pdf->Cell(32,4,'Lights/Buttons Test',0,0,'L');
$pdf->Cell(32,4,'',1,1,'L');
$pdf->Cell(100,1,'',0,1,'L');

$pdf->Cell(32,4,'Diagnostic Test',0,0,'L');
$pdf->Cell(32,4,'',1,0,'L');
$pdf->Cell(2,4,'',0,0,'L');
$pdf->Cell(32,4,'Touchscreen Test',0,0,'L');
$pdf->Cell(32,4,'',1,1,'L');
$pdf->Cell(100,1,'',0,1,'L');

$pdf->Cell(32,4,'OS/Drivers+Updates',0,0,'L');
$pdf->Cell(16,4,'',1,0,'L');
$pdf->Cell(16,4,'',1,0,'L');
$pdf->Cell(2,4,'',0,0,'L');
$pdf->Cell(32,4,'LCD Test/Check',0,0,'L');
$pdf->Cell(32,4,'',1,1,'L');
$pdf->Cell(100,1,'',0,1,'L');

$pdf->Cell(32,4,'Wireless Test',0,0,'L');
$pdf->Cell(32,4,'',1,0,'L');
$pdf->Cell(2,4,'',0,0,'L');
$pdf->Cell(32,4,'Sleep Mode Test',0,0,'L');
$pdf->Cell(32,4,'',1,1,'L');
$pdf->Cell(100,1,'',0,1,'L');

$pdf->Cell(32,4,'Passmark Score',0,0,'L');
$pdf->Cell(32,4,'',1,0,'L');
$pdf->Cell(2,4,'',0,0,'L');
$pdf->Cell(32,4,'2-in-1 Flip Test',0,0,'L');
$pdf->Cell(32,4,'',1,1,'L');
$pdf->Cell(100,1,'',0,1,'L');

$pdf->Cell(32,4,'BIOS Version Flash',0,0,'L');
$pdf->Cell(32,4,'',1,0,'L');
$pdf->Cell(2,4,'',0,0,'L');
$pdf->Cell(32,4,'Keyboard Test',0,0,'L');
$pdf->Cell(32,4,'',1,1,'L');
$pdf->Cell(100,2,'',0,1,'L');

$pdf->Cell(32,4,'3D Graphics Test',0,0,'L');
$pdf->Cell(16,4,'',1,0,'L');
$pdf->Cell(16,4,'',1,0,'L');
$pdf->Cell(2,4,'',0,0,'L');
$pdf->Cell(32,4,'Touchpad Test',0,0,'L');
$pdf->Cell(32,4,'',1,1,'L');
$pdf->Cell(100,2,'',0,1,'L');

$pdf->Cell(32,4,'CPU Test',0,0,'L');
$pdf->Cell(16,4,'',1,0,'L');
$pdf->Cell(16,4,'',1,0,'L');
$pdf->Cell(2,4,'',0,0,'L');
$pdf->Cell(32,4,'DVDRW Test',0,0,'L');
$pdf->Cell(32,4,'',1,1,'L');
$pdf->Cell(100,2,'',0,1,'L');


$pdf->Cell(32,4,'Battery Test',0,0,'L');
$pdf->Cell(32,4,'',1,0,'L');
$pdf->Cell(2,4,'',0,0,'L');
$pdf->Cell(32,4,'Compare Service Tag',0,0,'L');
$pdf->Cell(32,4,'',1,1,'L');
$pdf->Cell(100,1,'',0,1,'L');

$pdf->Cell(32,4,'Audio/Headphone Test',0,0,'L');
$pdf->Cell(16,4,'',1,0,'L');
$pdf->Cell(16,4,'',1,0,'L');
$pdf->Cell(2,4,'',0,0,'L');
$pdf->Cell(32,4,'Specification Check',0,0,'L');
$pdf->Cell(32,4,'',1,1,'L');
$pdf->Cell(100,1,'',0,1,'L');

$pdf->Cell(32,4,'USB/Ports Check',0,0,'L');
$pdf->Cell(32,4,'',1,0,'L');
$pdf->Cell(2,4,'',0,0,'L');
$pdf->Cell(32,4,'Internal Check',0,0,'L');
$pdf->Cell(32,4,'',1,1,'L');
$pdf->Cell(100,1,'',0,1,'L');

$pdf->Cell(32,4,'Microphone Check',0,0,'L');
$pdf->Cell(32,4,'',1,0,'L');
$pdf->Cell(2,4,'',0,0,'L');
$pdf->Cell(32,4,'Visual Inspection',0,0,'L');
$pdf->Cell(32,4,'',1,1,'L');
$pdf->Cell(100,1,'',0,1,'L');

$pdf->Cell(32,4,'Camera(s) Test',0,0,'L');
$pdf->Cell(32,4,'',1,0,'L');
$pdf->Cell(2,4,'',0,0,'L');
$pdf->Cell(32,4,'Activation',0,0,'L');
$pdf->Cell(32,4,'',1,1,'L');
$pdf->Cell(100,1,'',0,1,'L');





$pdf->Cell(110,2,'',0,1,'C');

$pdf->Cell(10,2,'',0,1,'C');



$pdf->SetFont('Arial','B',8);
$pdf->SetLineWidth(0.2);
$pdf->SetDrawColor(200,200,200);

$pdf->Cell(90,5,'Issues & Work Carried Out',1,0,'C');
$pdf->Cell(20,5,'Technician',1,0,'C');
$pdf->Cell(20,5,'Date',1,1,'C');


for ($x = 0; $x <= 10; $x++) {
    $pdf->Cell(90,5,'',1,0,'C');
$pdf->Cell(20,5,'',1,0,'C');
$pdf->Cell(20,5,'',1,1,'C');
} 

$pdf->Cell(110,2,'',0,1,'C');


$pdf->SetFont('Arial','',8);
$pdf->SetLineWidth(0);
$pdf->SetDrawColor(0,0,0);

$pdf->Cell(10,2,'',0,1,'C');
$pdf->Cell(32,4,'A/C Adaptor Check',0,0,'L');
$pdf->Cell(32,4,'',1,0,'L');
$pdf->Cell(2,4,'',0,0,'L');
$pdf->Cell(32,4,'Cleaned/Packed By',0,0,'L');
$pdf->Cell(32,4,'',1,1,'L');
$pdf->Cell(100,1,'',0,1,'L');

$pdf->Cell(10,2,'',0,1,'C');

$pdf->SetFont('Arial','',16);
$pdf->Cell(20,10,'S/TAG:',0,0,'L');
$pdf->SetFont('Arial','B',16);
$pdf->Cell(40,10,$st_tag,0,0,'L');

$pdf->Cell(20,10,'',0,0,'L');

$pdf->SetFont('Arial','',16);
$pdf->Cell(20,10,'NDC #:',0,0,'R');
$pdf->SetFont('Arial','B',16);
$pdf->Cell(20,10,$value,0,0,'R');


$pdf->Cell(10,15,'',0,1,'C');

$pdf->Cell(38,10,'',0,0,'L');
$st_tag = '*' . $st_tag . '*';
$pdf->SetFont('barcode','',44);
$pdf->Cell(5,5,$st_tag,0,0,'L');




		$item++;
	
}

$pdf->Output();

?>