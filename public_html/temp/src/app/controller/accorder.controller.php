<?php

$db = M::db();

if($action =="deleteOrder") {
    header('Content-Type: application/json; charset=utf-8');
    try 
    {
        $data = $_POST["data"];            
        $id = intval($data["id"]);
            $db->exec("delete from acc_orders where aor_id=$id and aor_state='Awaiting'");
            http_response_code(200);
            echo json_encode('{ "success" : true  }');
        
    }
    catch  (Exception $e) {
        http_response_code(500);
        echo json_encode('{ "success" : false, "error": "'. $e->getMessage().'" }');
    }
}
if($action =="completeorder") {
    header('Content-Type: application/json; charset=utf-8');
    try 
    {
        $data = $_POST["data"];            
        $id = intval($data["id"]);

         $c = $db->query("select * from acc_orderprod where (aop_sn='' and aop_delivered=aop_quantity) and  aop_order=".$id)->fetchAll();

        if (count($c)==0)
        {            

            $currentDate = date("Y-m-d");
            $c = $db->query("select * from acc_orderprod where aop_sn!='' and  aop_order=".$id)->fetchAll();
            foreach($c as $v) {                
                $db->exec("INSERT INTO acc_stock SET ast_status=1, ast_order=".$id.", ast_original_product=".$v["aop_product"].", ast_product=".$v["aop_product"].", ast_servicetag='".$v["aop_sn"]."',  ast_date='".$currentDate."', ast_addedby = '".$_SESSION["user_id"]."';");
                $db->exec("update acc_orderprod set aop_delivered=1 where aop_sn='".$v["aop_sn"]."' AND aop_order=".$id );
            }
            $db->exec("update acc_orders inner join (select aop_order , sum(aop_quantity) c, sum(aop_delivered) cd  from acc_orderprod group by aop_order ) op on op.aop_order = acc_orders.aor_id set acc_orders.aor_total_delivered=op.cd, acc_orders.aor_total_items=op.c where acc_orders.aor_id=op.aop_order and acc_orders.aor_id=$id");
            $db->exec("update acc_orders set aor_state ='Completed' where aor_id=$id and aor_total_items=aor_total_delivered");
            http_response_code(200);
            echo json_encode('{ "success" : true  }');
        }        
        else {
            http_response_code(200);
            echo json_encode('{ "success" : true, "error": "Error occured!"  }');
        }
    }
    catch  (Exception $e) {
        http_response_code(500);
        echo json_encode('{ "success" : false, "error": "'. $e->getMessage().'" }');
    }
}

