<?php

$db = M::db();
if($action =="orderinpost") {
    header('Content-Type: application/json; charset=utf-8');
    try 
    {
        $data = $_POST["data"];           
        $id = intval($data["id"]);    
        $token = $data["token"];  

        $ord = $db->get("orders", "*", ["or_id" => $id]);

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
    
                $oritem = $db->query("SELECT * from orderprod where op_order=".$id." and op_product=".$pr_id)->fetchAll();
    
                if (count($oritem) == 1){
                    $db->exec("UPDATE orderprod SET op_delivered = op_delivered + 1 where op_order='".$id."' AND op_product='".$pr_id."';");
                } 
                $vatAmount = $cost*($ord['or_vat_rate']/100);
                $netPrice = $cost - $vatAmount;
                $db->exec("INSERT INTO stock SET st_status=1, st_order=".$id.", st_product=".$pr_id.", st_servicetag='".$serial."', st_cost=".$cost.", st_vat_type='".$ord['or_vat_type']."', st_vat_rate=".$ord['or_vat_rate'].", st_vat_amount=".$vatAmount.", st_netprice=".$netPrice.", st_date='".$currentDate."', st_addedby = '".$_SESSION["user_id"]."';");
            }
        }
    
        $db->exec("update orders inner join (select op_order , sum(op_quantity) c, sum(op_delivered) cd  from orderprod group by op_order ) op on op.op_order = orders.or_id set orders.or_total_delivered=op.cd, orders.or_total_items=op.c where orders.or_id=op.op_order and orders.or_id=$id");

        $isComplete = true;

        $ordprods = $db->query("select op_product from orderprod WHERE op_order = $id");

        foreach ($ordprods as $prod) {
            $orprod = $db->query("SELECT op_quantity, op_delivered from orderprod where op_order=".$id." and op_product=".$prod['op_product'])->fetchAll();
            if($orprod){
                if ($orprod[0]['op_quantity'] != $orprod[0]['op_delivered']) {
                    $isComplete = false;
                }
            } else {
                $isComplete = false;
            }
        }

        if($isComplete){
            $db->exec("update orders set or_state = 'Completed' where orders.or_id=$id");
        }

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
    $input_array = $db->query("SELECT * FROM stock WHERE st_product=".$pr_id." AND st_order=".$id)->fetchAll();
    foreach($input_array as $ki=>$vi) {
        array_push($inputhtml ,[ "serial" => $vi["st_servicetag"], "cost"=> $vi["st_cost"] ]);
    }

    $result = $db->query("select op_quantity from orderprod where op_product=".$pr_id." and op_order=".$id."")->fetchAll(); 
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
        "remain" => intval($result[0]["op_quantity"]) - count($inputhtml),
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
        $cost = $data["cost"];  
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

        
            
        $serial_count = $db->count("stock",["st_servicetag" => $serial]);

        if($serial_count>0)
            throw new Exception('Serial already used at database!');
        

        $tokened[$pr_id][$serial] = $cost;
        $_SESSION[$token] = $tokened;
        
        http_response_code(200);
        echo json_encode('{ "success" : true }');
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode('{ "success" : false, "error": "'. $e->getMessage().'" }');
    }

}




