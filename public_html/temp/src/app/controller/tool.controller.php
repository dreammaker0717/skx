<?php

$db = M::db();


if($action =="dell_part_finder_export_ajax") {

        $data= $db->query("select `dp_sku`,`dp_name`,`dp_box_label`,`dp_box_subtitle`,`dp_supplier_stock_code`,`dp_condition`,`dp_keyboard_language`,`dp_image`,`dp_listed`,`dp_mpn`,`dp_category` from dell_part")->fetchAll(PDO::FETCH_ASSOC);
       header('Content-Type: text/csv');
       header('Content-Disposition: attachment; filename="components.csv"');       
       $fp = fopen('php://output', 'wb');
       fputcsv($fp, ["sku","name","box_label","box_subtitle","supplier_stock_code","condition","keyboard_language","image"],',');
        foreach ($data as $line) {            
            fputcsv($fp, $line, ',');
        }
        fclose($fp);    

}


if($action=="aproductsfromboxlabel") {
    $data = $_POST["data"];           
    $label = $data["label"];
    
    header('Content-Type: application/json; charset=utf-8');
    $rs = $db->exec("select * from  aproducts  where apr_box_label=:label",[ ":label"=> [$label,PDO::PARAM_STR]  ])->fetchAll();
    $output= array(
        "success"=>true,
        "data" => $rs
    );
    http_response_code(200);
    echo json_encode(utf8ize($output));
    exit();

}

