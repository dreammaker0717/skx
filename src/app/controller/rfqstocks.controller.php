<?php
use Medoo\Medoo;
$db=M::db();

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

if($action=="search") {
    $start = intval($_POST["start"]);
    $length = intval($_POST["length"]);
    //error_log($_POST['filter']);
    if ($length == -1) {
        $length = "100000";
    }

    $category = $_POST["category"];

    $_SF = "";
    if ($category != "") {
        $_SF = "category = " . intval($category);
    } else {
        $_SF = "1=1 ";
    }

    if ($_POST['filter'] == "low") {
        $_SF .= " AND magqty <= lowstock";
    } else if ($_POST['filter'] == "qty"){
        $_SF .= " AND delta != 0 AND prodtype != 2 AND ignoredelta = 0";
    } else if ($_POST['filter'] == "oos"){
        $_SF .= " AND isinstock = 0 AND (invqty > 0 OR magqty > 0 OR inprogqty > 0)";
    } else if ($_POST['filter'] == "dis"){
        $_SF .= " AND isdisabled = 2 AND (invqty > 0 OR magqty > 0 OR inprogqty > 0)";
    } else if ($_POST['filter'] == "qtyignored"){
        $_SF .= " AND ignoredelta = 1";
    } else if ($_POST['filter'] == "isassembled"){
        $_SF .= " AND isassembled = 1";
    }

    $_SFC = $_SF;

    $order_field = "id asc";
    if (!empty($_POST["order"])) {
        $order_field = $_POST['columns'][$_POST['order']['0']['column']]['data'] . " " . strtoupper($_POST['order']['0']['dir']);
    }
    
    $_PARA = [];
    $SKUSEARCH = $_POST["skuSearch"];
    if (!empty($SKUSEARCH)) {
        $_SF .= " AND (sku like :skusearch)";
        $_PARA[":skusearch"] = "%" . $SKUSEARCH . "%";
    }
    
    $NAMESEARCH = $_POST["nameSearch"];
    if (!empty($NAMESEARCH)) {
        $_SF .= " AND (name like :namesearch)";
        $_PARA[":namesearch"] = "%" . $NAMESEARCH . "%";
    }

    $query = "select id, prodtype, productid, sku, name, suppliercomments, category, magqty, lowstock, invqty, fbaqty, soldqty, inprogqty, delta, ordqty, lastqty, ordprice, magprice, arrived, status, orddate, isassembled, ignoredelta, isinstock, isdisabled
    from all_products
    where  $_SF
    order by $order_field
    limit $start,$length";

    $data = $db->query($query,$_PARA)->fetchAll();

    $numRows = 0;
    $numRowsTotal = 0;

    $query_count = "select count(*) as c from all_products where $_SFC ";
    $query_count2 = "select count(*) as c from all_products where $_SF ";

    $numRowsTotal = $db->query($query_count2, $_PARA)->fetchAll();
    $numRows = $db->query($query_count)->fetchAll();

    $output = array(
        "draw" => intval($_POST["draw"]),
        "iTotalRecords" => $numRows[0]["c"],
        "iTotalDisplayRecords" => $numRowsTotal[0]["c"] ?? 0,
        "data" => $data,
        "t" => "r0",
    );
    $json = json_encode(utf8ize($output));
    echo $json;

} else if($action=="update_low") {
    try {
        $data = $_POST["data"];
        $table = null;
        $cellData = array();
        $key = null;
        switch ($data['prodtype']) {
        	case 1:
        		$table = "nwp_products";
        		$cellData = ["npr_lowstock" => $data['value']];
        		$key = "npr_id";
        		break;
        	
        	case 2:
        		$table = "nwp_products2";
        		$cellData = ["npr2_lowstock" => $data['value']];
        		$key = "npr2_id";
        		break;
        	
        	case 3:
        		$table = "aproducts";
        		$cellData = ["apr_lowstock" => $data['value']];
        		$key = "apr_id";
        		break;
        	
        	case 4:
        		$table = "dell_part";
        		$cellData = ["dp_lowstock" => $data['value']];
        		$key = "dp_id";
        		break;
        	
        	default:
        		$table = null;
        		$cellData = null;
        		$key = null;
        		break;
        }
        $db->update($table,$cellData,[$key => $data["id"]]);
        $db->update("all_products",["lowstock" => $data['value']],["prodtype" => $data['prodtype'],"productid" => $data["id"] ]);
        header('Content-Type: application/json; charset=utf-8');
        http_response_code(200);
        echo json_encode('{ "success" : true }');
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode('{ "success" : false, "error": "'. $e->getMessage().'" }');
    }
} else if($action=="move_to_ignored_del") {
    try {
        $data = $_POST["data"];
        $table = null;
        $cellData = array();
        $key = null;
        switch ($data['prodtype']) {
            case 1:
                $table = "nwp_products";
                $cellData = ["npr_ignore_delta" => 1];
                $key = "npr_id";
                break;
            
            case 3:
                $table = "aproducts";
                $cellData = ["apr_ignore_delta" => 1];
                $key = "apr_id";
                break;
            
            case 4:
                $table = "dell_part";
                $cellData = ["dp_ignore_delta" => 1];
                $key = "dp_id";
                break;
            
            default:
                $table = null;
                $cellData = null;
                $key = null;
                break;
        }

        $db->update($table,$cellData,[$key => $data["id"]]);
        $db->update("all_products",["ignoredelta" => 1],["prodtype" => $data['prodtype'],"productid" => $data["id"]]);

        header('Content-Type: application/json; charset=utf-8');
        http_response_code(200);
        echo json_encode('{ "success" : true }');
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode('{ "success" : false, "error": "'. $e->getMessage().'" }');
    }
} else if($action=="sync") {
    $finalData = array();
    $index = 0;

    $nwpQuery = "SELECT npr_id, npr_sku, npr_name, npr_magprice, npr_suppliercomments, npr_category, npr_magqty, npr_lowstock, npr_isassembled, npr_ignore_delta, npr_is_in_stock, npr_is_disabled FROM nwp_products";
    $nwpData = $db->query($nwpQuery)->fetchAll();
    for ($i = 0; $i < count($nwpData); $i++) {
        $finalData[$index]['id'] = $index + 1;
        $finalData[$index]['prodtype'] = 1;
        $finalData[$index]['productid'] = $nwpData[$i]['npr_id'];
        $finalData[$index]['sku'] = $nwpData[$i]['npr_sku'];
        $finalData[$index]['name'] = $nwpData[$i]['npr_name'];
        $finalData[$index]['magprice'] = $nwpData[$i]['npr_magprice'];
        $finalData[$index]['suppliercomments'] = $nwpData[$i]['npr_suppliercomments'];
        $finalData[$index]['category'] = $nwpData[$i]['npr_category'];
        $finalData[$index]['magqty'] = $nwpData[$i]['npr_magqty'];
        $finalData[$index]['lowstock'] = $nwpData[$i]['npr_lowstock'];
        $finalData[$index]['isassembled'] = $nwpData[$i]['npr_isassembled'];
        $finalData[$index]['ignoredelta'] = $nwpData[$i]['npr_ignore_delta'];
        $finalData[$index]['isinstock'] = $nwpData[$i]['npr_is_in_stock'];
        $finalData[$index]['isdisabled'] = $nwpData[$i]['npr_is_disabled'];

        $nwpStockQuery = "SELECT COUNT(*) as c FROM nwp_stock WHERE (nst_status = 7 OR nst_status = 22 OR nst_status = 6) AND nst_product = " . $nwpData[$i]['npr_id'];
        $nwpStockData = $db->query($nwpStockQuery)->fetchAll();
        $finalData[$index]['invqty'] = $nwpStockData[0]['c'];

        $finalData[$index]['fbaqty'] = 0;
        $nwpSoldQuery = "SELECT COUNT(*) as c FROM nwp_stock WHERE (nst_status = 4 OR nst_status = 16 OR nst_status = 20) AND nst_product = " . $nwpData[$i]['npr_id'];
        $nwpSoldData = $db->query($nwpSoldQuery)->fetchAll();
        $finalData[$index]['soldqty'] = $nwpSoldData[0]['c'];

        $nwpInProgressQuery = "SELECT COUNT(*) as c FROM nwp_stock WHERE (nst_status = 1 OR nst_status = 11) AND nst_product = " . $nwpData[$i]['npr_id'];
        $nwpInProgressData = $db->query($nwpInProgressQuery)->fetchAll();
        $finalData[$index]['inprogqty'] = $nwpInProgressData[0]['c'];

        $nwpOrderQuery = "SELECT rfq_orderproducts.rfqop_quantity, rfq_orderproducts.rfqop_price, rfq_orderproducts.rfqop_arrived, rfq_orders.rfqo_state, rfq_orders.rfqo_date FROM rfq_orderproducts LEFT JOIN rfq_orders ON rfq_orderproducts.rfqo_id = rfq_orders.rfqo_id WHERE rfq_orderproducts.rfqop_prodtype = 1 AND rfq_orderproducts.rfqop_product = " . $nwpData[$i]['npr_id'] . " ORDER BY rfq_orders.rfqo_date DESC";
        if ($nwpOrderData = $db->query($nwpOrderQuery)->fetchAll()) {
            $quantity = 0;
            $arrived = 0;
            foreach ($nwpOrderData as $entry) {
                if ($entry['rfqo_state'] == "On Order" || $entry['rfqo_state'] == "Part Arrived") {
                    $quantity += $entry['rfqop_quantity'];
                    $arrived += $entry['rfqop_arrived'];
                }
            }
            $finalData[$index]['ordqty'] = $quantity;
            $finalData[$index]['lastqty'] = $nwpOrderData[0]['rfqop_quantity'];
            $finalData[$index]['ordprice'] = $nwpOrderData[0]['rfqop_price'];
            $finalData[$index]['arrived'] = $arrived;
            $finalData[$index]['status'] = $nwpOrderData[0]['rfqo_state'];
            $finalData[$index]['orddate'] = $nwpOrderData[0]['rfqo_date'];
        } else {
            $finalData[$index]['ordqty'] = 0;
            $finalData[$index]['lastqty'] = 0;
            $finalData[$index]['ordprice'] = 0;
            $finalData[$index]['arrived'] = 0;
            $finalData[$index]['status'] = "";
            $finalData[$index]['orddate'] = "";
        }

        $index++;
    }


    $nwp2Query = "SELECT npr2_id, npr2_sku, npr2_name, npr2_magprice, npr2_suppliercomments, npr2_category, npr2_magqty, npr2_lowstock, npr2_is_in_stock, npr2_is_disabled FROM nwp_products2";
    $nwp2Data = $db->query($nwp2Query)->fetchAll();
    for ($i = 0; $i < count($nwp2Data); $i++) {
        $finalData[$index]['id'] = $index + 1;
        $finalData[$index]['prodtype'] = 2;
        $finalData[$index]['productid'] = $nwp2Data[$i]['npr2_id'];
        $finalData[$index]['sku'] = $nwp2Data[$i]['npr2_sku'];
        $finalData[$index]['name'] = $nwp2Data[$i]['npr2_name'];
        $finalData[$index]['magprice'] = $nwp2Data[$i]['npr2_magprice'];
        $finalData[$index]['suppliercomments'] = $nwp2Data[$i]['npr2_suppliercomments'];
        $finalData[$index]['category'] = $nwp2Data[$i]['npr2_category'];
        $finalData[$index]['magqty'] = $nwp2Data[$i]['npr2_magqty'];
        $finalData[$index]['lowstock'] = $nwp2Data[$i]['npr2_lowstock'];
        $finalData[$index]['isassembled'] = 0;
        $finalData[$index]['ignoredelta'] = 0;
        $finalData[$index]['isinstock'] = $nwp2Data[$i]['npr2_is_in_stock'];
        $finalData[$index]['isdisabled'] = $nwp2Data[$i]['npr2_is_disabled'];

        $finalData[$index]['invqty'] = 0;
        $finalData[$index]['fbaqty'] = 0;
        $finalData[$index]['soldqty'] = 0;
        $finalData[$index]['inprogqty'] = 0;

        $nwp2OrderQuery = "SELECT rfq_orderproducts.rfqop_quantity, rfq_orderproducts.rfqop_price, rfq_orderproducts.rfqop_arrived, rfq_orders.rfqo_state, rfq_orders.rfqo_date FROM rfq_orderproducts LEFT JOIN rfq_orders ON rfq_orderproducts.rfqo_id = rfq_orders.rfqo_id WHERE rfq_orderproducts.rfqop_prodtype = 2 AND rfq_orderproducts.rfqop_product = " . $nwp2Data[$i]['npr2_id'] . " ORDER BY rfq_orders.rfqo_date DESC";
        if ($nwp2OrderData = $db->query($nwp2OrderQuery)->fetchAll()) {
            $quantity = 0;
            $arrived = 0;
            foreach ($nwp2OrderData as $entry) {
                if ($entry['rfqo_state'] == "On Order" || $entry['rfqo_state'] == "Part Arrived") {
                    $quantity += $entry['rfqop_quantity'];
                    $arrived += $entry['rfqop_arrived'];
                }
            }
            $finalData[$index]['ordqty'] = $quantity;
            $finalData[$index]['lastqty'] = $nwp2OrderData[0]['rfqop_quantity'];
            $finalData[$index]['ordprice'] = $nwp2OrderData[0]['rfqop_price'];
            $finalData[$index]['arrived'] = $arrived;
            $finalData[$index]['status'] = $nwp2OrderData[0]['rfqo_state'];
            $finalData[$index]['orddate'] = $nwp2OrderData[0]['rfqo_date'];
        } else {
            $finalData[$index]['ordqty'] = 0;
            $finalData[$index]['lastqty'] = 0;
            $finalData[$index]['ordprice'] = 0;
            $finalData[$index]['arrived'] = 0;
            $finalData[$index]['status'] = "";
            $finalData[$index]['orddate'] = "";
        }

        $index++;
    }


    $aprQuery = "SELECT apr_id, apr_sku, apr_name, apr_magprice, apr_suppliercomments, apr_category, apr_magqty, apr_lowstock, apr_isassembled, apr_ignore_delta, apr_is_in_stock, apr_is_disabled FROM aproducts";
    $aprData = $db->query($aprQuery)->fetchAll();
    for ($i = 0; $i < count($aprData); $i++) {
        $finalData[$index]['id'] = $index + 1;
        $finalData[$index]['prodtype'] = 3;
        $finalData[$index]['productid'] = $aprData[$i]['apr_id'];
        $finalData[$index]['sku'] = $aprData[$i]['apr_sku'];
        $finalData[$index]['name'] = $aprData[$i]['apr_name'];
        $finalData[$index]['magprice'] = $aprData[$i]['apr_magprice'];
        $finalData[$index]['suppliercomments'] = $aprData[$i]['apr_suppliercomments'];
        $finalData[$index]['category'] = $aprData[$i]['apr_category'];
        $finalData[$index]['magqty'] = $aprData[$i]['apr_magqty'];
        $finalData[$index]['lowstock'] = $aprData[$i]['apr_lowstock'];
        $finalData[$index]['isassembled'] = $aprData[$i]['apr_isassembled'];
        $finalData[$index]['ignoredelta'] = $aprData[$i]['apr_ignore_delta'];
        $finalData[$index]['isinstock'] = $aprData[$i]['apr_is_in_stock'];
        $finalData[$index]['isdisabled'] = $aprData[$i]['apr_is_disabled'];

        $aprStockQuery = "SELECT COUNT(*) as c FROM acc_stock WHERE (ast_status = 7 OR ast_status = 22 OR ast_status = 6) AND ast_product = " . $aprData[$i]['apr_id'];
        $aprStockData = $db->query($aprStockQuery)->fetchAll();
        $finalData[$index]['invqty'] = $aprStockData[0]['c'];

        $aprFBAQuery = "SELECT COUNT(*) as c FROM acc_stock WHERE ast_status = 29 AND ast_product = " . $aprData[$i]['apr_id'];
        $aprFBAData = $db->query($aprFBAQuery)->fetchAll();
        $finalData[$index]['fbaqty'] = $aprFBAData[0]['c'];

        $aprSoldQuery = "SELECT COUNT(*) as c FROM acc_stock WHERE (ast_status = 16 OR ast_status = 29 OR ast_status = 30) AND ast_product = " . $aprData[$i]['apr_id'];
        $aprSoldData = $db->query($aprSoldQuery)->fetchAll();
        $finalData[$index]['soldqty'] = $aprSoldData[0]['c'];

        $aprInProgressQuery = "SELECT COUNT(*) as c FROM acc_stock WHERE (ast_status = 1 OR ast_status = 2 OR ast_status = 3) AND ast_product = " . $aprData[$i]['apr_id'];
        $aprInProgressData = $db->query($aprInProgressQuery)->fetchAll();
        $finalData[$index]['inprogqty'] = $aprInProgressData[0]['c'];

        $aprOrderQuery = "SELECT rfq_orderproducts.rfqop_quantity, rfq_orderproducts.rfqop_price, rfq_orderproducts.rfqop_arrived, rfq_orders.rfqo_state, rfq_orders.rfqo_date FROM rfq_orderproducts LEFT JOIN rfq_orders ON rfq_orderproducts.rfqo_id = rfq_orders.rfqo_id WHERE rfq_orderproducts.rfqop_prodtype = 3 AND rfq_orderproducts.rfqop_product = " . $aprData[$i]['apr_id'] . " ORDER BY rfq_orders.rfqo_date DESC";
        if ($aprOrderData = $db->query($aprOrderQuery)->fetchAll()) {
            $quantity = 0;
            $arrived = 0;
            foreach ($aprOrderData as $entry) {
                if ($entry['rfqo_state'] == "On Order" || $entry['rfqo_state'] == "Part Arrived") {
                    $quantity += $entry['rfqop_quantity'];
                    $arrived += $entry['rfqop_arrived'];
                }
            }
            $finalData[$index]['ordqty'] = $quantity;
            $finalData[$index]['lastqty'] = $aprOrderData[0]['rfqop_quantity'];
            $finalData[$index]['ordprice'] = $aprOrderData[0]['rfqop_price'];
            $finalData[$index]['arrived'] = $arrived;
            $finalData[$index]['status'] = $aprOrderData[0]['rfqo_state'];
            $finalData[$index]['orddate'] = $aprOrderData[0]['rfqo_date'];
        } else {
            $finalData[$index]['ordqty'] = 0;
            $finalData[$index]['lastqty'] = 0;
            $finalData[$index]['ordprice'] = 0;
            $finalData[$index]['arrived'] = 0;
            $finalData[$index]['status'] = "";
            $finalData[$index]['orddate'] = "";
        }

        $index++;
    }


    $dpQuery = "SELECT dp_id, dp_sku, dp_name, dp_magprice, dp_suppliercomments, dp_category, dp_magqty, dp_lowstock, dp_isassembled, dp_ignore_delta, dp_is_in_stock, dp_is_disabled FROM dell_part";
    $dpData = $db->query($dpQuery)->fetchAll();
    for ($i = 0; $i < count($dpData); $i++) {
        $finalData[$index]['id'] = $index + 1;
        $finalData[$index]['prodtype'] = 4;
        $finalData[$index]['productid'] = $dpData[$i]['dp_id'];
        $finalData[$index]['sku'] = $dpData[$i]['dp_sku'];
        $finalData[$index]['name'] = $dpData[$i]['dp_name'];
        $finalData[$index]['magprice'] = $dpData[$i]['dp_magprice'];
        $finalData[$index]['suppliercomments'] = $dpData[$i]['dp_suppliercomments'];
        $finalData[$index]['category'] = $dpData[$i]['dp_category'];
        $finalData[$index]['magqty'] = $dpData[$i]['dp_magqty'];
        $finalData[$index]['lowstock'] = $dpData[$i]['dp_lowstock'];
        $finalData[$index]['isassembled'] = $dpData[$i]['dp_isassembled'];
        $finalData[$index]['ignoredelta'] = $dpData[$i]['dp_ignore_delta'];
        $finalData[$index]['isinstock'] = $dpData[$i]['dp_is_in_stock'];
        $finalData[$index]['isdisabled'] = $dpData[$i]['dp_is_disabled'];

        $dpStockQuery = "SELECT COUNT(*) as c FROM dco_stock WHERE (dst_status = 7 OR dst_status = 22 OR dst_status = 6) AND dst_product = " . $dpData[$i]['dp_id'];
        $dpStockData = $db->query($dpStockQuery)->fetchAll();
        $finalData[$index]['invqty'] = $dpStockData[0]['c'];

        $dpFBAQuery = "SELECT COUNT(*) as c FROM dco_stock WHERE dst_status = 29 AND dst_product = " . $dpData[$i]['dp_id'];
        $dpFBAData = $db->query($dpFBAQuery)->fetchAll();
        $finalData[$index]['fbaqty'] = $dpFBAData[0]['c'];

        $dpSoldQuery = "SELECT COUNT(*) as c FROM dco_stock WHERE (dst_status = 16 OR dst_status = 29 OR dst_status = 30) AND dst_product = " . $dpData[$i]['dp_id'];
        $dpSoldData = $db->query($dpSoldQuery)->fetchAll();
        $finalData[$index]['soldqty'] = $dpSoldData[0]['c'];

        $dpInProgressQuery = "SELECT COUNT(*) as c FROM dco_stock WHERE (dst_status = 1 OR dst_status = 11) AND dst_product = " . $dpData[$i]['dp_id'];
        $dpInProgressData = $db->query($dpInProgressQuery)->fetchAll();
        $finalData[$index]['inprogqty'] = $dpInProgressData[0]['c'];

        $dpOrderQuery = "SELECT rfq_orderproducts.rfqop_quantity, rfq_orderproducts.rfqop_price, rfq_orderproducts.rfqop_arrived, rfq_orders.rfqo_state, rfq_orders.rfqo_date FROM rfq_orderproducts LEFT JOIN rfq_orders ON rfq_orderproducts.rfqo_id = rfq_orders.rfqo_id WHERE rfq_orderproducts.rfqop_prodtype = 4 AND rfq_orderproducts.rfqop_product = " . $dpData[$i]['dp_id'] . " ORDER BY rfq_orders.rfqo_date DESC";
        if ($dpOrderData = $db->query($dpOrderQuery)->fetchAll()) {
            $quantity = 0;
            $arrived = 0;
            foreach ($dpOrderData as $entry) {
                if ($entry['rfqo_state'] == "On Order" || $entry['rfqo_state'] == "Part Arrived") {
                    $quantity += $entry['rfqop_quantity'];
                    $arrived += $entry['rfqop_arrived'];
                }
            }
            $finalData[$index]['ordqty'] = $quantity;
            $finalData[$index]['lastqty'] = $dpOrderData[0]['rfqop_quantity'];
            $finalData[$index]['ordprice'] = $dpOrderData[0]['rfqop_price'];
            $finalData[$index]['arrived'] = $arrived;
            $finalData[$index]['status'] = $dpOrderData[0]['rfqo_state'];
            $finalData[$index]['orddate'] = $dpOrderData[0]['rfqo_date'];
        } else {
            $finalData[$index]['ordqty'] = 0;
            $finalData[$index]['lastqty'] = 0;
            $finalData[$index]['ordprice'] = 0;
            $finalData[$index]['arrived'] = 0;
            $finalData[$index]['status'] = "";
            $finalData[$index]['orddate'] = "";
        }

        $index++;
    }

    $db->drop("all_products");
    $db->create("all_products", [
        "id" => [
            "INT",
            "NOT NULL",
            "AUTO_INCREMENT",
            "PRIMARY KEY"
        ],
        "prodtype" => [
            "INT(1)",
            "NOT NULL"
        ],
        "productid" => [
            "INT(11)",
            "NOT NULL"
        ],
        "sku" => [
            "TEXT",
            "NOT NULL"
        ],
        "name" => [
            "TEXT",
            "NOT NULL"
        ],
        "magprice" => [
            "FLOAT(11)",
            "NOT NULL"
        ],
        "suppliercomments" => [
            "TEXT",
            "NOT NULL"
        ],
        "category" => [
            "INT(3)",
            "NOT NULL"
        ],
        "magqty" => [
            "INT(11)",
            "NOT NULL"
        ],
        "lowstock" => [
            "INT(11)",
            "NOT NULL"
        ],
        "invqty" => [
            "INT(11)",
            "NOT NULL"
        ],
        "fbaqty" => [
            "INT(11)",
            "NOT NULL"
        ],
        "soldqty" => [
            "INT(11)",
            "NOT NULL"
        ],
        "inprogqty" => [
            "INT(11)",
            "NOT NULL"
        ],
        "delta" => [
            "INT(11)",
            "NOT NULL"
        ],
        "ordqty" => [
            "INT(11)",
            "NOT NULL"
        ],
        "lastqty" => [
            "INT(11)",
            "NOT NULL"
        ],
        "ordprice" => [
            "FLOAT",
            "NOT NULL"
        ],
        "arrived" => [
            "INT(11)",
            "NOT NULL"
        ],
        "prodtype" => [
            "INT(11)",
            "NOT NULL"
        ],
        "status" => [
            "TEXT",
            "NOT NULL"
        ],
        "orddate" => [
            "DATE",
            "NOT NULL"
        ],
        "isassembled" => [
            "INT(1)",
            "NOT NULL"
        ],
        "ignoredelta" => [
            "INT(1)",
            "NOT NULL"
        ],
        "isinstock" => [
            "INT(1)",
            "NOT NULL"
        ],
        "isdisabled" => [
            "INT(1)",
            "NOT NULL"
        ],
    ]);

    foreach ($finalData as $entry) {
        $db->insert("all_products", [
            "prodtype" => $entry['prodtype'],
            "productid" => $entry['productid'],
            "sku" => $entry['sku'],
            "name" => $entry['name'],
            "magprice" => $entry['magprice'],
            "suppliercomments" => $entry['suppliercomments'],
            "category" => $entry['category'],
            "magqty" => $entry['magqty'],
            "lowstock" => $entry['lowstock'],
            "invqty" => $entry['invqty'],
            "fbaqty" => $entry['fbaqty'],
            "soldqty" => $entry['soldqty'],
            "inprogqty" => $entry['inprogqty'],
            "delta" => ($entry['invqty'] - $entry['magqty']),
            "ordqty" => $entry['ordqty'],
            "lastqty" => $entry['lastqty'],
            "ordprice" => $entry['ordprice'],
            "arrived" => $entry['arrived'],
            "status" => $entry['status'],
            "orddate" => $entry['orddate'],
            "isassembled" => $entry['isassembled'],
            "ignoredelta" => $entry['ignoredelta'],
            "isinstock" => $entry['isinstock'],
            "isdisabled" => $entry['isdisabled'],
        ]);
    }

    header('Content-Type: application/json; charset=utf-8');
    http_response_code(200);
    echo json_encode('{ "success" : true }');
} else {
    exit();
}