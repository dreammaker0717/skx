<?php
include(PATH_CONFIG."/constants.php");

$db = M::db();

if($action == "print-box-labels") {

    print("asd");
    exit();
}


if($action =="getinfo"){

    $data = $_POST["data"];
    $serial = $data["serial"];

    $d = $db->exec("
    select 'Accessories' as tbl, s.ast_servicetag, a.apr_id, a.apr_sku, a.apr_name, o.aor_supplier,  e.sp_name,s.ast_order , s.ast_solddate,s.ast_lastcomment  from acc_stock s 
    left join aproducts a on a.apr_id = s.ast_product 
    left join acc_orders o on o.aor_id = s.ast_order
    left join suppliers e on e.sp_id = o.aor_supplier
    where s.ast_servicetag=:serial
    union all 
    select 'New Item' as tbl, s.nst_servicetag, a.npr_id, a.npr_sku, a.npr_name, o.nor_supplier,  e.sp_name ,s.nst_order, s.nst_solddate,s.nst_lastcomment from nwp_stock s 
    left join nwp_products a on a.npr_id = s.nst_product 
    left join nwp_orders o on o.nor_id = s.nst_order
    left join suppliers e on e.sp_id = o.nor_supplier
    where s.nst_servicetag=:serial
    union all 
    select 'Dell Comp' as tbl, s.dst_servicetag, a.dp_id, a.dp_sku, a.dp_name, o.dor_supplier,  e.sp_name ,s.dst_order, s.dst_solddate,s.dst_lastcomment from dco_stock s 
    left join dell_part a on a.dp_id = s.dst_product 
    left join dco_orders o on o.dor_id = s.dst_order
    left join suppliers e on e.sp_id = o.dor_supplier
    where s.dst_servicetag=:serial
    ",[":serial"=>[$serial,PDO::PARAM_STR] ])->fetchAll();

    header('Content-Type: application/json; charset=utf-8');
    $output= array(
            "success"=>true,
            "serial"=>$serial, 
            "data" => $d
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
 
if($action=="move") {
    $data = $_POST["data"];           
    $serial = $data["serial"];
    
    header('Content-Type: application/json; charset=utf-8');
    try {
        $d = $db->exec("select * from rmac_items where rmac_servicetag=:rmac_servicetag",[":rmac_servicetag"=>[$serial,PDO::PARAM_STR] ])->fetchAll();

    //stock filters   
    if($part=="orange")
        $_SF = "1";
    elseif($part=="purple")
        $_SF = "2";
    elseif($part=="red")
        $_SF = "3";
    elseif($part=="azure")
        $_SF = "4";
    elseif($part=="indigo")
        $_SF = "5";        
    elseif($part=="pink")
        $_SF = "6";
    elseif($part=="yellow")
        $_SF = "53";    
    elseif($part=="darkgreen")
        $_SF = "7";    
    elseif($part=="black")
        $_SF = "8";    
    elseif($part=="stripped")
        $_SF = "24";   
    elseif($part=="cyan")
        $_SF = "8";    
    elseif($part=="maroon")
        $_SF = "54";    
    elseif($part=="darkgray")
        $_SF = "55";    
    


        $status=$_SF;
        if(count($d)==1){
            if($part=="sold")
                $db->exec("update rmac_items set rmac_status=:status  where rmac_servicetag=:rmac_servicetag",[ ":rmac_servicetag"=> [$serial,PDO::PARAM_STR]  , ":status"=> [$status,PDO::PARAM_STR] ]);
            else 
                $db->exec("update rmac_items set rmac_status=:status where rmac_servicetag=:rmac_servicetag",[ ":rmac_servicetag"=> [$serial,PDO::PARAM_STR]  , ":status"=> [$status,PDO::PARAM_STR] ]);
        
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
            "select rmac_status,count(rmac_ID) c from rmac_items  group by rmac_status")->fetchAll();
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

if($action == "creatermacproduct" ) {
    try {
        $data = $_POST["data"];            
        $rp = $db->insert("rmac_products",$data);    
        header('Content-Type: application/json; charset=utf-8');        
        http_response_code(200);
        echo json_encode('{ "success" : true, "id" : '.$db->id().' }');
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode('{ "success" : false, "error": "'. $e->getMessage().'" }');
    }
}


if($action == "creatermapimages") {

    $rmac_id = intval($_POST["id"]);
    $db->exec("update rmac_items set rmac_images='' where rmac_ID=:id",[ ":id"=> [$rmac_id,PDO::PARAM_STR] ]); 
    if( !empty( $_FILES ) ) {

        foreach( $_FILES[ 'image' ][ 'tmp_name' ] as $index => $tmpName )
        {
            if( !empty( $_FILES[ 'image' ][ 'error' ][ $index ] ) )
            {                
                return false; // return false also immediately perhaps??
            }
            $tmpName = $_FILES[ 'image' ][ 'tmp_name' ][ $index ];                            
            if( !empty( $tmpName ) && is_uploaded_file( $tmpName ) )
            {
                $someDestinationPath = "uploads/".time()."_".$_FILES[ 'image' ][ 'name' ][ $index ];
                $db->exec("update rmac_items set rmac_images=CONCAT(rmac_images, '$someDestinationPath,') where rmac_ID=:id",[ ":id"=> [$rmac_id,PDO::PARAM_STR] ]); 
                move_uploaded_file( $tmpName, $someDestinationPath ); // move to new location perhaps?
            }
        }

    }

}

if($action == "creatermap") {

    try {
        $rp = $db->insert("rmac_items",
        [         
            "rmac_servicetag"=> $_POST["scan_move"],
            "rmac_sku" => "",
            "rmac_product" => $_POST["product-text"],
            "rmac_productID" => $_POST["product"],
            "rmac_sku" => $_POST["product-sku"],
            "rmac_productTable" =>  $_POST["product-table"],
            "rmac_price" => "",
            "rmac_purchasedon" => $_POST["purchasedon"],
            "rmac_fullname" => $_POST["fullname"],
            "rmac_purchasedate" => $_POST["purchasedate"],
            "rmac_ordermumber" => $_POST["orderno"],
            "rmac_fault" => $_POST["fault"],
            "rmac_isours" => isset($_POST["itemisours"]) && $_POST["itemisours"]=="on" ? true : false,
            "rmac_iscomplete" => isset($_POST["itemiscomplete"]) && $_POST["itemiscomplete"]=="on" ? true : false,
            "rmac_isundamaged" => isset($_POST["itemisundamaged"]) && $_POST["itemisundamaged"]=="on" ? true : false,
            "rmac_supplier" => $_POST["rmac_supplier"],
            "rmac_supplierdate" => "",
            "rmac_supplierordernumber" => "",
            "rmac_supplierref" => "",
            "rmac_lastcomment" => $_POST["fault"],
            "rmac_datecreated" => date("Y-m-d"),
            "rmac_status" => "1"
            
        ]);    

        $iid = $db->id();

        header('Content-Type: application/json; charset=utf-8');
        http_response_code(200);
        echo json_encode('{ "success" : true , "id": '.$iid.'}');
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
                        $db->exec("update rmac_items set rmac_lastcomment=:comment where rmac_ID=:id",[":comment"=> [$vala, PDO::PARAM_STR], ":id"=>[intval($st_id),PDO::PARAM_INT ]]);
                        $cm = $vala;
                    }
                    if($keya == "st") {
                        if(intval($vala) > 0 ) {

                            if($vala == "24")
                                $db->exec("update rmac_items set  rmac_status=:status where rmac_ID=:id",[":status"=> [intval($vala), PDO::PARAM_INT], ":id"=>[intval($st_id), PDO::PARAM_INT] ]);
                            if($vala == "9") 
                                $db->exec("update rmac_items set  rmac_status=:status where rmac_ID=:id",[":status"=> [intval($vala), PDO::PARAM_INT], ":id"=>[intval($st_id), PDO::PARAM_INT] ]);
                            else 
                                $db->exec("update rmac_items set rmac_status=:status where rmac_ID=:id",[":status"=> [intval($vala), PDO::PARAM_INT], ":id"=>[intval($st_id), PDO::PARAM_INT] ]);
                            $st = $vala;
                        }
                    }               
                }
                $db->exec("INSERT INTO rmac_items_history SET rmac_date=NOW(), rmac_user=".$_SESSION["user_id"].", rmac_status=:st, rmac_comment=:cm, rmac_ID=:id",
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
          
            
            $db->exec("update rmac_items set rmac_lastcomment=:comment where rmac_ID=:id",[":comment"=> [$data, PDO::PARAM_STR], ":id"=>[intval($st_id),PDO::PARAM_INT ]]);            
            $db->exec("INSERT INTO rmac_items_history SET rmac_date=NOW(), ash_user=".$_SESSION["user_id"].", rmac_status=(select rmac_status from rmac_items where rmac_id=$st_id), rmac_comment=:cm, rmac_ID=:id",
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

    $order_field = "rmac_datecreated desc";
    
    

    if(!empty($_POST["order"])) {
        $order_field =  $_POST['columns'][$_POST['order']['0']['column']]['data'] ." ". strtoupper( $_POST['order']['0']['dir']  );
    }

   /// var_dump($order_field);
   
    $numRows = 0;
    $numRowsTotal = 0;
    
 

    //stock filters   
    if($part=="orange")
        $_SF = "rmac_status=1";
    elseif($part=="purple")
        $_SF = "rmac_status=2";
    elseif($part=="yellow")
        $_SF = "rmac_status=3";
    elseif($part=="red")
        $_SF = "rmac_status=3";
    elseif($part=="azure")
        $_SF = "rmac_status=4";
    elseif($part=="indigo")
        $_SF = "rmac_status=5";        
    elseif($part=="pink")
        $_SF = "rmac_status=6";
    elseif($part=="darkgreen")
        $_SF = "rmac_status=7";    
    elseif($part=="cyan")
        $_SF = "rmac_status=8";    
    elseif($part=="maroon")
        $_SF = "rmac_status=54";    
    elseif($part=="darkgray")
        $_SF = "rmac_status=55";    
    elseif($part=="search") 
        $_SF = "rmac_id>0";


        

    $_SFC= $_SF;
           
    $_PARA=[];

    $SEARCH = $_POST["search"]["value"];
    if(!empty($SEARCH)) {
        
        $_SF.= " AND (ast_id = :search_no or ast_servicetag like :search or aproducts.apr_name like :search )";
        $_PARA[":search"]   = "%".$SEARCH."%";
        $_PARA[":search_no"]   = $SEARCH;
        
    }
    
    /*
aproducts.apr_name,
            aproducts.apr_condition,
    */
    /*left join aproducts on aproducts.apr_id=rmac_productID and rmac_productTable='Accessories'
            left join nwp_products on nwp_products.nwp_id=rmac_productID and rmac_productTable='New Item'
            left join dell_part on dell_part.dp_id=rmac_productID and rmac_productTable='Dell Part'*/

    $query="select rmac_ID,            
            rmac_ordermumber,            
            rmac_lastcomment,
            rmac_datecreated,
            rmac_status
            ,rmac_sku,
            rmac_servicetag,
            rmac_images
            ,sp_name, rmac_product
        from rmac_items             
        left join suppliers on suppliers.sp_id =rmac_supplier
        where  $_SF            
        order by $order_field
        limit $start,$length";

    $query_count = "select count(*) as c from rmac_items  where $_SFC ";
    $query_count2 = "select count(*) as c from rmac_items  where $_SF ";

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