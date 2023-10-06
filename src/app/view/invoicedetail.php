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
							echo "<h2>INVOICE #" . $id . "</h2>";
							} else {
							echo "<h2>Create an Invoice</h2>";
							//echo '<div class="text-muted">Fill in the details in these options and then use one of the methods below to create an order.<br />
						//	</div> ';
						} ?>
					</div>
					<fieldset class="form-fieldset" style="width:90%; margin:20px auto;">
						<?php if (isset($id)) {

							$ord = $db->get("inv_inv", "*", ["inv_id" => $id]);

						} ?>
						<div class="mb-3">
							<form id="formdata">
								<div class="row">

									<div class="col-auto">
										<label class="mr-sm-2">Invoice Date</label>
										<h3 style="padding:0.3rem 0"><?php if (isset($ord)) {
											  echo $ord["inv_date"];
										  } ?></h3>
									</div>
									<div class="col-auto">
										<label class="mr-sm-2">Reference</label>
										<h3 style="padding:0.3rem 0"><?php if (isset($ord)) {
											  echo $ord["inv_reference"];
										  } ?></h3>
									</div>
                  <div class="col-auto">
                    <label class="mr-sm-2">Status</label>
                    <h3 style="padding:0.3rem 0"><?php if (isset($ord)) {
                        echo $ord["inv_status"];
                      } ?></h3>
                  </div>
									<div class="col-auto">
										<label class="mr-sm-2">Currency</label>
										<h3 style="padding:0.3rem 0">
											<?php if (isset($ord)) {
												switch ($ord["inv_currency"]) {
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
										<label class="mr-sm-2">Customer</label>
										<h3 style="padding:0.3rem 0"><?php if (isset($ord)) {
											  $supplier = $db->get("customers", "c_name", ["customer_id" => $ord["customer_id"]]);
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
											<th style="width:10%">QUNTITY</th>
											<th style="width:50%">DESCRIPTION</th>
											<th style="width:8%">NET</th>
											<th style="width:8%">VAT</th>
											<th style="width:8%">SUBTOTAL</th>
											<th style="width:8%">TOTAL</th>
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
		<!--	<div class="col-auto" style='margin-top:20px'>
				<button class='btn btn-success' onClick="CreateStockIns()" type='button'>Create Stock-Ins</button>
			</div>-->
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

//if (isset($ord))
//{
$data = array();

if (isset($id)) {
  //echo "here";exit;
  $typeQuery = "SELECT * FROM inv_items WHERE inv_id=" . $id;
  $typeData = $db->query($typeQuery)->fetchAll();
  $price = 0;
  $index = 0;

  foreach ($typeData as $type) {

    $partQuery = "SELECT * FROM all_products WHERE productid=" . $type['item_product_id'];
    $partData = $db->query($partQuery)->fetchAll();

    $newArr = ['id' => $partData[0]['productid'],'prodtype' => $partData[0]['prodtype'],'name' => $partData[0]['name'], 'quantity' => $type['item_qty'], 'price' => $type['item_price'], 'net' => $type['item_net'], 'taxrate' => $type['tax_rate'], 'vat_amt' => $type['item_tax'], 'subtotal' => $type['item_subtotal']];
    $data[$index] = $newArr;
    $index++;

  }
}


    foreach ($data as $k => $v) {
  		echo "listData.push({
  			id: " . $v["id"] .
  			", quantity:" . $v["quantity"] .
        ", net:" . $v["net"] .
        ", vat:" . $v["vat_amt"] .
        ", subtotal:" . $v["subtotal"] .
  			", prodtype:" . $v["prodtype"] .
  			", name:\"" .addslashes($v["name"]).
  			"\", price:" . $v["price"] .
  			", taxrate:'" . $v["taxrate"] .
  				"'});\r\n";
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
  var subtotalFinal = 0;
  var vatFinal = 0;
	listData.forEach(item => {
		var status = null;

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
    if(item['taxrate']==20){
      var vat = item['vat'];
    }else{
      vat = 0;
    }
    subtotal = (item['net']*item['quantity']);
    total = item['price']*item['quantity'];
		var subtotalpart = parseFloat((item['quantity']*item['net']).toFixed(2));
		$('#pr-table > tbody:last').append('<tr><td>' + item['quantity'] + '</td><td>' + item['name'] + '</td><td>' + item['net'] + '</td><td>' + item['vat'] + '</td><td>' + item['subtotal']  + '</td><td>' + total + '</td></tr>');
		dataindex++;
		subtotalFinal += subtotal;
    vatFinal = parseFloat(parseFloat(vatFinal) + parseFloat(vat)).toFixed(2);
	});
	subtotal = parseFloat(subtotal.toFixed(2));
	var shipfee = <?php if (isset($ord)) { echo round($ord['ship_fee'], 2); } ?>;
	var discount = <?php if (isset($ord)) { echo round($ord['discount'], 2); } ?>;

  var total = (parseFloat((subtotalFinal) +parseFloat(vatFinal) + parseFloat(shipfee)) - parseFloat(discount)).toFixed(2);

	$('#st-table > tbody:last').append('<tr><td style="width:10%;"></td><td style="width:50%;"></td><td style="width:8%;"></td><td style="width:8%;"></td><td style="width:8%; text-align: right; font-size: 0.7rem; color: #232e3c;">Subtotal: </td><td style="width:8%; font-size: 0.7rem;">' + subtotalFinal + '</td><td style="width:8%;"></td></tr>');
  $('#st-table > tbody:last').append('<tr><td style="width:10%;"></td><td style="width:50%;"></td><td style="width:8%;"></td><td style="width:8%;"></td><td style="width:8%; text-align: right; font-size: 0.7rem; color: #232e3c;">Vat: </td><td style="width:8%; font-size: 0.7rem;">' + vatFinal + '</td><td style="width:8%;"></td></tr>');
	$('#st-table > tbody:last').append('<tr><td style="width:10%;"></td><td style="width:50%;"></td><td style="width:8%;"></td><td style="width:8%;"></td><td style="width:8%; text-align: right; font-size: 0.7rem; color: #232e3c;">Ship Fee: </td><td style="width:8%; font-size: 0.7rem;">' + shipfee + '</td><td style="width:8%;"></td></tr>');
	$('#st-table > tbody:last').append('<tr><td style="width:10%;"></td><td style="width:50%;"></td><td style="width:8%;"></td><td style="width:8%;"></td><td style="width:8%; text-align: right; font-size: 0.7rem; color: #f92c00;">Discount: </td><td style="width:8%; font-size: 0.7rem;">' + discount + '</td><td style="width:8%;"></td></tr>');
	$('#st-table > tbody:last').append('<tr><td style="width:10%;"></td><td style="width:50%;"> <button class="btn btn-warning" onclick="DownloadInvoicePdf()" type="button">Download PDF</button></td><td style="width:8%;"></td><td style="width:8%;"></td><td style="width:8%; text-align: right; font-size: 1.2rem; color: #232e3c;">Total: </td><td style="width:8%; font-size: 1.2rem;">' + total + '</td><td style="width:8%;"></td></tr>');
}
function DownloadInvoicePdf(){
		window.location="/invoicepdfajax/" + <?php echo isset($id) ? $id : 0; ?>;
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