if($action =="maproduct") {
    header('Content-Type: application/json; charset=utf-8');
    try 
    {
        $data = $_POST["data"];    
        $mappedv = $data["mappedv"];
        $id = $data["id"];
        $mappedvm = $data["mappedvm"];            
        //$db->exec("insert into aproducts_map (apm_pn,apm_aproducts_id) values('$mappedvm', '$mappedv')");        
        //$data = $db->query("SELECT  apr_name, apr_sku FROM aproducts_map join aproducts on aproducts.apr_id = apm_aproducts_id where apm_pn=:pn",[":pn"=>$mappedvm])->fetchAll();

        $db->exec("update aproducts set apr_mpn = CONCAT(COALESCE(apr_mpn,'') , '$mappedv,') where apr_id = $mappedv;");

        $db->exec("update acc_orderprod set aop_product='$mappedv' where aop_order='$id'  and SUBSTR(aop_sn,4,5) = '$mappedvm'");

        $db->exec("update acc_stock set ast_product='$mappedv' where ast_product=0 AND SUBSTR(ast_servicetag,4,5) = '$mappedvm'");

        if (count($data) == 1)
        {
            $d = $data[0]["apr_name"]. " ".$data[0]["apr_sku"];
            http_response_code(200);
            echo json_encode('{ "success" : true, "description": "'.$d.'"  }');
        }        
        else {
            http_response_code(200);
            echo json_encode('{ "success" : true, "description": "Duplicate!"  }');
        }

        
    }
    catch  (Exception $e) {
        http_response_code(500);
        echo json_encode('{ "success" : false, "error": "'. $e->getMessage().'" }');
    }
}
if($action =="delproduct") {
    header('Content-Type: application/json; charset=utf-8');
    try 
    {
        $data = $_POST["data"];    
        $serial = $data["serial"];
        $id = $data["id"];
        $token2 = $data["token2"];  
        $tokened2 = array();
        if(isset($_SESSION[$token2])) {
            $tokened2 = $_SESSION[$token2];
        }
        if(isset($tokened2[$serial])) {
            unset($tokened2[$serial]);
            $_SESSION[$token2] = $tokened2;
        }
        
        $db->exec("DELETE from acc_orderprod where aop_order='$id' and aop_sn='$serial'");
        //$result2 = $db->query("SELECT ast_servicetag FROM acc_stock where ast_servicetag='$serial'")->fetchAll();

        http_response_code(200);
        echo json_encode('{ "success": true }');
    }
    catch  (Exception $e) {
        http_response_code(500);
        echo json_encode('{ "success" : false, "error": "'. $e->getMessage().'" }');
    }
}
if($action =="checkproduct") {
    header('Content-Type: application/json; charset=utf-8');
    try 
    {
        $data = $_POST["data"];    
        $serial = $data["serial"];
        $id = $data["id"];
        $token2 = $data["token2"];  
        $tokened2 = array();
        if(isset($_SESSION[$token2])) {
            $tokened2 = $_SESSION[$token2];
        }
        $pn = substr($serial, 3,5);

        $result = $db->query("SELECT aop_sn FROM acc_orderprod where aop_order='$id' and aop_sn='$serial'")->fetchAll();
        $result2 = $db->query("SELECT ast_servicetag FROM acc_stock where ast_servicetag='$serial'")->fetchAll();
        
        if(count($result) > 0  ||  count($result2) > 0) {

  
 
           echo json_encode('{ "success" : false, "error": "Serial Number Already in Database" }');

            die();
        }

        $data = $db->query("SELECT  apr_id, apr_name, apr_sku FROM aproducts  where apr_mpn like :pn LIMIT 1",[":pn"=>"%".$pn."%"])->fetchAll();
       // $data = $db->query("SELECT  apr_id, apr_name, apr_sku FROM aproducts_map join aproducts on aproducts.apr_id = apm_aproducts_id where apm_pn=:pn LIMIT 1",[":pn"=>$pn])->fetchAll();

        if (count($data) == 1){
            $d = $data[0]["apr_name"]. " ".$data[0]["apr_sku"];
            $tokened2[$serial] = $data[0]["apr_id"];
            http_response_code(200);
            echo json_encode([ "success" => true , "description" => $d], );
        }
        else {
            $tokened2[$serial] = 0;
            http_response_code(200);
            echo json_encode('{ "success" : true, "description": "<a href=\'javascript:void(0)\' onclick=\'assign_new(this)\'>No Product Assigned – Assign</a>"  }');
        }
        $_SESSION[$token2] = $tokened2;     
        $db->exec("update acc_orders inner join (select aop_order , sum(aop_quantity) c, sum(aop_delivered) cd  from acc_orderprod group by aop_order ) op on op.aop_order = acc_orders.aor_id set acc_orders.aor_total_delivered=op.cd, acc_orders.aor_total_items=op.c where acc_orders.aor_id=op.aop_order and acc_orders.aor_id=$id");
    }
    catch  (Exception $e) {
        http_response_code(500);
        echo json_encode('{ "success" : false, "error": "'. $e->getMessage().'" }');
    }
}
if($action =="orderinpost") {
    header('Content-Type: application/json; charset=utf-8');
    try 
    {
        $data = $_POST["data"];           
        $id = intval($data["id"]);    
        $token = $data["token"];  

        $tokened = array();
        if(isset($_SESSION[$token])) {
            $tokened = $_SESSION[$token];
        }
        $currentDate = date("Y-m-d");
        foreach($tokened as $key => $val) {
            $pr_id = $key ;
            foreach($val as $k=>$v) {
                $serial = $k;
                $cost = $v;
    
                $oritem = $db->query("SELECT * from acc_orderprod where aop_order=".$id." and aop_product=".$pr_id)->fetchAll();
    
                if (count($oritem) == 1){
                    $db->exec("UPDATE acc_orderprod SET aop_delivered = aop_delivered + 1 where aop_order='".$id."' AND aop_product='".$pr_id."';");
                } 
    
                $db->exec("INSERT INTO acc_stock SET ast_status=1, ast_order=".$id.", ast_original_product=".$pr_id.",  ast_product=".$pr_id.", ast_servicetag='".$serial."',  ast_date='".$currentDate."', ast_addedby = '".$_SESSION["user_id"]."';");

            }
           
        }
    
        $db->exec("update acc_orders inner join (select aop_order , sum(aop_quantity) c, sum(aop_delivered) cd  from acc_orderprod group by aop_order ) op on op.aop_order = acc_orders.aor_id set acc_orders.aor_total_delivered=op.cd, acc_orders.aor_total_items=op.c where acc_orders.aor_id=op.aop_order and acc_orders.aor_id=$id");
        unset($_SESSION[$token]);
        http_response_code(200);
        echo json_encode('{ "success" : true }');
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode('{ "success" : false, "error": "'. $e->getMessage().'" }');
    }

}
if($action == "orderin") {
     
    $data = $_POST["data"];           
    $id = intval($data["id"]);
    $pr_id = intval($data["pr_id"]);
    $token = $data["token"];  
    
    $tokened = array();
    if(isset($_SESSION[$token])) {
        $tokened = $_SESSION[$token];
    }

    $inputhtml=array();
    $input_array = $db->query("SELECT * FROM acc_stock WHERE ast_original_product=".$pr_id." AND ast_order=".$id)->fetchAll();
    foreach($input_array as $ki=>$vi) {
        array_push($inputhtml ,[ "s"=>"stock", "opr" => $vi["ast_original_product"], "pr"=>$vi["ast_product"], "serial" => $vi["ast_servicetag"], "cost"=> 0 ]);
    }

    $result = $db->query("select aop_quantity from acc_orderprod where aop_product=".$pr_id." and aop_order=".$id."")->fetchAll(); 
    foreach($tokened as $key => $val) {
        if($key == $pr_id) {
            foreach($val as $k=>$v) {                
                array_push($inputhtml, ["pr"=>$pr_id, "serial" => $k, "cost"=> $v ]);
            }
        }
    }
    $pt = [
        "data" => $inputhtml,
        "count" => count($inputhtml),
        "remain" => intval($result[0]["aop_quantity"]) - count($inputhtml),
        "success"=> true
    ];
    echo json_encode($pt);
    exit;
}

