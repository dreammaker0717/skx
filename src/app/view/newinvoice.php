<?php
if (isset($id)) {
	$id = intval($id);
}

$db = M::db();
include PATH_CONFIG . "/constants.php";
?>
<style type="text/css">
.frmSearch {margin: 2px 0px;padding:0px;border-radius:4px;}
#supplier-list{float:left;list-style:none;margin-top:-3px;padding:0;width:350px;position: absolute;}
#supplier-list li{padding: 10px; background: white; border-bottom: #bbb9b9 1px solid;}
#supplier-list li:hover{background:#ece3d2;cursor: pointer;}
#email{padding: 10px;border: #a8d4b1 1px solid;border-radius:4px;}
</style>
<div class="page-body">
	<div class="container-fluid">
		<div class="col-lg-11" style="margin:0 auto;">
			<div class="card card-lg">
				<div class="card-body" style="padding:3rem 1rem;">
					<div style="margin-left:1rem;width:45%;float:left;">
						<?php if (isset($id)) {
							echo "<h2>INVOICE #" . $id . "</h2>";
						} else {

							echo "<h2>Create an Invoice</h2>";
							//echo '<div class="text-muted">Fill in the details in these options and then use one of the methods below to create an order.<br /></div> ';
						}?>
						</div>
						<div style="margin-left:1rem;width:45%;float:left;text-align:right;">
							<?php if (isset($id)) {
							//	echo "<h2>INVOICE #" . $id . "</h2>";
							} else {
									$maxInvoice = 	$db->query("SELECT  MAX(inv_id) as maxid FROM inv_inv")->fetchAll();
									$invoiceNo = ++$maxInvoice[0]['maxid'];
								echo "<h2>INVOICE #SVX00000" .$invoiceNo. "</h2>";
								//echo '<div class="text-muted">Fill in the details in these options and then use one of the methods below to create an order.<br /></div> ';
							}?>
						</div>

					<fieldset class="form-fieldset" style="width:90%; margin:20px auto;">
						<?php if (isset($id)) { $ord = $db->get("inv_inv", "*", ["inv_id" => $id]); }?>
						<div class="mb-3">
							<form id="formdata">
								<div class="row">

									<div class="col-md-3">
										<label class="form-label required">Invoice Date</label>
										<input type="text" required class="form-control" value="<?php if (isset($ord)) {echo $ord["inv_date"];}else{echo date("d/m/Y");}?>" aria-describedby="Invoice Date" id="inv_date" />
									</div>
                  <div class="col-md-3">
                    <label class="form-label">Invoice Reference</label>
                    <input type="text" class="form-control" value="<?php if (isset($ord)) {echo $ord["inv_reference"];}?>" aria-describedby="Invoice Reference" id="inv_reference" />
                  </div>
									<div class="col-md-3" >
                    <?php
										if($ord['customer_id']){
											$customer = 	$db->query("SELECT  * FROM customers where customer_id=".$ord['customer_id']
													)->fetchAll();
										}
									?>
										<label class="form-label required">Customer</label>

										<input type="text" class="form-control" value="<?php if (isset($customer)) {echo $customer[0]["c_name"];}?>" aria-describedby="Customer" id="inv_customer" required/>
										<div id="suggesstion-box"></div>
								</div>
								<input type="hidden" class="form-control" value="<?php if (isset($customer)) {echo $customer[0]["customer_id"];}?>" aria-describedby="" id="inv_customer_id" />
									<div class="col-md-3">
										<label class="form-label required" >Currency</label>
										<select class="form-control" aria-describedby="Currency" name="inv_currency" id="inv_currency" required>
											<option value=""> Please Select </option>
											<option value="USD" <?php if (isset($ord) && $ord["inv_currency"] == "USD") {echo "selected";}?> >US Dollar</option>
											<option value="GBP" <?php if (isset($ord) && $ord["inv_currency"] == "GBP") {echo "selected";}?> >GBP</option>
											<option value="EUR" <?php if (isset($ord) && $ord["inv_currency"] == "EUR") {echo "selected";}?> >Euro</option>
											<option value="RMB" <?php if (isset($ord) && $ord["inv_currency"] == "RMB") {echo "selected";}?> >RMB</option>

										</select>
									</div>

									<input type="hidden" name="inv_user" id="inv_user" value="<?php echo $_SESSION["user_id"];?>"/>
									<input type="hidden" name="inv_status" id="inv_status" value="<?php if (isset($ord)) { echo $ord["inv_status"];}?>"/>
									<input type="hidden" name="payment_type" id="payment_type" value="<?php if (isset($ord)) {echo $ord["payment_type"];}?>"/>
									<input type="hidden" name="payment_reference" id="payment_reference" value="<?php if (isset($ord)) {echo $ord["payment_reference"];}?>"/>

									<div class="row">

											<div class="col-md-12  text-right" style="margin-top: 24px;">
												<div class="form-switch form-switch-lg" style="text-align:right;">
													<input type="checkbox" class="form-check-input" id="paymentStatus">
												  </div>
											</div>
									</div>
								</div>
							</form>
						</div>
					</fieldset>


					<?php if (!isset($ord) || (isset($ord) && $ord["rfq_state"] !== "Completed")) {?>

					<fieldset class="form-fieldset" style="width:90%; margin:20px auto;">
					<div class="row g-3">
						<div class="mb-3">
							<form class="row g-3">
								<div class="col-auto" style="width:45%">
									<label for="inputPassword2" class="form-label">SKU </label>

									<input list="skus" id="sku"  class="form-control" placeholder="Start typing SKU or product name..." autocomplete="off">
									<input type="hidden" name="sku" id="sku-hidden"  class="form-control">
									<datalist id=skus  >
										<?php
										$dataProd = array();
											$iProd = 0;
											$nspp = $db
												->query(
													"SELECT  * FROM all_products order by id asc"
												)
												->fetchAll();
										foreach ($nspp as $k => $v) {
											$newProd = ["id" => $v["id"], "sku" => $v["sku"], "name" => $v["name"],  "prodtype" => $v["prodtype"]];
											$dataProd[$iProd] = $newProd;
											$iProd++;
										}
											foreach ($dataProd as $k => $v) {

												echo "<option data-value=" .
													$v["prodtype"] . "-" . $v["id"] .
													">" .
													$v["sku"] .
													" - " .
													$v["name"] .
													"</option>";
											}
											?>
									</datalist>
<?php
if(isset($id)){
$dataInvoice = array();
	$iProd1 = 0;
	$nspp1 = $db
		->query(
			"SELECT  * FROM inv_items where inv_id=".$id
		)
		->fetchAll();
foreach ($nspp1 as $k1 => $v11) {
	$partQuery1 = "SELECT * FROM all_products WHERE productid=" . $v11['item_product_id'];
	$partData1 = $db->query($partQuery1)->fetchAll();
	$newProd1 = ["id" => $partData1[0]["productid"], "prodtype" => $partData1[0]['prodtype'], "quantity" => $v11["item_qty"], "name" => $partData1[0]["name"],  "price" => $v11["item_price"],  "taxrate" => $v11["tax_rate"]];
	$dataInvoice[$iProd1] = $newProd1;
	$iProd1++;
}

}
 ?>
								</div>
								<div class="col-auto" style="width:13%">
									<label for="quantity" class="form-label">Quantity</label>
									<input type="number" class="form-control" id="quantity" placeholder="Quantity">
								</div>
								<div class="col-auto" style="width:13%">
									<label for="price" class="form-label">Price</label>
									<input type="number" class="form-control" id="inv_price" placeholder="Price">
								</div>
								<div class="col-auto" style="width:15%">
									<label for="tax" class="form-label">TAX Rate</label>
									<select class="form-control" aria-describedby="Tax Rate" name="inv_tax" id="inv_tax" required>
										<option value=""> Please Select </option>
										<option value="20"  selected>T1</option>
										<option value="0">Z</option>
									</select>

								</div>
								<div class="col-auto mt-4">
									<button type="button" onClick="addNewProduct()" class="btn btn-primary" style="margin-top:10px;">Add</button>
								</div>
							</form>
						</div>
					</div>

			<?php }?>
						<div class="row mt-3">
							<div class="col-md-12">
								<div class="card">
									<div class="card-body">
										<div class="table-responsive">
										<table class='table table-vcenter card-table' id="pr-table">
												<thead>
													<tr>
														<th></th>
														<th>QUANTITY</th>
														<th>DESCRIPTION</th>
														<th>NET</th>
														<th>VAT</th>
														<th>SUBTOTAL</th>
														<th>TOTAL</th>
														<th width="15%">Action</th>
													</tr>
												</thead>
												<tbody>
													<?php
													$data = array();

													if (isset($ord)) {
														//echo "here";exit;
														$typeQuery = "SELECT item_id, inv_prodtype, item_product_id, item_qty, item_price,tax_rate FROM inv_items WHERE inv_id=" . $id;
														$typeData = $db->query($typeQuery)->fetchAll();
														$price = 0;
														$index = 0;
														foreach ($typeData as $type) {

															$partQuery = "SELECT * FROM all_products WHERE productid=" . $type['item_product_id'];
															$partData = $db->query($partQuery)->fetchAll();

															$newArr = ['id' => $partData[0]['productid'],'prodtype' => $partData[0]['prodtype'],'name' => $partData[0]['name'], 'quantity' => $type['item_qty'], 'price' => $type['item_price'], 'taxrate' => $type['tax_rate']];
															$data[$index] = $newArr;
															$index++;

														}
													}?>
												</tbody>
												<tfoot>
													<tr>
														<td colspan="6"></td>
														<th scope="row" style="text-align: right;">NET:</th>
														<td id="subtotal"></td></tr>
														<tr>
															<td colspan="6"></td>
															<th scope="row" style="text-align: right;">VAT:</th>
															<td id="vat_amt"></td></tr>
													<tr><td colspan="6"></td>
														<td scope="row" style="text-align: right;">SHIPPING:</td>
														<td><input type="number" value="<?php if (isset($ord)){ echo $ord['ship_fee'];}?>" onkeyup="modalkeyUp(event, 'ship_fee')" oninput="modalkeyUp(event, 'ship_fee')" class="comment form-control form-control-sm" id="ship_fee"></td>
													</tr>
													<tr>
														<td colspan="6"></td>
														<td scope="row" style="text-align: right;">DISCOUNT:</td>
														<td><input type="number" value="<?php if (isset($ord)){ echo $ord['discount'];}?>" onkeyup="modalkeyUp(event, 'discount')" oninput="modalkeyUp(event, 'discount')" class="comment form-control form-control-sm" id="discount"></td>
													</tr>
													<tr>
														<td colspan="6"></td>
														<th scope="row" style="text-align: right;">TOTAL</th>
														<td id="total"></td>
													</tr>
												</tfoot>
											</table>

							</div>
				</div>
			</div>
			</div>
			</div>
			<?php if (isset($ord) && $ord["rfq_state"] !== "Completed") {?>
			<div class="col-auto" style='margin-top:20px'>
				<button class='btn btn-primary' onClick="SaveOrder()" type='button'>Update</button>
				<button class='btn btn-danger' style="float:right;" onclick="DeleteInvoice()" type='button'>Delete</button>

			</div>
			<?php }?>
			<?php if (!isset($ord)) {?>
			<div class="col-auto" style='margin-top:20px'>
				<!--<button class='btn btn-primary' onClick="SaveInvoice()" type='button'>Save</button>-->
				<button class='btn btn-success' onClick="ShowConfirmModal()" type='button'>Create Invoice</button>
			</div>
			<?php }?>
		</fieldset>

			</div>
		</div>
	</div>
