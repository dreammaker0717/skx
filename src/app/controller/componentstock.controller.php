<?php
include(PATH_CONFIG."/constants.php");

$db = M::db();

if($action == "print-box-labels") {

    print("asd");
    exit();
}
if($action == "getstockbyid") {
    $data = $_POST["data"];           
    $ast_id = $data["ast_id"];
    
    header('Content-Type: application/json; charset=utf-8');
    $rs = $db->exec("select * from  dco_stock join dell_part on dell_part.dp_id = dco_stock.dst_product where dst_id=:dst_id",[ ":dst_id"=> [$ast_id,PDO::PARAM_STR]  ])->fetchAll();
    $output= array(
        "success"=>true,
        "data" => $rs
    );
    http_response_code(200);
    echo json_encode(utf8ize($output));
    exit();


    
}

if($action == "listed") {
    try {
        $st_id = intval($part);           
        $tv = $_POST["data"]["adv"];
        if($tv==='') $tv="NULL";
        if($tv!=0 && $tv!=1 && $tv!=2) {
            $tv="NULL";
        }


        $db->exec("UPDATE dell_part SET dp_listed=".$tv."  WHERE dp_id=".$st_id);
        header('Content-Type: application/json; charset=utf-8');
        http_response_code(200);
        echo json_encode('{ "success" : true }');
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode('{ "success" : false, "error": "'. $e->getMessage().'" }');
    }
}

if($action == "getstock") {
    $data = $_POST["data"];           
    $serial = $data["serial"];
    
    header('Content-Type: application/json; charset=utf-8');
    $rs = $db->exec("select dst_id, dst_status from dco_stock where dst_servicetag=:serial",[ ":serial"=> [$serial,PDO::PARAM_STR]  ])->fetchAll();
    $output= array(
        "success"=>true,
        "data" => $rs
    );
    http_response_code(200);
    echo json_encode(utf8ize($output));
    exit();


}
if($action=="updatestockbyid"){
    $data = $_POST["data"];           
    $serial = $data["serial"];
    $ast_id = $data["ast_id"];

    header('Content-Type: application/json; charset=utf-8');
    $db->exec("update dco_stock set dst_servicetag=:serial where dst_id=:ast_id",[ ":serial"=> [$serial,PDO::PARAM_STR]  , ":ast_id"=> [$ast_id,PDO::PARAM_STR] ]);
    $output= array(
            "success"=>true 
    );
    http_response_code(200);
    echo json_encode(utf8ize($output));
    exit();


}
if($action=="updateapr"){
    $data = $_POST["data"];           
    $serial = $data["serial"];
    $apr = $data["apr"];

    header('Content-Type: application/json; charset=utf-8');
    $db->exec("update dco_stock set dst_product=:apr where dst_servicetag=:serial",[ ":serial"=> [$serial,PDO::PARAM_STR]  , ":apr"=> [$apr,PDO::PARAM_STR] ]);
    $output= array(
            "success"=>true 
    );
    http_response_code(200);
    echo json_encode(utf8ize($output));
    exit();


}

if($action == "loadmodels") {
    $data = $_POST["data"];           
    $serial = $data["serial"];
    $apr_id= intval($data["apr_id"]);

    $pn = substr($serial, 3,5);

    $d = 
        $db->exec("select dp_id, dp_sku, dp_name, dp_condition from dell_part_map, dell_part where dpm_aproducts_id=dp_id AND ( dpm_pn=:apm_pn OR dp_id IN( select dpm_aproducts_id from dell_part_map where dpm_pn IN( select dpm_pn from dell_part_map where dpm_aproducts_id=:apr_id ) ) )",
        [":apm_pn"=>[$pn,PDO::PARAM_STR], ":apr_id" => [$apr_id, PDO::PARAM_INT] ])->fetchAll();
    //$d = $db->exec("select apr_id,apr_sku,apr_name,apr_condition from dell_part_map, dell_part where apm_pn=:apm_pn and apm_dell_part_id=apr_id ;",
    //[":apm_pn"=>[$pn,PDO::PARAM_STR] ])->fetchAll();

    header('Content-Type: application/json; charset=utf-8');
    $output= array(
            "success"=>true,
            "pn"=>$pn, 
            "data" => $d
    );
    http_response_code(200);
    echo json_encode(utf8ize($output));
    exit();
}