if($action == "orderinrem") {
    header('Content-Type: application/json; charset=utf-8');
    try 
    {
        $data = $_POST["data"];           
        $id = intval($data["id"]);
        $pr_id = intval($data["pr_id"]);
        $token = $data["token"];  
        $serial = $data["serial"];  
        $tokened = array();
        if(isset($_SESSION[$token])) {
            $tokened = $_SESSION[$token];
        }
        unset($tokened[$pr_id][$serial]);
        $_SESSION[$token] = $tokened;        
        http_response_code(200);
        echo json_encode('{ "success" : true }');
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode('{ "success" : false, "error": "'. $e->getMessage().'" }');
    }
}
if($action == "orderinadd") {
    header('Content-Type: application/json; charset=utf-8');
    try 
    {
        $data = $_POST["data"];           
        $id = intval($data["id"]);
        $pr_id = intval($data["pr_id"]);
        $token = $data["token"];  
        $serial = $data["serial"];  
        $tokened = array();
        if(isset($_SESSION[$token])) {
            $tokened = $_SESSION[$token];
        }
        if(!isset($tokened[$pr_id])) {
            $tokened[$pr_id] = array();
        }

        foreach($tokened as  $key=>$val) {
            foreach($val as $k=>$v) {  
                if(isset($tokened[$key][$serial]))
                    throw new Exception('Serial already used!');
            }
        }

        
            
        $serial_count = $db->count("acc_stock",["ast_servicetag" => $serial]);

        if($serial_count>0) {
            throw new Exception('" at database!');
        }

        $tokened[$pr_id][$serial] = 0;
        $_SESSION[$token] = $tokened;
        
        http_response_code(200);
        echo json_encode('{ "success" : true }');
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode('{ "success" : false, "error": "'. $e->getMessage().'" }');
    }

}




