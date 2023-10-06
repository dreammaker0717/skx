<?php

include(PATH_CONFIG."/constants.php");



$db = M::db();



if($action == "print-box-labels") {



    print("asd");

    exit();

}

if($action=="counts") {

    try {

        $data = $db->query(

            "select st_status,count(stock.st_id) c from stock, products,manufacturers,categories where  stock.st_product=products.pr_id AND mf_id=pr_manufacturer AND ct_id=pr_category  and st_actionreq=0 AND pr_part=0 group by st_status

            union

            select 17,count(stock.st_id) c from stock, products,manufacturers,categories where  stock.st_product=products.pr_id AND mf_id=pr_manufacturer AND ct_id=pr_category  and (st_actionreq=1 or st_status=17)    

            ")->fetchAll();

        $status = $_STATUSES;

   $data2 = $db->query(
            "select st_status,count(stock.st_id) c from stock, products,manufacturers,categories where  stock.st_product=products.pr_id AND mf_id=pr_manufacturer AND ct_id=pr_category  and st_actionreq=0 AND pr_part=0 AND (st_advertised = 0 or st_advertised is null) group by st_status
            union
            select 17,count(stock.st_id) c from stock, products,manufacturers,categories where  stock.st_product=products.pr_id AND mf_id=pr_manufacturer AND ct_id=pr_category  and (st_actionreq=1 or st_status=17) AND (st_advertised = 0 or st_advertised is null)
            ")->fetchAll();

                  

        $output = array(

            "draw"	=>	1,			

            "iTotalRecords"	=> 	count($data),

            "iTotalDisplayRecords"	=>  count($data),

            "data"	=> 	$data,
		"data2" => $data2,
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



if($action == "sellinfo") {

    

    $data = $_POST["data"];

    $id = $data["st_id"];

    $retail =$data["reatil"];

    $trade = $data["trade"];

    $rrp = $data["rrp"];

    $db->exec("UPDATE stock SET st_retail=:retail, st_trade=:trade, st_rrp=:rrp WHERE st_id=:id",[

        ":retail"=> [$retail, PDO::PARAM_STR], 

        ":trade"=> [$trade, PDO::PARAM_STR], 

        ":rrp"=> [$rrp, PDO::PARAM_STR], 

        ":id"=>[intval($st_id),PDO::PARAM_INT ]

    ]);

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

                        $db->exec("update stock set st_lastcomment=:comment where st_id=:id",[":comment"=> [$vala, PDO::PARAM_STR], ":id"=>[intval($st_id),PDO::PARAM_INT ]]);

                        $cm = $vala;

                    }

                    if($keya == "st") {

                        if(intval($vala) > 0 ) {



                            if($vala == "24")

                                $db->exec("update stock set st_strippeddate=CURRENT_TIMESTAMP, st_status=:status where st_id=:id",[":status"=> [intval($vala), PDO::PARAM_INT], ":id"=>[intval($st_id), PDO::PARAM_INT] ]);

                            if($vala == "9") 

                                $db->exec("update stock set st_despatcheddate=CURRENT_TIMESTAMP,  st_status=:status where st_id=:id",[":status"=> [intval($vala), PDO::PARAM_INT], ":id"=>[intval($st_id), PDO::PARAM_INT] ]);

                            else 

                                $db->exec("update stock set st_status=:status where st_id=:id",[":status"=> [intval($vala), PDO::PARAM_INT], ":id"=>[intval($st_id), PDO::PARAM_INT] ]);

                            $st = $vala;

                        }

                    }               

                }

                $db->exec("INSERT INTO stock_history SET sh_date=NOW(), sh_user=".$_SESSION["user_id"].", sh_status=:st, sh_comment=:cm, sh_stock=:id",

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



if($action == "sold") {

    try {

        $st_id = intval($part);           

        $db->exec("INSERT INTO stock_history SET sh_date=NOW(), sh_user=".$_SESSION["user_id"].", sh_status=16, sh_allocated=0, sh_allocatedto='', sh_allocatedemail='', sh_comment='Sold to ".$_POST["data"]["allocatedto"]."', sh_stock=".$st_id);                
        
        $db->exec("UPDATE stock SET  st_status=16, st_allocated=0, st_soldmethod= 'Manual', st_soldto= '" . $_POST["data"]["allocatedto"] . "', st_solddate=CURRENT_TIMESTAMP, st_allocatedemail='',st_soldprice=".$_POST["data"]['allocateprice'].", st_lastcomment='Sold to ".$_POST["data"]["allocatedto"]."'  WHERE st_id=".$st_id);        

        header('Content-Type: application/json; charset=utf-8');

        http_response_code(200);

        echo json_encode('{ "success" : true, "so":1 }');

    } catch (Exception $e) {

        http_response_code(500);

        echo json_encode('{ "success" : false, "error": "'. $e->getMessage().'" }');

    }

}



if($action == "dispatch") {

    try {

        $st_id = intval($part);                                   

        $db->exec("INSERT INTO stock_history SET sh_date=NOW(), sh_user=".$_SESSION["user_id"].", sh_status=9, sh_allocated=1, sh_allocatedto=(select st_allocatedto from stock where st_id=$st_id), sh_comment='Dispatched to '+(select st_allocatedto from stock where st_id=$st_id), sh_stock=".$st_id);                

        $db->exec("UPDATE stock SET  st_status=9, st_allocated=1, st_lastcomment='Dispatched to '+st_allocatedto  WHERE st_id=".$st_id);        

        header('Content-Type: application/json; charset=utf-8');

        http_response_code(200);

        echo json_encode('{ "success" : true, "so":1 }');

    } catch (Exception $e) {

        http_response_code(500);

        echo json_encode('{ "success" : false, "error": "'. $e->getMessage().'" }');

    }

}

if($action == "returnedforrefund") {

    try {

        $st_id = intval($part);                                   

        $db->exec("INSERT INTO stock_history SET sh_date=NOW(), sh_user=".$_SESSION["user_id"].", sh_status=1, sh_comment='Returned for Refund', sh_stock=".$st_id);                

        $db->exec("UPDATE stock SET  st_status=1, st_lastcomment='Returned for Refund' WHERE st_id=".$st_id);

        

        header('Content-Type: application/json; charset=utf-8');

        http_response_code(200);

        echo json_encode('{ "success" : true, "so":1 }');

    } catch (Exception $e) {

        http_response_code(500);

        echo json_encode('{ "success" : false, "error": "'. $e->getMessage().'" }');

    }

}

if($action == "buyercancelled") {

    try {

        $st_id = intval($part);                                   

        $db->exec("INSERT INTO stock_history SET sh_date=NOW(), sh_user=".$_SESSION["user_id"].", sh_status=6, sh_allocated=0, sh_allocatedto=(select st_allocatedto from stock where st_id=$st_id), sh_comment='Buyer Cancelled', sh_stock=".$st_id);                

        $db->exec("UPDATE stock SET  st_status=6,st_allocatedto='', st_allocated=0, st_lastcomment='Buyer Cancelled' WHERE st_id=".$st_id);

        

        header('Content-Type: application/json; charset=utf-8');

        http_response_code(200);

        echo json_encode('{ "success" : true, "so":1 }');

    } catch (Exception $e) {

        http_response_code(500);

        echo json_encode('{ "success" : false, "error": "'. $e->getMessage().'" }');

    }

}

if($action == "unallocate") {

    try {

        $st_id = intval($part);           

        $sself = $db->get("stock","*",["st_id"=> $st_id]);        

        

        if($sself["st_allocated"] =="1") {

            $db->exec("INSERT INTO stock_history SET sh_date=NOW(), sh_user=".$_SESSION["user_id"].", sh_status=(select st_status from stock where st_id=$st_id), sh_allocated=0, sh_allocatedto='', sh_allocatedemail='', sh_comment='Unallocated', sh_stock=".$st_id);

            $db->exec("UPDATE stock SET st_allocated=0, st_allocatedemail='', st_lastcomment='Unallocated'  WHERE st_id=".$st_id);

        }

        header('Content-Type: application/json; charset=utf-8');

        http_response_code(200);

        echo json_encode('{ "success" : true }');



    } catch (Exception $e) {

        http_response_code(500);

        echo json_encode('{ "success" : false, "error": "'. $e->getMessage().'" }');

    }

}





if($action == "stated") {

    try {

        $st_id = intval($part);           

        $db->exec("INSERT INTO stock_history SET sh_date=NOW(), sh_user=".$_SESSION["user_id"].", sh_status=(select st_status from stock where st_id=$st_id), sh_comment='State Changed to ". $_STATE[ $_POST["data"]["stt"] ]["Name"] ."', sh_stock=".$st_id);

        $db->exec("UPDATE stock SET st_state='".$_POST["data"]["stt"]."'  WHERE st_id=".$st_id);

        header('Content-Type: application/json; charset=utf-8');

        http_response_code(200);

        echo json_encode('{ "success" : true }');

    } catch (Exception $e) {

        http_response_code(500);

        echo json_encode('{ "success" : false, "error": "'. $e->getMessage().'" }');

    }

}

if($action == "record") {

    try {

        $st_id = intval($part);           

        $db->exec("INSERT INTO stock_history SET sh_date=NOW(), sh_user=".$_SESSION["user_id"].", sh_status=(select st_status from stock where st_id=$st_id), sh_comment='Record to ". $_RECORD[ $_POST["data"]["rec"] ] ."', sh_stock=".$st_id);

        $db->exec("UPDATE stock SET st_record='".$_POST["data"]["rec"]."'  WHERE st_id=".$st_id);

        header('Content-Type: application/json; charset=utf-8');

        http_response_code(200);

        echo json_encode('{ "success" : true }');

    } catch (Exception $e) {

        http_response_code(500);

        echo json_encode('{ "success" : false, "error": "'. $e->getMessage().'" }');

    }

}

if($action == "advertised") {

    try {

        $st_id = intval($part);           

        $db->exec("INSERT INTO stock_history SET sh_date=NOW(), sh_user=".$_SESSION["user_id"].", sh_status=(select st_status from stock where st_id=$st_id), sh_comment='Advertised to ". ( $_POST["data"]["adv"]=='' ? "" : $_ADVERTISED[ $_POST["data"]["adv"] ]["Name"] )."', sh_stock=".$st_id);

        $db->exec("UPDATE stock SET st_advertised='".$_POST["data"]["adv"]."'  WHERE st_id=".$st_id);

        header('Content-Type: application/json; charset=utf-8');

        http_response_code(200);

        echo json_encode('{ "success" : true }');

    } catch (Exception $e) {

        http_response_code(500);

        echo json_encode('{ "success" : false, "error": "'. $e->getMessage().'" }');

    }

}

if($action == "allocate") {

    try {

        $st_id = intval($part);           

        $db->exec("INSERT INTO stock_history SET sh_date=NOW(), sh_user=".$_SESSION["user_id"].", sh_status=(select st_status from stock where st_id=$st_id), sh_allocated=1, sh_allocatedto='".$_POST["data"]["allocatedto"]."',  sh_comment='Allocated to ".$_POST["data"]["allocatedto"]."', sh_stock=".$st_id);

        $db->exec("UPDATE stock SET st_allocated=1, st_allocatedto='".$_POST["data"]["allocatedto"]."',  st_lastcomment='Allocated to ".$_POST["data"]["allocatedto"]."'  WHERE st_id=".$st_id);

        header('Content-Type: application/json; charset=utf-8');

        http_response_code(200);

        echo json_encode('{ "success" : true }');

    } catch (Exception $e) {

        http_response_code(500);

        echo json_encode('{ "success" : false, "error": "'. $e->getMessage().'" }');

    }

}

if($action == "sold_price") {

    try {

        $st_id = intval($part);

        $db->exec("UPDATE stock SET st_soldprice=".$_POST["data"]["soldprice"]." WHERE st_id=".$st_id);

        header('Content-Type: application/json; charset=utf-8');

        http_response_code(200);

        echo json_encode('{ "success" : true }');

    } catch (Exception $e) {

        http_response_code(500);

        echo json_encode('{ "success" : false, "error": "'. $e->getMessage().'" }');

    }

}



if($action == "actioncompleted") { 

    try {

        $st_id = intval($part);   

        $db->exec("INSERT INTO stock_history SET sh_date=NOW(), sh_user=".$_SESSION["user_id"].", sh_status=(select st_status from stock where st_id=$st_id), sh_comment='Action Completed', sh_stock=".$st_id);

        $db->exec("UPDATE stock SET  st_status=18, st_actioncmp_date=CURRENT_TIMESTAMP, st_actionreq=0, st_lastcomment='Action Completed' WHERE st_id=".$st_id);

        

        header('Content-Type: application/json; charset=utf-8');

        http_response_code(200);

        echo json_encode('{ "success" : true, "status":18 }');

    } catch (Exception $e) {

        http_response_code(500);

        echo json_encode('{ "success" : false, "error": "'. $e->getMessage().'" }');

    }

}

if($action =="actioncancelled") {    

    try {

        $st_id = intval($part);             

        $db->exec("INSERT INTO stock_history SET sh_date=NOW(), sh_user=".$_SESSION["user_id"].", sh_status=(select st_status from stock where st_id=$st_id), sh_comment='Action Cancelled', sh_stock=".$st_id);

        $db->exec("UPDATE stock SET st_status=st_status_action, st_actionreq=0, st_lastcomment='Action Cancelled' WHERE st_id=".$st_id);

        header('Content-Type: application/json; charset=utf-8');

        http_response_code(200);

        echo json_encode('{ "success" : true }');

    } catch (Exception $e) {

        http_response_code(500);

        echo json_encode('{ "success" : false, "error": "'. $e->getMessage().'" }');

    }

}



if($action=="magsku") {
    try{
        if(isset($_POST["data"])) {
            $data = $_POST["data"]["cm"];         
            $st_id = intval($part);            
            $db->exec("update stock set st_magsku=:magsku where st_id=:id",[":magsku"=> [$data, PDO::PARAM_STR], ":id"=>[intval($st_id),PDO::PARAM_INT ]]);
            $db->exec("INSERT INTO stock_history SET sh_date=NOW(), sh_user=".$_SESSION["user_id"].", sh_status=(select st_status from stock where st_id=$st_id), sh_comment=:cm, sh_stock=:id",[":cm"=> ["SKU changed to ".$data, PDO::PARAM_STR],  ":id"=>[intval($st_id), PDO::PARAM_INT] ] );
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

          

            

            $db->exec("update stock set st_lastcomment=:comment where st_id=:id",[":comment"=> [$data, PDO::PARAM_STR], ":id"=>[intval($st_id),PDO::PARAM_INT ]]);            

            $db->exec("INSERT INTO stock_history SET sh_date=NOW(), sh_user=".$_SESSION["user_id"].", sh_status=(select st_status from stock where st_id=$st_id), sh_comment=:cm, sh_stock=:id",

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

if($action=="request") {

    try 

    {

        if(isset($_POST["data"])) {

            $data = $_POST["data"]["req"];                         

            if($data == null || $data == "") throw new Exception("Please fill comment field.");            

            $st_id = intval($part);            

            $st = 17;

            

            $db->exec("update stock set st_actionreq=1, st_actionreq_date=CURRENT_TIMESTAMP,st_status_action=st_status, st_status=$st,st_lastcomment=:comment where st_id=:id",[":comment"=> [$data, PDO::PARAM_STR], ":id"=>[intval($st_id),PDO::PARAM_INT ]]);            

            $db->exec("INSERT INTO stock_history SET sh_date=NOW(), sh_user=".$_SESSION["user_id"].", sh_status=:st, sh_comment=:cm, sh_stock=:id",

                            [":st"=> [$st, PDO::PARAM_INT], ":cm"=> [$data, PDO::PARAM_STR],  ":id"=>[intval($st_id), PDO::PARAM_INT] ] );

        

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



    $order_field = "st_date desc";

    

    $cat = intval($_POST["category"]);



    if(!empty($_POST["order"])) {

        $order_field =  $_POST['columns'][$_POST['order']['0']['column']]['data'] ." ". strtoupper( $_POST['order']['0']['dir']  );

    }



   /// var_dump($order_field);

   

    $numRows = 0;

    $numRowsTotal = 0;

    

 



    //stock filters   

    if($part=="orange")

        $_SF = "st_actionreq=0 AND pr_part=0 AND st_status=1";

    elseif($part=="purple")

        $_SF = "st_actionreq=0 AND pr_part=0 AND st_status=2";

    elseif($part=="red")

        $_SF = "st_actionreq=0 AND pr_part=0 AND st_status=3";

    elseif($part=="lightblue")

        $_SF = "st_actionreq=0 AND pr_part=0 AND st_status=4";

    elseif($part=="darkblue")

        $_SF = "st_actionreq=0 AND pr_part=0 AND st_status=5";        

    elseif($part=="lightgreen")

        $_SF = "st_actionreq=0 AND pr_part=0 AND st_status=6";    

    elseif($part=="darkgreen")

        $_SF = "st_actionreq=0 AND pr_part=0 AND st_status=7";    

    elseif($part=="black")

        $_SF = "st_actionreq=0 AND pr_part=0 AND st_status=8";    

    elseif($part=="stripped")

        $_SF = "st_actionreq=0 AND pr_part=0 AND st_status=24";   

    elseif($part=="gray")

        $_SF = "st_actionreq=0 AND pr_part=0 AND st_status=9";    

    elseif($part=="action")

        $_SF = "((st_actionreq=1 AND pr_part=0) OR st_status=17)";    

    elseif($part=="actioncmp")

        $_SF = "st_actionreq=0 AND pr_part=0 AND st_status=18";    

    elseif($part=="brown")

        $_SF = "st_actionreq=0 AND pr_part=0 AND st_status=11";    

    elseif($part=="sold")

        $_SF = "st_actionreq=0 AND pr_part=0 AND st_status=16";    

    elseif($part=="search") 

        $_SF = "st_id>0";



    

    if($cat>0) {

        $_SF .= " AND pr_category=".$cat." ";

    }



    $_SFC= $_SF;

           

    $_PARA=[];



    $SEARCH = $_POST["search"]["value"];

    if(!empty($SEARCH)) {

        

        $_SF.= " AND (mf_name like :search OR pr_name like :search OR st_id = :search_no or st_servicetag like :search )";

        $_PARA[":search"]   = "%".$SEARCH."%";

        $_PARA[":search_no"]   = $SEARCH;

        

    }

    



    $query="select st_id,st_product,st_order,st_servicetag,st_lastcomment,st_date,st_status,

            st_specs,st_retail,st_magenabled,st_magsku,st_allocated,st_allocatedto,st_allocatedemail,st_onsale,st_soldprice,st_soldcountry,st_soldmethod,st_soldto,st_actionreq,st_record,st_solddate,            

            mf_name,ct_name,pr_name,pr_title,st_advertised,st_strippeddate,st_actionreq_date,st_state,st_status_action,st_actioncmp_date 

        from stock, manufacturers, products, categories

        where $_SF

            AND  mf_id=pr_manufacturer AND ct_id=pr_category AND st_product=pr_id

        order by $order_field

        limit $start,$length";



    $query_count = "select count(*) as c from stock, manufacturers, products, categories where $_SFC AND  mf_id=pr_manufacturer AND ct_id=pr_category AND st_product=pr_id";

    $query_count2 = "select count(*) as c from stock, manufacturers, products, categories where $_SF AND  mf_id=pr_manufacturer AND ct_id=pr_category AND st_product=pr_id";



   //echo  $query_count;



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