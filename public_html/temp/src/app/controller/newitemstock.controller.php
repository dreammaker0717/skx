<?php
include(PATH_CONFIG."/constants.php");

$db = M::db();

if($action == "print-box-labels") {

    print("asd");
    exit();
}
if($action == "getstockbyid") {
    $data = $_POST["data"];           
    $nst_id = $data["nst_id"];
    
    header('Content-Type: application/json; charset=utf-8');
    $rs = $db->exec("select * from  nwp_stock join nwp_products on nwp_products.npr_id = nwp_stock.nst_product where nst_id=:nst_id",[ ":nst_id"=> [$nst_id,PDO::PARAM_STR]  ])->fetchAll();
    $output= array(
        "success"=>true,
        "data" => $rs
    );
    http_response_code(200);
    echo json_encode(utf8ize($output));
    exit();


}
if($action == "getstock") {
    $data = $_POST["data"];           
    $serial = $data["serial"];
    
    header('Content-Type: application/json; charset=utf-8');
    $rs = $db->exec("select nst_id,nst_status from nwp_stock where nst_servicetag=:serial",[ ":serial"=> [$serial,PDO::PARAM_STR]  ])->fetchAll();
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
    $nst_id = $data["nst_id"];

    header('Content-Type: application/json; charset=utf-8');
    $db->exec("update nwp_stock set nst_servicetag=:serial where nst_id=:nst_id",[ ":serial"=> [$serial,PDO::PARAM_STR]  , ":nst_id"=> [$nst_id,PDO::PARAM_STR] ]);
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
    $db->exec("update nwp_stock set nst_product=:apr where nst_servicetag=:serial",[ ":serial"=> [$serial,PDO::PARAM_STR]  , ":apr"=> [$apr,PDO::PARAM_STR] ]);
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
        $db->exec("select npr_id,npr_sku, npr_name, npr_condition from nwp_products_map, nwp_products where npm_aproducts_id=npr_id AND ( npm_pn=:apm_pn OR npr_id IN( select npm_aproducts_id from nwp_products_map where npm_pn IN( select npm_pn from nwp_products_map where npm_aproducts_id=:apr_id ) ) )",
        [":apm_pn"=>[$pn,PDO::PARAM_STR], ":apr_id" => [$apr_id, PDO::PARAM_INT] ])->fetchAll();
    //$d = $db->exec("select apr_id,apr_sku,apr_name,apr_condition from aproducts_map, aproducts where apm_pn=:apm_pn and apm_aproducts_id=apr_id ;",
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
        $d = $db->exec("select * from nwp_stock where nst_status = 16 AND nst_servicetag=:serial",[":serial"=>[$serial,PDO::PARAM_STR] ])->fetchAll();        
        if(count($d)==1){
            $db->exec("update nwp_stock set nst_status=1, nst_lastcomment='Returned by Customer' where nst_servicetag=:serial",[ ":serial"=> [$serial,PDO::PARAM_STR] ]);        
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
        $d = $db->exec("select * from nwp_stock where nst_servicetag=:serial",[":serial"=>[$serial,PDO::PARAM_STR] ])->fetchAll();

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
    


        $status=$_SF;
        if(count($d)==1){
            if($part=="sold")
                $db->exec("update nwp_stock set nst_status=:status, nst_lastcomment=:lc where nst_servicetag=:serial",[ ":lc"=> ["Sold to Order ".$order,PDO::PARAM_STR], ":serial"=> [$serial,PDO::PARAM_STR]  , ":status"=> [$status,PDO::PARAM_STR] ]);
            else 
                $db->exec("update nwp_stock set nst_status=:status where nst_servicetag=:serial",[ ":serial"=> [$serial,PDO::PARAM_STR]  , ":status"=> [$status,PDO::PARAM_STR] ]);
        
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
            "select nst_status,count(nst_id) c from nwp_stock  group by nst_status")->fetchAll();
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
                        $db->exec("update nwp_stock set nst_lastcomment=:comment where nst_id=:id",[":comment"=> [$vala, PDO::PARAM_STR], ":id"=>[intval($st_id),PDO::PARAM_INT ]]);
                        $cm = $vala;
                    }
                    if($keya == "st") {
                        if(intval($vala) > 0 ) {

                            if($vala == "24")
                                $db->exec("update nwp_stock set nst_strippeddate=CURRENT_TIMESTAMP, nst_status=:status where nst_id=:id",[":status"=> [intval($vala), PDO::PARAM_INT], ":id"=>[intval($st_id), PDO::PARAM_INT] ]);
                            if($vala == "9") 
                                $db->exec("update nwp_stock set nst_despatcheddate=CURRENT_TIMESTAMP, nst_status=:status where nst_id=:id",[":status"=> [intval($vala), PDO::PARAM_INT], ":id"=>[intval($st_id), PDO::PARAM_INT] ]);
                            else 
                                $db->exec("update nwp_stock set nst_status=:status where nst_id=:id",[":status"=> [intval($vala), PDO::PARAM_INT], ":id"=>[intval($st_id), PDO::PARAM_INT] ]);
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
          
            
            $db->exec("update nwp_stock set nst_lastcomment=:comment where nst_id=:id",[":comment"=> [$data, PDO::PARAM_STR], ":id"=>[intval($st_id),PDO::PARAM_INT ]]);            
            $db->exec("INSERT INTO nwp_stock_history SET nsh_date=NOW(), nsh_user=".$_SESSION["user_id"].", nsh_status=(select nst_status from nwp_stock where nst_id=$st_id), nsh_comment=:cm, nsh_stock=:id",
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


if($action == "listed") {
    try {
        $st_id = intval($part);      
        $tv = $_POST["data"]["adv"];
        if($tv==='') $tv="NULL";
        if($tv!=0 && $tv!=1 && $tv!=2) {
            $tv="NULL";
        }            
        $db->exec("UPDATE nwp_products SET npr_listed=".$tv."  WHERE npr_id=".$st_id);
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

    $order_field = "nst_date desc";
    
    $cat = intval($_POST["category"]);

    if(!empty($_POST["order"])) {
        $order_field =  $_POST['columns'][$_POST['order']['0']['column']]['data'] ." ". strtoupper( $_POST['order']['0']['dir']  );
    }

   /// var_dump($order_field);
   
    $numRows = 0;
    $numRowsTotal = 0;
    
 

    //stock filters   
    if($part=="orange")
        $_SF = "nst_status=1";
    elseif($part=="purple")
        $_SF = "nst_status=2";
    elseif($part=="red")
        $_SF = "nst_status=3";
    elseif($part=="lightblue")
        $_SF = "nst_status=4";
    elseif($part=="darkblue")
        $_SF = "nst_status=5";        
    elseif($part=="lightgreen")
        $_SF = "nst_status=6";
    elseif($part=="green")
        $_SF = "nst_status=22";    
    elseif($part=="darkgreen")
        $_SF = "nst_status=7";    
    elseif($part=="black")
        $_SF = "nst_status=8";    
    elseif($part=="stripped")
        $_SF = "nst_status=24";   
    elseif($part=="gray")
        $_SF = "nst_status=9";    
    elseif($part=="action")
        $_SF = "(nst_status=17)";    
    elseif($part=="actioncmp")
        $_SF = "nst_status=18";    
    elseif($part=="brown")
        $_SF = "nst_status=11";    
    elseif($part=="sold")
        $_SF = "nst_status=16";    
    elseif($part=="search") 
        $_SF = "ast_id>0";


        
    
    if($cat>0) {
        $_SF .= " AND npr_category=".$cat." ";
    }

    $_SFC= $_SF;
           
    $_PARA=[];
    
    $SEARCH = $_POST["search"]["value"];




    if( isset($_POST["BATCHED"]) && $_POST["BATCHED"]=="1") {

        if(!empty($SEARCH)) {
        
            $_SF.= " AND (nwp_products.npr_name like :search or nwp_products.npr_sku like :search )";
            $_PARA[":search"]   = "%".$SEARCH."%";            
            
        }


        $query="select nwp_products.npr_name,nwp_products.npr_condition,nst_status,npr_sku,npr_listed,
        ct_name, npr_id , count(nwp_stock.nst_id) as counter
        from nwp_stock left join nwp_products on nwp_products.npr_id=nst_product  left join categories on ct_id = nwp_products.npr_category    
        where $_SF 
        group by nwp_products.npr_name, nwp_products.npr_condition,nst_status,npr_sku,ct_name, npr_id order by $order_field limit $start,$length";

        $query_count  = "select sum(xc) as c from (select count(*) as xc from nwp_stock left join nwp_products on nwp_products.npr_id=nst_product left join categories on ct_id = nwp_products.npr_category where $_SFC group by nwp_products.npr_name,nwp_products.npr_condition,nst_status,npr_sku,ct_name, npr_id) as p";
        $query_count2 = "select sum(xc) as c from (select count(*) as xc from nwp_stock left join nwp_products on nwp_products.npr_id=nst_product left join categories on ct_id = nwp_products.npr_category where $_SF  group by nwp_products.npr_name,nwp_products.npr_condition,nst_status,npr_sku,ct_name, npr_id) as p";

    }
    else 
    {


        if(!empty($SEARCH)) {
            
            $_SF.= " AND (nst_id = :search_no or nst_servicetag like :search or  nwp_products.npr_sku like :search or nwp_products.npr_name like :search )";
            $_PARA[":search"]   = "%".$SEARCH."%";
            $_PARA[":search_no"]   = $SEARCH;
            
        }
        

        $query="select nst_id,nwp_products.npr_name,nst_order,nwp_products.npr_condition,nst_lastcomment,nst_date,nst_status,npr_sku,
                nst_record,nwp_products.npr_box_label,nwp_products.npr_box_subtitle,nwp_products.npr_image,nst_state,nst_servicetag,ct_name, npr_id ,ct_id,nco.nor_date,sup.sp_name, nco.nor_reference
            from nwp_stock left join nwp_products on nwp_products.npr_id=nst_product left join categories on ct_id = nwp_products.npr_category
left join nwp_orders nco on nco.nor_id = nwp_stock.nst_order 
left join suppliers sup on sup.sp_id = nco.nor_supplier
            where  $_SF            
            order by $order_field
            limit $start,$length";

        $query_count = "select count(*) as c from nwp_stock left join nwp_products on nwp_products.npr_id=nst_product where $_SFC ";
        $query_count2 = "select count(*) as c from nwp_stock left join nwp_products on nwp_products.npr_id=nst_product where $_SF ";

    }


   //echo $db->debug()->query($query_count2,$_PARA);

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