</div>


</div>


<div class="modal fade" tabindex="-1" aria-labelledby="notAddedModalLabel" aria-hidden="true" id="notAddedModal">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="notAddedModalLabel">Add Items</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Add at least one item.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" tabindex="-1" aria-labelledby="notCheckedModalLabel" aria-hidden="true" id="notCheckedModal">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="notCheckedModalLabel">Choose Items</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Choose at least one item.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal" id="confirmModal_1" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered  modal-xl">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="confirmModalLabel1">Payment Information</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body">
					<fieldset class="form-fieldset">
						<div class="mb-3">
							<form id="formdatamodal1">
								<div class="row">
									<div class="col-md-3">
										<label class="form-label required">Payment Type</label>
										<select class="form-control" aria-describedby="Payment Type" name="pay_payment_type" id="pay_payment_type" required>
											<option value=""> Please Select </option>
											<option value="Cash" <?php if (isset($ord) && $ord["payment_type"] == "Cash") {echo "selected";}?>>Cash</option>
											<option value="Bank Transfer" <?php if (isset($ord) && $ord["payment_type"] == "Bank Transfer") {echo "selected";}?>>Bank Transfer</option>
											<option value="Credit Card" <?php if (isset($ord) && $ord["payment_type"] == "Credit Card") {echo "selected";}?>>Credit Card</option>
											<option value="Cheque" <?php if (isset($ord) && $ord["payment_type"] == "Cheque") {echo "selected";}?>>Cheque</option>
										</select>
									</div>
									<div class="col-md-3">
										<label class="form-label required">Payment Reference</label>
										<input type="text" required class="form-control" value="<?php if (isset($ord)) {echo $ord["payment_reference"];}?>" aria-describedby="Payment Reference" id="pay_payment_reference" />
									</div>
                </div>

							</form>
						</div>
					</fieldset>
         </div>
         <div class="modal-footer">
             <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
             <button type="button" class="btn btn-primary" id="SavePayment">Save</button>
         </div>
         </div>
      </div>
   </div>
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered  modal-xl">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="confirmModalLabel">Create New Invoice</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body">
					<fieldset class="form-fieldset">
						<div class="mb-3">
							<form id="formdatamodal">
								<div class="row">
									<?php if (isset($ord)) {?>

									<?php }?>
									<div class="col-md-3">
										<label class="form-label required">Invoice Date</label>
										<input type="text" required class="form-control" aria-describedby="Invoice Date" id="invmodal_date" />
										<input type="hidden" class="form-control" aria-describedby="" id="invmodal_quantity" />
									</div>
                  <div class="col-md-3">
                    <label class="form-label ">Invoice Reference</label>
                    <input type="text" class="form-control" value="" aria-describedby="Invoice Reference" id="invmodal_reference" />
                  </div>
									<div class="col-md-3" >
										<label class="form-label required">Customer</label>
										<input type="hidden" class="form-control" value="<?php if (isset($customer)) {echo $customer["customer_id"];}?>" aria-describedby="" id="invmodal_customer_id" />
										<input type="text" class="form-control" value="<?php if (isset($customer)) {echo $customer["c_name"];}?>" aria-describedby="Customer" id="invmodal_customer" required />
										<div id="suggesstion-box1"></div>
								</div>
								<div class="col-md-3">
									<label class="form-label required">Currency</label>
									<select class="form-control" aria-describedby="Currency" name="invmodal_currency" id="invmodal_currency" required>
										<option value=""> Please Select </option>
										<option value="USD" <?php if (isset($ord) && $ord["inv_currency"] == "USD") {echo "selected";}?> >US Dollar</option>
										<option value="GBP" <?php if (isset($ord) && $ord["inv_currency"] == "GBP") {echo "selected";}?> >GBP</option>
										<option value="EUR" <?php if (isset($ord) && $ord["inv_currency"] == "EUR") {echo "selected";}?> >Euro</option>
										<option value="RMB" <?php if (isset($ord) && $ord["inv_currency"] == "RMB") {echo "selected";}?> >RMB</option>
									</select>
								</div>



								</div>
								<div class="row">

										<div class="col-md-12  text-right" style="margin-top: 24px;">
											<div class="form-switch form-switch-lg" style="text-align:right;">
												<input type="checkbox" class="form-check-input" id="invmodal_paymentStatus">
												</div>
										</div>
								</div>

