<?php
include(PATH_CONFIG."/constants.php");

$db = M::db();

if($action =="getinfo"){

    $data = $_POST["data"];
    $serial = $data["serial"];

    $query = "SELECT sales_orders.order_number, sales_orders.sales_channel, sales_orders.recipient, sales_orders.order_date, sales_orders.order_total, sales_requested_products.requested_sku, sales_requested_products.requested_product FROM sales_orderserials LEFT JOIN sales_orderitems ON sales_orderserials.sales_orderitems_id = sales_orderitems.id LEFT JOIN sales_orders ON sales_orderitems.sales_order_id = sales_orders.id  LEFT JOIN sales_requested_products ON sales_requested_products.sales_order_id = sales_orders.id WHERE sales_orderserials.serial_number = '" . $serial . "'";

    $data = $db->query($query)->fetchAll();

    $success = false;
    $supplierid = 0;
    $itemStatus = 0;
    $producttype = 0;
    $productid = 0;
    $sku = null;
    $name = null;

    $nwpSerialSql = "SELECT nst_product, nst_order, nst_status FROM nwp_stock WHERE nst_servicetag = '" . $serial . "'";
    if($nwpSerialData = $db->query($nwpSerialSql)->fetchAll()){
        $success = true;
        $itemStatus = $nwpSerialData[0]['nst_status'];
        $producttype = 1;
        $productid = $nwpSerialData[0]['nst_product'];
        $nwpOrderSql = "SELECT nor_supplier FROM nwp_orders WHERE nor_id = " . $nwpSerialData[0]['nst_order'];
        $nwpOrderData = $db->query($nwpOrderSql)->fetchAll();
        $supplierid = $nwpOrderData[0]['nor_supplier'];
        $nwpProductSql = "SELECT npr_sku, npr_name FROM nwp_products WHERE npr_id = " . $nwpSerialData[0]['nst_product'];
        $nwpProductData = $db->query($nwpProductSql)->fetchAll();
        $sku = $nwpProductData[0]['npr_sku'];
        $name = $nwpProductData[0]['npr_name'];
    } else {
        $accSerialSql = "SELECT ast_product, ast_order, ast_status FROM acc_stock WHERE ast_servicetag = '" . $serial . "'";
        if($accSerialData = $db->query($accSerialSql)->fetchAll()){
            $success = true;
            $producttype = 3;
            $productid = $accSerialData[0]['ast_product'];
            $itemStatus = $accSerialData[0]['ast_status'];
            $accOrderSql = "SELECT aor_supplier FROM acc_orders WHERE aor_id = " . $accSerialData[0]['ast_order'];
            $accOrderData = $db->query($accOrderSql)->fetchAll();
            $supplierid = $accOrderData[0]['aor_supplier'];
            $accProductSql = "SELECT apr_sku, apr_name FROM aproducts WHERE apr_id = " . $accSerialData[0]['ast_product'];
            $accProductData = $db->query($accProductSql)->fetchAll();
            $sku = $accProductData[0]['apr_sku'];
            $name = $accProductData[0]['apr_name'];
        } else {
            $dcoSerialSql = "SELECT dst_product, dst_order, dst_status FROM dco_stock WHERE dst_servicetag = '" . $serial . "'";
            if($dcoSerialData = $db->query($dcoSerialSql)->fetchAll()){
                $success = true;
                $producttype = 4;
                $productid = $dcoSerialData[0]['dst_product'];
                $itemStatus = $dcoSerialData[0]['dst_status'];
                $dcoOrderSql = "SELECT dor_supplier FROM dco_orders WHERE dor_id = " . $dcoSerialData[0]['dst_order'];
                $dcoOrderData = $db->query($dcoOrderSql)->fetchAll();
                $supplierid = $dcoOrderData[0]['dor_supplier'];
                $dcoProductSql = "SELECT dp_sku, dp_name FROM dell_part WHERE dp_id = " . $dcoSerialData[0]['dst_product'];
                $dcoProductData = $db->query($dcoProductSql)->fetchAll();
                $sku = $dcoProductData[0]['dp_sku'];
                $name = $dcoProductData[0]['dp_name'];
            }
        }
    }
    if ($success){        
        $output= array(
            "success"=>true,
            "serial"=>$serial, 
            "data" => $data[0],
            "status" => $itemStatus,
            "producttype" => $producttype,
            "productid" => $productid,
            "sku" => $sku,
            "name" => $name,
            "supplier" => $supplierid
        );

        header('Content-Type: application/json; charset=utf-8');
        http_response_code(200);
        echo json_encode(utf8ize($output));
    } else {
        $output= array(
            "success"=>false,
            "serial"=>$serial, 
            "data" => null,
            "status" => 0,
            "producttype" => 0,
            "productid" => 0,
            "sku" => null,
            "name" => null,
            "supplier" => 0
        );
        header('Content-Type: application/json; charset=utf-8');
        http_response_code(200);
        echo json_encode(utf8ize($output));
    }
    exit();
} 

