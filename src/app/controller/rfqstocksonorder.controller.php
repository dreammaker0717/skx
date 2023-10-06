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

    if ($length == -1) {
        $length = "100000";
    }

    $_PARA = [];

    $_SF = "rfq_orders.rfqo_state = 'On Order'";

    $_SFC = $_SF;
    
    $_PARA = [];
    $SEARCH = $_POST["search"]["value"];
    if (!empty($SEARCH)) {
        $_SF .= " AND (all_products.sku like :search OR all_products.name like :search)";
        $_PARA[":search"] = "%" . $SEARCH . "%";
    }

    $query = "SELECT rfq_orders.rfqo_id, rfq_orders.rfqo_payment, rfq_orderproducts.rfqop_prodtype, rfq_orderproducts.rfqop_quantity, rfq_orderproducts.rfqop_price, rfq_orderproducts.rfqop_suppliercomments, all_products.sku, all_products.name, all_products.suppliercomments, rfq_orders.rfqo_date, suppliers.sp_name FROM rfq_orderproducts LEFT JOIN (all_products CROSS JOIN rfq_orders CROSS JOIN suppliers) ON (all_products.prodtype=rfq_orderproducts.rfqop_prodtype AND all_products.productid=rfq_orderproducts.rfqop_product AND rfq_orders.rfqo_id=rfq_orderproducts.rfqo_id AND suppliers.sp_id = rfq_orders.rfqo_supplier) 
    WHERE $_SF
    ORDER BY rfq_orders.rfqo_id 
    DESC LIMIT $start,$length";
    
    $data = $db->query($query,$_PARA)->fetchAll();

    $numRows = 0;
    $numRowsTotal = 0;

    $query_count = "SELECT count(*) AS c FROM rfq_orderproducts LEFT JOIN (all_products CROSS JOIN rfq_orders) ON (all_products.prodtype=rfq_orderproducts.rfqop_prodtype AND all_products.productid=rfq_orderproducts.rfqop_product AND rfq_orders.rfqo_id=rfq_orderproducts.rfqo_id) WHERE $_SFC ";
    $query_count2 = "SELECT count(*) AS c FROM rfq_orderproducts LEFT JOIN (all_products CROSS JOIN rfq_orders) ON (all_products.prodtype=rfq_orderproducts.rfqop_prodtype AND all_products.productid=rfq_orderproducts.rfqop_product AND rfq_orders.rfqo_id=rfq_orderproducts.rfqo_id) WHERE $_SF ";

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

} else{
    exit();
}