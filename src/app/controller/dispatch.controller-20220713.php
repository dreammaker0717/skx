<?php
include(PATH_CONFIG."/constants.php");

$db = M::db();

if($action == "print-box-labels") {

    print("asd");
    exit();
}

if($action=="updateapr"){
    $data = $_POST["data"];           
    $serial = $data["serial"];
    $apr = $data["apr"];

    header('Content-Type: application/json; charset=utf-8');
    $db->exec("update acc_stock set ast_product=:apr where ast_servicetag=:serial",[ ":serial"=> [$serial,PDO::PARAM_STR]  , ":apr"=> [$apr,PDO::PARAM_STR] ]);
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

    $pn = substr($serial, 3,5);

    $d = $db->exec("select apr_id,apr_sku,apr_name,apr_condition from aproducts_map, aproducts where apm_pn=:apm_pn and apm_aproducts_id=apr_id ;",
    [":apm_pn"=>[$pn,PDO::PARAM_STR] ])->fetchAll();

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
        $d = $db->exec("select * from acc_stock where ast_status = 16 AND ast_servicetag=:serial",[":serial"=>[$serial,PDO::PARAM_STR] ])->fetchAll();        
        if(count($d)==1){
            $db->exec("update acc_stock set ast_status=1, ast_lastcomment='Returned by Customer' where ast_servicetag=:serial",[ ":serial"=> [$serial,PDO::PARAM_STR] ]);        
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



        $tb = "acc_stock";
        $p = "a";

        $d = $db->exec("select * from acc_stock where ast_servicetag=:serial",[":serial"=>[$serial,PDO::PARAM_STR] ])->fetchAll();
    
        if(count($d) == 0 ) {
            $d = $db->exec("select * from nwp_stock where nst_servicetag=:serial",[":serial"=>[$serial,PDO::PARAM_STR] ])->fetchAll();

            if(count($d) == 1 ){
                $tb = "nwp_stock";
                $p="n";
            }
        }


        $status=$_SF;
        if(count($d)==1){
            if($part=="sold")
                $db->exec("update ".$tb." set ".$p."st_status=:status, ".$p."st_lastcomment=:lc where ".$p."st_servicetag=:serial",[ ":lc"=> ["Sold to Order ".$order,PDO::PARAM_STR], ":serial"=> [$serial,PDO::PARAM_STR]  , ":status"=> [$status,PDO::PARAM_STR] ]);
            else 
                $db->exec("update ".$tb." set ".$p."st_status=:status where ".$p."st_servicetag=:serial",[ ":serial"=> [$serial,PDO::PARAM_STR]  , ":status"=> [$status,PDO::PARAM_STR] ]);
        
            $output="{ \"success\":true, \"part\":\"".$part."\"}";
            http_response_code(200);
            echo json_encode(utf8ize($output));
        }
        else {
            $output="{ \"success\":false, \"error\":\"Serial Number Not Found!\" }";
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
            "select ast_status,count(ast_id) c from acc_stock  group by ast_status")->fetchAll();
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
                        $db->exec("update acc_stock set ast_lastcomment=:comment where ast_id=:id",[":comment"=> [$vala, PDO::PARAM_STR], ":id"=>[intval($st_id),PDO::PARAM_INT ]]);
                        $cm = $vala;
                    }
                    if($keya == "st") {
                        if(intval($vala) > 0 ) {

                            if($vala == "24")
                                $db->exec("update acc_stock set ast_strippeddate=CURRENT_TIMESTAMP, ast_status=:status where ast_id=:id",[":status"=> [intval($vala), PDO::PARAM_INT], ":id"=>[intval($st_id), PDO::PARAM_INT] ]);
                            if($vala == "9") 
                                $db->exec("update acc_stock set ast_despatcheddate=CURRENT_TIMESTAMP,  ast_status=:status where ast_id=:id",[":status"=> [intval($vala), PDO::PARAM_INT], ":id"=>[intval($st_id), PDO::PARAM_INT] ]);
                            else 
                                $db->exec("update acc_stock set ast_status=:status where ast_id=:id",[":status"=> [intval($vala), PDO::PARAM_INT], ":id"=>[intval($st_id), PDO::PARAM_INT] ]);
                            $st = $vala;
                        }
                    }               
                }
                $db->exec("INSERT INTO acc_stock_history SET ash_date=NOW(), ash_user=".$_SESSION["user_id"].", ash_status=:st, ash_comment=:cm, ash_stock=:id",
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
          
            
            $db->exec("update acc_stock set ast_lastcomment=:comment where ast_id=:id",[":comment"=> [$data, PDO::PARAM_STR], ":id"=>[intval($st_id),PDO::PARAM_INT ]]);            
            $db->exec("INSERT INTO acc_stock_history SET ash_date=NOW(), ash_user=".$_SESSION["user_id"].", ash_status=(select ast_status from acc_stock where ast_id=$st_id), ash_comment=:cm, ash_stock=:id",
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
if($action=="search") {
	
    $sv = $_POST["search"]["value"];
    $start = intval($_POST["start"]);
    $length = intval($_POST["length"]);

    $order_field = "ast_date desc";
    
    $cat = intval($_POST["category"]);

    if(!empty($_POST["order"])) {
        $order_field =  $_POST['columns'][$_POST['order']['0']['column']]['data'] ." ". strtoupper( $_POST['order']['0']['dir']  );
    }

   /// var_dump($order_field);
   
    $numRows = 0;
    $numRowsTotal = 0;
    
 

    //stock filters   
    if($part=="orange")
        $_SF = "ast_status=1";
    elseif($part=="purple")
        $_SF = "ast_status=2";
    elseif($part=="red")
        $_SF = "ast_status=3";
    elseif($part=="lightblue")
        $_SF = "ast_status=4";
    elseif($part=="darkblue")
        $_SF = "ast_status=5";        
    elseif($part=="lightgreen")
        $_SF = "ast_status=6";
    elseif($part=="green")
        $_SF = "ast_status=22";    
    elseif($part=="darkgreen")
        $_SF = "ast_status=7";    
    elseif($part=="black")
        $_SF = "ast_status=8";    
    elseif($part=="stripped")
        $_SF = "ast_status=24";   
    elseif($part=="gray")
        $_SF = "ast_status=9";    
    elseif($part=="action")
        $_SF = "(ast_status=17)";    
    elseif($part=="actioncmp")
        $_SF = "ast_status=18";    
    elseif($part=="brown")
        $_SF = "ast_status=11";    
    elseif($part=="sold")
        $_SF = "ast_status=16";    
    elseif($part=="search") 
        $_SF = "ast_id>0";


        
    
    if($cat>0) {
        $_SF .= " AND apr_category=".$cat." ";
    }

    $_SFC= $_SF;
           
    $_PARA=[];

    $SEARCH = $_POST["search"]["value"];
    if(!empty($SEARCH)) {
        
        $_SF.= " AND (ast_id = :search_no or ast_servicetag like :search or aproducts.apr_name like :search )";
        $_PARA[":search"]   = "%".$SEARCH."%";
        $_PARA[":search_no"]   = $SEARCH;
        
    }
    

    $query="select ast_id,aproducts.apr_name,ast_order,aproducts.apr_condition,ast_lastcomment,ast_date,ast_status,apr_sku,
            ast_record,aproducts.apr_box_label,aproducts.apr_box_subtitle,aproducts.apr_image,ast_state,ast_servicetag,ct_name,ct_id
        from acc_stock left join aproducts on aproducts.apr_id=ast_product  left join categories on ct_id = aproducts.apr_category
        where  $_SF            
        order by $order_field
        limit $start,$length";

    $query_count = "select count(*) as c from acc_stock left join aproducts on aproducts.apr_id=ast_product where $_SFC ";
    $query_count2 = "select count(*) as c from acc_stock left join aproducts on aproducts.apr_id=ast_product where $_SF ";

   //echo  $query;

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