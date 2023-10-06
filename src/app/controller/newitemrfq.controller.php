<?php

$db = M::db();
if($action=="search") {
	
    $sv = $_POST["search"]["value"];
    $start = intval($_POST["start"]);
    $length = intval($_POST["length"]);

    $order_field = "rfq_rfq.rfq_date desc";
    
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
        
        $_SF.= " AND ( rfq_rfq.rfq_reference like :search OR rfq_rfq.rfq_id = :search_no )";
        $_PARA[":search"]   = "%".$SEARCH."%";
        $_PARA[":search_no"]   = $SEARCH;
        
    }    

    $query=
        "SELECT rfq_rfq.rfq_id, rfq_rfq.rfq_date, users.username as rfq_user_name, rfq_rfq.rfq_state, rfq_rfq.rfq_total_items, rfq_rfq.rfq_total_ordered, rfq_rfq.rfq_reference, rfq_rfq.rfq_currency 
        FROM rfq_rfq left join users on rfq_rfq.rfq_user = users.user_id
        WHERE  $_SF
        order by $order_field 
        limit $start,$length";

    $query_count = "select count(*) as c FROM rfq_rfq left join users on rfq_rfq.rfq_user = users.user_id WHERE  $_SFC ";
    $query_count2 = "select count(*) as c FROM rfq_rfq left join users on rfq_rfq.rfq_user = users.user_id WHERE  $_SF ";

    $data = $db->query($query,$_PARA)->fetchAll();
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

} else if($action=="saverfqorder") {
        try 
    {    
        $data = $_POST["data"];
        $id = $_POST["id"];
        $totalItems = 0;
        foreach ($data["listData"] as $listEntry) {
            $totalItems += $listEntry['quantity'];
        }
        if($id==0) {
            $dataSaveRfq = array(
                'rfq_date' => date("Y-m-d", strtotime(str_replace("/","-",$data["date"]))),
                'rfq_user' => $_POST['user'],
                'rfq_state' => "Draft",
                'rfq_total_items' => $totalItems,
                'rfq_total_ordered' => 0,
                'rfq_reference' => $data['reference'],
                'rfq_currency' => $data['currency'],
            );
            $db->insert('rfq_rfq', $dataSaveRfq);
            $id = $db->id();

            foreach ($data["listData"] as $entry) {
                $dataSaveItems = array(
                    'rfq_id' => $id,
                    'rfq_prodtype' => $entry['prodtype'],
                    'rfq_product' => $entry['id'],
                    'rfq_quantity' => $entry['quantity'],
                    'rfq_price' => $entry['price'],
                    'rfq_suppliercomments' => $entry['suppliercomments'],
                );
                $db->insert('rfq_items', $dataSaveItems);
            }

            header('Content-Type: application/json; charset=utf-8');
            http_response_code(200);
            echo json_encode('{ "success" : true , "id":'.$id.'}');
            
        } else {
            $dataSaveRfq = array(
                'rfq_date' => date("Y-m-d", strtotime(str_replace("/","-",$data["date"]))),
                'rfq_user' => $_POST['user'],
                'rfq_state' => "Draft",
                'rfq_total_items' => $totalItems,
                'rfq_total_ordered' => 0,
                'rfq_reference' => $data['reference'],
                'rfq_currency' => $data['currency'],
                'rfq_payment' => 0,
            );
            $db->update('rfq_rfq', $dataSaveRfq, ["id" => $id]);

            $db->delete("rfq_items", ["rfq_id" => $id]);

            foreach ($data["listData"] as $entry) {
                $dataSaveItems = array(
                    'rfq_id' => $id,
                    'rfq_prodtype' => $entry['prodtype'],
                    'rfq_product' => $entry['id'],
                    'rfq_quantity' => $entry['quantity'],
                    'rfq_price' => $entry['price'],
                    'rfq_suppliercomments' => $entry['suppliercomments'],
                );
                $db->insert('rfq_items', $dataSaveItems);
            }

            header('Content-Type: application/json; charset=utf-8');
            http_response_code(200);
            echo json_encode('{ "success" : true , "id":'.$id.'}');
        }

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode('{ "success" : false, "error": "'. $e->getMessage().'" }');
    }
} else if($action=="createorder") {
    try {
        $data = $_POST["data"];
        $id = $_POST["id"];
        $totalItems = 0;
        foreach ($data["listData"] as $listEntry) {
            $totalItems += $listEntry['quantity'];
        }
        if($id==0) {
            $dataSaveRfq = array(
                'rfq_date' => date("Y-m-d", strtotime(str_replace("/","-",$data["date"]))),
                'rfq_user' => $_POST['user'],
                'rfq_state' => "Draft",
                'rfq_total_items' => $totalItems,
                'rfq_total_ordered' => 0,
                'rfq_reference' => $data['reference'],
                'rfq_currency' => $data['currency'],
            );
            $db->insert('rfq_rfq', $dataSaveRfq);
            $id = $db->id();

            foreach ($data["listData"] as $entry) {
                $dataSaveItems = array(
                    'rfq_id' => $id,
                    'rfq_prodtype' => $entry['prodtype'],
                    'rfq_product' => $entry['id'],
                    'rfq_quantity' => $entry['quantity'],
                    'rfq_price' => $entry['price'],
                    'rfq_suppliercomments' => $entry['suppliercomments'],
                );
                $db->insert('rfq_items', $dataSaveItems);
            }
        }
            
        $dataSaveRfqOrder = array(
                'rfq_id' => $id,
                'rfqo_date' => date("Y-m-d", strtotime(str_replace("/","-",$data["date"]))),
                'rfqo_user' => $_POST['user'],
                'rfqo_state' => "On Order",
                'rfqo_total_items' => $totalItems,
                'rfqo_total_ordered' => 0,
                'rfqo_reference' => $data['reference'],
                'rfqo_supplier' => $data['supplier'],
                'rfqo_currency' => $data['currency'],
                'rfqo_payment' => 0,
                'rfqo_label_fee' => $data['labelfee'],
                'rfqo_ship_fee' => $data['shipfee'],
                'rfqo_bank_fee' => $data['bankfee'],
                'rfqo_surcharge' => $data['surcharge'],
                'rfqo_credit' => $data['credit'],
                'rfqo_discount' => $data['discount'],
            );
        $db->insert('rfq_orders', $dataSaveRfqOrder);
        $rfqoID = $db->id();

        foreach ($data["listData"] as $entry) {
            $dataSaveRfqOrderItems = array(
                'rfqo_id' => $rfqoID,
                'rfqop_prodtype' => $entry['prodtype'],
                'rfqop_product' => $entry['id'],
                'rfqop_quantity' => $entry['quantity'],
                'rfqop_price' => $entry['price'],
                'rfqop_suppliercomments' => $entry['suppliercomments'],
            );
            $db->insert('rfq_orderproducts', $dataSaveRfqOrderItems);
        }

        header('Content-Type: application/json; charset=utf-8');
        http_response_code(200);
        echo json_encode('{ "success" : true , "id":'.$id.'}');
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode('{ "success" : false, "error": "'. $e->getMessage().'" }');
    }
} else if($action=="createrfqorder") {
    try {
        $data = $_POST["data"];
        $id = $_POST["id"];
        $totalItems = 0;
        foreach ($data["listData"] as $listEntry) {
            $totalItems += $listEntry['quantity'];
        }
            
        $dataSaveRfqOrder = array(
                'rfq_id' => 0,
                'rfqo_date' => date("Y-m-d", strtotime(str_replace("/","-",$data["date"]))),
                'rfqo_user' => $_POST['user'],
                'rfqo_state' => "On Order",
                'rfqo_total_items' => $totalItems,
                'rfqo_total_ordered' => 0,
                'rfqo_vatid' => $data['vatType'],
                'rfqo_reference' => $data['reference'],
                'rfqo_supplier' => $data['supplier'],
                'rfqo_currency' => $data['currency'],
                'rfqo_payment' => 0,
                'rfqo_label_fee' => $data['labelfee'],
                'rfqo_ship_fee' => $data['shipfee'],
                'rfqo_bank_fee' => $data['bankfee'],
                'rfqo_surcharge' => $data['surcharge'],
                'rfqo_credit' => $data['credit'],
                'rfqo_discount' => $data['discount'],
            );
        $db->insert('rfq_orders', $dataSaveRfqOrder);
        $rfqoID = $db->id();

        foreach ($data["listData"] as $entry) {
            $dataSaveRfqOrderItems = array(
                'rfqo_id' => $rfqoID,
                'rfqop_prodtype' => $entry['prodtype'],
                'rfqop_product' => $entry['id'],
                'rfqop_quantity' => $entry['quantity'],
                'rfqop_price' => $entry['price'],
                'rfqop_suppliercomments' => $entry['suppliercomments'],
            );
            $db->insert('rfq_orderproducts', $dataSaveRfqOrderItems);
        }

        header('Content-Type: application/json; charset=utf-8');
        http_response_code(200);
        echo json_encode('{ "success" : true}');
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode('{ "success" : false, "error": "'. $e->getMessage().'" }');
    }
} else if($action=="deleteorder") {
    try {
        $data = $_POST["data"];
        $db->delete("rfq_items", ["rfq_id" => $data["id"]]);
        $db->delete("rfq_rfq", ["rfq_id" => $data["id"]]);
        header('Content-Type: application/json; charset=utf-8');
        http_response_code(200);
        echo json_encode('{ "success" : true}');
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode('{ "success" : false, "error": "'. $e->getMessage().'" }');
    }
} else if($action=="get_suggestion") {
    $finalData = array();
    $index = 0;

    $nwpQuery = "SELECT npr_id, npr_sku, npr_name FROM nwp_products WHERE npr_sku LIKE '%" .$_POST["term"]. "%' OR". " npr_name LIKE '%" .$_POST["term"] . "%' LIMIT 10";
    $nwpData = $db->query($nwpQuery)->fetchAll();
    for ($i = 0; $i < count($nwpData); $i++) {
        $finalData[$index]['prodtype'] = 1;
        $finalData[$index]['productid'] = $nwpData[$i]['npr_id'];
        $finalData[$index]['sku'] = $nwpData[$i]['npr_sku'];
        $finalData[$index]['name'] = $nwpData[$i]['npr_name'];

        $index++;
    }

    $nwp2Query = "SELECT npr2_id, npr2_sku, npr2_name FROM nwp_products2 WHERE npr2_sku LIKE '%" .$_POST["term"]. "%' OR". " npr2_name LIKE '%" .$_POST["term"] . "%' LIMIT 10";
    $nwp2Data = $db->query($nwp2Query)->fetchAll();
    for ($i = 0; $i < count($nwp2Data); $i++) {
        $finalData[$index]['prodtype'] = 2;
        $finalData[$index]['productid'] = $nwp2Data[$i]['npr2_id'];
        $finalData[$index]['sku'] = $nwp2Data[$i]['npr2_sku'];
        $finalData[$index]['name'] = $nwp2Data[$i]['npr2_name'];

        $index++;
    }

    $aprQuery = "SELECT apr_id, apr_sku, apr_name FROM aproducts WHERE apr_sku LIKE '%" .$_POST["term"]. "%' OR". " apr_name LIKE '%" .$_POST["term"] . "%' LIMIT 10";
    $aprData = $db->query($aprQuery)->fetchAll();
    for ($i = 0; $i < count($aprData); $i++) {
        $finalData[$index]['id'] = $index + 1;
        $finalData[$index]['prodtype'] = 3;
        $finalData[$index]['productid'] = $aprData[$i]['apr_id'];
        $finalData[$index]['sku'] = $aprData[$i]['apr_sku'];
        $finalData[$index]['name'] = $aprData[$i]['apr_name'];

        $index++;
    }


    $dpQuery = "SELECT dp_id, dp_sku, dp_name FROM dell_part WHERE dp_sku LIKE '%" .$_POST["term"]. "%' OR". " dp_name LIKE '%" .$_POST["term"] . "%' LIMIT 10";
    $dpData = $db->query($dpQuery)->fetchAll();
    for ($i = 0; $i < count($dpData); $i++) {
        $finalData[$index]['id'] = $index + 1;
        $finalData[$index]['prodtype'] = 4;
        $finalData[$index]['productid'] = $dpData[$i]['dp_id'];
        $finalData[$index]['sku'] = $dpData[$i]['dp_sku'];
        $finalData[$index]['name'] = $dpData[$i]['dp_name'];

        $index++;
    }

    echo json_encode($finalData);
} else if($action=="get_product_details") {
    $price = 0;
    $supplierComments = "";
    $partQuery = null;
    $newArr = array();
    $lastRfqQuery = "SELECT rfq_items.rfq_price, rfq_items.rfq_suppliercomments FROM rfq_items LEFT JOIN rfq_rfq ON rfq_items.rfq_id = rfq_rfq.rfq_id WHERE rfq_product=" . $_POST['productid'] . " AND rfq_prodtype=" . $_POST['prodtype'] . " ORDER BY rfq_rfq.rfq_date DESC";

    if ($lastRfqData = $db->query($lastRfqQuery)->fetchAll()) {
        $price = $lastRfqData[0]['rfq_price'];
        $supplierComments = $lastRfqData[0]['rfq_suppliercomments'];
    }

    if ($_POST['prodtype'] == 1) {
        $partQuery = "SELECT npr_name, npr_condition, npr_sku, npr_magqty, npr_suppliercomments FROM nwp_products WHERE npr_id=" . $_POST['productid'];
        $partData = $db->query($partQuery)->fetchAll();
        $nwpStockQuery = "SELECT COUNT(*) as c FROM nwp_stock WHERE (nst_status = 7 OR nst_status = 22 OR nst_status = 6) AND nst_product = " . $_POST['productid'];
        $nwpStockData = $db->query($nwpStockQuery)->fetchAll();
        $quantity = 0;
        $arrived = 0;
        $nwpOrderQuery = "SELECT rfq_orderproducts.rfqop_quantity, rfq_orderproducts.rfqop_arrived, rfq_orders.rfqo_state FROM rfq_orderproducts LEFT JOIN rfq_orders ON rfq_orderproducts.rfqo_id = rfq_orders.rfqo_id WHERE rfq_orderproducts.rfqop_prodtype = 1 AND rfq_orderproducts.rfqop_product = " . $_POST['productid'];
        if ($nwpOrderData = $db->query($nwpOrderQuery)->fetchAll()) {
            foreach ($nwpOrderData as $entry) {
                if ($entry['rfqo_state'] == "On Order" || $entry['rfqo_state'] == "Part Arrived") {
                    $quantity+= $entry['rfqop_quantity'];
                    $arrived+= $entry['rfqop_arrived'];
                }
            }
        }
        $newArr = ['name' => $partData[0]['npr_name'], 'condition' => $partData[0]['npr_condition'], 'sku' => $partData[0]['npr_sku'], 'invqty' => $nwpStockData[0]['c'], 'magqty' => $partData[0]['npr_magqty'], 'orderqty' => ($quantity - $arrived), 'price' => $price, "prodtype" => 1, "id" => $_POST['productid'], "suppliercomments" => $supplierComments, "product_suppliercomments" => $partData[0]['npr_suppliercomments']];
    } else if ($_POST['prodtype'] == 2) {
        $partQuery = "SELECT npr2_name, npr2_condition, npr2_sku, npr2_magqty, npr2_suppliercomments FROM nwp_products2 WHERE npr2_id=" . $_POST['productid'];
        $partData = $db->query($partQuery)->fetchAll();
        $quantity = 0;
        $arrived = 0;
        $nwp2OrderQuery = "SELECT rfq_orderproducts.rfqop_quantity, rfq_orderproducts.rfqop_arrived, rfq_orders.rfqo_state FROM rfq_orderproducts LEFT JOIN rfq_orders ON rfq_orderproducts.rfqo_id = rfq_orders.rfqo_id WHERE rfq_orderproducts.rfqop_prodtype = 2 AND rfq_orderproducts.rfqop_product = " . $_POST['productid'];
        if ($nwp2OrderData = $db->query($nwp2OrderQuery)->fetchAll()) {
            foreach ($nwp2OrderData as $entry) {
                if ($entry['rfqo_state'] == "On Order" || $entry['rfqo_state'] == "Part Arrived") {
                    $quantity+= $entry['rfqop_quantity'];
                    $arrived+= $entry['rfqop_arrived'];
                }
            }
        }
        $newArr = ['name' => $partData[0]['npr2_name'], 'condition' => $partData[0]['npr2_condition'], 'sku' => $partData[0]['npr2_sku'], 'invqty' => 0, 'magqty' => $partData[0]['npr2_magqty'], 'orderqty' => ($quantity - $arrived), 'price' => $price, "prodtype" => 2, "id" => $_POST['productid'], "suppliercomments" => $supplierComments, "product_suppliercomments" => $partData[0]['npr2_suppliercomments']];
    } else if ($_POST['prodtype'] == 3) {
        $partQuery = "SELECT apr_name, apr_condition, apr_sku, apr_magqty, apr_suppliercomments FROM aproducts WHERE apr_id=" . $_POST['productid'];
        $partData = $db->query($partQuery)->fetchAll();
        $aprStockQuery = "SELECT COUNT(*) as c FROM acc_stock WHERE (ast_status = 7 OR ast_status = 22 OR ast_status = 6) AND ast_product = " . $_POST['productid'];
        $aprStockData = $db->query($aprStockQuery)->fetchAll();
        $quantity = 0;
        $arrived = 0;
        $aprOrderQuery = "SELECT rfq_orderproducts.rfqop_quantity, rfq_orderproducts.rfqop_arrived, rfq_orders.rfqo_state FROM rfq_orderproducts LEFT JOIN rfq_orders ON rfq_orderproducts.rfqo_id = rfq_orders.rfqo_id WHERE rfq_orderproducts.rfqop_prodtype = 3 AND rfq_orderproducts.rfqop_product = " . $_POST['productid'];
        if ($aprOrderData = $db->query($aprOrderQuery)->fetchAll()) {
            foreach ($aprOrderData as $entry) {
                if ($entry['rfqo_state'] == "On Order" || $entry['rfqo_state'] == "Part Arrived") {
                    $quantity+= $entry['rfqop_quantity'];
                    $arrived+= $entry['rfqop_arrived'];
                }
            }
        }
        $newArr = ['name' => $partData[0]['apr_name'], 'condition' => $partData[0]['apr_condition'], 'sku' => $partData[0]['apr_sku'], 'invqty' => $aprStockData[0]['c'], 'magqty' => $partData[0]['apr_magqty'], 'orderqty' => ($quantity - $arrived), 'price' => $price, "prodtype" => 3, "id" => $_POST['productid'], "suppliercomments" => $supplierComments, "product_suppliercomments" => $partData[0]['apr_suppliercomments']];
    } else if ($_POST['prodtype'] == 4) {
        $partQuery = "SELECT dp_name, dp_condition, dp_sku, dp_magqty, dp_suppliercomments FROM dell_part WHERE dp_id=" . $_POST['productid'];
        $partData = $db->query($partQuery)->fetchAll();
        $dpStockQuery = "SELECT COUNT(*) as c FROM dco_stock WHERE (dst_status = 7 OR dst_status = 22 OR dst_status = 6) AND dst_product = " . $_POST['productid'];
        $dpStockData = $db->query($dpStockQuery)->fetchAll();
        $quantity = 0;
        $arrived = 0;
        $dpOrderQuery = "SELECT rfq_orderproducts.rfqop_quantity, rfq_orderproducts.rfqop_arrived, rfq_orders.rfqo_state FROM rfq_orderproducts LEFT JOIN rfq_orders ON rfq_orderproducts.rfqo_id = rfq_orders.rfqo_id WHERE rfq_orderproducts.rfqop_prodtype = 4 AND rfq_orderproducts.rfqop_product = " . $_POST['productid'];
        if ($dpOrderData = $db->query($dpOrderQuery)->fetchAll()) {
            foreach ($dpOrderData as $entry) {
                if ($entry['rfqo_state'] == "On Order" || $entry['rfqo_state'] == "Part Arrived") {
                    $quantity+= $entry['rfqop_quantity'];
                    $arrived+= $entry['rfqop_arrived'];
                }
            }
        }
        $newArr = ['name' => $partData[0]['dp_name'], 'condition' => $partData[0]['dp_condition'], 'sku' => $partData[0]['dp_sku'], 'invqty' => $dpStockData[0]['c'], 'magqty' => $partData[0]['dp_magqty'], 'orderqty' => ($quantity - $arrived), 'price' => $price, "prodtype" => 4, "id" => $_POST['productid'], "suppliercomments" => $supplierComments, "product_suppliercomments" => $partData[0]['dp_suppliercomments']];
    }

    echo json_encode($newArr);
} else {
    exit();
}