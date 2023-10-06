<?php

$db = M::db();

if($action =="deleteOrder") {
    header('Content-Type: application/json; charset=utf-8');
    try
    {
        $data = $_POST["data"];
        $id = intval($data["id"]);
            $db->exec("delete from dco_orders where dor_id=$id and dor_state='Awaiting'");
            http_response_code(200);
            echo json_encode('{ "success" : true  }');

    }
    catch  (Exception $e) {
        http_response_code(500);
        echo json_encode('{ "success" : false, "error": "'. $e->getMessage().'" }');
    }
}

if($action =="reopenorder") {
    header('Content-Type: application/json; charset=utf-8');
    try
    {
        $data = $_POST["data"];
        $id = intval($data["id"]);

        $currentDate = date("Y-m-d");

        $db->exec("update dco_orders inner join (select dop_order , sum(dop_quantity) c, sum(dop_delivered) cd  from dco_orderprod group by dop_order ) op on op.dop_order = dco_orders.dor_id set dco_orders.dor_total_delivered=op.cd, dco_orders.dor_total_items=op.c where dco_orders.dor_id=op.dop_order and dco_orders.dor_id=$id");
        $db->exec("update dco_orders set dor_state ='Awaiting' where dor_id=$id ");
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

         $c = $db->query("select * from dco_orderprod where (dop_sn='' and dop_delivered=dop_quantity) and  dop_order=".$id)->fetchAll();

        if (count($c)==0)
        {

            $currentDate = date("Y-m-d");
            $c = $db->query("select * from dco_orderprod where dop_sn!='' and  dop_order=".$id)->fetchAll();
            foreach($c as $v) {

                $qr = $db->query("select * from dco_stock where dst_order=".$id." and dst_servicetag='".$v["dop_sn"]."'")->fetchAll();
                if(count($qr) == 0)
                {
                    $db->exec("INSERT INTO dco_stock SET dst_status=1, dst_order=".$id.", dst_product=".$v["dop_product"].", dst_servicetag='".$v["dop_sn"]."', dst_date='".$currentDate."', dst_addedby = '".$_SESSION["user_id"]."';");
                    $db->exec("update dco_orderprod set dop_delivered=1 where dop_sn='".$v["dop_sn"]."' AND dop_order=".$id );
                }
            }
            $db->exec("update dco_orders inner join (select dop_order , sum(dop_quantity) c, sum(dop_delivered) cd  from dco_orderprod group by dop_order ) op on op.dop_order = dco_orders.dor_id set dco_orders.dor_total_delivered=op.cd, dco_orders.dor_total_items=op.c where dco_orders.dor_id=op.dop_order and dco_orders.dor_id=$id");
            $db->exec("update dco_orders set dor_state ='Completed' where dor_id=$id and dor_total_items=dor_total_delivered");
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
        $db->exec("insert into dco_products_map (dpm_pn,dpm_aproducts_id) values('$mappedvm', '$mappedv')");
        $data = $db->query("SELECT dp_name,dp_sku FROM dco_products_map join dell_part on dell_part.dp_id = dpm_aproducts_id where dpm_pn=:pn",[":pn"=>$mappedvm])->fetchAll();
        $db->exec("update dco_orderprod set dop_product='$mappedv' where dop_order='$id'  and SUBSTR(dop_sn,4,5) = '$mappedvm'");

        $db->exec("update dco_stock set dst_product='$mappedv' where dst_product=0 AND SUBSTR(dst_servicetag,4,5) = '$mappedvm'");

        if (count($data) == 1)
        {
            $d = $data[0]["dp_name"]. " ".$data[0]["dp_sku"];
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

        $db->exec("DELETE from dco_orderprod where dop_order='$id' and dop_sn='$serial'");
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

        $result = $db->query("SELECT dop_sn FROM dco_orderprod where dop_order='$id' and dop_sn='$serial'")->fetchAll();
        $result2 = $db->query("SELECT dst_servicetag FROM dco_stock where dst_servicetag='$serial'")->fetchAll();

        if(count($result) > 0  ||  count($result2) > 0) {



           echo json_encode('{ "success" : false, "error": "Serial Number Already in Database" }');

            die();
        }


        $data = $db->query("SELECT  dp_id, dp_name, dp_sku FROM dell_part where dp_mpn=:pn LIMIT 1",[":pn"=>$pn])->fetchAll();

        if (count($data) == 1){
            $d = $data[0]["dp_name"]. " ".$data[0]["dp_sku"];
            $tokened2[$serial] = $data[0]["dp_id"];
            http_response_code(200);
            echo json_encode([ "success" => true , "description" => $d], );
        }
        else {
            $tokened2[$serial] = 0;
            http_response_code(200);
            echo json_encode('{ "success" : true, "description": "<b>No Product Assigned </b>"  }');
        }
        $_SESSION[$token2] = $tokened2;
        $db->exec("update dco_orders inner join (select dop_order , sum(dop_quantity) c, sum(dop_delivered) cd  from dco_orderprod group by dop_order ) op on op.dop_order = dco_orders.dor_id set dco_orders.dor_total_delivered=op.cd, dco_orders.dor_total_items=op.c where dco_orders.dor_id=op.dop_order and dco_orders.dor_id=$id");
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

                $oritem = $db->query("SELECT * from dco_orderprod where dop_order=".$id." and dop_product=".$pr_id)->fetchAll();

                if (count($oritem) == 1){
                    $db->exec("UPDATE dco_orderprod SET dop_delivered = dop_delivered + 1 where dop_order='".$id."' AND dop_product='".$pr_id."';");
                }

                $db->exec("INSERT INTO dco_stock SET dst_status=1, dst_order=".$id.", dst_product=".$pr_id.", dst_servicetag='".$serial."', dst_date='".$currentDate."', dst_addedby = '".$_SESSION["user_id"]."';");

            }

        }

        $db->exec("update dco_orders inner join (select dop_order , sum(dop_quantity) c, sum(dop_delivered) cd  from dco_orderprod group by dop_order ) op on op.dop_order = dco_orders.dor_id set dco_orders.dor_total_delivered=op.cd, dco_orders.dor_total_items=op.c where dco_orders.dor_id=op.dop_order and dco_orders.dor_id=$id");
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
    $input_array = $db->query("SELECT * FROM dco_stock WHERE dst_product=".$pr_id." AND dst_order=".$id)->fetchAll();
    foreach($input_array as $ki=>$vi) {
        array_push($inputhtml ,[ "serial" => $vi["dst_servicetag"], "cost"=> 0 ]);
    }

    $result = $db->query("select dop_quantity from dco_orderprod where dop_product=".$pr_id." and dop_order=".$id."")->fetchAll();
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
        "remain" => intval($result[0]["dop_quantity"]) - count($inputhtml),
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



        $serial_count = $db->count("dco_stock",["dst_servicetag" => $serial]);

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




if($action == "addsupplier") {


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

            $db->insert("suppliers",["sp_name"=>$data["name"],"sp_contact"=> $data["contact"], "sp_del"=>'0', "sp_email"=>$data["email"]]);
            $id = $db->id();
            if($data['groups']){
              //$feedback = $data['groups']);
              for($i = 0;$i<count($data['groups']);$i++){
                $db->insert("supplier_groups_new",["sg_supplier_id"=>$id,"g_id"=> $data["groups"][$i]]);
              }
            //  var_dump($data['groups']);
            }

        }
        else {
            $db->exec("update suppliers set sp_name='".$data["name"]."', sp_contact='".$data["contact"]."', sp_email='".$data["email"]."' where sp_id=".$id);
            $db->exec("delete from supplier_groups_new where sg_supplier_id=$id");
            for($i = 0;$i<count($data['groups']);$i++){
              $db->insert("supplier_groups_new",["sg_supplier_id"=>$id,"g_id"=> $data["groups"][$i]]);
            }

        }



        unset($_SESSION[$token]);

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
    $ord = $db->get("dco_orders","*",["dor_id"=>$id]);


    $query="select dp_name, dp_sku, dp_condition, dop_quantity from dell_part, dco_orderprod  where dop_sn='' and dp_id = dop_product and dop_order=$id";
    $data = $db->query($query)->fetchAll();
    foreach($data as $v) {

        echo "<tr><td>".$v["dp_name"]." ((".$v["dp_sku"].")</td><td>".$v["dop_quantity"]."</td>";
        echo "<th></th>";
        echo "</tr>";

    }

    if(isset($_SESSION[$token])) {
        $tokened = $_SESSION[$token];

        if(is_array($tokened)) {
            foreach($tokened as $k=>$v){


                $q = "select dp_name, dp_sku, dp_condition from dell_part where dp_id = ".intval($v["pn"]);
                $se = $db->query($q)->fetchAll()[0];
                echo "<tr><td>".$se["dp_sku"]."</td><td>".$se["dp_name"]."</td><td>".$se["dp_condition"]."</td><td>".$v["qa"]."</td>";
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

    $order_field = $_POST['columns']['0']['data'];


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

      $_SF.= " AND ( sp_name like :search OR sp_email like :search OR sp_contact like :search OR sp_id = :search_no )";
      $_PARA[":search"]   = "%".$SEARCH."%";
      $_PARA[":search_no"]   = $SEARCH;

    }

//SELECT suppliers.sp_id,suppliers.sp_name,suppliers.sp_contact,suppliers.sp_email,groups.gs_name FROM suppliers LEFT JOIN supplier_groups ON suppliers.sp_id = supplier_groups.sg_supplier_id LEFT JOIN groups ON supplier_groups.g_id = groups.gs_id ORDER BY sp_id
    //$query="SELECT suppliers.sp_id,suppliers.sp_name,suppliers.sp_contact,suppliers.sp_email,groups.gs_name FROM suppliers LEFT JOIN supplier_groups_new ON suppliers.sp_id = supplier_groups_new.sg_supplier_id LEFT JOIN groups ON supplier_groups_new.g_id = groups.gs_id ORDER BY sp_id
  //  limit $start,$length";

    $query="SELECT * from suppliers  WHERE  $_SF  ORDER BY $order_field limit $start,$length";
    $data = $db->query($query,$_PARA)->fetchAll();

    $finalData = array();
    $item = array();
    $cnt = 1;
    foreach($data as $d){

      $item['sp_id'] = $d['sp_id'];
      $item['sp_name'] = $d['sp_name'];
      $item['sp_del'] = $d['sp_del'];
      $item['sp_contact'] = $d['sp_contact'];
      $item['sp_email'] = $d['sp_email'];
      $group= "";
      $query1="SELECT * from supplier_groups_new inner join groups where supplier_groups_new.g_id=groups.gs_id and supplier_groups_new.sg_supplier_id=".$d['sp_id'];
      $data1 = $db->query($query1,$_PARA)->fetchAll();
      foreach($data1 as $d1){
        $group.= $d1['gs_name'].",";
      }
      $group = rtrim($group,',');
      $item['gs_name'] = $group;
      $finalData[]=$item;
      $cnt++;
    }

    $data = array("data"=>$finalData);
    $query_count = "SELECT count(*) as c FROM suppliers";

    $query_count2 = "SELECT count(*) as c FROM suppliers";

    //echo  $db->debug()->query($query,$_PARA);

    //$data = $db->query($query,$_PARA)->fetchAll();
    $numRowsTotal = $db->query($query_count2,$_PARA)->fetchAll();
    $numRows = $db->query($query_count)->fetchAll();


    $output = array(
		"draw"	=>	intval($_POST["draw"]),
		"iTotalRecords"	=> 	$numRows[0]["c"],
		"iTotalDisplayRecords"	=>  $numRowsTotal[0]["c"],
		"data"	=> 	$finalData,
        "t" => "r0"
	);

    echo json_encode($output);

}
else {
    exit();
}
