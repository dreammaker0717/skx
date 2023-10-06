<?php

$db = M::db();
include PATH_CONFIG . "/constants.php";
if($action == "putorderitems") {
    
    try {    
        $data = $_POST["data"];   
        $token = $data["token"];
        $id = intval($data["id"]);
        $tokened = array();
        if(isset($_SESSION[$token])) {
            $tokened=$_SESSION[$token];
        }

        $count = 0;
        $query="SELECT st_date,pr_id,pr_name,mf_name,st_ebay_seller,st_ebay_itemid,st_ebay_orderid,st_servicetag,st_cost,st_id,st_lastcomment,st_status FROM stock left join products ON st_product=pr_id left join manufacturers on  pr_manufacturer=mf_id left join categories on pr_category=ct_id WHERE st_order=$id ORDER BY st_date DESC";
        $data = $db->query($query)->fetchAll();                        
        foreach($data as $k=>$v) {
            $count++;
        }
        foreach($tokened as $k=>$v) {
            $count++;
            $db->exec("INSERT INTO stock SET st_status=1, st_order=".$id.", st_product=".$v["pr_id"].", st_ebay_seller='".$v["st_ebay_seller"]."', st_ebay_itemid='".$v["st_ebay_itemid"]."', st_ebay_orderid='".$v["st_ebay_orderid"]."', st_servicetag='".$v["st_servicetag"]."', st_cost=".$v["st_cost"].", st_vat_type='Margin', st_vat_rate=0, st_vat_amount=0, st_netprice=".$v["st_cost"].", st_date='". DateTime::createFromFormat('d/m/Y', $v["st_date"])->format('Y-m-d') ."', st_addedby = '".$_SESSION["user_id"]."';");
        }
        
        $db->exec("update orders set or_total_items = ".$count.", or_total_delivered = ".$count." where or_id= $id");

        unset($_SESSION[$token]);

        header('Content-Type: application/json; charset=utf-8');
        http_response_code(200);
        echo json_encode('{ "success" : true }');
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode('{ "success" : false, "error": "'. $e->getMessage().'" }');
    }        
}

if($action =="get_suggestion") {
    $query = "SELECT pr_id, pr_name, mf_name FROM products LEFT JOIN manufacturers ON pr_manufacturer = mf_id WHERE pr_name LIKE '%" .$_POST["term"]. "%' OR". " pr_title LIKE '%" .$_POST["term"] . "%' LIMIT 50";
    $data = $db->query($query)->fetchAll();
    echo json_encode($data);
}

if($action =="get_order_id") {
    try {
        $id;
        $date = date('Y-m-01');
        $query = "SELECT or_id FROM orders WHERE or_date='".$date."' AND or_type='eBay'";
        $data = $db->query($query)->fetchAll();
        if($data){
            $id = $data[0]['or_id'];
        } else{
            $db->update("orders",["or_state"=>"Completed"],["or_type"=>"eBay"]);
            $time=strtotime($date);
            $month=date("F",$time);
            $year=date("Y",$time);
            $reference = "eBay Orders ".$month." ".$year;
            $db->insert("orders",["or_date"=> $date, "or_supplier"=>1, "or_total_items"=>0, "or_total_delivered"=>0, "or_fix_rate"=>100, "or_reference"=>$reference, "or_vat_type"=>'Margin', "or_vat_rate"=>0, "or_state"=>"Awaiting", "or_type"=>"eBay"]);
            $id = $db->id();
        }
        header('Content-Type: application/json; charset=utf-8');
        http_response_code(200);
        echo json_encode('{ "success" : true , "id":'.$id.'}');
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode('{ "success" : false, "error": "'. $e->getMessage().'" }');
    }     
}

if($action =="getorderitems") {
    $data = $_POST["data"];   
    $id = intval($data["id"]);
    $token = $data["token"];
    if($id==0) $id=-9999;
    $ord = $db->get("orders","*",["or_id"=>$id]);

    $query="SELECT st_date,pr_id,pr_name,mf_name,st_ebay_seller,st_ebay_itemid,st_ebay_orderid,st_servicetag,st_cost,st_id,st_lastcomment,st_status FROM stock left join products ON st_product=pr_id left join manufacturers on  pr_manufacturer=mf_id left join categories on pr_category=ct_id WHERE st_order=$id ORDER BY st_date DESC";
    $data = $db->query($query)->fetchAll();                        
    foreach($data as $k=>$v) {
        if ($v["st_status"] != 8) {
            $cont = "<td style='width:50px;'><button class='btn btn-sm btn-warning' onClick='rtsUpdate(".$v["st_id"].")'>RTS</button></td>";
        } else{
            $cont = "-";
        }
        echo "<tr><td>" . date("d/m/Y", strtotime($v["st_date"])) . "</td><td></a>&nbsp&nbsp<a href=\"javascript:PrintBoxLabels(". $v["st_id"] .")\"><img src=\"https://img.icons8.com/material-outlined/24/000000/print.png\"></a></td><td><a href='../stock/" . $v["st_id"] . "'>". $v["st_id"] ."</a></td><td>".$v["pr_name"]."</td><td>".$v["st_servicetag"]."</td><td>".$v["st_ebay_seller"]."</td><td>".$v["st_ebay_itemid"]."</td><td>".$v["st_ebay_orderid"]."</td><td>".$v["st_cost"]."</td><td><input type=text onkeyup=\"CommentkeyUp(event,".$v["st_id"].")\" lass=\"form-control form-control-sm\" value=\"".$v["st_lastcomment"]."\"></td><td>".$_STATUSES[$v["st_status"]]["Name"]."</td>";
        echo $cont;
        echo "</tr>";

    }
    if(isset($_SESSION[$token])) {
        $tokened = $_SESSION[$token];

        if(is_array($tokened)) {
            foreach($tokened as $k=>$v){
                echo "<tr><td>".$v["st_date"]."</td><td>-</td><td>-</td><td>".$v["pr_name"]."</td><td>".$v["st_servicetag"]."</td><td>".$v["st_ebay_seller"]."</td><td>".$v["st_ebay_itemid"]."</td><td>".$v["st_ebay_orderid"]."</td><td>".$v["st_cost"]."</td><td>-</td><td>-</td>";
                echo "<td>-</td>";
                echo "</tr>";
            }
        }
    }
}


