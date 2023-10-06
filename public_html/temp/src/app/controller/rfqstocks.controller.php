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
    
    $finalData = array();
    $index = 0;

    $nwpQuery = "SELECT npr_id, npr_sku, npr_name, npr_suppliercomments, npr_category, npr_magqty, npr_lowstock FROM nwp_products";
    $nwpData = $db->query($nwpQuery)->fetchAll();
    for ($i = 0; $i < count($nwpData); $i++) {
    	$finalData[$index]['id'] = $index + 1;
    	$finalData[$index]['prodtype'] = 1;
    	$finalData[$index]['productid'] = $nwpData[$i]['npr_id'];
    	$finalData[$index]['sku'] = $nwpData[$i]['npr_sku'];
    	$finalData[$index]['name'] = $nwpData[$i]['npr_name'];
    	$finalData[$index]['suppliercomments'] = $nwpData[$i]['npr_suppliercomments'];
    	$finalData[$index]['category'] = $nwpData[$i]['npr_category'];
        $finalData[$index]['magqty'] = $nwpData[$i]['npr_magqty'];
    	$finalData[$index]['lowstock'] = $nwpData[$i]['npr_lowstock'];

    	$nwpStockQuery = "SELECT COUNT(*) as c FROM nwp_stock WHERE (nst_status = 7 OR nst_status = 22 OR nst_status = 6) AND nst_product = " . $nwpData[$i]['npr_id'];
    	$nwpStockData = $db->query($nwpStockQuery)->fetchAll();
    	$finalData[$index]['invqty'] = $nwpStockData[0]['c'];

		$nwpOrderQuery = "SELECT rfq_orderproducts.rfqop_quantity, rfq_orderproducts.rfqop_price, rfq_orderproducts.rfqop_arrived, rfq_orders.rfqo_state, rfq_orders.rfqo_date FROM rfq_orderproducts LEFT JOIN rfq_orders ON rfq_orderproducts.rfqo_id = rfq_orders.rfqo_id WHERE rfq_orderproducts.rfqop_prodtype = 1 AND rfq_orderproducts.rfqop_product = " . $nwpData[$i]['npr_id'];
    	if ($nwpOrderData = $db->query($nwpOrderQuery)->fetchAll()) {
    		$finalData[$index]['ordqty'] = $nwpOrderData[0]['rfqop_quantity'];
    		$finalData[$index]['ordprice'] = $nwpOrderData[0]['rfqop_price'];
    		$finalData[$index]['arrived'] = $nwpOrderData[0]['rfqop_arrived'];
    		$finalData[$index]['status'] = $nwpOrderData[0]['rfqo_state'];
    		$finalData[$index]['orddate'] = $nwpOrderData[0]['rfqo_date'];
    	} else {
    		$finalData[$index]['ordqty'] = "*";
    		$finalData[$index]['ordprice'] = "*";
    		$finalData[$index]['orddate'] = "*";
    		$finalData[$index]['status'] = "*";
    		$finalData[$index]['orddate'] = "*";
    	}

    	$index++;
    }


    $nwp2Query = "SELECT npr2_id, npr2_sku, npr2_name, npr2_suppliercomments, npr2_category, npr2_magqty, npr2_lowstock FROM nwp_products2";
    $nwp2Data = $db->query($nwp2Query)->fetchAll();
    for ($i = 0; $i < count($nwp2Data); $i++) {
    	$finalData[$index]['id'] = $index + 1;
    	$finalData[$index]['prodtype'] = 2;
    	$finalData[$index]['productid'] = $nwp2Data[$i]['npr2_id'];
    	$finalData[$index]['sku'] = $nwp2Data[$i]['npr2_sku'];
    	$finalData[$index]['name'] = $nwp2Data[$i]['npr2_name'];
    	$finalData[$index]['suppliercomments'] = $nwp2Data[$i]['npr2_suppliercomments'];
    	$finalData[$index]['category'] = $nwp2Data[$i]['npr2_category'];
        $finalData[$index]['magqty'] = $nwp2Data[$i]['npr2_magqty'];
    	$finalData[$index]['lowstock'] = $nwp2Data[$i]['npr2_lowstock'];

    	$finalData[$index]['invqty'] = "*";

		$nwp2OrderQuery = "SELECT rfq_orderproducts.rfqop_quantity, rfq_orderproducts.rfqop_price, rfq_orderproducts.rfqop_arrived, rfq_orders.rfqo_state, rfq_orders.rfqo_date FROM rfq_orderproducts LEFT JOIN rfq_orders ON rfq_orderproducts.rfqo_id = rfq_orders.rfqo_id WHERE rfq_orderproducts.rfqop_prodtype = 1 AND rfq_orderproducts.rfqop_product = " . $nwp2Data[$i]['npr2_id'];
    	if ($nwp2OrderData = $db->query($nwp2OrderQuery)->fetchAll()) {
    		$finalData[$index]['ordqty'] = $nwp2OrderData[0]['rfqop_quantity'];
    		$finalData[$index]['ordprice'] = $nwp2OrderData[0]['rfqop_price'];
    		$finalData[$index]['arrived'] = $nwp2OrderData[0]['rfqop_arrived'];
    		$finalData[$index]['status'] = $nwp2OrderData[0]['rfqo_state'];
    		$finalData[$index]['orddate'] = $nwp2OrderData[0]['rfqo_date'];
    	} else {
    		$finalData[$index]['ordqty'] = "*";
    		$finalData[$index]['ordprice'] = "*";
    		$finalData[$index]['orddate'] = "*";
    		$finalData[$index]['status'] = "*";
    		$finalData[$index]['orddate'] = "*";
    	}

    	$index++;
    }


    $aprQuery = "SELECT apr_id, apr_sku, apr_name, apr_suppliercomments, apr_category, apr_magqty, apr_lowstock FROM aproducts";
    $aprData = $db->query($aprQuery)->fetchAll();
    for ($i = 0; $i < count($aprData); $i++) {
    	$finalData[$index]['id'] = $index + 1;
    	$finalData[$index]['prodtype'] = 3;
    	$finalData[$index]['productid'] = $aprData[$i]['apr_id'];
    	$finalData[$index]['sku'] = $aprData[$i]['apr_sku'];
    	$finalData[$index]['name'] = $aprData[$i]['apr_name'];
    	$finalData[$index]['suppliercomments'] = $aprData[$i]['apr_suppliercomments'];
    	$finalData[$index]['category'] = $aprData[$i]['apr_category'];
        $finalData[$index]['magqty'] = $aprData[$i]['apr_magqty'];
    	$finalData[$index]['lowstock'] = $aprData[$i]['apr_lowstock'];

    	$aprStockQuery = "SELECT COUNT(*) as c FROM acc_stock WHERE (ast_status = 7 OR ast_status = 22 OR ast_status = 6) AND ast_product = " . $aprData[$i]['apr_id'];
    	$aprStockData = $db->query($aprStockQuery)->fetchAll();
    	$finalData[$index]['invqty'] = $aprStockData[0]['c'];

		$aprOrderQuery = "SELECT rfq_orderproducts.rfqop_quantity, rfq_orderproducts.rfqop_price, rfq_orderproducts.rfqop_arrived, rfq_orders.rfqo_state, rfq_orders.rfqo_date FROM rfq_orderproducts LEFT JOIN rfq_orders ON rfq_orderproducts.rfqo_id = rfq_orders.rfqo_id WHERE rfq_orderproducts.rfqop_prodtype = 1 AND rfq_orderproducts.rfqop_product = " . $aprData[$i]['apr_id'];
    	if ($aprOrderData = $db->query($aprOrderQuery)->fetchAll()) {
    		$finalData[$index]['ordqty'] = $aprOrderData[0]['rfqop_quantity'];
    		$finalData[$index]['ordprice'] = $aprOrderData[0]['rfqop_price'];
    		$finalData[$index]['arrived'] = $aprOrderData[0]['rfqop_arrived'];
    		$finalData[$index]['status'] = $aprOrderData[0]['rfqo_state'];
    		$finalData[$index]['orddate'] = $aprOrderData[0]['rfqo_date'];
    	} else {
    		$finalData[$index]['ordqty'] = "*";
    		$finalData[$index]['ordprice'] = "*";
    		$finalData[$index]['orddate'] = "*";
    		$finalData[$index]['status'] = "*";
    		$finalData[$index]['orddate'] = "*";
    	}

    	$index++;
    }


    $dpQuery = "SELECT dp_id, dp_sku, dp_name, dp_suppliercomments, dp_category, dp_magqty, dp_lowstock FROM dell_part";
    $dpData = $db->query($dpQuery)->fetchAll();
    for ($i = 0; $i < count($dpData); $i++) {
    	$finalData[$index]['id'] = $index + 1;
    	$finalData[$index]['prodtype'] = 3;
    	$finalData[$index]['productid'] = $dpData[$i]['dp_id'];
    	$finalData[$index]['sku'] = $dpData[$i]['dp_sku'];
    	$finalData[$index]['name'] = $dpData[$i]['dp_name'];
    	$finalData[$index]['suppliercomments'] = $dpData[$i]['dp_suppliercomments'];
    	$finalData[$index]['category'] = $dpData[$i]['dp_category'];
        $finalData[$index]['magqty'] = $dpData[$i]['dp_magqty'];
    	$finalData[$index]['lowstock'] = $dpData[$i]['dp_lowstock'];

    	$dpStockQuery = "SELECT COUNT(*) as c FROM dco_stock WHERE (dst_status = 7 OR dst_status = 22 OR dst_status = 6) AND dst_product = " . $dpData[$i]['dp_id'];
    	$nwpStockData = $db->query($nwpStockQuery)->fetchAll();
    	$finalData[$index]['invqty'] = $nwpStockData[0]['c'];

		$dpOrderQuery = "SELECT rfq_orderproducts.rfqop_quantity, rfq_orderproducts.rfqop_price, rfq_orderproducts.rfqop_arrived, rfq_orders.rfqo_state, rfq_orders.rfqo_date FROM rfq_orderproducts LEFT JOIN rfq_orders ON rfq_orderproducts.rfqo_id = rfq_orders.rfqo_id WHERE rfq_orderproducts.rfqop_prodtype = 1 AND rfq_orderproducts.rfqop_product = " . $dpData[$i]['dp_id'];
    	if ($dpOrderData = $db->query($dpOrderQuery)->fetchAll()) {
    		$finalData[$index]['ordqty'] = $dpOrderData[0]['rfqop_quantity'];
    		$finalData[$index]['ordprice'] = $dpOrderData[0]['rfqop_price'];
    		$finalData[$index]['arrived'] = $dpOrderData[0]['rfqop_arrived'];
    		$finalData[$index]['status'] = $dpOrderData[0]['rfqo_state'];
    		$finalData[$index]['orddate'] = $dpOrderData[0]['rfqo_date'];
    	} else {
    		$finalData[$index]['ordqty'] = "*";
    		$finalData[$index]['ordprice'] = "*";
    		$finalData[$index]['orddate'] = "*";
    		$finalData[$index]['status'] = "*";
    		$finalData[$index]['orddate'] = "*";
    	}

    	$index++;
    }
	
    if ($_POST["search"]["value"] != "") {
		$finalData = array_filter($finalData, function ($var) {
			$skuPattern = "/" . preg_replace('/([^A-Za-z0-9])/i', '\\\\$1', $_POST["search"]["value"]) . "/i";
			$namePattern = "/" . preg_replace('/([^A-Za-z0-9])/i', '\\\\$1', $_POST["search"]["value"]) . "/i";
			if (preg_match($skuPattern, $var['sku']) == 1 || preg_match($namePattern, $var['name']) == 1) {
				return true;
			} else {
				return false;
			}
		});
    }

    if ($_POST["category"] != "") {
        $finalData = array_filter($finalData, function ($var) {
            if ($var['category'] == $_POST["category"]) {
                return true;
            } else {
                return false;
            }
        });
    }


    if(!empty($_POST["order"])) {
        if ($_POST['order']['0']['dir'] == "asc") {
            usort($finalData, function ($item1, $item2) {
                return $item1[$_POST['columns'][$_POST['order']['0']['column']]['data']] <=> $item2[$_POST['columns'][$_POST['order']['0']['column']]['data']];
            });
        } else {
            usort($finalData, function ($item1, $item2) {
                return $item2[$_POST['columns'][$_POST['order']['0']['column']]['data']] <=> $item1[$_POST['columns'][$_POST['order']['0']['column']]['data']];
            });
        }
    }

	$outputData = array_slice($finalData, $start, $length);

    $output = array(
		"draw"	=>	intval($_POST["draw"]),			
		"iTotalRecords"	=> 	count($finalData),
		"iTotalDisplayRecords"	=>  count($finalData),
		"data"	=> 	$outputData,
        "t" => "r0",
	);

    echo json_encode(utf8ize($output));

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
        $db->update($table,$cellData,[$key => $data["id"] ]);    
        header('Content-Type: application/json; charset=utf-8');
        http_response_code(200);
        echo json_encode('{ "success" : true }');
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode('{ "success" : false, "error": "'. $e->getMessage().'" }');
    }
}