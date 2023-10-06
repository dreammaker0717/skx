<?php
error_reporting(E_ALL);
error_reporting(-1);
ini_set('error_reporting', E_ALL);

include PATH_CONFIG . "/constants.php";

$db = M::db();

if ($action == "printLabel") {
    $data = $_POST["data"];
    $serial = $data["serial"];

    header("Content-Type: application/json; charset=utf-8");
    $rs = $db
        ->exec(
            "select ast_id,ast_status,aproducts.apr_condition, 'acc_stock' as tar from  acc_stock left join aproducts on aproducts.apr_id=ast_product where ast_servicetag=:serial union all 
             select nst_id,nst_status,nwp_products.npr_condition ,'nwp_stock' as tar from nwp_stock left join nwp_products on nwp_products.npr_id=nst_product where nst_servicetag=:serial union all
             select dst_id,dst_status,dell_part.dp_condition, 'dell_part' as tar from  dco_stock left join dell_part on dell_part.dp_id=dst_product where dco_stock.dst_servicetag=:serial
             ",
            [":serial" => [$serial, PDO::PARAM_STR]]
        )
        ->fetchAll();
    if (count($rs) == 1) {
        $output = [
            "success" => true,
            "data" => $rs,
        ];
        http_response_code(200);
        echo json_encode(utf8ize($output));
        exit();
    } else {
        $output =
            "{ \"success\":false, \"error\":\"Serial Number Not Found!\" }";
        http_response_code(500);
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
