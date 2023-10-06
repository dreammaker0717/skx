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
                'rfqo_state' => "Draft",
                'rfqo_total_items' => $totalItems,
                'rfqo_total_ordered' => 0,
                'rfqo_reference' => $data['reference'],
                'rfqo_supplier' => $data['supplier'],
                'rfqo_currency' => $data['currency'],
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
} else {
    exit();
}