<div class="row mt-3">
							<div class="col-md-12">
								<div class="card">
									<div class="card-body">
										<div class="table-responsive">
										<table class='table table-vcenter card-table' id="prmodal-table">
									<thead>
										<tr>

											<th>QUANTITY</th>
											<th style="width:50%">DESCRIPTION</th>
											<th  style="width:9%">NET</th>
											<th  style="width:9%">VAT</th>
											<th style="width:9%">SUBTOTAL</th>
											<th style="width:9%">TOTAL</th>
											</tr>
									</thead>
									<tbody>
									</tbody>
									<tfoot>
										<tr>
											<td colspan="4"></td>
											<th scope="row" style="text-align: right;">NET:</th>
											<td id="subtotal_1"></td></tr>
											<tr>
												<td colspan="4"></td>
												<th scope="row" style="text-align: right;">VAT:</th>
												<td id="vat_amt1"></td></tr>

										<tr><td colspan="4"></td>
											<td scope="row" style="text-align: right;">SHIPPING:</td>
											<td><input type="number" onkeyup="modalkeyUp(event, 'ship_fee')" oninput="modalkeyUp(event, 'ship_fee')" class="comment form-control form-control-sm" id="inv_ship_fee"></td>
										</tr>
										<tr>
											<td colspan="4"></td>
											<td scope="row" style="text-align: right;">DISCOUNT:</td>
											<td><input type="number" onkeyup="modalkeyUp(event, 'discount')" oninput="modalkeyUp(event, 'discount')" class="comment form-control form-control-sm" id="inv_discount"></td>
										</tr>
										<tr>
											<td colspan="4"></td>
											<th scope="row" style="text-align: right;">TOTAL</th>
											<td id="total_1"></td>
										</tr>
									</tfoot>
								</table>
							</div>
							</div>
							</div>
							</div>
							</div>
							</form>
						</div>
					</fieldset>
         </div>
         <div class="modal-footer">
             <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
             <button type="button" class="btn btn-primary" id="presetAddButton" onclick='CreateInvoice();'>Create</button>
         </div>
         </div>
      </div>
   </div>
