<?php
include PATH_CONFIG . "/constants.php";

$db = M::db();

if ($action == "get") {
    $data = $_POST["data"];
    $serial = $data["serial"];


    
    header("Content-Type: application/json; charset=utf-8");
    $rs = $db->exec("select * from acc_nwp_stock where ast_servicetag=:serial",
            [":serial" => [$serial, PDO::PARAM_STR]] )->fetchAll(PDO::FETCH_ASSOC);

    if (count($rs) == 1) {
        $rss = $db->exec("select ast_status,count(*) as c from acc_nwp_stock where apr_sku =:sku group by ast_status;",
        [":sku" => [$rs[0]["apr_sku"], PDO::PARAM_STR]] )->fetchAll(PDO::FETCH_ASSOC);



        $output = [
            "success" => true,
            "data" => $rs,
            "data2" => $rss
        ];
        http_response_code(200);
        echo json_encode(utf8ize($output));
        exit();
    }
    else if (count($rs) > 1) {
        $output =
        "{ \"success\":false, \"error\":\"Serial Number Found more than once!!\" }";
        http_response_code(200);
        echo json_encode(utf8ize($output));
    } else {
        $output =
            "{ \"success\":false, \"error\":\"Serial Number Not Found!\" }";
        http_response_code(200);
        echo json_encode(utf8ize($output));
    }
}

function utf8ize($d)
{
    if (is_array($d)) {
        foreach ($d as $k => $v) {
            $d[$k] = utf8ize($v);
        }
    } elseif (is_string($d)) {
        return utf8_encode($d);
    }
    return $d;
}
