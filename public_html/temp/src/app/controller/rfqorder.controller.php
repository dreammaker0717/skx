<?php

$db = M::db();

if($action=="search") {
	
    $sv = $_POST["search"]["value"];
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
        "SELECT rfq_orders.rfqo_id, rfq_orders.rfqo_date, users.username as rfqo_user_name, suppliers.sp_name as rfqo_supplier_name, rfq_orders.rfqo_reference, rfq_orders.rfqo_user, rfq_orders.rfqo_state , rfq_orders.rfq_id 
        FROM rfq_orders left join users on rfq_orders.rfqo_user = users.user_id left join suppliers on rfq_orders.rfqo_supplier = suppliers.sp_id
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

    error_log(print_r($data, true));

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
    $db->update("rfq_orderproducts",["rfqop_arrived" => $_POST["data"]],["id" => $_POST["id"]]);
    header('Content-Type: application/json; charset=utf-8');
    http_response_code(200);
    echo json_encode('{ "success" : true }');
} else {
    exit();
}