<script>
var products = [];
var listData = [];
var id = 0;
var user = 0;
$(function() {

	<?php

	foreach ($dataProd as $k => $v) {

		echo "products.push({
		    		id: " . $v["id"] .
						", prodtype:" . $v["prodtype"] .
						", quantity:'" . $v["quantity"] .
						"', name:\"" .addslashes($v["name"]).
						"\", price:'" . $v["price"] .
						"', taxrate:'" . $v["taxrate"] .
						"'});\r\n";
	}

	foreach ($data as $k => $v) {
		echo "listData.push({
			id: " . $v["id"] .
			", quantity:" . $v["quantity"] .
			", prodtype:" . $v["prodtype"] .
			", name:\"" .addslashes($v["name"]).
			"\", price:" . $v["price"] .
			", taxrate:'" . $v["taxrate"] .
				"'});\r\n";
	}

if (isset($id)) {
	echo "id = " . $id . ";";
}
echo "user = " . $_SESSION["user_id"] . ";";

?>

	if (sessionStorage.getItem('listData') != null) {
		var tempListData = JSON.parse(sessionStorage.getItem("listData"));
		tempListData.forEach(function(entry){
			products.forEach(function(product){
				if(product['prodtype'] == entry["prodtype"] && product['id'] == entry["id"]) {
					listData.push({
	    			id: product['id'],
						prodtype: product['prodtype'],
						quantity: product['quantity'],
						taxrate: product['taxrate'],
						price: product['price']

					});
				}
			});
		});
		sessionStorage.clear();
	}
	$("#confirmModal_1").on("hidden.bs.modal", function () {
		$( "#paymentStatus" ).prop( "checked", false );
		$("#inv_status").val('Unpaid');
	});
	$('body').on('change', '#paymentStatus', function () {
    if ($(this).is(':checked')) {
			$('#confirmModal_1').modal('show');
				return false;
    }
    else {
			if ($(this).is(':checked')) {

			}
			$("#inv_status").val('Unpaid');
    }
});
$('body').on('click', '#SavePayment', function () {
	if (!$('#formdatamodal1').valid()){
    	new_toast("warning", "Please fill the form!");
        return false;
	}
	$("#payment_type").val($("#pay_payment_type").val());
	$("#payment_reference").val($("#pay_payment_reference").val());
	$("#inv_status").val('Paid');
	$("#confirmModal_1").removeClass("in");
  $(".modal-backdrop").remove();
  $('body').removeClass('modal-open');
  $('body').css('padding-right', '');
	$("#confirmModal_1").hide();
});


	if ($("#inv_date").length > 0) {
		new Litepicker({
			element: document.getElementById('inv_date'),
			format: 'DD/MM/YYYY'
		});
	}

	if ($('.pr-table tbody tr').length > 0) {
		$('#completeorderbut').css("display", "none");
	}

	document.title = "INVOICE # <?php if (isset($id)) {echo $id;}?>" + document.title;


	if (document.querySelector('input[list]') != null) {
		document.querySelector('input[list]').addEventListener('input', function(e) {
			var input = e.target,
				list = input.getAttribute('list'),
				options = document.querySelectorAll('#' + list + ' option'),
				hiddenInput = document.getElementById(input.getAttribute('id') + '-hidden'),
				inputValue = input.value;

			hiddenInput.value = inputValue;

			for (var i = 0; i < options.length; i++) {
				var option = options[i];

				if (option.innerText === inputValue) {
					hiddenInput.value = option.getAttribute('data-value');
					break;
				}
			}
		});
	}
	redrawTable();
});
function selectSupplier(val) {
	$("#email").val(val);
	$("#suggesstion-box").hide();
}
//$('#supplier-list').on('click', '.suppclick', function(e){
//		$("#email").val(val);
	//	$("#suggesstion-box").hide();
