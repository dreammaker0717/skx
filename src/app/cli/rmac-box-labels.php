<?php
include "phpqrcode/qrlib.php";
require_once("rpdf.php");
	

$item=1;
$items = explode(",", $_POST['stock_items']);
$db = M::db();
foreach ($items as &$value) {
	
	$sto1 = $db->get("rmac_items", ["rmac_product","rmac_sku","rmac_ID","rmac_fault", "rmac_datecreated", "rmac_supplier"], [ "rmac_ID" => $value ]);	
	$model = $sto1["rmac_product"];
	$st_tag = $sto1["rmac_sku"];

	$sup = $db->get("suppliers", ["sp_name"], ["sp_id"=> $sto1["rmac_supplier"]]);
	
	$box_label = "SVX-0-000-000";
	$vr =   strrev( "".$value );
	for($i=0; $i < strlen($vr);$i++ )
	{
		$nv= strlen($box_label) - (1+$i);
		$box_label[ $nv ] = $vr[$i];
	}


	$box_subtitle =  $sto1["rmac_fault"];


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
	$pdf->TextWithDirection(5,40, $sto1["rmac_datecreated"] ,'U');

	$pdf->Line(0,45,102,45);
	$pdf->SetFont('Arial','',8);
	$pdf->SetXY(0,42);
	$pdf->Cell(102,10,'  www.ndc.co.uk',0,1,'L');
	$pdf->SetXY(0,42);
	$pdf->Cell(102,10,$value.'  ',0,1,'R');
	$pdf->SetXY(0,42);
	$pdf->Cell(102,10,$st_tag,0,1,'C');
	$pdf->Line(30,10,30,45);
		
	$pdf->Code39(20,3,$box_label,1,5);

		$pdf->SetXY(31,17);	

	switch (strlen($box_label)) {
		case strlen($box_label)==7:
			$pdf->SetFont('Arial','B',45);
			break;

		case strlen($box_label)<=6:
			$pdf->SetFont('Arial','B',50);
			break;
		case strlen($box_label)>6:
			$pdf->SetFont('Arial','B',24);
			break;
	}

		$pdf->Cell(70,10,$box_label,0,1,'C');
		$pdf->SetFont('Arial','',8);
		$pdf->Cell(115,0,$sup["sp_name"],0,1,'C');
		$pdf->SetXY(21,30);
		$pdf->SetFont('Arial','',9);	

		$words = explode(" ",$box_subtitle);

		$wp = intval( count($words) / 2);

		$sb1 = "";
		$sb2 = "";

		for($i=0;$i< count($words); $i++) {
			
			if($wp>$i) {
				$sb1.=" ".$words[$i];
			}
			else $sb2.=" ".$words[$i];
		}
		
		$pdf->Cell(70,10,$sb1,0,2,'C');
		$pdf->Cell(70,0,$sb2,0,1,'C');


		$item++;
		
}

$pdf->Output();
?>