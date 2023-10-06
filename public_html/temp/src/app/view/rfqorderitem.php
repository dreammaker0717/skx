<?php
if (isset($id)) {
    $id = intval($id);
}

$db = M::db();
include PATH_CONFIG . "/constants.php";
?>
<div class="page-body">
	<div class="container-fluid">
		<div class="col-lg-11" style="margin:0 auto;">
			<div class="card card-lg">
				<div class="card-body" style="padding:3rem 1rem;">
					<div style="margin-left:1rem">
						<?php if (isset($id)) {
							echo "<h2>ORDER #" . $id . "</h2>";
							} else {
							echo "<h2>Create an Order for New Items</h2>";
							echo '<div class="text-muted">Fill in the details in these options and then use one of the methods below to create an order.<br />
							</div> ';
						} ?>
					</div>
					<fieldset class="form-fieldset" style="width:90%; margin:20px auto;">
						<?php if (isset($id)) {
							$ord = $db->get("rfq_orders", "*", ["rfqo_id" => $id]);
						} ?>
						<div class="mb-3">
							<form id="formdata">
								<div class="row">
									<?php if (isset($ord)) { ?>
									<div class="col-auto">
										<label class="mr-sm-2">State</label>
										<h3 style="padding:0.3rem 0"><?php if (isset($ord)) {
											  echo $ord["rfqo_state"];
										  } ?></h3>
									</div>
									<?php } ?>
									<div class="col-auto">
										<label class="mr-sm-2">Order Date</label>
										<h3 style="padding:0.3rem 0"><?php if (isset($ord)) {
											  echo $ord["rfqo_date"];
										  } ?></h3>
									</div>
									<div class="col-auto">
										<label class="mr-sm-2">Reference</label>
										<h3 style="padding:0.3rem 0"><?php if (isset($ord)) {
											  echo $ord["rfqo_reference"];
										  } ?></h3>
									</div>
									<div class="col-auto">
										<label class="mr-sm-2">Currency</label>
										<h3 style="padding:0.3rem 0">
											<?php if (isset($ord)) {
												switch ($ord["rfqo_currency"]) {
													case "USD":
														echo "US Dollar";
														break;
													case "GBP":
														echo "GBP";
														break;
													case "EUR":
														echo "Euro";
														break;
													case "RMB":
														echo "RMB";
														break;
													default:
														"";
														break;
												}
											} ?>
										</h3>
									</div>

									<div class="col-auto">
										<label class="mr-sm-2">Supplier</label>
										<h3 style="padding:0.3rem 0"><?php if (isset($ord)) {
											  $supplier = $db->get("suppliers", "sp_name", ["sp_id" => $ord["rfqo_supplier"]]);
											  echo $supplier;
										  } ?></h3>
									</div>
								</div>
							</form>
						</div>
					</fieldset>

					
					<?php if (
					!isset($ord) ||
					(isset($ord) && $ord["rfqo_state"] !== "Completed")
					) { ?>
					<div style="width:90%; text-align:left;margin:0 auto;">
						<h3 style="margin-bottom:0;margin-top:2rem;";>Non-Dell Products</h3>
						<span class="text-muted">Use this method for creating orders for non-Dell products. Add the products and quantities, then click on the "Create" button at the bottom to create the order. Serial numbers can be entered after creating the order.</span>
					</div>
					<fieldset class="form-fieldset" style="width:90%; margin:20px auto;">	
					
	
			<?php } ?>
						<div class="row mt-3">
							<div class="col-md-12">
								<div class="card">
									<div class="card-body">
										<div class="table-responsive">
										<table class='table table-vcenter card-table' id="pr-table">
									<thead>
										<tr>
											<th style="width:10%">SKU</th>
											<th style="width:50%">Description</th>
											<th style="width:8%">Status</th>
											<th style="width:8%">Price</th>
											<th style="width:8%">Qty</th>
											<th style="width:8%">Total</th>
											<th style="width:8%">Arrived</th>
										</tr>
									</thead>
									<tbody>
									</tbody>
								</table>
							</div>
				</div>
			</div>
			<div class="card">
									<div class="card-body">
										<div class="table-responsive">
										<table class='table table-vcenter card-table' id="st-table">
									<tbody>
									</tbody>
								</table>
							</div>
				</div>
			</div>
			</div>
			</div>
			<div class="col-auto" style='margin-top:20px'>
				<button class='btn btn-success' onClick="CreateStockIns()" type='button'>Create Stock-Ins</button>
			</div>
		</fieldset>

			</div>
		</div>
	</div>		
</div>		