//});



function SaveOrder(){
	if (!$('#formdata').valid())
        return false;
    if(listData.length == 0){
    	new_toast("warning", "Please add products!");
        return false;
    }

    var pd = {
        action: "saveinvoice",
        id: id,
        user: user,
        data: {
					date: $('#inv_date').val(),
					payment_type: $('#payment_type').val(),
					currency: $('#inv_currency').val(),
					customer: $('#inv_customer_id').val(),
					status: $('#inv_status').val(),
					reference: $('#inv_reference').val(),
					pay_reference: $('#payment_reference').val(),
					shipfee : parseInt($('#ship_fee').val()),
					discount : parseInt($('#discount').val()),
				  listData: listData
        }
    };

    $.ajax({
        url: "/newinvoiceajax",
        data: pd,
        type: 'POST',
        error: function(a) {
            a = JSON.parse(a.responseJSON);
            if (!a.success) {
                failSound();
                new_toast("danger", "Error! Reason is " + a.error);
            }
        },
        success: function(a) {
            a = JSON.parse(a);
            if (a.success) {
                new_toast("success", "Success6.");
                location.href = "/newinvoiceitem/" + a.id;
            } else {
                failSound();
                new_toast("danger", "Error! Reason is " + a.error);
            }
        }
    });
}

function DownloadExcel(){
		window.location="/rfqexcelajax/" + <?php echo isset($id) ? $id : 0; ?>;
}
<?php
if(isset($id)){
 ?>
function EmailExcelModal(){
	$('#mailModal').modal('show');
	$("#body").val("");
	$("#email").val("");
	return false;
}
function EmailExcelToSupplier() {
  //  if (!$('#formdatamodal1').valid())
    //    return false;

		//	var group_selection = 	$("#groups").is(':selected');
		if($("#email").val()=="" && $("#groups").prop("selected") != true){
			$("#erro_msg").css("display","block");
			$("#erro_msg").html("Please search email or select supplier group.");
			return false;
		}
		var id = <?php echo isset($id) ? $id : 0; ?>;
		var pd = {
        action: "sendExcelToSupplier",
				data: {
					  subject: $('#subject').val(),
						body: $('#body').val(),
            email: $('#email').val(),
            groups: $('#groups').val(),
						id: id
        }
    };

    $.ajax({
        url: "/sendToSupplerajax/"+id,
        data: pd,
        type: 'POST',
        error: function(a) {
            a = JSON.parse(a.responseJSON);
            if (!a.success) {
                failSound();
                new_toast("danger", "Error! Reason is " + a.error);
            }
        },
        success: function(a) {
				    a = JSON.parse(a);
            if (a.success) {
							$('#mailModal').modal('hide');
                new_toast("success", "Mail sent successfully.");
              //  location.href = "/itemrfq/" + a.id;
            } else {
                failSound();
                new_toast("danger", "Error! Reason is " + a.error);
            }
        }
    });
}
<?php }?>
function selectCustomer(val,id) {
	$("#inv_customer").val(val);
	$("#inv_customer_id").val(id);
	$("#suggesstion-box").hide();
}
$("#inv_customer").keyup(function() {
	var pd = {
		action: "searchcustomer",
		data: {
		keyword:$("#inv_customer").val()
		}
	};
	$.ajax({
		url: "/getCustomerajax",
		data: pd,
		type: 'POST',
		beforeSend: function() {
			$("#inv_customer").css("background", "#FFF");
		},
		success: function(data) {
			$("#suggesstion-box").show();
			$("#suggesstion-box").html(data);
			$("#inv_customer").css("background", "#FFF");

		}
	});
	});
