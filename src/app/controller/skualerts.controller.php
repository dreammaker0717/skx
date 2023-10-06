<?php
include PATH_CONFIG . "/constants.php";

$db = M::db();
if ($action == "get_list") {

    $start = intval($_POST["start"]);
    $length = intval($_POST["length"]);
    if ($length == -1) {
        $length = "100000";
    }

    $search_field = "";
    $search_field_count = "";
    $SEARCH = $_POST["search"]["value"];
    if (!empty($SEARCH)) {
        $search_field = "where skualerts.sku like '%" . $SEARCH . "%' ";
        $search_field_count = "where sku like '%" . $SEARCH . "%' ";
    }

    $query = "select skualerts.id, skualerts.sku, skualerts_messages.message, skualerts_messages.id as message_id
        from skualerts
        left join skualerts_messages
        on skualerts.message_id = skualerts_messages.id "
        . $search_field .
        "order by skualerts.id
        limit " . $start . ", " . $length;

    $data = $db->query($query)->fetchAll();

    $numRows = 0;

    $query_count = "select count(*) as c from skualerts " . $search_field_count;

    $numRows = $db->query($query_count)->fetchAll();

    $output = array(
        "draw" => intval($_POST["draw"]),
        "iTotalRecords" => $numRows[0]["c"],
        "iTotalDisplayRecords" => $numRows[0]["c"],
        "data" => $data,
        "t" => "r0",
    );
    $json = json_encode(utf8ize($output));
    echo $json;
} else if ($action == "add_sku_alert" || $action == "update_sku_alert") {
    $sku = $_POST["sku"];
    $messageID = $_POST["messageID"];

    $dataSKUAlert = array(
        'sku' => $sku,
        'message_id' => $messageID,
    );

    if ($action == "add_sku_alert") {
        $db->insert('skualerts', $dataSKUAlert);
    } else if ($action == "update_sku_alert") {
        $db->update('skualerts', $dataSKUAlert, ['id' => $_POST["skuAlertID"]]);
    }
    header('Content-Type: application/json; charset=utf-8');
    http_response_code(200);
    echo json_encode('{ "success" : true }');
} else if ($action == "delete_sku_alert") {
    $deleteID = intval($_POST["skuAlertID"]);
    $db->delete("skualerts", ["id" => $deleteID]);
    header('Content-Type: application/json; charset=utf-8');
    http_response_code(200);
    echo json_encode('{ "success" : true }');
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