</div>
</div>
<script>
var listData = [];
var id = 0;
$(function() {
<?php
$data = array();
if (isset($ord)) {
	$opQuery = "SELECT id, rfqop_prodtype, rfqop_product, rfqop_quantity, rfqop_price, rfqop_arrived, rfqop_suppliercomments FROM rfq_orderproducts WHERE rfqo_id=" . $id;
	$opData = $db->query($opQuery)->fetchAll();
	$index = 0;
	foreach ($opData as $type) {
		$partQuery = null;
		if ($type['rfqop_prodtype'] == 1) {
			$partQuery = "SELECT npr_name, npr_sku, npr_suppliercomments FROM nwp_products WHERE npr_id=" . $type['rfqop_product'];
			$partData = $db->query($partQuery)->fetchAll();
			$newArr = ["id" => $type['id'], "name" => $partData[0]['npr_name'], "sku" => $partData[0]['npr_sku'], "quantity" => $type['rfqop_quantity'], "price" => $type['rfqop_price'], "arrived" => $type['rfqop_arrived'], "suppliercomments" => $type['rfqop_suppliercomments'], "prodtype" => 1];
			$data[$index] = $newArr;
			$index++;
		} else if ($type['rfqop_prodtype'] == 2) {
			$partQuery = "SELECT npr2_name, npr2_sku, npr2_suppliercomments FROM nwp_products2 WHERE npr2_id=" . $type['rfqop_product'];
			$partData = $db->query($partQuery)->fetchAll();
			$newArr = ["id" => $type['id'], "name" => $partData[0]['npr2_name'], "sku" => $partData[0]['npr2_sku'], "quantity" => $type['rfqop_quantity'], "price" => $type['rfqop_price'], "arrived" => $type['rfqop_arrived'], "suppliercomments" => $type['rfqop_suppliercomments'], "prodtype" => 2];
			$data[$index] = $newArr;
			$index++;
		} else if ($type['rfqop_prodtype'] == 3) {
			$partQuery = "SELECT apr_name, apr_sku, apr_suppliercomments FROM aproducts WHERE apr_id=" . $type['rfqop_product'];
			$partData = $db->query($partQuery)->fetchAll();
			$newArr = ["id" => $type['id'], "name" => $partData[0]['apr_name'], "sku" => $partData[0]['apr_sku'], "quantity" => $type['rfqop_quantity'], "price" => $type['rfqop_price'], "arrived" => $type['rfqop_arrived'], "suppliercomments" => $type['rfqop_suppliercomments'], "prodtype" => 3];
			$data[$index] = $newArr;
			$index++;
		} else if ($type['rfqop_prodtype'] == 4) {
			$partQuery = "SELECT dp_name, dp_sku, dp_suppliercomments FROM dell_part WHERE dp_id=" . $type['rfqop_product'];
			$partData = $db->query($partQuery)->fetchAll();
			$newArr = ["id" => $type['id'], "name" => $partData[0]['dp_name'], "sku" => $partData[0]['dp_sku'], "quantity" => $type['rfqop_quantity'], "price" => $type['rfqop_price'], "arrived" => $type['rfqop_arrived'], "suppliercomments" => $type['rfqop_suppliercomments'], "prodtype" => 4];
			$data[$index] = $newArr;
			$index++;
		}
	}
}
		foreach ($data as $k => $v) {
	    	echo "listData.push({ 
	    		id:".$v["id"].
	    		", prodtype:".$v["prodtype"].
	        	", name:\"".addslashes($v["name"]).
	        	"\", sku:\"".$v["sku"].
	        	"\", suppliercomments:\"".addslashes($v["suppliercomments"]).
	        	"\", quantity:".$v["quantity"].
	        	", price:". round($v["price"], 2).
	        	", arrived:".$v["arrived"].
	        	"});\r\n";
		}
		if (isset($id)) {
			echo "id = " . $id . ";";
		}
	?>

	document.title = "Order # <?php if (isset($id)) { echo $id; } ?>" + document.title;
	redrawTable();
});

