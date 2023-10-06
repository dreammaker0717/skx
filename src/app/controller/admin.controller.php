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

$_FILTER=[];
$_SELECT="*";
$_JOIN=[];
if($part == "suppliers") {
    $part="suppliers";
    $_SEARCH= ["sp_name[~]"];
    $key="sp_id";
}
if($part == "supplier_groups") {
    $key="sg_id";
    $_SEARCH= ["sg_name[~]"];
    $_JOIN=[ "[>]suppliers" => ["sg_supplier_id"=>"sp_id"] ];
}
if($part == "users") {
    $_SEARCH= ["fullname[~]","email[~]"];
    $_FILTER["deleted"] = "0";
    $_JOIN = [ "[>]userroles" => ["user_role"=>"ur_id"] ];
    $key="user_id";
}
if($part == "customers") {
    $_SEARCH= ["c_name[~]","c_email[~]"];
    $key="customer_id";
}
if($part == "laptopspecs") {
    $part="specs";
    $_FILTER["spec_laptop"] = "1";
    $_JOIN=[ "[>]categories" => ["spec_categ"=>"ct_id"] ];
    $_SEARCH= ["spec_name[~]"];
    $key="spec_id";
}
if($part == "desktopspecs") {
    $part="specs";
    $_FILTER["spec_laptop"] = "0";
    $_JOIN=[ "[>]categories" => ["spec_categ"=>"ct_id"] ];
    $_SEARCH= ["spec_name[~]"];
    $key="spec_id";
}

if($part == "categories") {
    $_SEARCH= ["ct_name[~]"];
    $key="ct_id";
}
if($part == "accproductmap" || $part == "aproducts_map") {
    $part="aproducts_map";
    $_SEARCH= ["apr_name[~]","apr_sku[~]","apm_pn[~]"];
    $_SELECT = ["apm_id","apm_pn","apm_aproducts_id", "apr_name_sku" => Medoo::raw("CONCAT(apr_name,' ',apr_sku,' (',apr_condition,')')"), "apr_sku" ];
    $_JOIN=[ "[>]aproducts" => ["apm_aproducts_id"=>"apr_id"] ];
    $key="apm_id";
}
if($part == "manufacturers") {
    $_SEARCH= ["mf_name[~]"];
    $key="mf_id";
}

if($part == "dell_part") {

    $key="dp_id";
}

if($part == "subsubcategories") {

    $_SEARCH= ["ss_name[~]"];
    $key="ss_id";
    $_JOIN=[ "[>]subcategories" => ["ss_subcategory"=>"sc_id"] ];
}
if($part == "subcategories") {
    $key="sc_id";
    $_SELECT = ["sc_id","sc_name","sc_category","sc_del","ct_name"];
    $_SEARCH= ["sc_name[~]"];
    $_JOIN=[ "[>]categories" => ["sc_category"=>"ct_id"] ];
}
if($part == "products") {
    $key="pr_id";
    $_SELECT = [
            "pr_id","pr_name","pr_title","pr_description","pr_category","pr_subcategorie","pr_subsubcategorie",
            "pr_manufacturer","pr_soldon","pr_part","pr_partnumber","pr_unique","pr_del",
            "ct_name","ss_name","sc_name","mf_name","son_name","uqt_name","yn_name"
        ];
    $_SEARCH= ["pr_name[~]", "sc_name[~]"];
    $_JOIN=[
        "[>]categories" => ["pr_category"=>"ct_id"] ,
        "[>]subcategories" => ["pr_subcategorie"=>"sc_id"] ,
        "[>]subsubcategories" => ["pr_subsubcategorie"=>"ss_id"] ,
        "[>]manufacturers" => ["pr_manufacturer"=>"mf_id"],
        "[>]soldon" => ["pr_soldon" => "son_id"],
        "[>]uniquetype" => ["pr_unique" => "uqt_id"],
        "[>]yesno" => ["pr_part" => "yn_id"]

    ];
}
if($part == "aproducts") {
    $key="apr_id";
    $_SELECT = [
            "apr_id","apr_name","apr_sku","apr_condition","apr_box_label","apr_box_subtitle","apr_image","apr_del","apr_category" ,"apr_mpn", "apr_isassembled"
            ,"ct_id","ct_name","apm_pns"
        ];
    $_SEARCH= ["apr_name[~]","apr_sku[~]"];

    $_JOIN=[
        "[>]categories" => ["apr_category"=>"ct_id"] ,
        "[>]aproducts_map_group_concat" => ["apr_id"=>"apm_aproducts_id"] ,

    ];



}
if($part == "newitemproducts") {
    $part = "nwp_products";
    $key="npr_id";
    $_SELECT = [
            "npr_id","npr_name","npr_sku","npr_condition","npr_box_label","npr_box_subtitle","npr_image","npr_del","npr_category"
            ,"ct_id","ct_name","npm_pns","npr_mpn", "npr_magqty", "npr_lowstock", "npr_isassembled"
        ];
    $_SEARCH= ["npr_name[~]","npr_sku[~]"];

    $_JOIN=[
        "[>]categories" => ["npr_category"=>"ct_id"] ,
        "[>]nwp_products_map_group_concat" => ["npr_id"=>"npm_aproducts_id"] ,

    ];



}

