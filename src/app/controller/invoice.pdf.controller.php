<?php
use alhimik1986\PhpExcelTemplator\params\CallbackParam;
use alhimik1986\PhpExcelTemplator\params\ExcelParam;
use alhimik1986\PhpExcelTemplator\PhpExcelTemplator;
use alhimik1986\PhpExcelTemplator\setters\CellSetterArrayValueSpecial;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once("vendor/autoload.php");
require_once(PATH_APP."/cli/invoice.php");//$pdf = new PDF_Invoice();
$pdf = new PDF_Invoice(  );

$db=M::db();

$invQuery = "SELECT inv_inv.inv_id,inv_inv.inv_date,inv_inv.inv_reference,inv_inv.payment_type,inv_inv.payment_reference,inv_inv.netamount,inv_inv.total_vat,inv_inv.total_amount,inv_inv.ship_fee,inv_inv.discount, customers.c_name FROM inv_inv LEFT JOIN customers ON inv_inv.customer_id = customers.customer_id WHERE inv_inv.inv_id=" . $part;
$invData = $db->query($invQuery)->fetchAll();

$pdf->AddPage();
$pdf->addSociete( "MaSociete",
                "MonAdresse\n" .
                "75000 PARIS\n".
                "R.C.S. PARIS B 000 000 007\n" .
                "Capital : 18000 " . EURO );
$pdf->fact_dev( "INVOICE#","SVX00000".$invData[0]['inv_id']);
//$pdf->temporaire( "Devis temporaire" );
$pdf->addDate($invData[0]['inv_date']);
$pdf->addClient($invData[0]['c_name']);
$pdf->addPageNumber("1");
//$pdf->addClientAdresse("Ste\nM. XXXX\n3ème étage\n33, rue d'ailleurs\n75000 PARIS");
//$pdf->addReglement("Chèque à réception de facture");
//$pdf->addEcheance("03/12/2003");
//$pdf->addNumTVA("FR888777666");
//$pdf->addReference("Devis ... du ....");

$cols=array( "Quantity"    => 18,
           "Description"  => 78,
           "NET AMOUNT"     => 26,
           "VAT"      => 20,
           "SUBTOTAL" => 30,
           "TOTAL"          => 20 );
$pdf->addCols( $cols);
$cols=array( "Quantity"    => "L",
           "Description"  => "L",
           "NET AMOUNT"     => "C",
           "VAT"      => "R",
           "SUBTOTAL" => "R",
           "TOTAL"          => "C" );
$pdf->addLineFormat( $cols);
$pdf->addLineFormat($cols);

$y    = 56;
$itemQuery = "SELECT * from inv_items WHERE inv_id=" . $part;
$itemData = $db->query($itemQuery)->fetchAll();

foreach ($itemData as $key => $item) {

  $partQuery = "SELECT * FROM all_products WHERE productid=" . $item['item_product_id'];
  $partData = $db->query($partQuery)->fetchAll();

  $line = array( "Quantity"    => $item['item_qty'],
               "Description"  => $partData[0]['name'],
               "NET AMOUNT"     => $item['item_net'],
               "VAT"      => $item['item_tax'],
               "SUBTOTAL" => $item['item_subtotal'],
               "TOTAL"          => $item['item_price']*$item['item_qty'], );
  $size = $pdf->addLine( $y, $line );
  $y   += $size + 5;
}
//$pdf->addCadreTVAs();

$tot_prods = array( array ( "px_unit" => 600, "qte" => 1, "tva" => 1 ),
                  array ( "px_unit" =>  10, "qte" => 1, "tva" => 1 ));
$tab_tva = array( "1"       => 19.6,
                "2"       => 5.5);
$params  = array( "RemiseGlobale" => 1,
                    "remise_tva"     => 1,       // {la remise s'applique sur ce code TVA}
                    "remise"         => 0,       // {montant de la remise}
                    "remise_percent" => 10,      // {pourcentage de remise sur ce montant de TVA}
                "FraisPort"     => 1,
                    "portTTC"        => 10,      // montant des frais de ports TTC
                                                 // par defaut la TVA = 19.6 %
                    "portHT"         => 0,       // montant des frais de ports HT
                    "portTVA"        => 19.6,    // valeur de la TVA a appliquer sur le montant HT
                "AccompteExige" => 1,
                    "accompte"         => 0,     // montant de l'acompte (TTC)
                    "accompte_percent" => 15,    // pourcentage d'acompte (TTC)
                "Remarque" => "Avec un acompte, svp..." );

//$pdf->addTVAs( $params, $tab_tva, $tot_prods);
$pdf->addCadreEurosFrancs($invData[0]['netamount'],$invData[0]['total_vat'],$invData[0]['ship_fee'],$invData[0]['discount'],$invData[0]['total_amount']);
$pdf->Output();
exit;

 ?>
