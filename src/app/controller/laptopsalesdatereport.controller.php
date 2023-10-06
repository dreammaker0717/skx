<?php
use alhimik1986\PhpExcelTemplator\params\CallbackParam;
use alhimik1986\PhpExcelTemplator\params\ExcelParam;
use alhimik1986\PhpExcelTemplator\PhpExcelTemplator;
use alhimik1986\PhpExcelTemplator\setters\CellSetterArrayValueSpecial;

require_once("vendor/autoload.php");

include PATH_CONFIG . "/constants.php";

$db = M::db();
if ($action == "search") {

    $start = intval($_POST["start"]);
    $length = intval($_POST["length"]);
    if ($length == -1) {
        $length = "100000";
    }

    $startDate = $_POST["startDate"];
    $endDate = $_POST["endDate"];
    $vatType = $_POST["vatType"];
    $status = $_POST["status"];

    $_SF = "l.order_date between " . $startDate . " AND " . $endDate;
    if ($vatType != "All") {
        $_SF .= " AND t.soldvattype = '" . $vatType . "'";
    }
    if ($status != "All") {
        if ($status == "refunded_all") {
            $_SF .= " AND (t.status = 'refunded_not_like' OR t.status = 'refunded_faulty' OR t.status = 'refunded_undelivered' OR t.status = 'refunded_other')";
        } else {
            $_SF .= " AND t.status = '" . $status . "'";
        }
    }
    $_SFC = $_SF;

    $order_field = "l.order_date desc";
    if (!empty($_POST["order"])) {
        $order_field = "l." . $_POST['columns'][$_POST['order']['0']['column']]['data'] . " " . strtoupper($_POST['order']['0']['dir']);
    }

    $_PARA = [];
    $SEARCH = $_POST["search"]["value"];
    if (!empty($SEARCH)) {
        $_SF .= " AND (l.order_number like :search OR l.recipient like :search OR s.st_servicetag like :search OR r.requested_sku like :search OR p.sp_name like :search)";
        $_PARA[":search"] = "%" . $SEARCH . "%";
    }

    $query = "select l.id, l.order_date, l.order_number, l.recipient, l.country, l.status, group_concat(concat(p.sp_name ,'<a href=\'/order/' , s.st_order, '\'> #', s.st_order, '</a>') separator '<br>') as supplier, group_concat(r.requested_sku separator '<br>') as sku, group_concat(s.st_servicetag separator '<br>') as servicetag, group_concat(s.st_cost separator '<br>') as cost, group_concat(t.soldnetprice separator '<br>') as net, group_concat(t.soldvat separator '<br>') as vat, group_concat(t.soldprice separator '<br>') as gross, group_concat((t.soldnetprice - s.st_netprice) separator '<br>') as profit, group_concat(t.soldvattype separator '<br>') as vattype, group_concat(t.status separator '<br>') as item_status

    from laptop_orders l left join laptop_requested_products r on r.laptop_order_id = l.id left join laptop_orderserials t on t.laptop_requesteditem_id = r.id left join stock s on s.st_servicetag = t.serial_number left join orders o on o.or_id = s.st_order left join suppliers p on p.sp_id = o.or_supplier 
    where  $_SF
    group by l.id
    order by $order_field
    limit $start,$length";

    $data = $db->query($query, $_PARA)->fetchAll();

    $numRows = 0;
    $numRowsTotal = 0;

    $query_count = "select count(*) over() as c from laptop_orders l left join laptop_requested_products r on r.laptop_order_id = l.id left join laptop_orderserials t on t.laptop_requesteditem_id = r.id left join stock s on s.st_servicetag = t.serial_number left join orders o on o.or_id = s.st_order left join suppliers p on p.sp_id = o.or_supplier where $_SFC group by l.id";
    $query_count2 = "select count(*) over() as c from laptop_orders l left join laptop_requested_products r on r.laptop_order_id = l.id left join laptop_orderserials t on t.laptop_requesteditem_id = r.id left join stock s on s.st_servicetag = t.serial_number left join orders o on o.or_id = s.st_order left join suppliers p on p.sp_id = o.or_supplier where $_SF group by l.id";

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

} else if($action == "laptopsalesdate_excel"){
    $startDate = $_POST["startDate"];
    $endDate = $_POST["endDate"];
    $vatType = $_POST["vatType"];
    $status = $_POST["status"];

    define("SPECIAL_ARRAY_TYPE", CellSetterArrayValueSpecial::class);

    $order_date = array();
    $order_number = array();
    $recipient = array();
    $country = array();
    $supplier = array();
    $sku = array();
    $servicetag = array();
    $cost = array();
    $net = array();
    $vat = array();
    $gross = array();
    $profit = array();
    $vattype = array();

    $_SF = "l.order_date between " . $startDate . " AND " . $endDate;
    if ($vatType != "All") {
        $_SF .= " AND t.soldvattype = '" . $vatType . "'";
    }
    if ($status != "All") {
        if ($status == "refunded_all") {
            $_SF .= " AND (t.status = 'refunded_not_like' OR t.status = 'refunded_faulty' OR t.status = 'refunded_undelivered' OR t.status = 'refunded_other')";
        } else {
            $_SF .= " AND t.status = '" . $status . "'";
        }
    }
    $_PARA = [];
    $SEARCH = $_POST["search"];
    if ($SEARCH != "null") {
        $_SF .= " AND (l.order_number like :search OR l.recipient like :search OR s.st_servicetag like :search OR r.requested_sku like :search OR p.sp_name like :search)";
        $_PARA[":search"] = "%" . $SEARCH . "%";
    }

    $query = "select l.id, l.order_date, l.order_number, l.recipient, l.country, l.status, concat(p.sp_name ,' #', s.st_order) as supplier, r.requested_sku as sku, s.st_servicetag as servicetag, s.st_cost as cost, t.soldnetprice as net, t.soldvat as vat, t.soldprice as gross, (t.soldnetprice - s.st_netprice) as profit, t.soldvattype as vattype

    from laptop_orders l left join laptop_requested_products r on r.laptop_order_id = l.id left join laptop_orderserials t on t.laptop_requesteditem_id = r.id left join stock s on s.st_servicetag = t.serial_number left join orders o on o.or_id = s.st_order left join suppliers p on p.sp_id = o.or_supplier 
    where  $_SF
    order by l.order_date desc, l.order_number desc";

    $data = $db->query($query, $_PARA)->fetchAll();

    $orderNumberTemp = "";

    foreach ($data as $entry) {
        if ($orderNumberTemp == "") {
            $orderNumberTemp = $entry['order_number'];

            $order_number[] = $entry['order_number'];

            $date = new DateTime("@".$entry['order_date']);
            $date->setTimezone(new DateTimeZone('Europe/London'));
            $order_date[] = $date->format('Y-m-d H:i:s');

            $recipient[] = $entry['recipient'];

            $country[] = $entry['country'];
        } else {
            if ($orderNumberTemp == $entry['order_number']) {
                $order_number[] = "";

                $order_date[] = "";

                $recipient[] = "";

                $country[] = "";
            } else {
                $orderNumberTemp = $entry['order_number'];

                $order_number[] = $entry['order_number'];

                $date = new DateTime("@".$entry['order_date']);
                $date->setTimezone(new DateTimeZone('Europe/London'));
                $order_date[] = $date->format('Y-m-d H:i:s');

                $recipient[] = $entry['recipient'];

                $country[] = $entry['country'];
            }
        }

        $supplier[] = $entry['supplier'];
        $sku[] = $entry['sku'];
        $servicetag[] = $entry['servicetag'];
        $cost[] = $entry['cost'];
        $net[] = $entry['net'];
        $vat[] = $entry['vat'];
        $gross[] = $entry['gross'];
        $profit[] = $entry['profit'];
        $vattype[] = $entry['vattype'];
    }

    $params = [
        "[order_date]" => new ExcelParam(SPECIAL_ARRAY_TYPE, $order_date),
        "[order_number]" => new ExcelParam(SPECIAL_ARRAY_TYPE, $order_number),
        "[recipient]" => new ExcelParam(SPECIAL_ARRAY_TYPE, $recipient),
        "[country]" => new ExcelParam(SPECIAL_ARRAY_TYPE, $country),
        "[supplier]" => new ExcelParam(SPECIAL_ARRAY_TYPE, $supplier),
        "[sku]" => new ExcelParam(SPECIAL_ARRAY_TYPE, $sku),
        "[servicetag]" => new ExcelParam(SPECIAL_ARRAY_TYPE, $servicetag),
        "[cost]" => new ExcelParam(SPECIAL_ARRAY_TYPE, $cost),
        "[net]" => new ExcelParam(SPECIAL_ARRAY_TYPE, $net),
        "[vat]" => new ExcelParam(SPECIAL_ARRAY_TYPE, $vat),
        "[gross]" => new ExcelParam(SPECIAL_ARRAY_TYPE, $gross),
        "[profit]" => new ExcelParam(SPECIAL_ARRAY_TYPE, $profit),
        "[vattype]" => new ExcelParam(SPECIAL_ARRAY_TYPE, $vattype),
    ];
    PhpExcelTemplator::outputToFile(__DIR__ . "/laptop_sales_date_excel_template.xlsx", "./laptop_sales_date_excel.xlsx", $params);
}

function utf8ize($d)
{
    if (is_array($d)) {
        foreach ($d as $k => $v) {
            $d[$k] = utf8ize($v);
        }
    } else if (is_string($d)) {
        return utf8_encode($d);
    }
    return $d;
}