if($action=="get_orderno_suggestion") {
    $ordernoQuery = "SELECT id, order_number FROM sales_orders WHERE order_number LIKE '%" .$_POST["term"]. "%' LIMIT 30";
    $ordernoData = $db->query($ordernoQuery)->fetchAll();
    echo json_encode($ordernoData);
} 

if($action=="get_order_details") {
    $ordernoQuery = "SELECT id, sales_channel, recipient, order_date, order_total FROM sales_orders WHERE id = " .$_POST["id"];
    $orderdata = $db->query($ordernoQuery)->fetchAll();
    $orderItemsSql = "SELECT sku FROM sales_orderitems WHERE sales_order_id = " . $orderdata[0]['id'];
    $orderItemsData = $db->query($orderItemsSql)->fetchAll();

    $partData = array();
    $index = 0;
    foreach ($orderItemsData as $entry) {
        $nprPartQuery = "SELECT npr_id, npr_name, npr_sku FROM nwp_products WHERE npr_box_label = '" . $entry['sku'] . "'";
        if ($nprPartData = $db->query($nprPartQuery)->fetchAll()) {
            $nprSupplierQuery = "SELECT nwp_orders.nor_supplier, suppliers.sp_name FROM nwp_stock LEFT JOIN nwp_orders ON nwp_stock.nst_order = nwp_orders.nor_id LEFT JOIN suppliers ON nwp_orders.nor_supplier = suppliers.sp_id WHERE nwp_stock.nst_product = " . $nprPartData[0]['npr_id'] . "  GROUP BY nwp_orders.nor_supplier";
            $nprSupplierData = $db->query($nprSupplierQuery)->fetchAll();

            $partData[$index] = ["prodtype" => 1, "prodid" => $nprPartData[0]['npr_id'], "sku" => $nprPartData[0]['npr_sku'], "name" => $nprPartData[0]['npr_name'], "suppliers" => $nprSupplierData];
            $index++;
        } else {
            $npr2PartQuery = "SELECT npr2_id, npr2_name, npr2_sku FROM nwp_products2 WHERE npr2_box_label = '" . $entry['sku'] . "'";
            if ($npr2PartData = $db->query($npr2PartQuery)->fetchAll()) {
                $partData[$index] = ["prodtype" => 2, "prodid" => $npr2PartData[0]['npr2_id'], "sku" => $npr2PartData[0]['npr2_sku'], "name" => $npr2PartData[0]['npr2_name'], "suppliers" => null];
                $index++;
            } else {
                $aprPartQuery = "SELECT apr_id, apr_name, apr_sku FROM aproducts WHERE apr_box_label = '" . $entry['sku'] . "'";
                if ($aprPartData = $db->query($aprPartQuery)->fetchAll()) {
                    $aprSupplierQuery = "SELECT acc_orders.aor_supplier, suppliers.sp_name FROM acc_stock LEFT JOIN acc_orders ON acc_stock.ast_order = acc_orders.aor_id LEFT JOIN suppliers ON acc_orders.aor_supplier = suppliers.sp_id WHERE acc_stock.ast_product = " . $aprPartData[0]['apr_id'] . " GROUP BY acc_orders.aor_supplier";
                    $aprSupplierData = $db->query($aprSupplierQuery)->fetchAll();
                    $partData[$index] = ["prodtype" => 3, "prodid" => $aprPartData[0]['apr_id'], "sku" => $aprPartData[0]['apr_sku'], "name" => $aprPartData[0]['apr_name'], "suppliers" => $aprSupplierData];
                    $index++;
                } else {
                    $dpPartQuery = "SELECT dp_id, dp_name, dp_sku FROM dell_part WHERE dp_box_label = '" . $entry['sku'] . "'";
                    if ($dpPartData = $db->query($dpPartQuery)->fetchAll()) {
                        $dpSupplierQuery = "SELECT dco_orders.dor_supplier, suppliers.sp_name FROM dco_stock LEFT JOIN dco_orders ON dco_stock.dst_order = dco_orders.dor_id LEFT JOIN suppliers ON dco_orders.dor_supplier = suppliers.sp_id WHERE dco_stock.dst_product = " . $dpPartData[0]['dp_id'] . " GROUP BY dco_orders.dor_supplier";
                        $dpSupplierData = $db->query($dpSupplierQuery)->fetchAll();
                        $partData[$index] = ["prodtype" => 4, "prodid" => $dpPartData[0]['dp_id'], "sku" => $dpPartData[0]['dp_sku'], "name" => $dpPartData[0]['dp_name'], "suppliers" => $dpSupplierData];
                        $index++;
                    }
                }
            }
        }
    }

    $output = ['success' => true, 'orderdata' => $orderdata[0], 'partdata' => $partData];
    header('Content-Type: application/json; charset=utf-8');
    http_response_code(200);
    echo json_encode(utf8ize($output));
} 

