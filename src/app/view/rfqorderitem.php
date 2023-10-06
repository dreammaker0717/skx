<style>
	.skxw100 {width:100px;}
.skxw50 {width:50px;}
	.skxcenter {text-align:center; display:block;}
	table.skxtotals td {padding:3px; border:0; font-size:0.8rem;}

	
</style>


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
										<h3 style="padding:0.3rem 0" id="orderState"><?php if (isset($ord)) {
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
									<?php if (isset($ord)) {?>
									<div class="col-auto">
										<span>Payment Status</span>
										<div class="form-check form-switch mt-2">
										  <input class="form-check-input" type="checkbox" role="switch" id="paymentStatusCheck" <?php if($ord["rfqo_payment"] == 1) echo "checked"; ?> onclick='handlePaymentStatusCheckClick(this);'>
										  <label class="form-check-label" id="paymentStatusCheckLabel" for="paymentStatusCheck"><?php if($ord["rfqo_payment"] == 1) echo "Paid"; else echo "Unpaid"; ?></label>
										</div>
									</div>
									<div class="col-auto">
										<span>VAT Type</span>
										<select class='form-control' id="vat_type" onChange="updateVATType()">
											<?php 
											$vats = $db->query("SELECT * from vat_rates")->fetchAll();
											foreach ($vats as $vat) {
												$selected = "";
												if ($ord["rfqo_vatid"] == $vat['vat_id']) {
													$selected = "selected";
												}
												echo "<option value='" . $vat['vat_id'] . "' " . $selected . ">" . $vat['vat_label'] . "</option>";
											}
											?>
										</select>
									</div>
									<?php }?>
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
							<div class="col-md-12" style="width:90%;margin:0 auto;">
								<div class="card">
									<div class="card-body">
										<div class="table-responsive">
										<table class='table table-vcenter card-table' id="pr-table">
									<thead>
										<tr>
											<th style="width:15%">SKU</th>
											<th style="width:45%">Description</th>
											<th style="width:8%;text-align:center;">Status</th>
											<th style="width:8%;text-align:center;">Price</th>
											<th style="width:8%;text-align:center;">Qty</th>
											<th style="width:8%;text-align:center;">Total</th>
											<th style="width:8%;text-align:center;">Arrived</th>
										</tr>
									</thead>
									<tbody>
									</tbody>
								</table>
							</div>
				</div>
			</div>
			<div class="card" style="margin-top:1rem;">
									<div class="card-body">
										<div class="table-responsive">
										<table class='table table-vcenter card-table skxtotals' id="st-table">
									<tbody>
									</tbody>
								</table>
							</div>
				</div>
			</div>
			</div>
			</div>
			<div class="row justify-content-between">
				<div class="col-auto" style='margin-top:20px'>
					<button class='btn btn-success' onClick="location.href='/rfqorders'" type='button'>Back</button>
					<button class='btn btn-warning' onClick="DownloadPdf()" type='button'>Download PDF</button>
				</div>
				<div class="col-auto" style='margin-top:20px'>
					<button class='btn btn-success' onClick="CreateStockIns()" type='button'>Create Stock-Ins</button>
				</div>
			</div>
		</fieldset>

			</div>
		</div>
	</div>
	<div class="col-lg-11 mt-4" style="margin:0 auto;display:none;" id="stock_in_card">
			<div class="card card-lg">
				<div class="card-body" style="padding:3rem 1rem;">
					<div style="margin-left:1rem">
						<h2>Stock Ins</h2>
					</div>
						 <div class="form-fieldset" style="width:90%; margin:20px auto;">
						<div class="table-responsive">
								<table class='table table-vcenter card-table' id="stin-table">
									<thead>
										<tr>
											<th style="width:20%;">Date</th>
											<th style="width:50%;">Reference</th>
											<th style="width:15%;">State</th>
											<th style="width:15%;">Order</th>
										</tr>
									</thead>
									<tbody>
									</tbody>
								</table>
							</div>
					</div>
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
			$newArr = ["id" => $type['id'], "name" => $partData[0]['npr_name'], "sku" => $partData[0]['npr_sku'], "quantity" => $type['rfqop_quantity'], "price" => $type['rfqop_price'], "arrived" => $type['rfqop_arrived'], "suppliercomments" => $type['rfqop_suppliercomments'], "prodid" => $type['rfqop_product'], "prodtype" => 1];
			$data[$index] = $newArr;
			$index++;
		} else if ($type['rfqop_prodtype'] == 2) {
			$partQuery = "SELECT npr2_name, npr2_sku, npr2_suppliercomments FROM nwp_products2 WHERE npr2_id=" . $type['rfqop_product'];
			$partData = $db->query($partQuery)->fetchAll();
			$newArr = ["id" => $type['id'], "name" => $partData[0]['npr2_name'], "sku" => $partData[0]['npr2_sku'], "quantity" => $type['rfqop_quantity'], "price" => $type['rfqop_price'], "arrived" => $type['rfqop_arrived'], "suppliercomments" => $type['rfqop_suppliercomments'], "prodid" => $type['rfqop_product'], "prodtype" => 2];
			$data[$index] = $newArr;
			$index++;
		} else if ($type['rfqop_prodtype'] == 3) {
			$partQuery = "SELECT apr_name, apr_sku, apr_suppliercomments FROM aproducts WHERE apr_id=" . $type['rfqop_product'];
			$partData = $db->query($partQuery)->fetchAll();
			$newArr = ["id" => $type['id'], "name" => $partData[0]['apr_name'], "sku" => $partData[0]['apr_sku'], "quantity" => $type['rfqop_quantity'], "price" => $type['rfqop_price'], "arrived" => $type['rfqop_arrived'], "suppliercomments" => $type['rfqop_suppliercomments'], "prodid" => $type['rfqop_product'], "prodtype" => 3];
			$data[$index] = $newArr;
			$index++;
		} else if ($type['rfqop_prodtype'] == 4) {
			$partQuery = "SELECT dp_name, dp_sku, dp_suppliercomments FROM dell_part WHERE dp_id=" . $type['rfqop_product'];
			$partData = $db->query($partQuery)->fetchAll();
			$newArr = ["id" => $type['id'], "name" => $partData[0]['dp_name'], "sku" => $partData[0]['dp_sku'], "quantity" => $type['rfqop_quantity'], "price" => $type['rfqop_price'], "arrived" => $type['rfqop_arrived'], "suppliercomments" => $type['rfqop_suppliercomments'], "prodid" => $type['rfqop_product'], "prodtype" => 4];
			$data[$index] = $newArr;
			$index++;
		}
	}
}
		foreach ($data as $k => $v) {
	    	echo "listData.push({ 
	    		id:".$v["id"].
	    		", prodtype:".$v["prodtype"].
	    		", prodid:".$v["prodid"].
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
	LoadStockIns();
});

