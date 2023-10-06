<?php

$db = M::db();
if($action=="searchcustomer") {



    $numRows = 0;
    $numRowsTotal = 0;
    $_SF =" 1=1 ";
    $_SFC= $_SF;

    $_PARA=[];

    $SEARCH = $_POST["keyword"];
    if(!empty($SEARCH)) {

      $_SF.= " AND (customers.c_name like:search OR customers.c_email:search)";
      $_PARA[":search"]   = "%".$SEARCH."%";
      $_PARA[":search_no"]   = $SEARCH;

    }

     $query="SELECT c_name from customers
        WHERE  $_SF";

    $data = $db->query($query,$_PARA)->fetchAll();

    $output = array(
		      "data"	=> 	$data
    );

    echo json_encode($output);

}else if($action=="search") {

    $sv = $_POST["search"]["value"];
    $start = intval($_POST["start"]);
    $length = intval($_POST["length"]);

    $order_field = "inv_inv.inv_id desc";

    if(!empty($_POST["order"])) {
        $order_field =  $_POST['columns'][$_POST['order']['0']['column']]['data'] ." ". strtoupper( $_POST['order']['0']['dir']  );
    }

    $numRows = 0;
    $numRowsTotal = 0;
    $_SF =" 1=1 ";
    $_SFC= $_SF;

    $_PARA=[];

    $SEARCH = $_POST["search"]["value"];
    if(!empty($SEARCH)) {

      $_SF.= " AND (customers.c_name like:search OR inv_inv.inv_status like :search OR inv_inv.payment_type like :search OR inv_inv.inv_id = :search_no )";
      $_PARA[":search"]   = "%".$SEARCH."%";
      $_PARA[":search_no"]   = $SEARCH;

      //  $_SF.= " AND ( inv_inv.inv_id = :search_no OR inv_inv)";
      //  $_PARA[":search"]   = "%".$SEARCH."%";
      //  $_PARA[":search_no"]   = $SEARCH;

    }

     $query=
        "SELECT inv_inv.inv_id, inv_inv.inv_date,inv_inv.inv_status,inv_inv.payment_type, customers.c_name
        FROM inv_inv left join customers on inv_inv.customer_id = customers.customer_id
        WHERE  $_SF
        order by $order_field
        limit $start,$length";

    $query_count = "select count(*) as c FROM inv_inv left join customers on inv_inv.customer_id = customers.customer_id WHERE  $_SFC ";
    $query_count2 = "select count(*) as c FROM inv_inv left join customers on inv_inv.customer_id = customers.customer_id WHERE  $_SF ";

    $data = $db->query($query,$_PARA)->fetchAll();
    $numRowsTotal = $db->query($query_count2,$_PARA)->fetchAll();
    $numRows = $db->query($query_count)->fetchAll();

    $output = array(
		"draw"	=>	intval($_POST["draw"]),
		"iTotalRecords"	=> 	$numRows[0]["c"],
		"iTotalDisplayRecords"	=>  $numRowsTotal[0]["c"],
		"data"	=> 	$data,
        "t" => "r0"
	);

    echo json_encode($output);

} else if($action=="saveinvoice") {
        try
    {
        $data = $_POST["data"];
        $id = $_POST["id"];
        $totalItems = 0;
        foreach ($data["listData"] as $listEntry) {
            $totalItems += $listEntry['quantity'];
        }
        /*if($id==0) {
            $dataSaveRfq = array(
                'rfq_date' => date("Y-m-d", strtotime(str_replace("/","-",$data["date"]))),
                'rfq_user' => $_POST['user'],
                'rfq_state' => "Draft",
                'rfq_total_items' => $totalItems,
                'rfq_total_ordered' => 0,
                'rfq_reference' => $data['reference'],
                'rfq_currency' => $data['currency'],
            );
            $db->insert('rfq_rfq', $dataSaveRfq);
            $id = $db->id();

            foreach ($data["listData"] as $entry) {
                $dataSaveItems = array(
                    'rfq_id' => $id,
                    'rfq_prodtype' => $entry['prodtype'],
                    'rfq_product' => $entry['id'],
                    'rfq_quantity' => $entry['quantity'],
                    'rfq_price' => $entry['price'],
                    'rfq_suppliercomments' => $entry['suppliercomments'],
                );
                $db->insert('rfq_items', $dataSaveItems);
            }

            header('Content-Type: application/json; charset=utf-8');
            http_response_code(200);
            echo json_encode('{ "success" : true , "id":'.$id.'}');
*/
        //} else {
            $dataSaveRfq = array(
              'inv_date' => date("Y-m-d", strtotime(str_replace("/","-",$data["date"]))),
              'customer_id' => $data['customer'],
              'inv_user' => $_POST['user'],
              'inv_status' => $data['status'],
              'inv_reference' => $data['reference'],
              'payment_type' => $data['payment_type'],
              'inv_currency' => $data['currency'],
              'total_items' => $totalItems
            );
            $db->update('inv_inv', $dataSaveRfq, ["inv_id" => $id]);

            $db->delete("inv_items", ["inv_id" => $id]);

            foreach ($data["listData"] as $entry) {
                $dataSaveItems = array(
                  'inv_id' => $id,
                  'inv_prodtype' => $entry['prodtype'],
                  'item_product_id' => $entry['id'],
                  'item_qty' => $entry['quantity'],
                  'item_price' => $entry['price']
                );
                $db->insert('inv_items', $dataSaveItems);
            }

            header('Content-Type: application/json; charset=utf-8');
            http_response_code(200);
            echo json_encode('{ "success" : true , "id":'.$id.'}');
        //}

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode('{ "success" : false, "error": "'. $e->getMessage().'" }');
    }
} else if($action=="createinvoice") {
    try {
        $data = $_POST["data"];
        $id = $_POST["id"];
        $totalItems = 0;
        foreach ($data["listData"] as $listEntry) {

          if($listEntry['taxrate'] ==0){
            $netPrice1 = $listEntry['price'];
            $tax1 = 0;
          }else{
            $netPrice1 = number_format((($listEntry['price']/($listEntry['price']+$listEntry['taxrate']))*100),2);
            $tax1 = number_format(($listEntry['price']-$netPrice1), 2);
          }
          //echo $netPrice1;
          //  $totalItems += $listEntry['quantity'];
          //  $netAmount += $listEntry['quantity']*$listEntry['price'];
          //  $vatAmount += ($listEntry['quantity']*$listEntry['price'])*$listEntry['taxrate']/100;
          $totalItems += $listEntry['quantity'];
          $netAmount += $listEntry['quantity']*$netPrice1;
          $vatAmount += $tax1;

        }

 $totalAmount +=$netAmount+$vatAmount;

        $dataSaveInvoice = array(
                'inv_date' => date("Y-m-d", strtotime(str_replace("/","-",$data["date"]))),
                'customer_id' => $data['customer'],
                'inv_user' => $_POST['user'],
                'inv_reference' => $data['reference'],
                'inv_status' => $data['status'],
                'payment_type' => $data['payment_type'],
                'payment_reference' => $data['pay_reference'],
                'inv_currency' => $data['currency'],
                'ship_fee' => $data['shipfee'],
                'discount' => $data['discount'],
                'netamount' => $netAmount,
                'total_vat' => $vatAmount,
                'total_amount' => $totalAmount,
                'total_items' => $totalItems

            );
        $db->insert('inv_inv', $dataSaveInvoice);
        $rfqoID = $db->id();
        $netPrice = 0;
        $tax = 0;
        foreach ($data["listData"] as $entry) {
          if($entry['taxrate'] ==0){
            $netPrice = $entry['price'];
            $tax = 0;
          }else{
            $netPrice = number_format((($entry['price']/($entry['price']+$entry['taxrate']))*100),2);
            $tax = number_format(($entry['price']-$netPrice), 2);
          }

            $dataSaveInvoiceItems = array(
                'inv_id' => $rfqoID,
                'inv_prodtype' => $entry['prodtype'],
                'item_product_id' => $entry['id'],
                'item_qty' => $entry['quantity'],
                'item_price' => $entry['price'],
                'item_net' => $netPrice,
                'item_tax' => $tax,
                'item_subtotal' => $netPrice*$entry['quantity'],
                'tax_rate' => $entry['taxrate'],
            );
            $db->insert('inv_items', $dataSaveInvoiceItems);
        }

        header('Content-Type: application/json; charset=utf-8');
        http_response_code(200);
        echo json_encode('{ "success" : true , "id":'.$id.'}');
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode('{ "success" : false, "error": "'. $e->getMessage().'" }');
    }
} else if($action=="deleteinvoice") {
    try {
        $data = $_POST["data"];
        $db->delete("inv_items", ["inv_id" => $data["id"]]);
        $db->delete("inv_inv", ["inv_id" => $data["id"]]);
        header('Content-Type: application/json; charset=utf-8');
        http_response_code(200);
        echo json_encode('{ "success" : true}');
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode('{ "success" : false, "error": "'. $e->getMessage().'" }');
    }
} else {
    exit();
}