if($action == "addproduct") {
    try 
    {    
        $data = $_POST["data"];   
        $token = $data["token"];   
        $pr = intval($data["pr"]);
        $dt = $data["dt"];
        $it = $data["it"];
        $si = $data["si"];
        $oi = $data["oi"];
        $st = $data["st"];
        $ct = $data["ct"];
        $id = $data["id"];

        $tokened = array();
        if(isset($_SESSION[$token])) {
            $tokened=$_SESSION[$token];
        }

        foreach($tokened as $val) {
            if($val['st_servicetag'] == $st)
                throw new Exception('Serial already used!');
        }
            
        $serial_count = $db->count("stock",["st_servicetag" => $st]);

        if($serial_count>0)
            throw new Exception('Serial already used at database!');
        
        $query="SELECT pr_name FROM products left join manufacturers on  pr_manufacturer=mf_id WHERE pr_id=$pr";
        $pro = $db->query($query)->fetchAll();

        array_push($tokened, array( 
            "st_date"=>$dt,
            "pr_id"=>$pr,
            "pr_name"=> $pro[0]["pr_name"],
            "st_ebay_seller" => $si,
            "st_ebay_itemid" => $it,
            "st_ebay_orderid" => $oi,
            "st_servicetag" => $st,
            "st_cost" => $ct,
        ));

        $_SESSION[$token] = $tokened;
        header('Content-Type: application/json; charset=utf-8');
        http_response_code(200);
        echo json_encode('{ "success" : true }');
    } catch (Exception $e) {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code(200);
        echo json_encode('{ "success" : false, "error": "'. $e->getMessage().'" }');
    }
}

if($action=="update_comment"){
    try {
        $st_id = $_POST['st_id'];
        $data = $_POST['data'];
        $db->exec("update stock set st_lastcomment=:comment where st_id=:id",[":comment"=> [$data, PDO::PARAM_STR], ":id"=>[intval($st_id),PDO::PARAM_INT ]]);
        $db->exec("INSERT INTO stock_history SET sh_date=NOW(), sh_user=".$_SESSION["user_id"].", sh_status=(select st_status from stock where st_id=$st_id), sh_comment=:cm, sh_stock=:id",[":cm"=> [$data, PDO::PARAM_STR],  ":id"=>[intval($st_id), PDO::PARAM_INT] ] );

        header('Content-Type: application/json; charset=utf-8');
        http_response_code(200);
        echo json_encode('{ "success" : true }');
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode('{ "success" : false, "error": "'. $e->getMessage().'" }');
    }
}

if($action=="update_rts"){
    try {
        $st_id = $_POST['st_id'];
        $db->exec("update stock set st_status=8, st_cost=0, st_netprice=0 where st_id=:id",[":id"=>[intval($st_id),PDO::PARAM_INT ]]);
        $db->exec("INSERT INTO stock_history SET sh_date=NOW(), sh_user=".$_SESSION["user_id"].", sh_status=(select st_status from stock where st_id=$st_id), sh_comment=:cm, sh_stock=:id",[":cm"=> ["Item is returned to seller.", PDO::PARAM_STR],  ":id"=>[intval($st_id), PDO::PARAM_INT] ] );

        header('Content-Type: application/json; charset=utf-8');
        http_response_code(200);
        echo json_encode('{ "success" : true }');
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode('{ "success" : false, "error": "'. $e->getMessage().'" }');
    }
}


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
    $_SF =" or_type='eBay'";
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
            purple+red+lightblue+actionreq+actioncmp+brown+lightgreen as st_combined, orange as st_orange, darkgreen as st_darkgreen, sold+gray as st_sold , black+stripped as st_black, CONCAT(darkgreen+sold+gray, ' out of ', or_total_items, '/ ' , TRUNCATE((((darkgreen+sold+gray)*100) / or_total_items),0),'%') as st_fix_rate, CONCAT(TRUNCATE(((sold*100) / or_total_items),0),'%') as st_sell_through_rate 
        FROM order_distribution left join suppliers on or_supplier=sp_id 
        WHERE $_SF
        order by $order_field 
        limit $start,$length";
//echo $query;
    $query_count = "select count(*) as c FROM order_distribution left join suppliers on or_supplier=sp_id WHERE $_SFC ";
    $query_count2 = "select count(*) as c FROM order_distribution left join suppliers on or_supplier=sp_id WHERE $_SF ";


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

if($action=="update_vat_type") {
    $id = $_POST["id"];
    $db->update("orders",["or_vat_type" => $_POST["data"]],["or_id" => $id]);

    header('Content-Type: application/json; charset=utf-8');
    http_response_code(200);
    echo json_encode('{ "success" : true }');
}
else {
    exit();
}

