<?php

$db = M::db();

if($action=="search") {
	
    $sv = $_POST["search"]["value"];
    $start = intval($_POST["start"]);
    $length = intval($_POST["length"]);

    $order_field = "aor_date desc";
    

    if(!empty($_POST["order"])) {
        $order_field =  $_POST['columns'][$_POST['order']['0']['column']]['data'] ." ". strtoupper( $_POST['order']['0']['dir']  );
    }

    $numRows = 0;
    $numRowsTotal = 0;
    $_SF =" aor_supplier=sp_id ";
    $_SFC= $_SF;
           
    $_PARA=[];

    $SEARCH = $_POST["search"]["value"];
    if(!empty($SEARCH)) {
        
        $_SF.= " AND (sp_name like :search OR aor_id = :search_no )";
        $_PARA[":search"]   = "%".$SEARCH."%";
        $_PARA[":search_no"]   = $SEARCH;
        
    }
    

    $query=
        "SELECT *, 
            orange as ast_orange, purple as ast_purple, red as ast_red, lightblue as ast_lightblue, lightgreen as ast_lightgreen, 
            darkgreen as ast_darkgreen, sold+gray as ast_sold , black+stripped as ast_black,  
            actioncmp as ast_action, brown as ast_brown , CONCAT(  darkgreen+sold+gray, ' / ' , TRUNCATE( (((darkgreen+sold+gray)*100) / aor_total_items),0),'%')  as ast_fix_rate, CONCAT(TRUNCATE(((sold*100) / aor_total_items),0),'%') as ast_sell_through_rate, vat_label
        FROM acc_order_distribution left join vat_rates on vat_type = aor_vat_type AND vat_percent = aor_vat_rate, suppliers WHERE  $_SF
        order by $order_field 
        limit $start,$length";

    $query_count = "select count(*) as c FROM acc_order_distribution, suppliers WHERE  $_SFC ";
    $query_count2 = "select count(*) as c FROM acc_order_distribution, suppliers WHERE  $_SF ";

    $data = $db->query($query,$_PARA)->fetchAll();
    $numRowsTotal = $db->query($query_count2,$_PARA)->fetchAll();
    $numRows = $db->query($query_count)->fetchAll();

    //select count(*), st_order,st_status from stock where $ group by st_order,st_status



    $output = array(
		"draw"	=>	intval($_POST["draw"]),			
		"iTotalRecords"	=> 	$numRows[0]["c"],
		"iTotalDisplayRecords"	=>  $numRowsTotal[0]["c"],
		"data"	=> 	$data,
        "t" => "r0"
	);

    echo json_encode($output);

}
else {
    exit();
}