if($action == "creatermac" || $action == "updatermac" || $action == "createreturnrmac") {
    $supplierid = null;
    $supplierdate = null;
    $supplierordernumber = null;
    $supplierref = null;
    if($_POST["scan_move"] != null){
        $nwpSerialSql = "SELECT nst_order FROM nwp_stock WHERE nst_servicetag = '" . $_POST["scan_move"] . "'";
        if($nwpSerialData = $db->query($nwpSerialSql)->fetchAll()){
            $supplierordernumber = $nwpSerialData[0]['nst_order'];
            $nwpOrderSql = "SELECT nor_supplier, rfqorderid FROM nwp_orders WHERE nor_id = " . $nwpSerialData[0]['nst_order'];
            $nwpOrderData = $db->query($nwpOrderSql)->fetchAll();
            $supplierid = $nwpOrderData[0]['nor_supplier'];

            $nwpRfqOrderSql = "SELECT rfqo_date, rfqo_reference FROM rfq_orders WHERE rfqo_id = " . $nwpOrderData[0]['rfqorderid'];
            $nwpRfqOrderData = $db->query($nwpRfqOrderSql)->fetchAll();
            if ($nwpRfqOrderData) {
                $supplierdate = $nwpRfqOrderData[0]['rfqo_date'];
                $supplierref = $nwpRfqOrderData[0]['rfqo_reference'];
            }
        } else {
            $accSerialSql = "SELECT ast_order FROM acc_stock WHERE ast_servicetag = '" . $_POST["scan_move"] . "'";
            if($accSerialData = $db->query($accSerialSql)->fetchAll()){
                $supplierordernumber = $accSerialData[0]['ast_order'];
                $accOrderSql = "SELECT aor_supplier, rfqorderid FROM acc_orders WHERE aor_id = " . $accSerialData[0]['ast_order'];
                $accOrderData = $db->query($accOrderSql)->fetchAll();
                $supplierid = $accOrderData[0]['aor_supplier'];

                $accRfqOrderSql = "SELECT rfqo_date, rfqo_reference FROM rfq_orders WHERE rfqo_id = " . $accOrderData[0]['rfqorderid'];
                $accRfqOrderData = $db->query($accRfqOrderSql)->fetchAll();
                if ($accRfqOrderData) {
                    $supplierdate = $accRfqOrderData[0]['rfqo_date'];
                    $supplierref = $accRfqOrderData[0]['rfqo_reference'];
                }
            } else {
                $dcoSerialSql = "SELECT dst_order FROM dco_stock WHERE dst_servicetag = '" . $_POST["scan_move"] . "'";
                if($dcoSerialData = $db->query($dcoSerialSql)->fetchAll()){
                    $supplierordernumber = $dcoSerialData[0]['dst_order'];
                    $dcoOrderSql = "SELECT dor_supplier, rfqorderid FROM dco_orders WHERE dor_id = " . $dcoSerialData[0]['dst_order'];
                    $dcoOrderData = $db->query($dcoOrderSql)->fetchAll();
                    $supplierid = $dcoOrderData[0]['dor_supplier'];

                    $dcoRfqOrderSql = "SELECT rfqo_date, rfqo_reference FROM rfq_orders WHERE rfqo_id = " . $dcoOrderData[0]['rfqorderid'];
                    $dcoRfqOrderData = $db->query($dcoRfqOrderSql)->fetchAll();
                    if ($dcoRfqOrderData) {
                        $supplierdate = $dcoRfqOrderData[0]['rfqo_date'];
                        $supplierref = $dcoRfqOrderData[0]['rfqo_reference'];
                    }
                }
            }
        }
    } else{
        $supplierid = $_POST["rmac_supplier"];
    }

    if ($action == "creatermac"){
        try {
            $product = explode ("-", $_POST["product-hidden"]);
            $db->insert("rmac_items", [
                "rmac_servicetag"=> $_POST["scan_move"],
                "rmac_sku" => $_POST["product-sku"],
                "rmac_product" => $_POST["product-text"],
                "rmac_productID" => $product[1],
                "rmac_prodType" =>  $product[0],
                "rmac_price" => isset($_POST["productprice"]) ? $_POST["productprice"] : null,
                "rmac_purchasedon" => isset($_POST["purchasedon"]) ? $_POST["purchasedon"] : null,
                "rmac_fullname" => $_POST["fullname"],
                "rmac_purchasedate" => isset($_POST["purchasedate"]) ? $_POST["purchasedate"] : null,
                "rmac_ordernumber" => isset($_POST["orderno"]) ? $_POST["orderno"] : null,
                "rmac_fault" => $_POST["fault"],
                "rmac_isours" => isset($_POST["itemisours"]) && $_POST["itemisours"]=="on" ? 1 : 0,
                "rmac_iscomplete" => isset($_POST["itemiscomplete"]) && $_POST["itemiscomplete"]=="on" ? 1 : 0,
                "rmac_isundamaged" => isset($_POST["itemisundamaged"]) && $_POST["itemisundamaged"]=="on" ? 1 : 0,
                "rmac_supplier" => $supplierid,
                "rmac_supplierdate" => $supplierdate,
                "rmac_supplierordernumber" => $supplierordernumber,
                "rmac_supplierref" => $supplierref,
                "rmac_user" => $_SESSION["user_id"],
                "rmac_lastcomment" => "New return created on ". date("Y-m-d"),
                "rmac_datecreated" => date("Y-m-d"),
                "rmac_status" => 1,
                "is_internal" => $_POST["isInternal"]=="true" ? 1 : 0
            ]);
            $rmac_ID = $db->id();
            $db->exec("INSERT INTO rmac_items_history SET rmac_date=NOW(), rmac_user=".$_SESSION["user_id"].", rmac_status=(select rmac_status from rmac_items where rmac_id=$rmac_ID), rmac_comment=:cm, rmac_ID=:id", [":cm"=> ["New return created on ". date("Y-m-d"), PDO::PARAM_STR], ":id"=>[intval($rmac_ID), PDO::PARAM_INT]]);

            http_response_code(200);
            echo json_encode('{ "success" : true , "id": '.$rmac_ID.'}');
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode('{ "success" : false, "error": "'. $e->getMessage().'" }');
        }
    } else if ($action == "updatermac"){
        try {
            $rmac_ID = $_POST["id"];
            $product = explode ("-", $_POST["product-hidden"]);
            $db->update("rmac_items", [
                "rmac_servicetag"=> $_POST["scan_move"],
                "rmac_sku" => $_POST["product-sku"],
                "rmac_product" => $_POST["product-text"],
                "rmac_productID" => $product[1],
                "rmac_prodType" =>  $product[0],
                "rmac_price" => isset($_POST["productprice"]) ? $_POST["productprice"] : null,
                "rmac_purchasedon" => isset($_POST["purchasedon"]) ? $_POST["purchasedon"] : null,
                "rmac_fullname" => $_POST["fullname"],
                "rmac_purchasedate" => isset($_POST["purchasedate"]) ? $_POST["purchasedate"] : null,
                "rmac_ordernumber" => isset($_POST["orderno"]) ? $_POST["orderno"] : null,
                "rmac_fault" => $_POST["fault"],
                "rmac_isours" => isset($_POST["itemisours"]) && $_POST["itemisours"]=="on" ? 1 : 0,
                "rmac_iscomplete" => isset($_POST["itemiscomplete"]) && $_POST["itemiscomplete"]=="on" ? 1 : 0,
                "rmac_isundamaged" => isset($_POST["itemisundamaged"]) && $_POST["itemisundamaged"]=="on" ? 1 : 0,
                "rmac_supplier" => $supplierid,
                "rmac_supplierdate" => $supplierdate,
                "rmac_supplierordernumber" => $supplierordernumber,
                "rmac_supplierref" => $supplierref,
                "rmac_user" => $_SESSION["user_id"],
                "rmac_lastcomment" => "Return updated on ". date("Y-m-d"),
                "rmac_datecreated" => date("Y-m-d"),
                "rmac_status" => 1,
                "is_internal" => $_POST["isInternal"]=="true" ? 1 : 0
            ],["rmac_ID" => $rmac_ID]);

            $db->exec("INSERT INTO rmac_items_history SET rmac_date=NOW(), rmac_user=".$_SESSION["user_id"].", rmac_status=(select rmac_status from rmac_items where rmac_id=$rmac_ID), rmac_comment=:cm, rmac_ID=:id", [":cm"=> ["Return updated on ". date("Y-m-d"), PDO::PARAM_STR], ":id"=>[intval($rmac_ID), PDO::PARAM_INT]]);

            http_response_code(200);
            echo json_encode('{ "success" : true, "id": '.$rmac_ID.'}');
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode('{ "success" : false, "error": "'. $e->getMessage().'" }');
        }
    } else if ($action == "createreturnrmac"){
        try {
            $product = explode ("-", $_POST["product-hidden"]);
            $db->insert("rmac_items", [
                "rmac_servicetag"=> $_POST["scan_move"],
                "rmac_sku" => $_POST["product-sku"],
                "rmac_product" => $_POST["product-text"],
                "rmac_productID" => $product[1],
                "rmac_prodType" =>  $product[0],
                "rmac_price" => isset($_POST["productprice"]) ? $_POST["productprice"] : null,
                "rmac_purchasedon" => isset($_POST["purchasedon"]) ? $_POST["purchasedon"] : null,
                "rmac_fullname" => $_POST["fullname"],
                "rmac_purchasedate" => isset($_POST["purchasedate"]) ? $_POST["purchasedate"] : null,
                "rmac_ordernumber" => isset($_POST["orderno"]) ? $_POST["orderno"] : null,
                "rmac_fault" => $_POST["fault"],
                "rmac_isours" => isset($_POST["itemisours"]) && $_POST["itemisours"]=="on" ? 1 : 0,
                "rmac_iscomplete" => isset($_POST["itemiscomplete"]) && $_POST["itemiscomplete"]=="on" ? 1 : 0,
                "rmac_isundamaged" => isset($_POST["itemisundamaged"]) && $_POST["itemisundamaged"]=="on" ? 1 : 0,
                "rmac_supplier" => $supplierid,
                "rmac_supplierdate" => $supplierdate,
                "rmac_supplierordernumber" => $supplierordernumber,
                "rmac_supplierref" => $supplierref,
                "rmac_user" => $_SESSION["user_id"],
                "rmac_lastcomment" => "New return created on ". date("Y-m-d"),
                "rmac_datecreated" => date("Y-m-d"),
                "rmac_status" => 55,
                "is_internal" => $_POST["isInternal"]=="true" ? 1 : 0
            ]);
            $rmac_ID = $db->id();
            $db->exec("INSERT INTO rmac_items_history SET rmac_date=NOW(), rmac_user=".$_SESSION["user_id"].", rmac_status=(select rmac_status from rmac_items where rmac_id=$rmac_ID), rmac_comment=:cm, rmac_ID=:id", [":cm"=> ["New return created on ". date("Y-m-d"), PDO::PARAM_STR], ":id"=>[intval($rmac_ID), PDO::PARAM_INT]]);

            http_response_code(200);
            echo json_encode('{ "success" : true , "id": '.$rmac_ID.'}');
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode('{ "success" : false, "error": "'. $e->getMessage().'" }');
        }
    }
}

