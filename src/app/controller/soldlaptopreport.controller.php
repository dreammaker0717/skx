<?php

include(PATH_CONFIG."/constants.php");

$db = M::db();

if($action=="sold_laptops_check") {
    $start = intval($_POST["start"]);
    $length = intval($_POST["length"]);
    $stockList = $_POST["stocklist"];

    if ($length == -1) {
        $length = "100000";
    }
    
    $_SF = "";
    $_SFC = $_SF;

    $_PARA = [];
    $SEARCH = $_POST["search"]["value"];
    if (!empty($SEARCH)) {
        $_SF .= " AND (products.pr_name like :search OR stock.st_lastcomment like :search)";
        $_PARA[":search"] = "%" . $SEARCH . "%";
    }

    $query = "select stock.st_id, stock.st_servicetag, stock.st_lastcomment, stock.st_cost, stock.st_soldprice, stock.st_allocatedto, stock.st_solddate, products.pr_name, sales_orders.order_number, sales_orders.sales_channel, sales_orders.country
    from stock left join products on stock.st_product = products.pr_id left join sales_orderserials on stock.st_servicetag = sales_orderserials.serial_number left join sales_orderitems on sales_orderserials.sales_orderitems_id = sales_orderitems.id left join sales_orders on sales_orders.id = sales_orderitems.sales_order_id
    where stock.st_id in ($stockList) and stock.st_status = 16 $_SF
    order by stock.st_solddate
    limit $start,$length";

    $data = $db->query($query,$_PARA)->fetchAll();
    
    $numRows = 0;

    $query_count = "select count(*) as c from stock left join products on stock.st_product = products.pr_id left join sales_orderserials on stock.st_servicetag = sales_orderserials.serial_number left join sales_orderitems on sales_orderserials.sales_orderitems_id = sales_orderitems.id left join sales_orders on sales_orders.id = sales_orderitems.sales_order_id where stock.st_id in ($stockList) and stock.st_status = 16 $_SFC";
    $query_count2 = "select count(*) as c from stock left join products on stock.st_product = products.pr_id left join sales_orderserials on stock.st_servicetag = sales_orderserials.serial_number left join sales_orderitems on sales_orderserials.sales_orderitems_id = sales_orderitems.id left join sales_orders on sales_orders.id = sales_orderitems.sales_order_id where stock.st_id in ($stockList) and stock.st_status = 16 $_SF";

    $numRowsTotal = $db->query($query_count2, $_PARA)->fetchAll();
    $numRows = $db->query($query_count)->fetchAll();

    $output = array(
        "draw" => intval($_POST["draw"]),
        "iTotalRecords" => $numRows[0]["c"],
        "iTotalDisplayRecords" => $numRowsTotal[0]["c"] ?? 0,
        "data" => $data,
        "t" => "r0",
    );
    echo json_encode(utf8ize($output));
} else if($action=="stockid_exists_check"){
    $stockList = $_POST["stocklist"];

    $stockids = preg_split ("/\,/", $stockList);
    $notFoundList = $stockids;
    $checkExistsSql = "select st_id from stock where st_id in ($stockList) and st_status = 16";
    if ($listExistsInt = $db->query($checkExistsSql)) {
        $listExists = $listExistsInt->fetchAll();
        foreach ($listExists as $stid) {
            array_splice($notFoundList, array_search($stid['st_id'], $notFoundList), 1);
        }
    }
    echo json_encode(['success' => true, 'notFoundList' => $notFoundList]);
} else {
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