function CreateInvoice() {
	if (!$('#formdata').valid()){
		 new_toast("warning", "Please fill the form!");
				return false;
 }
		if(listData.length == 0){
		 $('#notAddedModal').modal('show');
				return false;
		}

		var isChecked = false;
		listData.forEach(item => {
		 if (item['checked'] == true) {
			 isChecked = true;
		 }
		});

		if(!isChecked){
		 $('#notCheckedModal').modal('show');
				return false;
		}

    var pd = {
        action: "createinvoice",
        id: id,
        user: $("#inv_user").val(),
        data: {
            date: $('#invmodal_date').val(),
            payment_type: $('#payment_type').val(),
            currency: $('#invmodal_currency').val(),
            customer: $('#inv_customer_id').val(),
            status: $('#inv_status').val(),
            reference: $('#invmodal_reference').val(),
						pay_reference: $('#payment_reference').val(),
						shipfee : parseInt($('#ship_fee').val()),
						discount : parseInt($('#discount').val()),
            listData: listData.filter(obj => obj.checked == true)
        }
    };
	//$('#confirmModal').modal('hide');
    $.ajax({
        url: "/newinvoiceajax",
        data: pd,
        type: 'POST',
        error: function(a) {
            a = JSON.parse(a.responseJSON);
            if (!a.success) {
                failSound();
                new_toast("danger", "Error! Reason is " + a.error);
            }
        },
        success: function(a) {
            a = JSON.parse(a);
            if (a.success) {
                new_toast("success", "Success6.");
                location.href = "/invoices";
            } else {
                failSound();
                new_toast("danger", "Error! Reason is " + a.error);
            }
        }
    });
}

function DeleteInvoice() {
    if (!confirm("Do you really want to delete invoice?")) return false;
    var id = <?php echo isset($id) ? $id : 0; ?> ;
    var pd = {
        action: "deleteinvoice",
        data: {
            id: id
        }
    };
    $.ajax({
        url: "/newinvoiceajax",
        data: pd,
        type: 'POST',
        success: function(a) {
            a = JSON.parse(a);
            if (a.success) {
                new_toast("success", "Success");
                location.href = "/invoices";
            } else
                new_toast("danger", "Error! Reason is " + a.error);
        }
    });
}

// function CompleteOrder() {

//     if ($('.psr-table tbody tr').length > 0) {
//         var id = <?php echo isset($id) ? $id : 0; ?> ;
//         var pd = {
//             action: "completeorder",
//             data: {
//                 id: id
//             }
//         };
//         SendAction(pd, id, function(a, b, c) {
//             console.log(a, b, c);
//             window.location.reload();
//         });

//     } else {
//         new_toast("danger", "There is no input!");
//     }
// }

function addNewProduct() {
    var pn = $('[name="sku"]').val();
    var qa = $('#quantity').val();
		var prc = $('#inv_price').val();
		var tx = $('#inv_tax').val();

    $('#sku').val('');
    $('[name="sku"]').val('');
    $('#quantity').val('');
		$('#inv_price').val('');

    if (pn == "") {
        failSound();
        new_toast("warning", "Please select a product!");
        return false;
    }

    if (qa == "" || qa <= 0) {
        failSound();
        new_toast("warning", "Please enter a valid quantity!");
        return false;
    }
		if (prc == "" || prc <= 0) {
        failSound();
        new_toast("warning", "Please enter a price!");
        return false;
    }

    var prodtype = pn.split("-")[0];
    var prodID = pn.split("-")[1];

    var doesExist = false;
    listData.forEach(item => {
    	if (item['prodtype'] == prodtype && item['id'] == prodID) {
        	doesExist = true;
    	}
    });

    if (doesExist) {
    	failSound();
    	new_toast("warning", "The item already exists in the list!");
        return false;
    }

    products.forEach(product => {
			if (product['prodtype'] == prodtype && product['id'] == prodID) {
			listData.push({
	    		id: product["id"],
	        	prodtype: product['prodtype'],
	    		  checked: false,
	        	quantity: qa,
	        	taxrate: tx,
	        	price: prc
	        });
			};
    	});
    redrawTable();
}

function handleSelectClick(e, type, id){
	listData.forEach(item => {
		if (item['prodtype'] == type && item['id'] == id) {
			if (e.checked) {
				item['checked'] = true;
			} else {
				item['checked'] = false;
			}
		}
	});
	updateTotals();
}

function SPkeyUp(ev, prodType, prodID){
    listData.forEach(item => {
    	if (item['prodtype'] == prodType && item['id'] == prodID) {
    		item['suppliercomments'] = ev.target.value;
    	}
    });
}