if($action == "creatermacmedia") {
    $rmac_id = intval($_POST["id"]);
    //$db->exec("update rmac_items set rmac_images='' where rmac_ID=:id",[ ":id"=> [$rmac_id,PDO::PARAM_STR] ]);
    //$db->exec("update rmac_items set rmac_videos='' where rmac_ID=:id",[ ":id"=> [$rmac_id,PDO::PARAM_STR] ]);
    if( !empty( $_FILES ) ) {
        foreach( $_FILES[ 'image' ][ 'tmp_name' ] as $index => $tmpName ){
            if( !empty( $_FILES[ 'image' ][ 'error' ][ $index ] ) ){                
                return false; // return false also immediately perhaps??
            }
            $tmpName = $_FILES[ 'image' ][ 'tmp_name' ][ $index ];                            
            if( !empty( $tmpName ) && is_uploaded_file( $tmpName ) ){
                $someDestinationPath = "/uploads/".time()."_".$_FILES[ 'image' ][ 'name' ][ $index ];
                $db->exec("update rmac_items set rmac_images=CONCAT(rmac_images, '$someDestinationPath,') where rmac_ID=:id",[ ":id"=> [$rmac_id,PDO::PARAM_STR] ]); 
                move_uploaded_file( $tmpName, $_SERVER['DOCUMENT_ROOT'] . $someDestinationPath ); // move to new location perhaps?
            }
        }

        foreach( $_FILES[ 'video' ][ 'tmp_name' ] as $index => $tmpName ){
            if( !empty( $_FILES[ 'video' ][ 'error' ][ $index ] ) ){                
                return false; // return false also immediately perhaps??
            }
            $tmpName = $_FILES[ 'video' ][ 'tmp_name' ][ $index ];                            
            if( !empty( $tmpName ) && is_uploaded_file( $tmpName ) ){
                $someDestinationPath = "/uploads/".time()."_".$_FILES[ 'video' ][ 'name' ][ $index ];
                $db->exec("update rmac_items set rmac_videos=CONCAT(rmac_videos, '$someDestinationPath,') where rmac_ID=:id",[ ":id"=> [$rmac_id,PDO::PARAM_STR] ]); 
                move_uploaded_file( $tmpName, $_SERVER['DOCUMENT_ROOT'] . $someDestinationPath ); // move to new location perhaps?
            }
        }
    }
}

