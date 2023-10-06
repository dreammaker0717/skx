<?php

$db = M::db();
if($action=="search") {
	
    $sv = $_POST["search"]["value"];
    $start = intval($_POST["start"]);
    $length = intval($_POST["length"]);

    $order_field = "sku ASC";
    

    if(!empty($_POST["order"])) {
        $order_field =  $_POST['columns'][$_POST['order']['0']['column']]['data'] ." ". strtoupper( $_POST['order']['0']['dir']  );
    }

    $numRows = 0;
    $numRowsTotal = 0;
    $_SF ="ast_status = 7";
    $_SFC= $_SF;
           
    $_PARA=[];

    $SEARCH = $_POST["search"]["value"];
    if(!empty($SEARCH)) {
        $_SF.= " AND (apr_sku like :search)";
        $_PARA[":search"]   = "%".$SEARCH."%";
    }
    

    $query= "SELECT COUNT(*) AS c, apr_sku AS sku, SUM(ast_cost) AS cost, SUM(apr_magprice) AS price
        FROM `acc_stock` 
        LEFT JOIN aproducts ON ast_product = apr_id 
        WHERE $_SF
        GROUP BY sku
        order by $order_field 
        limit $start,$length";


    $query_count = "SELECT COUNT(*) OVER() AS c, apr_sku AS sku FROM acc_stock LEFT JOIN aproducts ON ast_product = apr_id WHERE $_SFC GROUP BY sku";
    $query_count2 = "SELECT COUNT(*) OVER() AS c, apr_sku AS sku FROM acc_stock LEFT JOIN aproducts ON ast_product = apr_id WHERE $_SF GROUP BY sku";

    $data = $db->query($query,$_PARA)->fetchAll();

    for ($i=0; $i < count($data); $i++) { 
        if ($data[$i]['sku'] == null) {
           $data[$i]['sku'] = "Others";
        }
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
} else if($action=="total_values") {
    $query= "SELECT COUNT(*) AS totalcount, SUM(ast_cost) AS totalcost, SUM(apr_magprice) AS totalprice
        FROM `acc_stock` 
        LEFT JOIN aproducts ON ast_product = apr_id 
        WHERE ast_status = 7
        LIMIT 0, 1";
    $data = $db->query($query)->fetchAll();
    echo json_encode($data[0]);
}