var dataindex = 0;
function  redrawTable(){
	$('#pr-table tbody').empty();
	$('#st-table tbody').empty();
	var subtotal = 0;
	listData.forEach(item => {
		var status = null;
		if (item['arrived'] == 0) {
			status = "<span class='skxw100 badge bg-yellow'>ON Order</span>";
		} else if (item['arrived'] > 0 && item['arrived'] < item['quantity']){
			status = "<span class='skxw100 badge bg-cyan'>Part Arrived</span>";
		} else {
			status = "<span class='skxw100 badge bg-lime'>Complete</span>";
		}
		var color = null;
		switch (item['prodtype']) {
			case 1:
				color = "green";
				$skuc = "teal"
				break;
			case 2:
				color = "blue";
				$skuc = "indigo"
				break;
			case 3:
				color = "red";
				$skuc = "pink"

				break;
			case 4:
				color = "magenta";
				$skuc = "purple"

				break;
			default:
				color = "";
				break;
		}
		var subtotalpart = item['quantity']*item['price'];
		$('#pr-table > tbody:last').append('<tr><td><div style="border-left: 6px solid ' + color + '; padding-left: 6px; height: auto;"><span style="width:140px; user-select: text;" class="badge badge-outline text-' + $skuc + '">' + item['sku'] + '</span></div></td><td><span style="font-weight:600">' + item['name'] + '</span><br><p style="color:grey; margin-bottom: 0px!important;">' + item['suppliercomments'] + '</p></td><td>' + status + '</td><td><span class="skxcenter">' + item['price'].toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})  + '</span></td><td><span class="badge bg-blue skxw50">' + item['quantity'] + '</span></td><td><span class="skxcenter">' + subtotalpart.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</span></td><td><input type="number" data-index="' + dataindex + '" onkeyup="ArkeyUp(event, ' + item['id'] + ')" onchange="ArOnChange(event, ' + item['id'] + ')" class="comment form-control form-control-sm" value="' + item['arrived'] + '"></td></tr>');
		dataindex++;
		subtotal += subtotalpart;
	});
	var labelfee = <?php if (isset($ord)) { echo $ord['rfqo_label_fee']; } ?>;
	var shipfee = <?php if (isset($ord)) { echo $ord['rfqo_ship_fee']; } ?>;
	var bankfee = <?php if (isset($ord)) { echo $ord['rfqo_bank_fee']; } ?>;
	var surcharge = <?php if (isset($ord)) { echo $ord['rfqo_surcharge']; } ?>;
	var credit = <?php if (isset($ord)) { echo $ord['rfqo_credit']; } ?>;
	var discount = <?php if (isset($ord)) { echo $ord['rfqo_discount']; } ?>;
	var total = subtotal + labelfee + shipfee + bankfee + surcharge + credit + discount;
	$('#st-table > tbody:last').append('<tr><td style="width:15%;"></td><td style="width:45%;"></td><td style="width:8%;"></td><td style="width:8%;"></td><td style="width:8%; text-align: right; color: #232e3c;font-weight:600;">Subtotal: </td><td style="width:8%; font-weight:600; text-align:center;">' + subtotal.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</td><td style="width:8%;"></td></tr>');
	$('#st-table > tbody:last').append('<tr><td colspan="4"></td><td style="width:8%; text-align: right; color: #232e3c;">Label Fee: </td><td style="width:8%; text-align:center;">' + labelfee.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</td><td style="width:8%;"></td></tr>');
	$('#st-table > tbody:last').append('<tr><td colspan="4"></td><td style="width:8%; text-align: right; color: #232e3c;">Bank Fee: </td><td style="width:8%; text-align:center;">' + bankfee.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</td><td style="width:8%;"></td></tr>');
	$('#st-table > tbody:last').append('<tr><td colspan="4"></td></td><td style="width:8%; text-align: right; color: #232e3c;">Surcharge: </td><td style="width:8%; text-align:center;">' + surcharge.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</td><td style="width:8%;"></td></tr>');
	$('#st-table > tbody:last').append('<tr><td colspan="4"></td></td><td style="width:8%; text-align: right; color: #f92c00;font-weight:800;">Credit: </td><td style="width:8%; text-align:center;">' + credit.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</td><td style="width:8%;"></td></tr>');
	$('#st-table > tbody:last').append('<tr><td colspan="4"></td><td style="width:8%; text-align: right; color: #f92c00;font-weight:800;">Discount: </td><td style="width:8%; text-align:center;">' + discount.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</td><td style="width:8%;"></td></tr>');
	$('#st-table > tbody:last').append('<tr><td colspan="4"></td><td style="width:8%; text-align: right; color: #000000;font-weight:800;">Total: </td><td style="width:8%; text-align:center;color:#000000;font-weight:800;">' + total.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</td><td style="width:8%;"></td></tr>');}

function ArkeyUp(e, ordprod_id){
    console.log("Event here ",e);
    if(e.code=="Enter") {
	    var data = e.target.value;
	    let listItem = [];
	    listData.forEach(item => {
	    	if (item['id'] == ordprod_id) {
	    		listItem = item;
	    	}
	    });
	    let quantity = listItem['quantity'];
	    let arrived = listItem['arrived'];
		if (data <= quantity) {
			e.preventDefault();
        	var $this = $(e.target);
        	var index = parseFloat($this.attr('data-index'));
        	$('[data-index="' + (index + 1).toString() + '"]').focus();
			$.ajax({ 
            	url:"/rfqorderajax",
            	data:{ action: "update_arrived", data: data, ordprod_id: ordprod_id},
            	type:'POST', 
            	success:function(a) {
            	a = JSON.parse(a);                              
            	if(a.success) {
            		listData.forEach(item => {
            			if (item['id'] == ordprod_id) {
            				item['arrived'] = data;
            			}
            		});
            		redrawTable();
            		$('#orderState').text(a.status);
                	new_toast("success","Success.");
            	} else if(!a.success && a.isLow){
					new_toast("danger","Error! Arrived cannot be less than already arrived.");
            	} else 
                	new_toast("danger","Error! Reason is "+a.error);
            	} 
        	});
		} else {
			new_toast("danger","Error! Arrived cannot be greater than quantity.");
		}
    }
}

function ArOnChange(e, ordprod_id){
    console.log("Event here ",e);
    var data = e.target.value;
    listData.forEach(item => {
    	if (item['id'] == ordprod_id) {
    		if (!isNaN(data) && !isNaN(parseFloat(data))) {
    			item['arrived'] = parseFloat(data);
    		}
    	}
    });
}

function handlePaymentStatusCheckClick(cb){
	var data = 0;
	var dataText = "Unpaid";
	if (cb.checked) {
		data = 1;
		dataText = "Paid";
	}
	$.ajax({ 
		url:"/rfqorderajax",
		data:{ action: "update_payment_status", data: data, id: id},
		type:'POST', 
		success:function(a) {
			a = JSON.parse(a);                              
			if(a.success) {
				$('#paymentStatusCheckLabel').text(dataText);
		    	new_toast("success","Success.");
			} else {
		    	new_toast("danger","Error! Reason is "+ a.error);
			}
		}
	});
}

function updateVATType(){
	var data = $('#vat_type').val();
	$.ajax({ 
		url:"/rfqorderajax",
		data:{ action: "update_vat_type", data: data, id: id},
		type:'POST', 
		success:function(a) {
			a = JSON.parse(a);                              
			if(a.success) {
		    	new_toast("success","Success.");
			} else {
		    	new_toast("danger","Error! Reason is "+ a.error);
			}
		}
	});
}

function DownloadPdf(){
	window.location="/rfqorderpdfajax/" + <?php echo isset($id) ? $id : 0; ?>;
}

function CreateStockIns(){
	let hasArrived = false;
	listData.forEach(item => {
		if (item['arrived'] > 0) {
			hasArrived = true;
		}
	});
	if (hasArrived) {
		$.ajax({ 
			url:"/rfqorderajax",
			data:{ action: "create_stockin", data: listData, id: id},
			type:'POST', 
			success:function(a) {
			a = JSON.parse(a);                              
			if(a.success) {
		    	new_toast("success","Success.");
		    	LoadStockIns();
			} else 
				if (a.alreadyExists) {
					new_toast("warning","Stock In Already Exists");
				} else {
					new_toast("danger","Error! Reason is "+a.error);
				}
			} 
		});
	} else {
		new_toast("warning","At least some product must arrive to create Stock-In.");
	}
}

function LoadStockIns(){
	$('#stin-table tbody').empty();
	$.ajax({ 
    	url:"/rfqorderajax",
    	data:{ action: "get_stockins", id: id},
    	type:'POST', 
    	success:function(a) {                        
	    	if(a.success) {
	    		if (a.data.length > 0) {
	    			$('#stock_in_card').show();
					$('#stin-table tbody').empty();
	    			a.data.forEach(item => {
	    				switch (item.type){
	    					case "nwp":
	    						$('#stin-table > tbody:last').append('<tr><td><span style="border-left: 6px solid green; padding-left: 6px; height: auto;"></span>' + item.date + '</td><td>' + item.reference + '</td><td>' + item.state + '</td><td> <a href="/itemorder/' + item.id + '">Order #' + item.id + '</a></td>');
	    						break;

	    					case "aor":
	    						$('#stin-table > tbody:last').append('<tr><td><span style="border-left: 6px solid red; padding-left: 6px; height: auto;"></span>' + item.date + '</td><td>' + item.reference + '</td><td>' + item.state + '</td><td> <a href="/accorder/' + item.id + '">Order #' + item.id + '</a></td>');
	    						break;

	    					case "dor":
	    						$('#stin-table > tbody:last').append('<tr><td><span style="border-left: 6px solid magenta; padding-left: 6px; height: auto;"></span>' + item.date + '</td><td>' + item.reference + '</td><td>' + item.state + '</td><td> <a href="/componentorder/' + item.id + '">Order #' + item.id + '</a></td>');
	    						break;
	    					default:
	    						break;
	    				}

	    			});
	    		}

	    	} else 
	        	new_toast("danger","Error! Reason is "+a.error);
	    	} 
	});
}
</script>