if($action == "putorderitems") {

   
    try 
    {    
        $data = $_POST["data"];   
        $token = $data["token"];  
        $token2 = $data["token2"];  
        
    
        
        $id = intval($data["id"]);
        $mi =0;
        $tokened = array();
        $tokened2 = array();
        
        if(isset($_SESSION[$token])) {
            $tokened=$_SESSION[$token];
        }       

        if(isset($_SESSION[$token2])) {
            $tokened2=$_SESSION[$token2];
        }       
        if($id==0) {
            $db->insert("acc_orders",["aor_reference"=>$data["reference"],"aor_date"=> date("Y-m-d", strtotime(str_replace("/","-",$data["date"])) ), "aor_supplier"=>$data["supplier"], "aor_state"=>"Awaiting"]);
            $id = $db->id();
            
        }
        else {
            $db->exec("update acc_orders set aor_reference=:aor_reference, aor_date=:aor_date, aor_supplier=:aor_supplier where aor_id=:id",
                [
                    ":aor_reference" => [$data["reference"], PDO::PARAM_STR]  ,
                    ":aor_date" => [date("Y-m-d", strtotime(str_replace("/","-",$data["date"])) ), PDO::PARAM_STR]  ,
                    ":aor_supplier" => [ $data["supplier"], PDO::PARAM_INT ]  ,
                    ":id"=> [ $id, PDO::PARAM_INT ]
                ]
            );
        }


        foreach($tokened2 as $key=>$value) {

            $rec = $db->get("acc_orderprod",["aop_quantity"],["aop_order"=>$id,  "aop_sn" => $key]);
            if($rec==null) {
                $db->insert("acc_orderprod",[
                    "aop_order" => $id,
                    "aop_product" => $value,
                    "aop_quantity" => 1,
                    "aop_delivered" => 0,
                    "aop_sn" => $key
                ]);
            }
        }

        foreach($tokened as $key=>$value) {
            $pn = intval($value["pn"]);
            $qa = intval($value["qa"]);  
            $mi++;
            $rec = $db->get("acc_orderprod",["aop_quantity"],[ "aop_sn" => "", "aop_order"=>$id, "aop_product"=>$pn]);
            if($rec!=null) {
                $db->exec("update acc_orderprod set aop_quantity=aop_quantity+".$qa." where aop_product=".$pr." and aop_order=".$id);
            }
            else {                            
                $db->insert("acc_orderprod",[
                    "aop_order" => $id,
                    "aop_product" => $pn,
                    "aop_quantity" => $qa,
                    "aop_delivered" => 0
                ]);
            }
        }

        unset($_SESSION[$token]);
        
        $db->exec("update acc_orders inner join (select aop_order ,sum(aop_quantity) c from aorderprod group by aop_order ) op on op.aop_order = acc_orders.aor_id set acc_orders.aor_total_items=op.c where acc_orders.aor_id=op.aop_order and acc_orders.aor_id=$id");

        $db->exec("update acc_orders set aor_state ='Completed' where aor_id=$id and aor_total_items=aor_total_delivered");



        header('Content-Type: application/json; charset=utf-8');
        http_response_code(200);
        echo json_encode('{ "success" : true , "id":'.$id.', "p": '.$mi.'}');
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
    $ord = $db->get("acc_orders","*",["aor_id"=>$id]);

    
    $query="select apr_name, apr_sku,apr_condition,aop_quantity from aproducts, acc_orderprod  where aop_sn='' and apr_id = aop_product and aop_order=$id";
    $data = $db->query($query)->fetchAll();                        
    foreach($data as $v) {
        
        echo "<tr><td>".$v["apr_name"]." ((".$v["apr_sku"].")</td><td>".$v["aop_quantity"]."</td>";        
        echo "<th></th>";
        echo "</tr>";

    }
    
    if(isset($_SESSION[$token])) {
        $tokened = $_SESSION[$token];

        if(is_array($tokened)) {
            foreach($tokened as $k=>$v){


                $q = "select apr_name, apr_sku,apr_condition from aproducts where apr_id = ".intval($v["pn"]);
                $se = $db->query($q)->fetchAll()[0];
                echo "<tr><td>".$se["apr_sku"]."</td><td>".$se["apr_name"]."</td><td>".$se["apr_condition"]."</td><td>".$v["qa"]."</td>";        
                echo "<th><button class='btn btn-sm btn-warning' onClick='RemoveItem(".$v["pn"].")'>Remove</button></th>";
                echo "</tr>";

            }
        }
    }
}

if($action=="removeproduct") {
    try 
    {
        $data = $_POST["data"];   
        $token = $data["token"];   
        $pr = intval($data["pr"]);        
        $id = intval($data["id"]);

        $tokened = array();
        if(isset($_SESSION[$token])) {
            $tokened=$_SESSION[$token];
        }
        foreach($tokened as $key=>$value) {
            if($value["pn"] == $pr) {       
                unset($tokened[$key]);            
                $_SESSION[$token] = $tokened;
                break;
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

if($action == "addproduct") {
    try 
    {    
        $data = $_POST["data"];   
        $token = $data["token"];   
        
        $qa = intval($data["qa"]);
        $pn = $data["pn"];
        $i=0;
        $tokened = array();
        $tt=0;
        if(isset($_SESSION[$token])) {
            $tokened=$_SESSION[$token];
        }

        $leave=false;
        
        foreach($tokened as $key=>&$value) {
            
            if($value["pn"] == $pn) {
                $value["qa"] = $value["qa"]  + $qa;
                $leave=true;                
                $i++;
                $tt=5;
                break;
            }
        }

        if(!$leave) {           
            array_push($tokened, array( 
                "pn"=>$pn,                  
                "qa" => $qa
            ));
            $i++;
            $tt=7;            
        }
        
        $_SESSION[$token] = $tokened;
        header('Content-Type: application/json; charset=utf-8');
        http_response_code(200);
        echo json_encode('{ "success" : true, "t":'.count($tokened).', "i":'.$i.', "tt":'.$tt.' }');
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode('{ "success" : false, "error": "'. $e->getMessage().'" }');
    }
}


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
    $_SF =" 1=1 ";
    $_SFC= $_SF;
           
    $_PARA=[];

    $SEARCH = $_POST["search"]["value"];
    if(!empty($SEARCH)) {
        
        $_SF.= " AND ( aor_reference like :search OR aor_id = :search_no )";
        $_PARA[":search"]   = "%".$SEARCH."%";
        $_PARA[":search_no"]   = $SEARCH;
        
    }
    

    $query=
        "SELECT *, 
            orange as ast_orange, purple as ast_purple, red as ast_red, lightblue as ast_lightblue, lightgreen as ast_lightgreen, sp_name,
            darkgreen as ast_darkgreen, green as ast_green,sold+gray as ast_sold , black+stripped as ast_black,  CONCAT(lightgreen,' / ', green ,' / ', darkgreen) as ast_greenish,
             actioncmp as ast_action, brown as ast_brown , CONCAT( TRUNCATE( (((lightgreen+green+darkgreen+sold+gray)*100) / aor_total_items),0),'% / ', lightgreen+green+darkgreen+sold+gray)  as ast_fix_rate 
        FROM acc_orders left join acc_order_distribution on ast_order = aor_id left join suppliers on suppliers.sp_id = aor_supplier 
        WHERE  $_SF
        order by $order_field 
        limit $start,$length";


    $query_count = "select count(*) as c FROM acc_orders left join acc_order_distribution on ast_order = aor_id WHERE  $_SFC ";
    $query_count2 = "select count(*) as c FROM acc_orders left join acc_order_distribution on ast_order = aor_id WHERE  $_SF ";


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

    echo json_encode($output);

}
else {
    exit();
}

