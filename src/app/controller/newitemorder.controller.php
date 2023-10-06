<?php

$db = M::db();

if($action =="deleteOrder") {
    header('Content-Type: application/json; charset=utf-8');
    try 
    {
        $data = $_POST["data"];            
        $id = intval($data["id"]);
            $db->exec("delete from nwp_orders where nor_id=$id and nor_state='Awaiting'");
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

         $c = $db->query("select * from nwp_orderprod where (nop_sn='' and nop_delivered=nop_quantity) and  nop_order=".$id)->fetchAll();

        if (count($c)==0)
        {            

            $currentDate = date("Y-m-d");
            $c = $db->query("select * from nwp_orderprod where nop_sn!='' and  nop_order=".$id)->fetchAll();
            foreach($c as $v) {                
                $db->exec("INSERT INTO nwp_stock SET nst_status=7, nst_order=".$id.", nst_product=".$v["nop_product"].", nst_servicetag='".$v["nop_sn"]."',  nst_date='".$currentDate."', nst_addedby = '".$_SESSION["user_id"]."';");
                $db->exec("update nwp_orderprod set nop_delivered=1 where nop_sn='".$v["nop_sn"]."' AND nop_order=".$id );
            }
            $db->exec("update nwp_orders inner join (select nop_order , sum(nop_quantity) c, sum(nop_delivered) cd  from nwp_orderprod group by nop_order ) op on op.nop_order = nwp_orders.nor_id set nwp_orders.nor_total_delivered=op.cd, nwp_orders.nor_total_items=op.c where nwp_orders.nor_id=op.nop_order and nwp_orders.nor_id=$id");
            $db->exec("update nwp_orders set nor_state ='Completed' where nor_id=$id and nor_total_items=nor_total_delivered");
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
        $db->exec("insert into nwp_products_map (npm_pn,npm_aproducts_id) values('$mappedvm', '$mappedv')");        
        $data = $db->query("SELECT npr_name,npr_sku FROM nwp_products_map join nwp_products on nwp_products.npr_id = npm_aproducts_id where npm_pn=:pn",[":pn"=>$mappedvm])->fetchAll();
        $db->exec("update nwp_orderprod set nop_product='$mappedv' where nop_order='$id'  and SUBSTR(nop_sn,4,5) = '$mappedvm'");

        $db->exec("update nwp_stock set nst_product='$mappedv' where nst_product=0 AND SUBSTR(nst_servicetag,4,5) = '$mappedvm'");

        if (count($data) == 1)
        {
            $d = $data[0]["npr_name"]. " ".$data[0]["npr_sku"];
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
        
        $db->exec("DELETE from nwp_orderprod where nop_order='$id' and nop_sn='$serial'");
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

        $result = $db->query("SELECT nop_sn FROM nwp_orderprod where nop_order='$id' and nop_sn='$serial'")->fetchAll();
        $result2 = $db->query("SELECT nst_servicetag FROM nwp_stock where nst_servicetag='$serial'")->fetchAll();
        
        if(count($result) > 0  ||  count($result2) > 0) {

  
 
           echo json_encode('{ "success" : false, "error": "Serial Number Already in Database" }');

            die();
        }


        $data = $db->query("SELECT  npr_id, npr_name, npr_sku FROM nwp_products_map join nwp_products on nwp_products.npr_id = npm_aproducts_id where npm_pn=:pn LIMIT 1",[":pn"=>$pn])->fetchAll();

        if (count($data) == 1){
            $d = $data[0]["npr_name"]. " ".$data[0]["npr_sku"];
            $tokened2[$serial] = $data[0]["npr_id"];
            http_response_code(200);
            echo json_encode([ "success" => true , "description" => $d], );
        }
        else {
            $tokened2[$serial] = 0;
            http_response_code(200);
            echo json_encode('{ "success" : true, "description": "<a href=\'javascript:void(0)\' onclick=\'assign_new(this)\'>No Product Assigned – Assign</a>"  }');
        }
        $_SESSION[$token2] = $tokened2;     
        $db->exec("update nwp_orders inner join (select nop_order , sum(nop_quantity) c, sum(nop_delivered) cd  from nwp_orderprod group by nop_order ) op on op.nop_order = nwp_orders.nor_id set nwp_orders.nor_total_delivered=op.cd, nwp_orders.nor_total_items=op.c where nwp_orders.nor_id=op.nop_order and nwp_orders.nor_id=$id");
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
    
                $oritem = $db->query("SELECT * from nwp_orderprod where nop_order=".$id." and nop_product=".$pr_id)->fetchAll();
    
                if (count($oritem) == 1){
                    $db->exec("UPDATE nwp_orderprod SET nop_delivered = nop_delivered + 1 where nop_order='".$id."' AND nop_product='".$pr_id."';");
                }

                $cost = 0;
                $vattype = "";
                $vat = 0;
                $netprice = 0;
                $orderQuery = "SELECT rfqorderid FROM nwp_orders WHERE nor_id = " . $id;
                $orderData = $db->query($orderQuery)->fetchAll();
                if($orderData[0]['rfqorderid'] != 0){
                    $rfqoQuery = "SELECT rfqo_vatid FROM rfq_orders WHERE rfqo_id = " . $orderData[0]['rfqorderid'];
                    $rfqoData = $db->query($rfqoQuery)->fetchAll();
                    $rfqopQuery = "SELECT rfqop_prodtype, rfqop_product, rfqop_price FROM rfq_orderproducts WHERE rfqo_id = " . $orderData[0]['rfqorderid'];
                    $rfqopData = $db->query($rfqopQuery)->fetchAll();
                    foreach ($rfqopData as $entry) {
                        if ($entry['rfqop_prodtype'] == 1 && $entry['rfqop_product'] == $pr_id) {
                            $cost = $entry['rfqop_price'];
                            if ($rfqoData[0]['rfqo_vatid'] != 0) {
                                $vats = $db->query("SELECT vat_percent, vat_type from vat_rates WHERE vat_id = " . $rfqoData[0]['rfqo_vatid'])->fetchAll();
                                $vattype = $vats[0]['vat_type'];
                                $netprice = ($cost*100)/(100+$vats[0]['vat_percent']);
                                $vat = $cost - $netprice;
                            }
                            break;
                        }
                    }
                }
    
                $db->exec("INSERT INTO nwp_stock SET nst_status=7, nst_order=".$id.", nst_product=".$pr_id.", nst_servicetag='".$serial."',  nst_date='".$currentDate."', nst_addedby = '".$_SESSION["user_id"]."', nst_cost=".$cost.", nst_vat_type='".$vattype."', nst_vat=".$vat.", nst_netprice=".$netprice.";");

            }
           
        }
    
        $db->exec("update nwp_orders inner join (select nop_order , sum(nop_quantity) c, sum(nop_delivered) cd  from nwp_orderprod group by nop_order ) op on op.nop_order = nwp_orders.nor_id set nwp_orders.nor_total_delivered=op.cd, nwp_orders.nor_total_items=op.c where nwp_orders.nor_id=op.nop_order and nwp_orders.nor_id=$id");
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
    $input_array = $db->query("SELECT * FROM nwp_stock WHERE nst_product=".$pr_id." AND nst_order=".$id)->fetchAll();
    foreach($input_array as $ki=>$vi) {
        array_push($inputhtml ,[ "serial" => $vi["nst_servicetag"], "cost"=> 0 ]);
    }

    $result = $db->query("select nop_quantity from nwp_orderprod where nop_product=".$pr_id." and nop_order=".$id."")->fetchAll(); 
    foreach($tokened as $key => $val) {
        if($key == $pr_id) {
            foreach($val as $k=>$v) {                
                array_push($inputhtml, [ "serial" => $k, "cost"=> $v ]);
            }
        }
    }
    $pt = [
        "data" => $inputhtml,
        "count" => count($inputhtml),
        "remain" => intval($result[0]["nop_quantity"]) - count($inputhtml),
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

        
            
        $serial_count = $db->count("nwp_stock",["nst_servicetag" => $serial]);

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
            $db->insert("nwp_orders",["nor_reference"=>$data["reference"],"nor_date"=> date("Y-m-d", strtotime(str_replace("/","-",$data["date"])) ), "nor_supplier"=>$data["supplier"], "nor_state"=>"Awaiting"]);
            $id = $db->id();
            
        }
        else {
            $db->exec("update nwp_orders set nor_reference=:aor_reference, nor_date=:aor_date, nor_supplier=:aor_supplier where nor_id=:id",
                [
                    ":aor_reference" => [$data["reference"], PDO::PARAM_STR]  ,
                    ":aor_date" => [date("Y-m-d", strtotime(str_replace("/","-",$data["date"])) ), PDO::PARAM_STR]  ,
                    ":aor_supplier" => [ $data["supplier"], PDO::PARAM_INT ]  ,
                    ":id"=> [ $id, PDO::PARAM_INT ]
                ]
            );
        }


        foreach($tokened2 as $key=>$value) {

            $rec = $db->get("nwp_orderprod",["nop_quantity"],["nop_order"=>$id,  "nop_sn" => $key]);
            if($rec==null) {
                $db->insert("nwp_orderprod",[
                    "nop_order" => $id,
                    "nop_product" => $value,
                    "nop_quantity" => 1,
                    "nop_delivered" => 0,
                    "nop_sn" => $key
                ]);
            }
        }

        foreach($tokened as $key=>$value) {
            $pn = intval($value["pn"]);
            $qa = intval($value["qa"]);  
            $mi++;
            $rec = $db->get("nwp_orderprod",["nop_quantity"],[ "nop_sn" => "", "nop_order"=>$id, "nop_product"=>$pn]);
            if($rec!=null) {
                $db->exec("update nwp_orderprod set nop_quantity=nop_quantity+".$qa." where nop_product=".$pr." and nop_order=".$id);
            }
            else {                            
                $db->insert("nwp_orderprod",[
                    "nop_order" => $id,
                    "nop_product" => $pn,
                    "nop_quantity" => $qa,
                    "nop_delivered" => 0
                ]);
            }
        }

        unset($_SESSION[$token]);
        
        $db->exec("update nwp_orders inner join (select nop_order ,sum(nop_quantity) c from nwp_orderprod group by nop_order ) op on op.nop_order = nwp_orders.nor_id set nwp_orders.nor_total_items=op.c where nwp_orders.nor_id=op.nop_order and nwp_orders.nor_id=$id");

        $db->exec("update nwp_orders set nor_state ='Completed' where nor_id=$id and nor_total_items=nor_total_delivered");



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
    $ord = $db->get("nwp_orders","*",["nor_id"=>$id]);

    
    $query="select npr_name, npr_sku, npr_condition, nop_quantity from nwp_products, nwp_orderprod  where nop_sn='' and npr_id = nop_product and nop_order=$id";
    $data = $db->query($query)->fetchAll();                        
    foreach($data as $v) {
        
        echo "<tr><td>".$v["npr_name"]." ((".$v["npr_sku"].")</td><td>".$v["nop_quantity"]."</td>";        
        echo "<th></th>";
        echo "</tr>";

    }
    
    if(isset($_SESSION[$token])) {
        $tokened = $_SESSION[$token];

        if(is_array($tokened)) {
            foreach($tokened as $k=>$v){


                $q = "select npr_name, npr_sku, npr_condition from nwp_products where npr_id = ".intval($v["pn"]);
                $se = $db->query($q)->fetchAll()[0];
                echo "<tr><td>".$se["npr_sku"]."</td><td>".$se["npr_name"]."</td><td>".$se["npr_condition"]."</td><td>".$v["qa"]."</td>";        
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

    $order_field = "nor_date desc";
    

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
        
        $_SF.= " AND ( nor_reference like :search OR nor_id = :search_no )";
        $_PARA[":search"]   = "%".$SEARCH."%";
        $_PARA[":search_no"]   = $SEARCH;
        
    }
    

    $query=
        "SELECT *, 
            orange as nst_orange, purple as nst_purple, red as nst_red, lightblue as nst_lightblue, lightgreen as nst_lightgreen, sp_name,
            darkgreen as nst_darkgreen, green as nst_green, sold+gray as nst_sold , black+stripped as nst_black,  CONCAT(lightgreen,' / ', green ,' / ', darkgreen) as nst_greenish,
             actioncmp as nst_action, brown as nst_brown , CONCAT( TRUNCATE( (((lightgreen+green+darkgreen+sold+gray)*100) / nor_total_items),0),'% / ', lightgreen+green+darkgreen+sold+gray)  as nst_fix_rate 
        FROM nwp_orders left join nwp_order_distribution on nst_order = nor_id left join suppliers on suppliers.sp_id = nor_supplier 
        WHERE  $_SF
        order by $order_field 
        limit $start,$length";


    $query_count = "select count(*) as c FROM nwp_orders left join nwp_order_distribution on nst_order = nor_id WHERE  $_SFC ";
    $query_count2 = "select count(*) as c FROM nwp_orders left join nwp_order_distribution on nst_order = nor_id WHERE  $_SF ";

    //echo  $db->debug()->query($query,$_PARA);

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