var dataindex = 0;
function  redrawTable(){
	$('#pr-table tbody').empty();
	$('#st-table tbody').empty();
	var subtotal = 0;
	listData.forEach(item => {
		var status = null;
		if (item['arrived'] == 0) {
			status = "ON Order";
		} else if (item['arrived'] > 0 && item['arrived'] < item['quantity']){
			status = "Part Arrived";
		} else {
			status = "Complete";
		}
		var color = null;
		switch (item['prodtype']) {
			case 1:
				color = "green";
				break;
			case 2:
				color = "blue";
				break;
			case 3:
				color = "red";
				break;
			case 4:
				color = "magenta";
				break;
			default:
				color = "";
				break;
		}
		var subtotalpart = Number((item['quantity']*item['price']).toFixed(2));
		$('#pr-table > tbody:last').append('<tr><td><div style="border-left: 6px solid ' + color + '; padding-left: 6px; height: auto;">' + item['sku'] + '</div></td><td>' + item['name'] + '<br><p style="color:grey; margin-bottom: 0px!important;">' + item['suppliercomments'] + '</p></td><td>' + status + '</td><td>' + item['price']  + '</td><td>' + item['quantity'] + '</td><td>' + subtotalpart + '</td><td><input type="number" data-index="' + dataindex + '" onkeyup="ArkeyUp(event, ' + item['id'] + ', ' + item['quantity'] + ')" class="comment form-control form-control-sm" value="' + item['arrived'] + '"></td></tr>');
		dataindex++;
		subtotal += subtotalpart;
	});
	subtotal = Number(subtotal.toFixed(2));
	var labelfee = <?php if (isset($ord)) { echo round($ord['rfqo_label_fee'], 2); } ?>;
	var shipfee = <?php if (isset($ord)) { echo round($ord['rfqo_ship_fee'], 2); } ?>;
	var bankfee = <?php if (isset($ord)) { echo round($ord['rfqo_bank_fee'], 2); } ?>;
	var surcharge = <?php if (isset($ord)) { echo round($ord['rfqo_surcharge'], 2); } ?>;
	var credit = <?php if (isset($ord)) { echo round($ord['rfqo_credit'], 2); } ?>;
	var discount = <?php if (isset($ord)) { echo round($ord['rfqo_discount'], 2); } ?>;
	var total = subtotal + labelfee + shipfee + bankfee + surcharge - credit - discount;
	$('#st-table > tbody:last').append('<tr><td style="width:10%;"></td><td style="width:50%;"></td><td style="width:8%;"></td><td style="width:8%;"></td><td style="width:8%; text-align: right; font-size: 0.7rem; color: #232e3c;">Subtotal: </td><td style="width:8%; font-size: 0.7rem;">' + subtotal + '</td><td style="width:8%;"></td></tr>');
	$('#st-table > tbody:last').append('<tr><td style="width:10%;"></td><td style="width:50%;"></td><td style="width:8%;"></td><td style="width:8%;"></td><td style="width:8%; text-align: right; font-size: 0.7rem; color: #232e3c;">Label Fee: </td><td style="width:8%; font-size: 0.7rem;">' + labelfee + '</td><td style="width:8%;"></td></tr>');
	$('#st-table > tbody:last').append('<tr><td style="width:10%;"></td><td style="width:50%;"></td><td style="width:8%;"></td><td style="width:8%;"></td><td style="width:8%; text-align: right; font-size: 0.7rem; color: #232e3c;">Ship Fee: </td><td style="width:8%; font-size: 0.7rem;">' + shipfee + '</td><td style="width:8%;"></td></tr>');
	$('#st-table > tbody:last').append('<tr><td style="width:10%;"></td><td style="width:50%;"></td><td style="width:8%;"></td><td style="width:8%;"></td><td style="width:8%; text-align: right; font-size: 0.7rem; color: #232e3c;">Bank Fee: </td><td style="width:8%; font-size: 0.7rem;">' + bankfee + '</td><td style="width:8%;"></td></tr>');
	$('#st-table > tbody:last').append('<tr><td style="width:10%;"></td><td style="width:50%;"></td><td style="width:8%;"></td><td style="width:8%;"></td><td style="width:8%; text-align: right; font-size: 0.7rem; color: #232e3c;">Surcharge: </td><td style="width:8%; font-size: 0.7rem;">' + surcharge + '</td><td style="width:8%;"></td></tr>');
	$('#st-table > tbody:last').append('<tr><td style="width:10%;"></td><td style="width:50%;"></td><td style="width:8%;"></td><td style="width:8%;"></td><td style="width:8%; text-align: right; font-size: 0.7rem; color: #f92c00;">Credit: </td><td style="width:8%; font-size: 0.7rem;">' + credit + '</td><td style="width:8%;"></td></tr>');
	$('#st-table > tbody:last').append('<tr><td style="width:10%;"></td><td style="width:50%;"></td><td style="width:8%;"></td><td style="width:8%;"></td><td style="width:8%; text-align: right; font-size: 0.7rem; color: #f92c00;">Discount: </td><td style="width:8%; font-size: 0.7rem;">' + discount + '</td><td style="width:8%;"></td></tr>');
	$('#st-table > tbody:last').append('<tr><td style="width:10%;"></td><td style="width:50%;"></td><td style="width:8%;"></td><td style="width:8%;"></td><td style="width:8%; text-align: right; font-size: 1.2rem; color: #232e3c;">Total: </td><td style="width:8%; font-size: 1.2rem;">' + total + '</td><td style="width:8%;"></td></tr>');
}

function ArkeyUp(e, id, quantity){
    console.log("Event here ",e);
    var data = e.target.value;
    if(e.code=="Enter") {
		if (data <= quantity) {
			e.preventDefault();
        	var $this = $(e.target);
        	var index = parseFloat($this.attr('data-index'));
        	$('[data-index="' + (index + 1).toString() + '"]').focus();
			$.ajax({ 
            	url:"/rfqorderajax",
            	data:{ action: "update_arrived", data: data, id: id},
            	type:'POST', 
            	success:function(a) {
            	a = JSON.parse(a);                                   
            	if(a.success) {
            		listData.forEach(item => {
            			if (item['id'] == id) {
            				item['arrived'] = data;
            			}
            		})
            		redrawTable();
                	new_toast("success","Success.");
            	}
            	else 
                	new_toast("danger","Error! Reason is "+a.error);
            	} 
        	});
		} else {
			new_toast("danger","Error! Arrived cannot be greater than quantity.");
		}
    }
}
</script>