if($action=="dell_part_finder_ajax") {
   
    $q="SELECT * FROM dell_part left join categories on dell_part.dp_category = categories.ct_id where 1=1 ";
    $qc = "SELECT COUNT(*) as c FROM dell_part left join categories on dell_part.dp_category = categories.ct_id where 1=1 ";
    $total = $db->count("dell_part");

    $map=[];
    if(isset($_POST["sku"]) && $_POST["sku"]!="" ) {
        $q.=" AND dp_sku like :sku ";
        $qc.=" AND dp_sku like :sku ";
        $map[":sku"] = "%".$_POST["sku"]."%";
    }
    if(isset($_POST["stockcode"]) && $_POST["stockcode"]!="" ) {
        $q.=" AND dp_supplier_stock_code like :dp_supplier_stock_code ";
        $qc.=" AND dp_supplier_stock_code like :dp_supplier_stock_code ";
        $map[":dp_supplier_stock_code"] = "%".$_POST["stockcode"]."%";
    }
    
    if(isset($_POST["ass"]) && $_POST["ass"]!="" ) {
        $q.=" AND dp_name like :ass ";
        $qc.=" AND dp_name like :ass ";
        $map[":ass"] = "%".$_POST["ass"]."%";
    }
        
    if(isset($_POST["category"]) && $_POST["category"]!="" ) {
        $q.=" AND dp_category = :dp_category ";
        $qc.=" AND dp_category = :dp_category ";
        $map[":dp_category"] = $_POST["dp_category"];
    }
     
    if(isset($_POST["pn"]) && $_POST["pn"]!="" ) {
        if(strlen( $_POST["pn"] ) == 23) {
            $q.=" AND dp_name like :pn ";
            $qc.=" AND dp_name like :pn ";
            $map[":pn"] = "%".  substr($_POST["pn"],3,5)."%";
            
        }
        else {
            $q.=" AND dp_name like :pn ";
            $qc.=" AND dp_name like :pn ";
            $map[":pn"] = "%".$_POST["pn"]."%";
        }
    }
    if(isset($_POST["plam"]) && $_POST["plam"]!="" ) {                                                
        if(strlen( $_POST["plam"] ) == 23) {
            $q.=" AND dp_name like :plam ";
            $qc.=" AND dp_name like :plam ";
            $map[":plam"] = "%".  substr($_POST["plam"],3,5)."%";
            
        }
        else {
            $q.=" AND dp_name like :plam ";
            $qc.=" AND dp_name like :plam ";
            $map[":plam"] = "%".$_POST["plam"]."%";
        }
        
    }
    if(isset($_POST["keyboard"]) && $_POST["keyboard"]!="" ) {                                                
        if(strlen( $_POST["keyboard"] ) == 23) {
            $q.=" AND dp_name like :keyboard ";
            $qc.=" AND dp_name like :keyboard ";
            $map[":keyboard"] = "%".  substr($_POST["keyboard"],3,5)."%";
            
        }
        else {
            $q.=" AND dp_name like :keyboard ";
            $qc.=" AND dp_name like :keyboard ";
            $map[":keyboard"] = "%".$_POST["keyboard"]."%";
        }
    
    }

    $sv = $_POST["search"]["value"];
    if($sv != "") {

        $qc.= " AND ( dp_id like '%$sv%' or dp_sku like '%$sv%' or dp_name like '%$sv%' )";
        $q.= " AND ( dp_id like '%$sv%' or dp_sku like '%$sv%' or dp_name like '%$sv%' )";
    }

    $ival =intval($_POST["order"]["0"]["column"]);
    $dp1 = ($ival==0) ? "dp_id" : (($ival == 1) ? "dp_sku" : (($ival== 2) ? "dp_name" : (($ival == 3) ? "dp_supplier_stock_code" : (($ival == 4) ? "dp_box_label" : (($ival == 5) ? "dp_box_subtitle" : "dp_sku")))));
    $asc = $_POST["order"]["0"]["dir"]=="asc" ? "asc" : "desc";

    //echo $dp1 . " ".$asc;

    //var_dump($qc);
    //exit;
    
    $totalc = $db->query($qc, $map)->fetchAll();


    $buff= '{ "iTotalRecords":'.$total.', "iTotalDisplayRecords":'.$totalc[0]["c"].', "draw":'.intval($_POST["draw"]).',  "data": [ ';
    //echo $q." LIMIT ".intval($_POST["start"]).",".intval($_POST["length"])." order by $dp1 $asc";
    header('Content-Type: application/json; charset=utf-8');
    $data = $db->query($q." order by $dp1 $asc LIMIT ".intval($_POST["start"]).",".intval($_POST["length"]),$map)->fetchAll();
    $data1 = array();

    foreach($data as $key=>$value){
        $id = $value["dp_id"];

        array_push($data1, array(
            "dp_id"  => $value["dp_id"],
            "dp_sku" => $value["dp_sku"],
            "dp_image" => $value["dp_image"],
            "print" => "<a href='javascript:PrintLabel($id)'><img src='https://img.icons8.com/material-outlined/24/000000/print.png'/></a>",
            "dp_name" => $value["dp_name"],
            "dp_magqty" => $value["dp_magqty"],
            "dp_lowstock" => "<input type=hidden  name=\"hlow_".$value["dp_id"]."\"  iden=\"s".$value["dp_id"]."\" class=\"hcomment form-control form-control-sm\" value=\"".$value["dp_lowstock"]."\"><input type=text  name=\"low_".$value["dp_id"]."\" onkeyup=\"LowkeyUp(event,".$value["dp_id"].")\" iden=\"s".$value["dp_id"]."\" class=\"comment form-control form-control-sm\" value=\"".$value["dp_lowstock"]."\">",

            "dp_mpn" => $value["dp_mpn"],
            "dp_supplier_stock_code" => $value["dp_supplier_stock_code"],
            "dp_box_label" => $value["dp_box_label"],
            "dp_box_subtitle" => $value["dp_box_subtitle"],
            "dp_condition" => $value["dp_condition"],
            "dp_category" => $value["dp_category"],
            //"print2" => "<form method='POST' action='/stocksprintajax/print-sku-qty' target='_blank'><div class='input-group input-group-sm mb-3'><input type='text' name='qty'  id='dp_id_".$value["dp_id"]."' class='form-control' placeholder='Quantity' aria-label='Quantity' aria-describedby='basic-addon2'><div class='input-group-append'><input type='hidden' name=sku value='".$value["dp_box_label"]."' /><button class='btn btn-sm btn-outline-secondary' type='submit'>Create</button></div></div></form>",
            "actions" => "<button onClick='updateModelShow(\"dp_id\",".$id.")' type='button' class='btn btn-sm btn-primary'>Update<Update</button>",

            "ct_id" => $value["ct_id"],
            "ct_name" => $value["ct_name"],


            "0"  => $value["dp_id"],
            "1" => $value["dp_sku"],
            "2" => "<a href='https://www.ndc.co.uk/pub/media/catalog/product".$value["dp_image"]."' onClick='return linkModelImage(this,event)' target=_blank><img src='https://img.icons8.com/material-outlined/24/000000/image.png'/></i></a>",
            "3" => "<a href='javascript:PrintLabel($id)'><img src='https://img.icons8.com/material-outlined/24/000000/print.png'/></a>",
            "4" => $value["dp_name"],
            "5" => $value["dp_magqty"],
            "6" => "<input type=hidden  name=\"hlow_".$value["dp_id"]."\"  iden=\"s".$value["dp_id"]."\" class=\"hcomment form-control form-control-sm\" value=\"".$value["dp_lowstock"]."\"><input type=text  name=\"low_".$value["dp_id"]."\" onkeyup=\"LowkeyUp(event,".$value["dp_id"].")\" iden=\"s".$value["dp_id"]."\" class=\"comment form-control form-control-sm\" value=\"".$value["dp_lowstock"]."\">",
            "7" => $value["dp_mpn"],
            "8" => $value["dp_supplier_stock_code"],
            "9" => $value["dp_box_label"],
            "10" => $value["dp_box_subtitle"],
            "11" => $value["dp_condition"],
            "12" => $value["ct_name"],
            //"10" => "<form method='POST' action='/stocksprintajax/print-sku-qty' target='_blank'><div class='input-group input-group-sm mb-3'><input type='text' name='qty'  id='dp_id_".$value["dp_id"]."' class='form-control' placeholder='Quantity' aria-label='Quantity' aria-describedby='basic-addon2'><div class='input-group-append'><input type='hidden' name=sku value='".$value["dp_box_label"]."' /><button class='btn btn-sm btn-outline-secondary' type='submit'>Create</button></div></div></form>",
            "13" => "<button onClick='updateModelShow(\"dp_id\",".$id.")' type='button' class='btn btn-sm btn-primary'>Update<Update</button>"

        ));

    }


    $arr = array(
        "draw"	=>	intval($_POST["draw"]),			
		"iTotalRecords"	=> 	$total,
		"iTotalDisplayRecords"	=>  $totalc[0]["c"],
		"data"	=> 	$data1

    );


    echo json_encode(utf8ize($arr));

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