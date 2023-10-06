<?php

$db = M::db();

if($action=="search") {
    $start = intval($_POST["start"]);
    $length = intval($_POST["length"]);

    $order_field = "rfq_orders.rfqo_date desc";
    

    if(!empty($_POST["order"])) {
        $order_field =  $_POST['columns'][$_POST['order']['0']['column']]['data'] ." ". strtoupper( $_POST['order']['0']['dir']  );
    }

    $numRows = 0;
    $numRowsTotal = 0;
    $_SF =" 1=1 ";
    $_SFC= $_SF;
           
    $_PARA=[];

    $SEARCH = $_POST["search"]["value"];
    if(!empty($SEARCH)) {
        
        $_SF.= " AND ( rfq_orders.rfqo_reference like :search OR rfq_orders.rfqo_id = :search_no )";
        $_PARA[":search"]   = "%".$SEARCH."%";
        $_PARA[":search_no"]   = $SEARCH;
        
    }
    

    $query=
        "SELECT rfq_orders.rfqo_id, rfq_orders.rfqo_date, rfq_orders.rfqo_payment, vat_rates.vat_label, users.username as rfqo_user_name, suppliers.sp_name as rfqo_supplier_name, rfq_orders.rfqo_reference, rfq_orders.rfqo_user, rfq_orders.rfqo_state , rfq_orders.rfq_id 
        FROM rfq_orders left join users on rfq_orders.rfqo_user = users.user_id left join suppliers on rfq_orders.rfqo_supplier = suppliers.sp_id left join vat_rates on vat_rates.vat_id = rfq_orders.rfqo_vatid
        WHERE  $_SF
        order by $order_field 
        limit $start,$length";

    $query_count = "select count(*) as c FROM rfq_orders left join users on rfq_orders.rfqo_user = users.user_id left join suppliers on rfq_orders.rfqo_supplier = suppliers.sp_id WHERE $_SFC ";
    $query_count2 = "select count(*) as c FROM rfq_orders left join users on rfq_orders.rfqo_user = users.user_id left join suppliers on rfq_orders.rfqo_supplier = suppliers.sp_id WHERE $_SF ";

    $data = $db->query($query,$_PARA)->fetchAll();

    for ($i=0; $i < count($data); $i++) { 
        $rfqoID = $data[$i]['rfqo_id'];
        $prodQuery = "SELECT rfqop_quantity, rfqop_price, rfqop_arrived FROM rfq_orderproducts WHERE rfqo_id = " . $rfqoID;
        $prodData = $db->query($prodQuery)->fetchAll();
        $totalQuantity = 0;
        $totalPrice = 0;
        $totalArrived = 0;
        foreach ($prodData as $prodItem) {
            $totalQuantity += $prodItem['rfqop_quantity'];
            $totalPrice += ($prodItem['rfqop_price'] * $prodItem['rfqop_quantity']);
            $totalArrived += $prodItem['rfqop_arrived'];
        }
        $data[$i]['total_quantity'] = $totalQuantity;
        $data[$i]['order_value'] = round($totalPrice, 2);
        $data[$i]['total_arrived'] = $totalArrived;
    }

    $numRowsTotal = $db->query($query_count2,$_PARA)->fetchAll();
    $numRows = $db->query($query_count)->fetchAll();


    $output = array(
		"draw"	=>	intval($_POST["draw"]),			
		"iTotalRecords"	=> 	$numRows[0]["c"],
		"iTotalDisplayRecords"	=>  $numRowsTotal[0]["c"],
		"data"	=> 	$data,
        "t" => "r0"
	);

    echo json_encode($output);

} else if($action=="update_arrived") {
    $ordprodId = $_POST["ordprod_id"];
    $arrived = $_POST["data"];
    $rfqoQuery = "SELECT rfqo_id, rfqop_prodtype, rfqop_product, rfqop_arrived FROM rfq_orderproducts WHERE id = " . $ordprodId;
    $rfqoData = $db->query($rfqoQuery)->fetchAll();

    $isLow = false;
    switch ($rfqoData[0]['rfqop_prodtype']) {
        case 1:
            $nwpOrderQuery = "SELECT nor_id FROM nwp_orders WHERE rfqorderid = " . $rfqoData[0]['rfqo_id'];
            $nwpOrderData = $db->query($nwpOrderQuery)->fetchAll();
            if ($nwpOrderData) {
                $nwpOrderProdQuery = "SELECT nop_quantity FROM nwp_orderprod WHERE nop_order = " . $nwpOrderData[0]['nor_id'] . " AND nop_product = " . $rfqoData[0]['rfqop_product'];
                $nwpOrderProdData = $db->query($nwpOrderProdQuery)->fetchAll();
                if ($nwpOrderProdData) {
                    if ($nwpOrderProdData[0]['nop_quantity'] < $arrived) {
                        $db->update('nwp_orderprod', ['nop_quantity' => $arrived], ["nop_order" => $nwpOrderData[0]['nor_id'], "nop_product" => $rfqoData[0]['rfqop_product']]);
                    } else{
                        $isLow = true;
                    }
                }
            }
            break;
        
        case 3:
            $accOrderQuery = "SELECT aor_id FROM acc_orders WHERE rfqorderid = " . $rfqoData[0]['rfqo_id'];
            $accOrderData = $db->query($accOrderQuery)->fetchAll();
            if ($accOrderData) {
                $accOrderProdQuery = "SELECT aop_quantity FROM acc_orderprod WHERE aop_order = " . $accOrderData[0]['aor_id'] . " AND aop_product = " . $rfqoData[0]['rfqop_product'];
                $accOrderProdData = $db->query($accOrderProdQuery)->fetchAll();
                if ($accOrderProdData) {
                    if ($accOrderProdData[0]['aop_quantity'] < $arrived) {
                        $db->update('acc_orderprod', ['aop_quantity' => $arrived], ["aop_order" => $accOrderData[0]['aor_id'], "aop_product" => $rfqoData[0]['rfqop_product']]);
                    } else{
                        $isLow = true;
                    }
                }
            }
            break;
        
        case 4:
            $dcoOrderQuery = "SELECT dor_id FROM dco_orders WHERE rfqorderid = " . $rfqoData[0]['rfqo_id'];
            $dcoOrderData = $db->query($dcoOrderQuery)->fetchAll();
            if ($dcoOrderData) {
                $dcoOrderProdQuery = "SELECT dop_quantity FROM dco_orderprod WHERE dop_order = " . $dcoOrderData[0]['dor_id'] . " AND dop_product = " . $rfqoData[0]['rfqop_product'];
                $dcoOrderProdData = $db->query($dcoOrderProdQuery)->fetchAll();
                if ($dcoOrderProdData) {
                    if ($dcoOrderProdData[0]['dop_quantity'] < $arrived) {
                        $db->update('dco_orderprod', ['dop_quantity' => $arrived], ["dop_order" => $dcoOrderData[0]['dor_id'], "dop_product" => $rfqoData[0]['rfqop_product']]);
                    } else{
                        $isLow = true;
                    }
                }
            }
            break;
        
        default:
            break;
    }
    if ($isLow) {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code(200);
        echo json_encode('{ "success" : false , "isLow" : true }');
    } else {
        $db->update("rfq_orderproducts",["rfqop_arrived" => $_POST["data"]],["id" => $ordprodId]);
    
        $quantityQuery = "SELECT rfqop_quantity, rfqop_arrived FROM rfq_orderproducts WHERE rfqo_id = " . $rfqoData[0]['rfqo_id'];
        $quantityData = $db->query($quantityQuery)->fetchAll();
        $totOrder = 0;
        $totArrived = 0;
        foreach ($quantityData as $entry) {
            $totOrder += $entry['rfqop_quantity'];
            $totArrived += $entry['rfqop_arrived'];
        }
        $state = null;
        if($totArrived == 0){
            $state = "On Order";
        } else if($totArrived > 0 && $totArrived < $totOrder){
            $state = "Part Arrived";
        } else {
            $state = "Completed";
        }

        $db->update("rfq_orders",["rfqo_state" => $state],["rfqo_id" => $rfqoData[0]['rfqo_id']]);

        header('Content-Type: application/json; charset=utf-8');
        http_response_code(200);
        echo json_encode('{ "success" : true , "status" : "' . $state . '"}');
    }
} else if($action=="update_payment_status") {
    $id = $_POST["id"];
    $db->update("rfq_orders",["rfqo_payment" => $_POST["data"]],["rfqo_id" => $id]);

    header('Content-Type: application/json; charset=utf-8');
    http_response_code(200);
    echo json_encode('{ "success" : true }');
} else if($action=="update_vat_type") {
    $id = $_POST["id"];
    $db->update("rfq_orders",["rfqo_vatid" => $_POST["data"]],["rfqo_id" => $id]);

    header('Content-Type: application/json; charset=utf-8');
    http_response_code(200);
    echo json_encode('{ "success" : true }');
} else if($action=="create_stockin") {
    $data = $_POST["data"];
    $id = $_POST["id"];

    $isAltered = false;

    $RfqoQuery = "SELECT rfqo_date, rfqo_supplier, rfqo_reference FROM rfq_orders WHERE rfqo_id = " . $id;
    $RfqoData = $db->query($RfqoQuery)->fetchAll();

    $nwpEntryQuery = "SELECT nor_id, nor_total_items FROM nwp_orders WHERE rfqorderid = " . $id;
    $nwpEntryData = $db->query($nwpEntryQuery)->fetchAll();

    if ($nwpEntryData) {
        $norTotal = 0;
        foreach ($data as $entry){
            if ($entry['prodtype'] == 1 && $entry['arrived'] > 0) {
                $norTotal += $entry['arrived'];
            }
        }
        if ($norTotal > $nwpEntryData[0]['nor_total_items']) {
            $db->update('nwp_orders', ['nor_total_items' => $norTotal], ['nor_id' => $nwpEntryData[0]['nor_id']]);
            $isAltered = true;
        }
        foreach ($data as $entry) {
            if ($entry['prodtype'] == 1 && $entry['arrived'] > 0) {
                $db->update("rfq_orderproducts",["rfqop_arrived" => $entry['arrived']],["id" => $entry['id']]);
                $nwpItemQuery = "SELECT nop_quantity FROM nwp_orderprod WHERE nop_order = " . $nwpEntryData[0]['nor_id'] . " AND nop_product = " . $entry['prodid'];
                $nwpItemData = $db->query($nwpItemQuery)->fetchAll();
                if ($nwpItemData) {
                    if ($entry['arrived'] > $nwpItemData[0]['nop_quantity']) {
                        $db->update('nwp_orderprod', ['nop_quantity' => $entry['arrived']], ["nop_order" => $nwpEntryData[0]['nor_id'], "nop_product" => $entry['prodid']]);
                        $isAltered = true;
                    }
                } else {
                    $dataCreateOrdersProdEntry = array(
                        'nop_order' => $nwpEntryData[0]['nor_id'],
                        'nop_product' => $entry['prodid'],
                        'nop_quantity' => $entry['arrived'],
                        'nop_delivered' => 0,
                        'nop_sn' => "",
                    );
                    $db->insert('nwp_orderprod', $dataCreateOrdersProdEntry);
                    $isAltered = true;
                }
            }
        }
    } else {
        $norTotal = 0;
        foreach ($data as $entry){
            if ($entry['prodtype'] == 1 && $entry['arrived'] > 0) {
                $norTotal += $entry['arrived'];
            }
        }
        if ($norTotal > 0) {
            $dataCreateOrdersEntry = array(
                    'nor_date' => $RfqoData[0]['rfqo_date'],
                    'nor_supplier' => $RfqoData[0]['rfqo_supplier'],
                    'nor_state' => "Awaiting",
                    'nor_total_items' => $norTotal,
                    'nor_total_delivered' => 0,
                    'nor_fix_rate' => 0.0,
                    'nor_reference' => $RfqoData[0]['rfqo_reference'],
                    'rfqorderid' => $id,
                );
            $db->insert('nwp_orders', $dataCreateOrdersEntry);
            $ordersID = $db->id();
            foreach ($data as $entry){
                if ($entry['prodtype'] == 1 && $entry['arrived'] > 0) {
                    $db->update("rfq_orderproducts",["rfqop_arrived" => $entry['arrived']],["id" => $entry['id']]);
                    $dataCreateOrdersProdEntry = array(
                        'nop_order' => $ordersID,
                        'nop_product' => $entry['prodid'],
                        'nop_quantity' => $entry['arrived'],
                        'nop_delivered' => 0,
                        'nop_sn' => "",
                    );
                    $db->insert('nwp_orderprod', $dataCreateOrdersProdEntry);
                    $isAltered = true;
                }
            }
        }
    }

    $accEntryQuery = "SELECT aor_id, aor_total_items FROM acc_orders WHERE rfqorderid = " . $id;
    $accEntryData = $db->query($accEntryQuery)->fetchAll();
    if ($accEntryData) {
        $accTotal = 0;
        foreach ($data as $entry){
            if ($entry['prodtype'] == 3 && $entry['arrived'] > 0) {
                $accTotal += $entry['arrived'];
            }
        }
        if ($norTotal > $accEntryData[0]['aor_total_items']) {
            $db->update('acc_orders', ['aor_total_items' => $aorTotal], ['aor_id' => $accEntryData[0]['aor_id']]);
            $isAltered = true;
        }
        foreach ($data as $entry) {
            if ($entry['prodtype'] == 3 && $entry['arrived'] > 0) {
                $db->update("rfq_orderproducts",["rfqop_arrived" => $entry['arrived']],["id" => $entry['id']]);
                $accItemQuery = "SELECT aop_quantity FROM acc_orderprod WHERE aop_order = " . $accEntryData[0]['aor_id'] . " AND aop_product = " . $entry['prodid'];
                $accItemData = $db->query($accItemQuery)->fetchAll();
                if ($accItemData) {
                    if ($entry['arrived'] > $accItemData[0]['aop_quantity']) {
                        $db->update('acc_orderprod', ['aop_quantity' => $entry['arrived']], ["aop_order" => $accEntryData[0]['aor_id'], "aop_product" => $entry['prodid']]);
                        $isAltered = true;
                    }
                } else {
                    $dataCreateOrdersProdEntry = array(
                        'aop_order' => $accEntryData[0]['aor_id'],
                        'aop_product' => $entry['prodid'],
                        'aop_quantity' => $entry['arrived'],
                        'aop_delivered' => 0,
                        'aop_sn' => "",
                    );
                    $db->insert('acc_orderprod', $dataCreateOrdersProdEntry);
                    $isAltered = true;
                }
            }
        }
    } else {
        $accTotal = 0;
        foreach ($data as $entry){
            if ($entry['prodtype'] == 3 && $entry['arrived'] > 0) {
                $accTotal += $entry['arrived'];
            }
        }
        if ($accTotal > 0) {
            $dataCreateOrdersEntry = array(
                'aor_date' => $RfqoData[0]['rfqo_date'],
                'aor_supplier' => $RfqoData[0]['rfqo_supplier'],
                'aor_state' => "Awaiting",
                'aor_total_items' => $accTotal,
                'aor_total_delivered' => 0,
                'aor_fix_rate' => 0.0,
                'aor_reference' => $RfqoData[0]['rfqo_reference'],
                'rfqorderid' => $id,
            );
            $db->insert('acc_orders', $dataCreateOrdersEntry);
            $ordersID = $db->id();
            foreach ($data as $entry){
                if ($entry['prodtype'] == 3 && $entry['arrived'] > 0) {
                    $db->update("rfq_orderproducts",["rfqop_arrived" => $entry['arrived']],["id" => $entry['id']]);
                    $dataCreateOrdersProdEntry = array(
                        'aop_order' => $ordersID,
                        'aop_product' => $entry['prodid'],
                        'aop_quantity' => $entry['arrived'],
                        'aop_delivered' => 0,
                        'aop_sn' => "",
                    );
                    $db->insert('acc_orderprod', $dataCreateOrdersProdEntry);
                    $isAltered = true;
                }
            }
        }
    }

    $dcoEntryQuery = "SELECT dor_id, dor_total_items FROM dco_orders WHERE rfqorderid = " . $id;
    $dcoEntryData = $db->query($dcoEntryQuery)->fetchAll();
    
    if ($dcoEntryData) {
        $dcoTotal = 0;
        foreach ($data as $entry){
            if ($entry['prodtype'] == 4 && $entry['arrived'] > 0) {
                $dcoTotal += $entry['arrived'];
            }
        }

        if ($dcoTotal > $dcoEntryData[0]['dor_total_items']) {
            $db->update('dco_orders', ['dor_total_items' => $dcoTotal], ['dor_id' => $dcoEntryData[0]['dor_id']]);
            $isAltered = true;
        }
        foreach ($data as $entry) {
            if ($entry['prodtype'] == 4 && $entry['arrived'] > 0) {
                $db->update("rfq_orderproducts",["rfqop_arrived" => $entry['arrived']],["id" => $entry['id']]);
                $dcoItemQuery = "SELECT dop_quantity FROM dco_orderprod WHERE dop_order = " . $dcoEntryData[0]['dor_id'] . " AND dop_product = " . $entry['prodid'];
                $dcoItemData = $db->query($dcoItemQuery)->fetchAll();
                if ($dcoItemData) {
                    if ($entry['arrived'] > $dcoItemData[0]['dop_quantity']) {
                        $db->update('dco_orderprod', ['dop_quantity' => $entry['arrived']], ["dop_order" => $dcoEntryData[0]['dor_id'], "dop_product" => $entry['prodid']]);
                        $isAltered = true;
                    }
                } else {
                    $dataCreateOrdersProdEntry = array(
                        'dop_order' => $dcoEntryData[0]['dor_id'],
                        'dop_product' => $entry['prodid'],
                        'dop_quantity' => $entry['arrived'],
                        'dop_delivered' => 0,
                        'dop_sn' => "",
                    );
                    $db->insert('dco_orderprod', $dataCreateOrdersProdEntry);
                    $isAltered = true;
                }
            }
        }
    } else {
        $dcoTotal = 0;
        foreach ($data as $entry){
            if ($entry['prodtype'] == 4 && $entry['arrived'] > 0) {
                $dcoTotal += $entry['arrived'];
            }
        }
        if ($dcoTotal > 0) {
            $dataCreateOrdersEntry = array(
                'dor_date' => $RfqoData[0]['rfqo_date'],
                'dor_supplier' => $RfqoData[0]['rfqo_supplier'],
                'dor_state' => "Awaiting",
                'dor_total_items' => $dcoTotal,
                'dor_total_delivered' => 0,
                'dor_fix_rate' => 0.0,
                'dor_reference' => $RfqoData[0]['rfqo_reference'],
                'dor_type' => "Standard",
                'rfqorderid' => $id,
            );
            $db->insert('dco_orders', $dataCreateOrdersEntry);
            $ordersID = $db->id();
            foreach ($data as $entry){
                if ($entry['prodtype'] == 4 && $entry['arrived'] > 0) {
                    $db->update("rfq_orderproducts",["rfqop_arrived" => $entry['arrived']],["id" => $entry['id']]);
                    $dataCreateOrdersProdEntry = array(
                            'dop_order' => $ordersID,
                            'dop_product' => $entry['prodid'],
                            'dop_quantity' => $entry['arrived'],
                            'dop_delivered' => 0,
                            'dop_sn' => "",
                        );
                    $db->insert('dco_orderprod', $dataCreateOrdersProdEntry);
                    $isAltered = true;
                }
            }
        }
    }
    if ($isAltered) {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code(200);
        echo json_encode('{ "success" : true }');
    } else {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code(200);
        echo json_encode('{ "success" : false,  "alreadyExists" : true}');
    }
} else if($action=="get_stockins") {
    $id = $_POST["id"];
    $data = array();
    $index = 0;

    $nwpQuery = "SELECT nor_id, nor_date, nor_reference, nor_state FROM nwp_orders WHERE rfqorderid = " . $id;
    $nwpoData = array();

    if ($nwpData = $db->query($nwpQuery)->fetchAll()) {
        $newArr = ["id" => $nwpData[0]['nor_id'], "date" => $nwpData[0]['nor_date'], "reference" => $nwpData[0]['nor_reference'], "state" => $nwpData[0]['nor_state'], "type" => "nwp"];
        $data[$index] = $newArr;
        $index++;
    }
    $accQuery = "SELECT aor_id, aor_date, aor_reference, aor_state FROM acc_orders WHERE rfqorderid = " . $id;
    $accoData = array();

    if ($accData = $db->query($accQuery)->fetchAll()) {
        $newArr = ["id" => $accData[0]['aor_id'], "date" => $accData[0]['aor_date'], "reference" => $accData[0]['aor_reference'], "state" => $accData[0]['aor_state'], "type" => "aor"];
        $data[$index] = $newArr;
        $index++;
    }
    $dcoQuery = "SELECT dor_id, dor_date, dor_reference, dor_state FROM dco_orders WHERE rfqorderid = " . $id;
    $dcoData = array();

    if ($dcoData = $db->query($dcoQuery)->fetchAll()) {
        $newArr = ["id" => $dcoData[0]['dor_id'], "date" => $dcoData[0]['dor_date'], "reference" => $dcoData[0]['dor_reference'], "state" => $dcoData[0]['dor_state'], "type" => "dor"];
        $data[$index] = $newArr;
        $index++;
    }


    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(array('success' => true, 'data' => $data));
} else {
    exit();
}