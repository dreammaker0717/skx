<?php

$db = M::db();
// if($action == "orderin") {
     
//     $data = $_POST["data"];           
//     $id = intval($data["id"]);
//     $pr_id = intval($data["pr_id"]);
//     $token = $data["token"];  
    
//     $tokened = array();
//     if(isset($_SESSION[$token])) {
//         $tokened = $_SESSION[$token];
//     }

//     $inputhtml=array();
//     $input_array = $db->query("SELECT * FROM stock WHERE st_product=".$pr_id." AND st_order=".$id)->fetchAll();
//     foreach($input_array as $ki=>$vi) {
//         array_push($inputhtml ,[ "serial" => $vi["st_servicetag"], "cost"=> $vi["st_cost"] ]);
//     }

//     $result = $db->query("select op_quantity from orderprod where op_product=".$pr_id." and op_order=".$id."")->fetchAll(); 
//     foreach($tokened as $key => $val) {
//         if($key == $pr_id) {
//             foreach($val as $k=>$v) {                
//                 array_push($inputhtml, [ "serial" => $k, "cost"=> $v ]);
//             }
//         }
//     }
//     $pt = [
//         "data" => $inputhtml,
//         "count" => count($inputhtml),
//         "remain" => intval($result[0]["op_quantity"]) - count($inputhtml),
//         "success"=> true
//     ];
//     echo json_encode($pt);
//     exit;
// }

// if($action == "orderinrem") {
//     header('Content-Type: application/json; charset=utf-8');
//     try 
//     {
//         $data = $_POST["data"];           
//         $id = intval($data["id"]);
//         $pr_id = intval($data["pr_id"]);
//         $token = $data["token"];  
//         $serial = $data["serial"];  
//         $tokened = array();
//         if(isset($_SESSION[$token])) {
//             $tokened = $_SESSION[$token];
//         }
//         unset($tokened[$pr_id][$serial]);
//         $_SESSION[$token] = $tokened;        
//         http_response_code(200);
//         echo json_encode('{ "success" : true }');
//     } catch (Exception $e) {
//         http_response_code(500);
//         echo json_encode('{ "success" : false, "error": "'. $e->getMessage().'" }');
//     }
// }

// if($action =="getorderitems") {
//     $data = $_POST["data"];   
//     $id = intval($data["id"]);
//     $token = $data["token"];
//     if($id==0) $id=-9999;
//     $ord = $db->get("orders","*",["or_id"=>$id]);

//     $query="SELECT pr_id,pr_name,pr_title,mf_name,ct_name, op_quantity FROM orderprod left join  products on op_product=pr_id left join manufacturers on  pr_manufacturer=mf_id left join categories on pr_category=ct_id WHERE   op_order=$id";
//     $data = $db->query($query)->fetchAll();                        
//     foreach($data as $k=>$v) {
        
//         echo "<tr><td>".$v["pr_id"]."</td><td>".$v["pr_name"]."</td><td>".$v["pr_title"]."</td><td>".$v["mf_name"]."</td><td>".$v["ct_name"]."</td><td>".$v["op_quantity"]."</td>";        
//         echo "<th></th>";
//         echo "</tr>";

//     }
//     if(isset($_SESSION[$token])) {
//         $tokened = $_SESSION[$token];

//         if(is_array($tokened)) {
//             foreach($tokened as $k=>$v){

//                 echo "<tr><td>".$v["pr_id"]."</td><td>".$v["pr_name"]."</td><td>".$v["pr_title"]."</td><td>".$v["mf_name"]."</td><td>".$v["ct_name"]."</td><td>".$v["op_quantity"]."</td>";        
//                 echo "<th style='width:50px;'><button class='btn btn-sm btn-warning' onClick='RemoveItem(".$v["pr_id"].")'>Remove</button></th>";
//                 echo "</tr>";

//             }
//         }
//     }
// }


if($action=="search") {
	
    $sv = $_POST["search"]["value"];
    $start = intval($_POST["start"]);
    $length = intval($_POST["length"]);

    $order_field = "or_date desc";
    

    if(!empty($_POST["order"])) {
        $order_field =  $_POST['columns'][$_POST['order']['0']['column']]['data'] ." ". strtoupper( $_POST['order']['0']['dir']  );
    }

    $numRows = 0;
    $numRowsTotal = 0;
    $_SF =" or_supplier=sp_id ";
    $_SFC= $_SF;
           
    $_PARA=[];

    $SEARCH = $_POST["search"]["value"];
    if(!empty($SEARCH)) {
        
        $_SF.= " AND (sp_name like :search OR or_id = :search_no )";
        $_PARA[":search"]   = "%".$SEARCH."%";
        $_PARA[":search_no"]   = $SEARCH;
        
    }
    

    $query=
        "SELECT *, 
            orange as st_orange, purple as st_purple, red as st_red, lightblue as st_lightblue, lightgreen as st_lightgreen, 
            darkgreen as st_darkgreen, sold+gray as st_sold , black+stripped as st_black,  
             actionreq+actioncmp as st_action, brown as st_brown , CONCAT(  darkgreen+sold+gray, ' / ' , TRUNCATE( (((darkgreen+sold+gray)*100) / or_total_items),0),'%')  as st_fix_rate, CONCAT(TRUNCATE(((sold*100) / or_total_items),0),'%') as st_sell_through_rate, vat_label
        FROM order_distribution left join vat_rates on vat_type = or_vat_type AND vat_percent = or_vat_rate, suppliers WHERE  $_SF
        order by $order_field 
        limit $start,$length";

    $query_count = "select count(*) as c FROM order_distribution, suppliers WHERE  $_SFC ";
    $query_count2 = "select count(*) as c FROM order_distribution, suppliers WHERE  $_SF ";

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