if($action=="moveback") {
    $data = $_POST["data"];           
    $serial = $data["serial"];
    
    header('Content-Type: application/json; charset=utf-8');
    try {
        $d = $db->exec("select * from dco_stock where dst_status = 16 AND dst_servicetag=:serial",[":serial"=>[$serial,PDO::PARAM_STR] ])->fetchAll();        
        if(count($d)==1){
            $db->exec("update dco_stock set dst_status=1, dst_lastcomment='Returned by Customer' where dst_servicetag=:serial",[ ":serial"=> [$serial,PDO::PARAM_STR] ]);        
            $output="{ \"success\":true, \"part\":\"".$part."\"}";
            http_response_code(200);
            echo json_encode(utf8ize($output));
        }
        else {
            http_response_code(500);
            echo json_encode('{ "success" : false, "error": "Serial not found or not in sold state!" }');    
        }
    }
    catch(Exception $e) {        
        http_response_code(500);
        echo json_encode('{ "success" : false, "error": "'. $e->getMessage().'" }');
    }
}
if($action=="move") {
    $data = $_POST["data"];           
    $serial = $data["serial"];
    $order = $data["order"];
    header('Content-Type: application/json; charset=utf-8');
    try {
        $d = $db->exec("select * from dco_stock where dst_servicetag=:serial",[":serial"=>[$serial,PDO::PARAM_STR] ])->fetchAll();

    //stock filters   
    if($part=="orange")
        $_SF = "1";
    elseif($part=="purple")
        $_SF = "2";
    elseif($part=="red")
        $_SF = "3";
    elseif($part=="lightblue")
        $_SF = "4";
    elseif($part=="darkblue")
        $_SF = "5";        
    elseif($part=="lightgreen")
        $_SF = "6";
    elseif($part=="green")
        $_SF = "22";    
    elseif($part=="darkgreen")
        $_SF = "7";    
    elseif($part=="black")
        $_SF = "8";    
    elseif($part=="stripped")
        $_SF = "24";   
    elseif($part=="gray")
        $_SF = "9";        
    elseif($part=="actioncmp")
        $_SF = "18";    
    elseif($part=="brown")
        $_SF = "11";    
    elseif($part=="sold")
        $_SF = "16";    
    elseif($part=="senttofba")
        $_SF = "29";    
    elseif($part=="usedinternal")
        $_SF = "30";  
    elseif($part=="rfs")
        $_SF = "44";    
    elseif($part=="rts")
        $_SF = "43";    

        $status=$_SF;
        if(count($d)==1){
            if($part=="sold")
                $db->exec("update dco_stock set dst_status=:status, dst_lastcomment=:lc where dst_servicetag=:serial",[ ":lc"=> ["Sold to Order ".$order,PDO::PARAM_STR], ":serial"=> [$serial,PDO::PARAM_STR]  , ":status"=> [$status,PDO::PARAM_STR] ]);
            else 
                $db->exec("update dco_stock set dst_status=:status where dst_servicetag=:serial",[ ":serial"=> [$serial,PDO::PARAM_STR]  , ":status"=> [$status,PDO::PARAM_STR] ]);
        
            $output="{ \"success\":true, \"part\":\"".$part."\"}";
            http_response_code(200);
            echo json_encode(utf8ize($output));
        }
        else {
            $output="{ \"success\":true, \"error\":\"serial not found!\" }";
            http_response_code(500);
            echo json_encode(utf8ize($output));
        }
    }
    catch(Exception $e) {        
        http_response_code(500);
        echo json_encode('{ "success" : false, "error": "'. $e->getMessage().'" }');
    }

}
if($action=="counts") {
    try {
        $data = $db->query(
            "select dst_status,count(dst_id) c from dco_stock  group by dst_status")->fetchAll();
        $status = $_STATUSES;
                  
        $output = array(
            "draw"	=>	1,			
            "iTotalRecords"	=> 	count($data),
            "iTotalDisplayRecords"	=>  count($data),
            "data"	=> 	$data,
            "status" =>  $status,
            "t" => "r0"
        );

        echo json_encode(utf8ize($output));
    }
    catch(Exception $e) {        
        http_response_code(500);
        echo json_encode('{ "success" : false, "error": "'. $e->getMessage().'" }');
    }
}
if($action=="insert") {
    try {
        //$data = $_POST["data"];    
        //unset($data["id"]);
        //$rp = $db->insert($part,$data);    
        header('Content-Type: application/json; charset=utf-8');
        http_response_code(200);
        echo json_encode('{ "success" : true }');
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode('{ "success" : false, "error": "'. $e->getMessage().'" }');
    }
}
if($action=="update") {
    try 
    {
        if(isset($_POST["data"])) {
            $data = $_POST["data"];             
            foreach($data as $key=>$value)
            {                
                $st_id = intval(str_replace("s","",$key));                
                $cm = "";
                $st = "";
                foreach($value as $keya=>$vala)
                {                        
                    if($keya == "cm") {
                        $db->exec("update dco_stock set dst_lastcomment=:comment where dst_id=:id",[":comment"=> [$vala, PDO::PARAM_STR], ":id"=>[intval($st_id),PDO::PARAM_INT ]]);
                        $cm = $vala;
                    }
                    if($keya == "st") {
                        if(intval($vala) > 0 ) {

                            if($vala == "24")
                                $db->exec("update dco_stock set dst_strippeddate=CURRENT_TIMESTAMP, dst_stdtus=:status where dst_id=:id",[":status"=> [intval($vala), PDO::PARAM_INT], ":id"=>[intval($st_id), PDO::PARAM_INT] ]);
                            if($vala == "9") 
                                $db->exec("update dco_stock set dst_despatcheddate=CURRENT_TIMESTAMP,  dst_status=:status where dst_id=:id",[":status"=> [intval($vala), PDO::PARAM_INT], ":id"=>[intval($st_id), PDO::PARAM_INT] ]);
                            else 
                                $db->exec("update dco_stock set dst_status=:status where dst_id=:id",[":status"=> [intval($vala), PDO::PARAM_INT], ":id"=>[intval($st_id), PDO::PARAM_INT] ]);
                            $st = $vala;
                        }
                    }               
                }
                $db->exec("INSERT INTO dco_stock_history SET dsh_date=NOW(), dsh_user=".$_SESSION["user_id"].", dsh_status=:st, dsh_comment=:cm, dsh_stock=:id",
                                [":st"=> [intval($st), PDO::PARAM_INT], ":cm"=> [$cm, PDO::PARAM_STR],  ":id"=>[intval($st_id), PDO::PARAM_INT] ] );
            }    
        }
        header('Content-Type: application/json; charset=utf-8');
        http_response_code(200);
        echo json_encode('{ "success" : true }');
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode('{ "success" : false, "error": "'. $e->getMessage().'" }');
    }

}
if($action=="comment") {
    try 
    {
        if(isset($_POST["data"])) {
            $data = $_POST["data"]["cm"];                         
            if($data == null || $data == "") throw new Exception("Please fill comment field.");            
            $st_id = intval($part);            
          
            
            $db->exec("update dco_stock set dst_lastcomment=:comment where dst_id=:id",[":comment"=> [$data, PDO::PARAM_STR], ":id"=>[intval($st_id),PDO::PARAM_INT ]]);            
            $db->exec("INSERT INTO dco_stock_history SET dsh_date=NOW(), dsh_user=".$_SESSION["user_id"].", dsh_status=(select dst_status from dco_stock where dst_id=$st_id), dsh_comment=:cm, dsh_stock=:id",
                            [":cm"=> [$data, PDO::PARAM_STR],  ":id"=>[intval($st_id), PDO::PARAM_INT] ] );
        
        }
        header('Content-Type: application/json; charset=utf-8');
        http_response_code(200);
        echo json_encode('{ "success" : true }');
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode('{ "success" : false, "error": "'. $e->getMessage().'" }');
    }
}
if($action=="delete") {
    try {        
        //$rp = $db->delete($part,[$key => $_POST["id"] ]);    
        header('Content-Type: application/json; charset=utf-8');
        http_response_code(200);
        echo json_encode('{ "success" : true }');
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode('{ "success" : false, "error": "'. $e->getMessage().'" }');
    }
}