function modalkeyUp(ev, type, prodType, prodID){
	//console.log("yes");
	if (type == "label_fee" || type == "ship_fee" || type == "bank_fee" || type == "surcharge" || type == "credit" || type == "discount") {
		updateTotals();
	} else if (type == "price") {
		listData.forEach(item => {
			if (prodType == item['prodtype'] && prodID == item['id']) {
				item['price'] = ev.target.value;

				if(item['taxrate']==0){
					var vat = 0;
					price = parseFloat(item['price']).toFixed(2);
				}else{
					var price = (parseFloat(item['price'])/(parseFloat(item['price'])+parseFloat(item['taxrate'])))*100;
					price = parseFloat(price).toFixed(2);
					var vat = parseFloat(item['price']-price).toFixed(2); //(item['price']*item['quantity']) * item['taxrate'] / 100;

				}
				var quantity = item['quantity'];
				 var subtotal = parseFloat((price*quantity)).toFixed(2);
				 var total = parseFloat((item['price']*quantity)).toFixed(2);


				$('#subtot_' + prodType + "-" + prodID).html(subtotal);
				$('#tot_' + prodType + "-" + prodID).html(total);
			}
		});
		updateTotals();
	} else if (type == "quantity") {
		listData.forEach(item => {
			if (prodType == item['prodtype'] && prodID == item['id']) {
				item['quantity'] = ev.target.value;
				if(item['taxrate']==0){
					var vat = 0;
					price = parseFloat(item['price']).toFixed(2);
				}else{
				//	var vat = (item['price']*item['quantity']) * item['taxrate'] / 100;
				var price = (parseFloat(item['price'])/(parseFloat(item['price'])+parseFloat(item['taxrate'])))*100;
				price = parseFloat(price).toFixed(2);
				var vat = parseFloat(item['price']-price).toFixed(2); //(item['price']*item['quantity']) * item['taxrate'] / 100;

				}
				$('#subtot_' + prodType + "-" + prodID).html(price * item['quantity']);
				$('#tot_' + prodType + "-" + prodID).html(price * item['quantity']);
			}
		});
		updateTotals();
	}
}

function removeItem(e, type, id){
	listData = listData.filter(obj => obj.prodtype != type || obj.id != id);
	redrawTable();
}

function showAnalyze(e, type, id){

}

function  redrawTable(){
	$('#pr-table tbody').empty();
 var subtotal = 0;
 var total = 0;
 subtotalFinal = 0;
var vat_amount = 0;
 totalFinal = 0;
 total_cnt = 0;
	listData.forEach(item => {
		products.forEach(product => {
			if (product['prodtype'] == item['prodtype'] && product['id'] == item['id']) {
				color = null;
				var vat = 0;
				if(item['taxrate']==0){
					var vat = 0;
					price = parseFloat(item['price']).toFixed(2);
				}else{
					var price = (parseFloat(item['price'])/(parseFloat(item['price'])+parseFloat(item['taxrate'])))*100;
					price = parseFloat(price).toFixed(2);
					var vat = parseFloat(item['price']-price).toFixed(2); //(item['price']*item['quantity']) * item['taxrate'] / 100;

				}
				var quantity = item['quantity'];
				var subtotal = parseFloat((price*quantity)).toFixed(2);
				var total = parseFloat((price*quantity)).toFixed(2);
				 		vat_amount = parseFloat(parseFloat(vat_amount)+parseFloat(vat)).toFixed(2);
				var total_cnt = parseFloat((parseFloat(item['price'])*quantity)).toFixed(2);

			   		subtotalFinal = (parseFloat(subtotalFinal)+parseFloat(subtotal)).toFixed(2);
				 		totalFinal = (parseFloat(totalFinal) + parseFloat(total_cnt)).toFixed(2);

				$('#pr-table > tbody:last').append('<tr><td><input type="checkbox" onclick="handleSelectClick(this, ' + item['prodtype'] + ', ' +item['id'] + ')"></td><td>' + item['quantity'] + '</td><td>' + product['name'] + '</td><td>' + price  + '</td><td>' + vat + '</td><td>' + subtotal + '</td><td>' + total_cnt + '</td><td><button class="btn btn-sm btn-danger" type="button" onclick="removeItem(this, ' + item['prodtype'] + ', ' +item['id'] + ')" style="margin-bottom: 10px;">Remove</button></td></tr>');
			}
		});



	});
	$("#subtotal").html(subtotalFinal);
	totalFinal = (parseFloat(subtotalFinal)+parseFloat(vat_amount)).toFixed(2);
	$("#vat_amt").html(vat_amount);
	if($("#ship_fee").val()!=""){
		totalFinal = (parseFloat(totalFinal)+parseInt($("#ship_fee").val())).toFixed(2);
	}
	if($("#discount").val()!=""){
		totalFinal = (parseFloat(totalFinal)-parseInt($("#discount").val())).toFixed(2);
	}
	$("#total_1").html(totalFinal);

	$("#total").html(totalFinal);
}
function ShowConfirmModal(){
	if (!$('#formdata').valid()){
    	new_toast("warning", "Please fill the form!");
        return false;
	}
    if(listData.length == 0){
    	$('#notAddedModal').modal('show');
        return false;
    }

    var isChecked = false;
    listData.forEach(item => {
    	if (item['checked'] == true) {
    		isChecked = true;
    	}
    });

    if(!isChecked){
    	$('#notCheckedModal').modal('show');
        return false;
    }


	$('#confirmModal').modal('show');

	$('#invmodal_date').val($('#inv_date').val());
	$('#invmodal_payment_type').val($('#payment_type').val());
	$('#invmodal_currency').val($('#inv_currency').val());
  $('#invmodal_customer').val($('#inv_customer').val());
  $('#invmodal_status').val($('#inv_status').val());
  $('#invmodal_reference').val($('#inv_reference').val());
	$('#invmodal_quantity').val($('#quantity').val());
	$('#inv_ship_fee').val($('#ship_fee').val());
	$('#inv_discount').val($('#discount').val());

	if($('#paymentStatus').is(':checked') == true){
		$( "#invmodal_paymentStatus" ).prop( "checked", true );
	}

	$('#prmodal-table tbody').empty();
	$('#total').html(0);


	listData.forEach(item => {
		if (item['checked'] == true) {
			products.forEach(product => {
				if (product['prodtype'] == item['prodtype'] && product['id'] == item['id']) {
					color = null;
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

					if(item['taxrate']==0){
						var vat = 0;
						price = parseFloat(item['price']).toFixed(2);
					}else{
						var price = (parseFloat(item['price'])/(parseFloat(item['price'])+parseFloat(item['taxrate'])))*100;
						price = parseFloat(price).toFixed(2);
						var vat = parseFloat(item['price']-price).toFixed(2); //(item['price']*item['quantity']) * item['taxrate'] / 100;

					}
					var quantity = item['quantity'];
					 var subtotal = parseFloat((price*quantity)).toFixed(2);
					 var total = parseFloat((item['price']*quantity)).toFixed(2);

					//<input type="number" onkeyup="modalkeyUp(event, \'quantity\', ' + item['prodtype'] + ', ' +item['id'] + ')" oninput="modalkeyUp(event, \'quantity\', ' + item['prodtype'] + ', ' +item['id'] + ')" class="comment form-control form-control-sm" value="' + item['quantity'] + '">
						$('#prmodal-table > tbody:last').append('<tr><td>'+item['quantity']+'</td><td>' + product['name'] +   '</td><td>' + price +'</td><td>' + vat +'</td><td id="subtot_' + item['prodtype']+ '-' +item['id'] + '">'+ subtotal+'</td><td id="tot_' + item['prodtype']+ '-' +item['id'] + '">' + (total) + '</td></tr>');
				}
			});
		}
	});

	updateTotals();
}
<?php
if(isset($ord) and $ord['inv_status']=='Paid'){
	?>

		$( "#paymentStatus" ).prop( "checked", true );
	<?php
}
 ?>
