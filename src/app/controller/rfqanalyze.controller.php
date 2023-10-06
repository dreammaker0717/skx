<?php

$db = M::db();

$type = $_POST["type"];
$id = $_POST["id"];

$dateArray = array();
for ($i=0; $i < 24; $i++) { 
	array_push($dateArray, date('Y-m-d', mktime(0, 0, 0, date('m') - $i, 1, date('Y'))));
}

$qtyArray = array_fill(0, 24, 0);
$priceArray = array_fill(0, 24, 0);
$salesArray = array_fill(0, 24, 0);

$prodSql = "SELECT rfq_orderproducts.rfqo_id, rfq_orderproducts.rfqop_quantity, rfq_orderproducts.rfqop_price, rfq_orderproducts.rfqop_arrived, rfq_orders.rfqo_date, suppliers.sp_name FROM rfq_orderproducts LEFT JOIN rfq_orders ON rfq_orderproducts.rfqo_id = rfq_orders.rfqo_id LEFT JOIN suppliers ON rfq_orders.rfqo_supplier = suppliers.sp_id WHERE rfq_orderproducts.rfqop_prodtype = " . $type . " AND rfq_orderproducts.rfqop_product = " . $id . " ORDER BY rfq_orders.rfqo_date DESC";
$prodInter = $db->query($prodSql);
$prodData = null;
if ($prodInter) {
	$prodData = $prodInter->fetchAll();
	for ($i=0; $i < count($dateArray); $i++) {
		if ($i == 0) {
			$startDate = date('Y-m-d', mktime(0, 0, 0, date('m') + 1, 1, date('Y')));
		} else {
			$startDate = $dateArray[$i - 1];
		}
		$priceCount = 0;
		foreach ($prodData as $entry) {
	        if (($entry['rfqo_date'] < $startDate) && ($entry['rfqo_date'] >= $dateArray[$i])) {
	            $qtyArray[$i] += $entry['rfqop_quantity'];
	            $priceArray[$i] = $priceArray[$i] + number_format($entry['rfqop_price'], 2);
	            $priceCount++;
	        }
	    }
	    if ($priceArray[$i] != 0) {
	    	$priceArray[$i] = number_format($priceArray[$i]/$priceCount, 2);
	    }
	}
}

$boxLabel = null;
$name = null;
$sku = null;
if ($type == 1) {
    $partQuery = "SELECT npr_name, npr_sku, npr_box_label FROM nwp_products WHERE npr_id = " . $id;
    $partData = $db->query($partQuery)->fetchAll();
    $boxLabel = $partData[0]['npr_box_label'];
    $name = $partData[0]['npr_name'];
    $sku = $partData[0]['npr_sku'];
} else if ($type == 2) {
    $partQuery = "SELECT npr2_name, npr2_sku, npr2_box_label FROM nwp_products2 WHERE npr2_id = " . $id;
    $partData = $db->query($partQuery)->fetchAll();
    $boxLabel = $partData[0]['npr2_box_label'];
    $name = $partData[0]['npr2_name'];
    $sku = $partData[0]['npr2_sku'];
} else if ($type == 3) {
    $partQuery = "SELECT apr_name, apr_sku, apr_box_label FROM aproducts WHERE apr_id = " . $id;
    $partData = $db->query($partQuery)->fetchAll();
    $boxLabel = $partData[0]['apr_box_label'];
    $name = $partData[0]['apr_name'];
    $sku = $partData[0]['apr_sku'];
} else if ($type == 4) {
    $partQuery = "SELECT dp_name, dp_sku, dp_box_label FROM dell_part WHERE dp_id = " . $id;
    $partData = $db->query($partQuery)->fetchAll();
    $boxLabel = $partData[0]['dp_box_label'];
    $name = $partData[0]['dp_name'];
    $sku = $partData[0]['dp_sku'];
}

$salesSql = "SELECT sales_orderitems.qty, sales_orders.order_date FROM sales_orderitems LEFT JOIN sales_orders ON sales_orderitems.sales_order_id = sales_orders.id WHERE sales_orderitems.sku = '" . $boxLabel . "'";

$salesInter = $db->query($salesSql);
if ($salesInter) {
	$salesData = $salesInter->fetchAll();
	for ($i=0; $i < count($dateArray); $i++) {
		if ($i == 0) {
			$startDate = date('Y-m-d', mktime(0, 0, 0, date('m') + 1, 1, date('Y')));
		} else {
			$startDate = $dateArray[$i - 1];
		}
		foreach ($salesData as $entry) {
	        if ((date('Y-m-d', $entry['order_date']) < $startDate) && (date('Y-m-d', $entry['order_date']) >= $dateArray[$i])) {
	            $salesArray[$i] += $entry['qty'];
	        }
	    }
	}
}

$newArr = ['success' => true, 'dates' => $dateArray, 'qty' => $qtyArray, 'price' => $priceArray, 'sales' => $salesArray, 'prodData' => $prodData, 'name' => $name, 'sku' => $sku];
echo json_encode($newArr);