if($action=="faulty_acc_report") {   
    $sv = $_POST["search"]["value"];
    $start = intval($_POST["start"]);
    $length = intval($_POST["length"]);
    if($length==-1) $length="100000";
    $order_field = "dst_date desc";    
   
    if(!empty($_POST["order"])) {
        $order_field =  $_POST['columns'][$_POST['order']['0']['column']]['data'] ." ". strtoupper( $_POST['order']['0']['dir']  );
    }
    $numRows = 0;
    $numRowsTotal = 0;
    $_SF = " dst_status IN(2,3) ";
              


    $_SFC= $_SF;        
    $_PARA=[];
    $SEARCH = $_POST["search"]["value"];
             
    if(!empty($SEARCH)) {
        
        $_SF.= " AND (dst_id = :search_no or dst_servicetag like :search or dell_part.dp_sku like :search or dell_part.dp_name like :search )";
        $_PARA[":search"]   = "%".$SEARCH."%";
        $_PARA[":search_no"]   = $SEARCH;
        
    }
    $query="select dst_id,dell_part.dp_name,dst_order,dell_part.dp_condition,dst_lastcomment,dst_date,dst_status,dp_sku,
        dst_record,dell_part.dp_box_label,dell_part.dp_box_subtitle,dell_part.dp_image,dst_state,dst_servicetag, dp_id, dco_orders.dor_date, dco_orders.dor_reference, suppliers.sp_name 
    from dco_stock left join dell_part on dell_part.dp_id=dst_product  left join dco_orders on dco_orders.dor_id = dst_order  
    left join suppliers on suppliers.sp_id = dco_orders.dor_supplier 
    where  $_SF            
    order by $order_field
    limit $start,$length";

    $query_count = "select count(*) as c from dco_stock left join dell_part on dell_part.dp_id=dst_product where $_SFC ";
    $query_count2 = "select count(*) as c from dco_stock left join dell_part on dell_part.dp_id=dst_product where $_SF ";

    $data = $db->query($query,$_PARA)->fetchAll();
    $numRowsTotal = $db->query($query_count2,$_PARA)->fetchAll();
    $numRows = $db->query($query_count)->fetchAll();
    $output = array(
		"draw"	=>	intval($_POST["draw"]),			
		"iTotalRecords"	=> 	$numRows[0]["c"],
		"iTotalDisplayRecords"	=>  $numRowsTotal[0]["c"]??0,
		"data"	=> 	$data,        
        "t" => "r0"
	);
    echo json_encode(utf8ize($output));
}