if($part == "newitemproducts2") {
    $part = "nwp_products2";
    $key="npr2_id";
    $_SELECT = [
            "npr2_id","npr2_name","npr2_sku","npr2_condition","npr2_box_label","npr2_box_subtitle","npr2_image","npr2_del","npr2_category"
            ,"ct_id","ct_name","npm_pns","npr2_mpn", "npr2_magqty", "npr2_lowstock", "npr2_suppliercomments"
        ];
    $_SEARCH= ["npr2_name[~]","npr2_sku[~]"];

    $_JOIN=[
        "[>]categories" => ["npr2_category"=>"ct_id"] ,
        "[>]nwp_products_map_group_concat" => ["npr2_id"=>"npm_aproducts_id"] ,

    ];



}




if($action=="insert") {
    try {
        $data = $_POST["data"];
        unset($data["id"]);
        if($part=="aproducts_map")
            unset($data["apr_sku"]);



        $ir = $db->insert($part,$data);

        header('Content-Type: application/json; charset=utf-8');
        http_response_code(200);
        echo json_encode('{ "success" : true }');
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode('{ "success" : false, "error": "'. $e->getMessage().'" }');
    }
}
if($action=="update") {

    try {
        $data = $_POST["data"];
        unset($data["id"]);
        $db->update($part,$data,[$key => $_POST["id"] ]);
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
        if($part=="aproducts_map") {
           $r =  $db->delete($part,[$key => $_POST["id"] ]);
        }
        if($part=="dell_part")
        {
           $r = $db->delete($part,[$key => $_POST["id"] ]);

           var_dump($r);
        }

        header('Content-Type: application/json; charset=utf-8');
        http_response_code(200);
        echo json_encode('{ "success" : true }');
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode('{ "success" : false, "error": "'. $e->getMessage().'" }');
    }
}
if($action=="search") {

    $sv = $_POST["search"]["value"];
    $start = intval($_POST["start"]);
    $length = intval($_POST["length"]);

    $order_field = $_POST['columns']['0']['data'];


    if(!empty($_POST["order"])) {
        $order_field = [  $_POST['columns'][$_POST['order']['0']['column']]['data']   => strtoupper( $_POST['order']['0']['dir']  ) ];
    }


   // echo $db->debug()->count($part, $_FILTER);

    $numRows = $db->count($part, $_FILTER);


    $SEARCH = $_POST["search"]["value"];
    if(!empty($SEARCH)) {


        if(count($_SEARCH)==1) {
            $_FILTER["OR"] = [
                $_SEARCH[0] => $SEARCH,
            ];
        }
        if(count($_SEARCH)==2) {
            $_FILTER["OR"] = [
                $_SEARCH[0] => $SEARCH,
                $_SEARCH[1] => $SEARCH
            ];
        }
        if(count($_SEARCH)==3) {
            $_FILTER["OR"] = [
                $_SEARCH[0] => $SEARCH,
                $_SEARCH[1] => $SEARCH,
                $_SEARCH[2] => $SEARCH
            ];
        }

    }

    $numRowsTotal = $db->count($part, $_FILTER);

    $_FILTER["LIMIT"] = [$start, $length];
    $_FILTER["ORDER"] = $order_field ;


    $t = count($_JOIN)== 0 ? "j0" : "j9";


    if(count($_JOIN)==0)
        $data = $db->select($part,$_SELECT, $_FILTER);
    else {
         //if($part=="products") echo $db->debug()->select($part,$_JOIN,$_SELECT, $_FILTER);
        $data = $db->select($part,$_JOIN,$_SELECT, $_FILTER);

    }
    //error_log($data);



    $output = array(
		"draw"	=>	intval($_POST["draw"]),
		"iTotalRecords"	=> 	$numRows,
		"iTotalDisplayRecords"	=>  $numRowsTotal,
		"data"	=> 	$data,
        "t" => $t
	);

    echo json_encode(utf8ize($output));

}
else if($action=="addaprnpn") {
    try {
        header('Content-Type: application/json; charset=utf-8');
        $newpn = $_POST["newpn"];
        $id = intval($_POST["id"]);

        if($newpn=="" ||$newpn==null)
            throw new ErrorException("Part Number can not be empty.");

        if($id==0 ||$id==null)
            throw new ErrorException("Product can not be empty.");

        $db->insert("nwp_products_map",["npm_pn"=> $newpn, "npm_aproducts_id" => $id]);


        http_response_code(200);
        echo json_encode('{ "success" : true }');
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode('{ "success" : false, "error": "'. $e->getMessage().'" }');
    }
}
else if($action=="addaprpn") {
    try {
        header('Content-Type: application/json; charset=utf-8');
        $newpn = $_POST["newpn"];
        $id = intval($_POST["id"]);

        if($newpn=="" ||$newpn==null)
            throw new ErrorException("Part Number can not be empty.");

        if($id==0 ||$id==null)
            throw new ErrorException("Product can not be empty.");

        $db->insert("aproducts_map",["apm_pn"=> $newpn, "apm_aproducts_id" => $id]);


        http_response_code(200);
        echo json_encode('{ "success" : true }');
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode('{ "success" : false, "error": "'. $e->getMessage().'" }');
    }
}
else if($action=="update_low") {
    try {
        $data = $_POST["data"];
        error_log(print_r($part,true));
        error_log(print_r($data,true));
        unset($data["id"]);
        $db->update($part,$data,[$key => $_POST["id"] ]);
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