if($action == "putorderitems") {
    function csvToArray($fp)
    {
        $result = [];
    
        $lp = explode("\r\n",$fp);
    
        for($i=0;$i<count($lp);$i++) {

            $arr = explode("," ,$lp[$i]);
            if(count($arr)!=3) continue;
            if($arr[0]=='model') continue;
			if($arr[0]=='"model"') continue;
            $model = str_replace("\"", "", $arr[0]);
            $serial = str_replace("\"", "", $arr[1]);
            $cost = $arr[2];
    
            if (empty($model) && empty($serial) && empty($cost)) {
                continue;
            }

    
            $result[] = [
                'model'     => $model,
                'serial'    => $serial,
                'cost'      => $cost,
            ];
        }
    
        return $result;
    }
    
    function validateData($inputArray, &$error)
    {
        $serials = array_column($inputArray, 'serial');
        $db = M::db();
        $serialsExists = $db->query("SELECT st_id from `stock` where st_servicetag IN ('". implode ("','" , $serials) ."');")->fetchAll();
    
        if (count($serialsExists) > 0) {
            $error = "Serial exists in DB!";
            return false;
        }
    
        if (count($serials) !== count(array_unique($serials))) {
            $error = "Non unique serials in file ".join(",", $serials)." to ".join(",",array_unique($serials));
            return false;
        }
    
        $models = array_column($inputArray, 'model');
    
        //echo "SELECT pr_id from `products` where pr_name IN ('". implode ("','" , $models) ."')";
        $modelExists = $db->query("SELECT pr_id from `products` where pr_name IN ('". implode ("','" , $models) ."');")->fetchAll();
    

        if (count($modelExists) !== count(array_unique($models))) {
            $error = "Model does not exists in DB! ".count($modelExists)." / ".join(",",array_unique($models));
            return false;
        }
    
        foreach ($inputArray as $line) {
            if (intval($line['cost']) === 0) {
                $error = "Price = 0!";
                return false;
            }
        }
    
        return true;
    }
    
    try 
    {    
        $data = $_POST["data"];   
        $token = $data["token"];  
        $vat_id = $data["vatType"];
        $vat = $db->query("SELECT * from vat_rates WHERE vat_id = " . $vat_id)->fetchAll();
        $csv = @$data["csv"];
        header('Content-Type: application/json; charset=utf-8');
        $id = intval($data["id"]);
        $mi =0;
        $tokened = array();
        if(isset($_SESSION[$token])) {
            $tokened=$_SESSION[$token];
        }

        if($id==0&&$csv!=null&&$csv!="") {
            $dar = csvToArray($csv);
            $err="";
            $bl = validateData($dar, $err);

            if(!$bl) {
                http_response_code(500);
                echo json_encode('{ "success" : false, "error": "'. $err.'" }');
                exit;
            }

            $db->insert("orders",["or_date"=> date("Y-m-d", strtotime(str_replace("/","-",$data["date"])) ), "or_supplier"=>$data["supplier"], "or_reference"=>$data["reference"], "or_vat_type"=>$vat[0]['vat_type'], "or_vat_rate"=>$vat[0]['vat_percent'], "or_state"=>"Completed", "or_type"=>"Standard"]);
            $id = $db->id();

            foreach ($dar as $order) {

                $model = $order['model'];
                $cost = $order['cost'];
                $serial = $order['serial'];
                $currentDate = date("Y-m-d");
                $prods = $db->query("SELECT pr_id from  products where pr_name='$model' limit 0,1")->fetchAll();

                if (count($prods) == 1) {
                    $vatAmount = $cost*($vat[0]['vat_percent']/(100+$vat[0]['vat_percent']));
                    $netPrice = $cost - $vatAmount;
                    $pr_id = $prods[0]["pr_id"];
                    $oritem = $db->query("SELECT * from orderprod where op_order=".$id." and op_product=".$pr_id)->fetchAll();

                    if (count($oritem) == 1){
                        $db->exec("UPDATE orderprod SET op_quantity = op_quantity + 1, op_delivered = op_delivered + 1 where op_order='".$id."' AND op_product='".$pr_id."';");
                    } else {
                        $db->exec("INSERT INTO orderprod SET op_order=".$id.", op_product=".$pr_id.", op_quantity=1, op_delivered=1;");
                    }

                    $db->exec("INSERT INTO stock SET st_status=1, st_order=".$id.", st_product=".$pr_id.", st_servicetag='".$serial."', st_cost=".$cost.", st_vat_type='".$vat[0]['vat_type']."', st_vat_rate=".$vat[0]['vat_percent'].", st_vat_amount=".$vatAmount.", st_netprice=".$netPrice.", st_date='".$currentDate."', st_addedby = '".$_SESSION["user_id"]."';");
                
                }
            }            

            $db->exec("update orders inner join (select op_order , sum(op_quantity) c, sum(op_delivered) cd  from orderprod group by op_order ) op on op.op_order = orders.or_id set orders.or_total_delivered=op.cd, orders.or_total_items=op.c where orders.or_id=op.op_order and orders.or_id=$id");

            unset($_SESSION[$token]);
        
            
            http_response_code(200);
            echo json_encode('{ "success" : true , "id":'.$id.', "p": '.$mi.'}');
            exit;
        }


        if($id==0) {
            $db->insert("orders",["or_date"=> date("Y-m-d", strtotime(str_replace("/","-",$data["date"])) ), "or_supplier"=>$data["supplier"], "or_reference"=>$data["reference"], "or_vat_type"=>$vat[0]['vat_type'], "or_vat_rate"=>$vat[0]['vat_percent'], "or_state"=>"Awaiting", "or_type"=>"Standard"]);
            $id = $db->id();
            
        }
        else {
            $db->exec("update orders set or_date=:or_date, or_supplier=:or_supplier, or_reference=:or_reference, or_vat_type=:or_vat_type, or_vat_rate=:or_vat_rate where or_id=:id",
                [
                    ":or_date" => [date("Y-m-d", strtotime(str_replace("/","-",$data["date"])) ), PDO::PARAM_STR]  ,
                    ":or_supplier" => [ $data["supplier"], PDO::PARAM_INT ]  ,
                    ":or_reference" => [ $data["reference"], PDO::PARAM_STR ]  ,
                    ":or_vat_type" => [ $vat[0]['vat_type'], PDO::PARAM_STR ]  ,
                    ":or_vat_rate" => [ $vat[0]['vat_percent'], PDO::PARAM_INT ]  ,
                    ":id"=> [ $id, PDO::PARAM_INT ]
                ]
            );
        }

        foreach($tokened as $key=>$value) {
            $pr = intval($value["pr_id"]);
            $qa = intval($value["op_quantity"]);  
            $mi++;
            $rec = $db->get("orderprod",["op_quantity"],["op_order"=>$id, "op_product"=>$pr]);
            if($rec!=null) {
                $db->exec("update orderprod set op_quantity=op_quantity+".$qa." where op_product=".$pr." and op_order=".$id);
            }
            else {                            
                $db->insert("orderprod",[
                    "op_order" => $id,
                    "op_product" => $pr,
                    "op_quantity" => $qa,
                    "op_delivered" => 0
                ]);
            }
        }

        unset($_SESSION[$token]);
        
        $db->exec("update orders inner join (select op_order ,sum(op_quantity) c from orderprod group by op_order ) op on op.op_order = orders.or_id set orders.or_total_items=op.c where orders.or_id=op.op_order and orders.or_id=$id");

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
    $ord = $db->get("orders","*",["or_id"=>$id]);

    $query="SELECT pr_id,pr_name,pr_title,mf_name,ct_name, op_quantity FROM orderprod left join  products on op_product=pr_id left join manufacturers on  pr_manufacturer=mf_id left join categories on pr_category=ct_id WHERE   op_order=$id";
    $data = $db->query($query)->fetchAll();                        
    foreach($data as $k=>$v) {
        
        echo "<tr><td>".$v["pr_id"]."</td><td>".$v["pr_name"]."</td><td>".$v["pr_title"]."</td><td>".$v["mf_name"]."</td><td>".$v["ct_name"]."</td><td>".$v["op_quantity"]."</td>";        
        echo "<th></th>";
        echo "</tr>";

    }
    if(isset($_SESSION[$token])) {
        $tokened = $_SESSION[$token];

        if(is_array($tokened)) {
            foreach($tokened as $k=>$v){

                echo "<tr><td>".$v["pr_id"]."</td><td>".$v["pr_name"]."</td><td>".$v["pr_title"]."</td><td>".$v["mf_name"]."</td><td>".$v["ct_name"]."</td><td>".$v["op_quantity"]."</td>";        
                echo "<th style='width:50px;'><button class='btn btn-sm btn-warning' onClick='RemoveItem(".$v["pr_id"].")'>Remove</button></th>";
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
            if($value["pr_id"] == $pr) {       
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
        $pr = intval($data["pr"]);
        $qa = intval($data["qa"]);
        $id = intval($data["id"]);
        $i=0;
        $tokened = array();
        $tt=0;
        if(isset($_SESSION[$token])) {
            $tokened=$_SESSION[$token];
        }


        $leave=false;
        
        foreach($tokened as $key=>&$value) {
            if($value["pr_id"] == $pr) {
                $value["op_quantity"] = $value["op_quantity"]  + $qa;
                $leave=true;                
                $i++;
                $tt=5;
                break;
            }
        }

        if(!$leave) {
            $query="SELECT pr_id,pr_name,pr_title,mf_name,ct_name FROM products left join manufacturers on  pr_manufacturer=mf_id left join categories on pr_category=ct_id WHERE pr_id=$pr";
            $pro = $db->query($query)->fetchAll();

            array_push($tokened, array( 
                "pr_id"=>$pr,  
                "pr_name"=> $pro[0]["pr_name"],
                "pr_title"=>$pro[0]["pr_title"],
                "mf_name" =>$pro[0]["mf_name"],
                "ct_name" => $pro[0]["ct_name"],
                "op_quantity" => $qa
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

    $order_field = "or_date desc";
    

    if(!empty($_POST["order"])) {
        $order_field =  $_POST['columns'][$_POST['order']['0']['column']]['data'] ." ". strtoupper( $_POST['order']['0']['dir']  );
    }

    $numRows = 0;
    $numRowsTotal = 0;
    $_SF =" or_type='Standard'";
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
            purple+red+lightblue+actionreq+actioncmp+brown+lightgreen as st_combined, orange as st_orange, darkgreen as st_darkgreen, sold+gray as st_sold , black+stripped as st_black, CONCAT(darkgreen+sold+gray, ' out of ', or_total_items, '/ ' , TRUNCATE((((darkgreen+sold+gray)*100) / or_total_items),0),'%') as st_fix_rate, CONCAT(TRUNCATE(((sold*100) / or_total_items),0),'%') as st_sell_through_rate, vat_label 
        FROM order_distribution left join suppliers on or_supplier=sp_id left join vat_rates on vat_type = or_vat_type AND vat_percent = or_vat_rate WHERE  $_SF
        order by $order_field 
        limit $start,$length";

//echo $query;
    $query_count = "select count(*) as c FROM order_distribution left join suppliers on or_supplier=sp_id left join vat_rates on vat_type = or_vat_type AND vat_percent = or_vat_rate WHERE  $_SFC ";
    $query_count2 = "select count(*) as c FROM order_distribution left join suppliers on or_supplier=sp_id left join vat_rates on vat_type = or_vat_type AND vat_percent = or_vat_rate WHERE $_SF ";

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