if($action=="search") {
	
    $sv = $_POST["search"]["value"];
    $start = intval($_POST["start"]);
    
    $length = intval($_POST["length"]);
    if($length==-1) $length="100000";

    $order_field = "dst_date desc";
    
  

    if(!empty($_POST["order"])) {
        $order_field =  $_POST['columns'][$_POST['order']['0']['column']]['data'] ." ". strtoupper( $_POST['order']['0']['dir']  );
    }

    $numRows = 0;
    $numRowsTotal = 0;
    
    //stock filters   
    $_SF = "";
    if($part=="orange")
        $_SF = "dst_status=1";
    elseif($part=="purple")
        $_SF = "dst_status=2";
    elseif($part=="red")
        $_SF = "dst_status=3";
    elseif($part=="lightblue")
        $_SF = "dst_status=4";
    elseif($part=="darkblue")
        $_SF = "dst_status=5";        
    elseif($part=="lightgreen")
        $_SF = "dst_status=6";
    elseif($part=="green")
        $_SF = "dst_status=22";    
    elseif($part=="darkgreen")
        $_SF = "dst_status=7";    
    elseif($part=="black")
        $_SF = "dst_status=8";    
    elseif($part=="stripped")
        $_SF = "dst_status=24";   
    elseif($part=="gray")
        $_SF = "dst_status=9";    
    elseif($part=="action")
        $_SF = "dst_status=17";    
    elseif($part=="actioncmp")
        $_SF = "dst_status=18";    
    elseif($part=="brown")
        $_SF = "dst_status=11";    
    elseif($part=="sold")
        $_SF = "dst_status=16";    
    elseif($part=="search") 
        $_SF = "dst_id>0";
    elseif($part=="senttofba")
        $_SF = "dst_status=29";     
    elseif($part=="usedinternal")
        $_SF = "dst_status=30";     
    elseif($part=="rts")
        $_SF = "dst_status=43";       
    elseif($part=="rfs")
        $_SF = "dst_status=44";       
   
        
    $_SFC= $_SF;        
    $_PARA=[];
    $SEARCH = $_POST["search"]["value"];
    if( isset($_POST["BATCHED"]) && $_POST["BATCHED"]=="1") {

        if(!empty($SEARCH)) {
        
            $_SF.= " AND (dell_part.dp_name like :search  or dell_part.dp_sku like :search)";
            $_PARA[":search"]   = "%".$SEARCH."%";            
            
        }
        $query="select dell_part.dp_name,dell_part.dp_condition,dst_status,dp_sku, dell_part.dp_listed, dp_id , count(dco_stock.dst_id) as counter, dell_part.dp_magqty 
        from dco_stock left join dell_part on dell_part.dp_id=dst_product
        where $_SF 
        group by dell_part.dp_name, dell_part.dp_condition, dst_status, dp_sku, dp_id order by $order_field limit $start,$length";

        $query_count = "select sum(xc) as c from (select count(*) as xc from dco_stock left join dell_part on dell_part.dp_id=dst_product where $_SFC group by dell_part.dp_name,dell_part.dp_condition,dst_status,dp_sku, dp_id) as p";
        $query_count2 = "select sum(xc) as c from (select count(*) as xc from dco_stock left join dell_part on dell_part.dp_id=dst_product  where $_SF group by dell_part.dp_name,dell_part.dp_condition,dst_status,dp_sku, dp_id) as p";
    }
    else 
    {            
        if(!empty($SEARCH)) {
            
            $_SF.= " AND (dst_id = :search_no or dst_servicetag like :search or dell_part.dp_sku like :search or dell_part.dp_name like :search )";
            $_PARA[":search"]   = "%".$SEARCH."%";
            $_PARA[":search_no"]   = $SEARCH;
            
        }
        $query="select dst_id,dell_part.dp_name,dst_order,dell_part.dp_condition,dst_lastcomment,dst_date,dst_status,dp_sku,
            dst_record,dell_part.dp_box_label,dell_part.dp_box_subtitle,dell_part.dp_image,dst_state,dst_servicetag, dp_id,aco.dor_date, sup.sp_name, aco.dor_reference
        from dco_stock left join dell_part on dell_part.dp_id=dst_product  
        left join dco_orders aco on aco.dor_id = dco_stock.dst_order left join suppliers sup on sup.sp_id = aco.dor_supplier
        where  $_SF            
        order by $order_field
        limit $start,$length";

        $query_count = "select count(*) as c from dco_stock left join dell_part on dell_part.dp_id=dst_product where $_SFC ";
        $query_count2 = "select count(*) as c from dco_stock left join dell_part on dell_part.dp_id=dst_product where $_SF ";
    }

    $data = $db->query($query,$_PARA)->fetchAll();
    $numRowsTotal = $db->query($query_count2,$_PARA)->fetchAll();
    $numRows = $db->query($query_count)->fetchAll();
    $output = array(
		"draw"	=>	intval($_POST["draw"]),			
		"iTotalRecords"	=> 	$numRows[0]["c"],
		"iTotalDisplayRecords"	=>  $numRowsTotal[0]["c"]??0,
		"data"	=> 	$data,        
        "t" => "r0"
	);
    echo json_encode(utf8ize($output));

}
else {
    exit();
}



function utf8ize($d) {
    if (is_array($d)) {
        foreach ($d as $k => $v) {
            $d[$k] = utf8ize($v);
        }
    } else if (is_string ($d)) {
        return utf8_encode($d);
    }
    return $d;
}