if($action=="counts") {
    try {
        $data = $db->query(
            "select rmac_status,count(rmac_ID) c from rmac_items  group by rmac_status")->fetchAll();
        $status = $_STATUSES;
                  
        $output = array(
            "draw"  =>  1,          
            "iTotalRecords" =>  count($data),
            "iTotalDisplayRecords"  =>  count($data),
            "data"  =>  $data,
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

if($action=="search") {
    $start = intval($_POST["start"]);
    $length = intval($_POST["length"]);

    $order_field = "rmac_ID desc";

    if(!empty($_POST["order"])) {
        $order_field =  $_POST['columns'][$_POST['order']['0']['column']]['data'] ." ". strtoupper( $_POST['order']['0']['dir']  );
    }

    $numRows = 0;
    $numRowsTotal = 0;
     
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
        
        $_SF.= " AND (rmac_ID = :search_no or rmac_servicetag like :search or rmac_product like :search )";
        $_PARA[":search"]   = "%".$SEARCH."%";
        $_PARA[":search_no"]   = $SEARCH;
        
    }
    
    $query="select rmac_ID,            
            rmac_ordernumber,            
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

    $data = $db->query($query,$_PARA)->fetchAll();
    $numRowsTotal = $db->query($query_count2,$_PARA)->fetchAll();
    $numRows = $db->query($query_count)->fetchAll();

    $output = array(
        "draw"  =>  intval($_POST["draw"]),         
        "iTotalRecords" =>  $numRows[0]["c"],
        "iTotalDisplayRecords"  =>  $numRowsTotal[0]["c"],
        "data"  =>  $data,
        "t" => "r0"
    );

    echo json_encode(utf8ize($output));
}

if($action=="comment") {
    try {
        if(isset($_POST["data"])) {
            $data = $_POST["data"]["cm"];                         
            if($data == null || $data == "") throw new Exception("Please fill comment field.");            
            $rmac_ID = intval($part);            
          
            $db->exec("update rmac_items set rmac_lastcomment=:comment where rmac_ID=:id",[":comment"=> [$data, PDO::PARAM_STR], ":id"=>[intval($rmac_ID),PDO::PARAM_INT]]);            
            $db->exec("INSERT INTO rmac_items_history SET rmac_date=NOW(), rmac_user=".$_SESSION["user_id"].", rmac_status=(select rmac_status from rmac_items where rmac_id=$rmac_ID), rmac_comment=:cm, rmac_ID=:id", [":cm"=> [$data, PDO::PARAM_STR], ":id"=>[intval($rmac_ID), PDO::PARAM_INT]]);
        }
        header('Content-Type: application/json; charset=utf-8');
        http_response_code(200);
        echo json_encode('{ "success" : true }');
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode('{ "success" : false, "error": "'. $e->getMessage().'" }');
    }
}

if($action=="reset_status") {
    $success = false;
    $serial = $_POST["data"];
    $nwpSerialSql = "SELECT nst_product FROM nwp_stock WHERE nst_servicetag = '" . $serial . "'";
    if($nwpSerialData = $db->query($nwpSerialSql)->fetchAll()){
        $success = true;
        $nwpProdSql = "SELECT npr_condition FROM nwp_products WHERE npr_id = " . $nwpSerialData[0]['nst_product'];
        $nwpProdData = $db->query($nwpProdSql)->fetchAll();
        $status = 6;
        if (preg_match("/new/i", $nwpProdData[0]['npr_condition']) == 1) {
            $status = 7;
        } else if (preg_match("/refurb/i", $nwpProdData[0]['npr_condition']) == 1 && !(preg_match("/grade b/i", $nwpProdData[0]['npr_condition']) == 1 || preg_match("/b grade/i", $nwpProdData[0]['npr_condition']) == 1)) {
            $status = 22;
        }
        $db->update("nwp_stock", ["nst_status" => $status, "nst_lastcomment" => "Returned by Customer"],["nst_servicetag" => $_POST["data"]]);
    } else {
        $accSerialSql = "SELECT ast_product FROM acc_stock WHERE ast_servicetag = '" . $serial . "'";
        if($accSerialData = $db->query($accSerialSql)->fetchAll()){
            $success = true;
            $accProdSql = "SELECT apr_condition FROM aproducts WHERE apr_id = " . $accSerialData[0]['ast_product'];
            $accProdData = $db->query($accProdSql)->fetchAll();
            $status = 6;
            if (preg_match("/new/i", $accProdData[0]['apr_condition']) == 1) {
                $status = 7;
            } else if (preg_match("/refurb/i", $accProdData[0]['apr_condition']) == 1 && !(preg_match("/grade b/i", $accProdData[0]['apr_condition']) == 1 || preg_match("/b grade/i", $accProdData[0]['apr_condition']) == 1)) {
                $status = 22;
            }
            $db->update("acc_stock", ["ast_status" => $status, "ast_lastcomment" => "Returned by Customer"],["ast_servicetag" => $_POST["data"]]);
        } else {
            $dcoSerialSql = "SELECT dst_product FROM dco_stock WHERE dst_servicetag = '" . $serial . "'";
            if($dcoSerialData = $db->query($dcoSerialSql)->fetchAll()){
                $success = true;
                $dcoProdSql = "SELECT dp_condition FROM dell_part WHERE dp_id = " . $dcoSerialData[0]['dst_product'];
                $dcoProdData = $db->query($dcoProdSql)->fetchAll();
                $status = 6;
                if (preg_match("/new/i", $dcoProdData[0]['dp_condition']) == 1) {
                    $status = 7;
                } else if (preg_match("/refurb/i", $dcoProdData[0]['dp_condition']) == 1 && !(preg_match("/grade b/i", $dcoProdData[0]['dp_condition']) == 1 || preg_match("/b grade/i", $dcoProdData[0]['dp_condition']) == 1)) {
                    $status = 22;
                }
                $db->update("dco_stock", ["dst_status" => $status, "dst_lastcomment" => "Returned by Customer"],["dst_servicetag" => $_POST["data"]]);
            }
        }
    }

    if($success){
        header('Content-Type: application/json; charset=utf-8');
        http_response_code(200);
        echo json_encode('{ "success" : true }');
    } else {
        http_response_code(500);
        echo json_encode('{ "success" : false}');
    }
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