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

    $order_field = "id asc";
    if (!empty($_POST["order"])) {
        $order_field = "suppliers." . $_POST['columns'][$_POST['order']['0']['column']]['data'] . " " . strtoupper($_POST['order']['0']['dir']);
    }

    $query = "select suppliers.sp_id, suppliers.sp_name, count(*) as item_count
    from rmac_items 
    left join suppliers on rmac_items.rmac_supplier = suppliers.sp_id 
    where rmac_items.rmac_supplier != 0 
    group by rmac_items.rmac_supplier 
    order by $order_field 
    limit $start,$length";

    $data = $db->query($query)->fetchAll();

    $numRows = 0;
    $numRowsTotal = 0;

    $query_count = "select count(*) over() as c from rmac_items where rmac_supplier != 0 group by rmac_supplier";
    $query_count2 = "select count(*) over() as c from rmac_items where rmac_supplier != 0 group by rmac_supplier";

    $numRowsTotal = $db->query($query_count2)->fetchAll();
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

} else if($action=="search_items") {
    $start = intval($_POST["start"]);
    $length = intval($_POST["length"]);

    if ($length == -1) {
        $length = "100000";
    }

    $id = $_POST["id"];

    $_SF = "rmac_supplier = " . intval($id);

    $_SFC = $_SF;

    $order_field = "id asc";
    if (!empty($_POST["order"])) {
        $order_field = $_POST['columns'][$_POST['order']['0']['column']]['data'] . " " . strtoupper($_POST['order']['0']['dir']);
    }
    
    $_PARA = [];
    $SEARCH = $_POST["search"]["value"];
    if (!empty($SEARCH)) {
        $_SF .= " AND (rmac_sku like :search OR rmac_fullname like :search)";
        $_PARA[":search"] = "%" . $SEARCH . "%";
    }

    $query = "select rmac_ID, rmac_prodType, rmac_productID, rmac_supplier, rmac_sku, rmac_images, rmac_product, rmac_servicetag, rmac_fullname, rmac_purchasedon 
    from rmac_items 
    where $_SF
    order by $order_field
    limit $start,$length";

    $data = $db->query($query,$_PARA)->fetchAll();

    for ($i=0; $i < count($data); $i++) { 
        switch ($data[$i]['rmac_prodType']) {
            case 1:
                if ($data[$i]['rmac_servicetag'] != null) {
                    $supplierStockQuery = "select nst_order from nwp_stock where nst_servicetag = '" . $data[$i]['rmac_servicetag'] . "'";
                    if ($supplierStockData = $db->query($supplierStockQuery)->fetchAll()) {
                        $supplierOrderQuery = "select nor_date, nor_reference from nwp_orders where nor_id = " . $supplierStockData[0]['nst_order'];
                        if ($supplierOrderData = $db->query($supplierOrderQuery)->fetchAll()) {
                            $data[$i]['supp_ordernumber'] = $supplierStockData[0]['nst_order'] . " - " . $supplierOrderData[0]['nor_reference'];
                            $data[$i]['supp_orderdate'] = $supplierOrderData[0]['nor_date'];
                        }
                    }
                } else {
                    $supplierOrderProdQuery = "select nwp_orders.nor_id, nwp_orders.nor_date, nwp_orders.nor_reference from nwp_orders left join nwp_orderprod on nwp_orders.nor_id = nwp_orderprod.nop_order where nwp_orderprod.nop_product = " . $data[$i]['rmac_productID'] . " and nwp_orders.nor_supplier = " . $data[$i]['rmac_supplier'] . " order by nwp_orders.nor_date desc";
                    if ($supplierOrderProdData = $db->query($supplierOrderProdQuery)->fetchAll()) {
                        $data[$i]['supp_ordernumber'] = $supplierOrderProdData[0]['nor_id'] . " - " . $supplierOrderProdData[0]['nor_reference'];
                        $data[$i]['supp_orderdate'] = $supplierOrderProdData[0]['nor_date'];
                    }
                }

                break;

            case 2:
                $data[$i]['supp_ordernumber'] = " - ";
                $data[$i]['supp_orderdate'] = " - ";

                break;

            case 3:
                if ($data[$i]['rmac_servicetag'] != null) {
                    $supplierStockQuery = "select ast_order from acc_stock where ast_servicetag = '" . $data[$i]['rmac_servicetag'] . "'";
                    if ($supplierStockData = $db->query($supplierStockQuery)->fetchAll()) {
                        $supplierOrderQuery = "select aor_date, aor_reference from acc_orders where aor_id = " . $supplierStockData[0]['ast_order'];
                        if ($supplierOrderData = $db->query($supplierOrderQuery)->fetchAll()) {
                            $data[$i]['supp_ordernumber'] = $supplierStockData[0]['ast_order'] . " - " . $supplierOrderData[0]['aor_reference'];
                            $data[$i]['supp_orderdate'] = $supplierOrderData[0]['aor_date'];
                        }
                    }
                } else {
                    $supplierOrderProdQuery = "select acc_orders.aor_id, acc_orders.aor_date, acc_orders.aor_reference from acc_orders left join acc_orderprod on acc_orders.aor_id = acc_orderprod.aop_order where acc_orderprod.aop_product = " . $data[$i]['rmac_productID'] . " and acc_orders.aor_supplier = " . $data[$i]['rmac_supplier'] . " order by acc_orders.aor_date desc";
                    if ($supplierOrderProdData = $db->query($supplierOrderProdQuery)->fetchAll()) {
                        $data[$i]['supp_ordernumber'] = $supplierOrderProdData[0]['aor_id'] . " - " . $supplierOrderProdData[0]['aor_reference'];
                        $data[$i]['supp_orderdate'] = $supplierOrderProdData[0]['aor_date'];
                    }
                }

                break;

            case 4:
                if ($data[$i]['rmac_servicetag'] != null) {
                    $supplierStockQuery = "select dst_order from dco_stock where dst_servicetag = '" . $data[$i]['rmac_servicetag'] . "'";
                    if ($supplierStockData = $db->query($supplierStockQuery)->fetchAll()) {
                        $supplierOrderQuery = "select dor_date, dor_reference from dco_orders where dor_id = " . $supplierStockData[0]['dst_order'];
                        if ($supplierOrderData = $db->query($supplierOrderQuery)->fetchAll()) {
                            $data[$i]['supp_ordernumber'] = $supplierStockData[0]['dst_order'] . " - " . $supplierOrderData[0]['dor_reference'];
                            $data[$i]['supp_orderdate'] = $supplierOrderData[0]['dor_date'];
                        }
                    }
                } else {
                    $supplierOrderProdQuery = "select dco_orders.dor_id, dco_orders.dor_date, dco_orders.dor_reference from dco_orders left join dco_orderprod on dco_orders.dor_id = dco_orderprod.dop_order where dco_orderprod.dop_product = " . $data[$i]['rmac_productID'] . " and dco_orders.dor_supplier = " . $data[$i]['rmac_supplier'] . " order by dco_orders.dor_date desc";
                    if ($supplierOrderProdData = $db->query($supplierOrderProdQuery)->fetchAll()) {
                        $data[$i]['supp_ordernumber'] = $supplierOrderProdData[0]['dor_id'] . " - " . $supplierOrderProdData[0]['dor_reference'];
                        $data[$i]['supp_orderdate'] = $supplierOrderProdData[0]['dor_date'];
                    }
                }

                break;
            
            default:
                $data[$i]['supp_ordernumber'] = " - ";
                $data[$i]['supp_orderdate'] = " - ";

                break;
        }
    }


    $numRows = 0;
    $numRowsTotal = 0;

    $query_count = "select count(*) as c from rmac_items where $_SFC ";
    $query_count2 = "select count(*) as c from rmac_items where $_SF ";

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
} 