function updateTotals(){

	var shipfee = parseInt($('#ship_fee').val());
	var discount = parseInt($('#discount').val());
	if(shipfee==""){
		var shipfee = parseInt($('#inv_ship_fee').val());
	}
	if(shipfee==""){
		var discount = parseInt($('#inv_discount').val());
	}


	var subtotal = 0;
	var FinalTotal = 0;
	var vat_amount = 0;
	checkedflag = 2;
	var FinalTotal1 = 0;
	var Finalsubtotal = 0;
	listData.forEach(item => {
		if (item['checked'] == true) {
			vat = 0;

			if(item['taxrate']==0){
				var vat = 0;
				price = parseFloat(item['price']).toFixed(2);
			}else{
				var price = (parseFloat(item['price'])/(parseFloat(item['price'])+parseFloat(item['taxrate'])))*100;
				price = parseFloat(price).toFixed(2);
				var vat = parseFloat(item['price']-price).toFixed(2); //(item['price']*item['quantity']) * item['taxrate'] / 100;

			}
			var quantity = item['quantity'];
			 var subtotal = parseFloat((price*quantity)).toFixed(2);
			 var total = parseFloat((item['price']*quantity)).toFixed(2);
			 Finalsubtotal = (parseFloat(Finalsubtotal)+parseFloat(subtotal)).toFixed(2);
			 FinalTotal1 = (parseFloat(FinalTotal1)+parseFloat(total)).toFixed(2);

			vat_amount = (parseFloat(vat_amount)+parseFloat(vat)).toFixed(2);
			//total = subtotal+vat;
			//FinalTotal = total;
			checkedflag = 1;
		}
	});

 if(checkedflag ==1){

	$('#subtotal').html(Finalsubtotal);
	$('#vat_amt').html(vat_amount);
	$('#vat_amt1').html(vat_amount);
	$('#subtotal_1').html(Finalsubtotal);
	//console.log("subtotal -"+Finalsubtotal);


	var FinalTotal = (parseFloat(Finalsubtotal)+parseFloat(vat_amount)).toFixed(2);
//	console.log("FinalTotal -"+FinalTotal);
	if(shipfee){
		FinalTotal = (parseFloat(FinalTotal)+parseFloat(shipfee)).toFixed(2);
	}
	if(discount>FinalTotal){
		new_toast("warning", "Dicount can't more than total.");
			return false;

	}
	if(discount){
			FinalTotal = (parseFloat(FinalTotal)-parseFloat(discount).toFixed(2)).toFixed(2);
	}
	// + labelfee + shipfee + bankfee + surcharge + credit + discount;
$('#total_1').html(FinalTotal);
	$('#total').html(FinalTotal);
}else{
	new_toast("warning", "Select atleast one product.");
		return false;
}
}
</script>
