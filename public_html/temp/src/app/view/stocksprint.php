<?php
if($part==="print-box-labels") 
{
    include_once(PATH_APP."/cli/print-box-labels.php");
}
if($part==="print-tracking-sheet") 
{
    include_once(PATH_APP."/cli/print-tracking-sheet.php");
}
if($part==="print-service-labels") 
{
    include_once(PATH_APP."/cli/print-service-labels.php");
}
if($part==="print-box-zlabels") 
{
    include_once(PATH_APP."/cli/print-box-zlabels.php");
}
if($part=="print-sku-qty"){
    include_once(PATH_APP."/cli/print-sku-qty.php");
}
if($part=="print-dell-part-qr"){
    include_once(PATH_APP."/cli/print-dell-part-qr.php");
}
