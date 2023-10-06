<style>
    .stid { font-weight: bolder;}
	.total_quantity, .total_arrived {
		display:block;
		width:100%;
		text-align:center;
	}
</style>
<?php
	include(PATH_CONFIG."/constants.php");
	$db=M::db();
	$_TITLE="RFQ Orders";
	$_FIELDS = [
		(object)array('sName' => 'rfqo_id', 'title' => 'No', 'data' => 'rfqo_id', 'type'=>'number' ),    
		(object)array('sName' => 'rfqo_date', 'title' => 'Date', 'data' => 'rfqo_date', 'type'=> 'string'),
		(object)array('sName' => 'rfqo_supplier_name', 'title' => 'Supplier', 'data' => 'rfqo_supplier_name', 'type'=> 'string'),
		(object)array('sName' => 'rfqo_reference', 'title' => 'Reference', 'data' => 'rfqo_reference', 'type'=> 'string'),
		(object)array('sName' => 'total_quantity', 'title' => 'Total<br/>Ordered', 'data' => 'total_quantity', 'type'=> 'number'),
		(object)array('sName' => 'total_arrived', 'title' => 'Total<br/>Arrived', 'data' => 'total_arrived', 'type'=> 'number'),
		(object)array('sName' => 'order_value', 'title' => 'Order<br/>Value', 'data' => 'order_value', 'type'=> 'number'),
		(object)array('sName' => 'vat_label', 'title' => 'VAT<br />Type', 'data' => 'vat_label', 'type'=> 'number'),
		(object)array('sName' => 'rfqo_state', 'title' => 'Status', 'data' => 'rfqo_state', 'type'=> 'string'),
		(object)array('sName' => 'rfqo_user_name', 'title' => 'User', 'data' => 'rfqo_user_name', 'type'=> 'string'),
		(object)array('sName' => 'rfqo_payment', 'title' => 'Payment<br />Status', 'data' => 'rfqo_payment', 'type'=> 'number'),
		(object)array('sName' => 'rfq_id', 'title' => 'RFQ ID', 'data' => 'rfq_id', 'type'=> 'string'),
		];
?>

<style>
    .markdown>table, .table {
    --tblr-table-bg: transparent;
    --tblr-table-accent-bg: #fff;
    }
    .markdown>table>thead, .table>thead {
        background-color: #f4f6fa;
    }
</style>
                                 
<div class="page-body">
	<div class="container-fluid">
		<div class="col-lg-11" style="margin:0 auto;">
			<div class="card card-lg">
				<div class="card-body" style="padding:3rem 1rem;">
					<div class="row align-items-center">
						<div class="col-auto">
							<h2 style="margin-left:1rem;"><?php echo $_TITLE; ?></h2>
						</div>
						<div class="col-auto d-none d-md-flex">
							<button type=button class='btn btn-primary' onclick="showCreateNewRfqModal()">
							<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
							<path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
							<line x1="12" y1="5" x2="12" y2="19"></line>
							<line x1="5" y1="12" x2="19" y2="12"></line>
							</svg>
							Create New Order</button>
						</div>
					</div>
					<div class="table-responsive" style="padding:10px;margin:10px;">
						<table id="dataList" class="table hover card-table table-vcenter text-nowrap datatable">
							<thead>
								<tr>                                 
									<?php
									if(isset($_FIELDS) && count($_FIELDS)>0) {
									for($i=0;$i<count($_FIELDS);$i++) {
									$ad = $_FIELDS[$i];
									echo '<th ><span>'.$ad->title.'</span></th>'."\r\n";
									}
									}
									?>                                
								</tr>
							</thead>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="newrfqModal" tabindex="-1" aria-labelledby="newrfqModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered  modal-xl">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="newrfqModalLabel">Create New Order</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body">
					<fieldset class="form-fieldset">
						<div class="mb-3">
							<form id="formdatamodal">
								<div class="row">
									<div class="col-auto">
										<label class="mr-sm-2">State</label>
										<h3 style="padding:0.3rem 0">Draft</h3>
									</div>
									<div class="col-auto">
										<label class="form-label required">Order Date</label>
										<input type="text" required class="form-control" aria-describedby="Order Date" id="rfqmodal_date" />
									</div>
									<div class="col-auto">
										<label class="form-label required">Reference</label>
										<input type="text" required class="form-control" aria-describedby="Reference" id="rfqmodal_reference" />
									</div>
									<div class="col-auto">
										<label class="form-label required">Currency</label>
										<select class="form-control" aria-describedby="Currency" name="rfqmodal_currency" id="rfqmodal_currency" required>
											<option value=""> Please Select </option>
											<option value="USD">US Dollar</option>
											<option value="GBP">GBP</option>
											<option value="EUR">Euro</option>
											<option value="RMB">RMB</option>
										</select>
									</div>
									<div class="col-auto">
										<label class="form-label required">Supplier</label>
										<select class="form-control" aria-describedby="Supplier" name="nor_supplier" id="rfqmodal_supplier" required>
											<option value=""> Please Select </option>
											<?php
											   $spp = $db
												   ->query(
													   "select sp_id,sp_name from suppliers where sp_del=0 order by sp_name asc"
												   )
												   ->fetchAll();
											   foreach ($spp as $k => $v) {
												   echo "<option value=" .
													   $v["sp_id"] .
													   ">" .
													   $v["sp_name"] .
													   "</option>";
											   }
											   ?>
										</select>
									</div>
									<div class="col-auto">
										<label class="form-label required">VAT Type</label>
										<select class='form-control' aria-describedby="VAT Type" name="vat_type" id="vat_type" required>
											<option value='0'>Standard - 20%</option>
											<option value='1'>Margin - 0%</option>
											<option value='2'>Import - 0%</option>
										</select>
									</div>
								</div>
									<div class="row mt-2 align-items-end">
										<div class="col-auto" style="width:50%">
											<label for="inputPassword2" class="form-label">SKU </label>
											<input list="skus" id="sku"  class="form-control" placeholder="Start typing SKU or product name..." autocomplete="off">
											<input type="hidden" name="sku" id="sku-hidden"  class="form-control">
											<datalist id="skus">										
											</datalist>
										</div>
										<div class="col-auto" style="width:20%">
											<label for="price" class="form-label">Price</label>
											<input type="number" class="form-control" id="price" placeholder="Quantity Ordered">
										</div>
										<div class="col-auto" style="width:20%">
											<label for="quantity" class="form-label">Quantity</label>
											<input type="number" class="form-control" id="quantity" placeholder="Quantity Ordered">
										</div>
										<div class="col-auto">
											<button type="button" onClick="addNewProduct()" class="btn btn-primary">Add</button>
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
											<th>SKU</th>
											<th>Description</th>
											<th>Condition</th>
											<th>Qty</th>
											<th>Price</th>
											<th>Total Price</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>
									</tbody>
									<tfoot>
										<tr><td></td><td></td><td></td><td></td><th scope="row" style="text-align: right;">Subtotal</th><td id="subtotal"></td></tr>
										<tr><td></td><td></td><td></td><td></td><td scope="row" style="text-align: right;">Label Fee</td><td><input type="number" onkeyup="modalkeyUp(event, 'label_fee')" oninput="modalkeyUp(event, 'label_fee')" class="comment form-control form-control-sm" id="label_fee"></td></tr>
										<tr><td></td><td></td><td></td><td></td><td scope="row" style="text-align: right;">Ship Fee</td><td><input type="number" onkeyup="modalkeyUp(event, 'ship_fee')" oninput="modalkeyUp(event, 'ship_fee')" class="comment form-control form-control-sm" id="ship_fee"></td></tr>
										<tr><td></td><td></td><td></td><td></td><td scope="row" style="text-align: right;">Bank Fee</td><td><input type="number" onkeyup="modalkeyUp(event, 'bank_fee')" oninput="modalkeyUp(event, 'bank_fee')" class="comment form-control form-control-sm" id="bank_fee"></td></tr>
										<tr><td></td><td></td><td></td><td></td><td scope="row" style="text-align: right;">Surcharge</td><td><input type="number" onkeyup="modalkeyUp(event, 'surcharge')" oninput="modalkeyUp(event, 'surcharge')" class="comment form-control form-control-sm" id="surcharge"></td></tr>
										<tr><td></td><td></td><td></td><td></td><td scope="row" style="text-align: right;">Credit</td><td><input type="number" onkeyup="modalkeyUp(event, 'credit')" oninput="modalkeyUp(event, 'credit')" class="comment form-control form-control-sm" id="credit"></td></tr>
										<tr><td></td><td></td><td></td><td></td><td scope="row" style="text-align: right;">Discount</td><td><input type="number" onkeyup="modalkeyUp(event, 'discount')" oninput="modalkeyUp(event, 'discount')" class="comment form-control form-control-sm" id="discount"></td></tr>
										<tr><td></td><td></td><td></td><td></td><th scope="row" style="text-align: right;">Total</th><td id="total"></td></tr>
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
             <button type="button" class="btn btn-primary" id="presetAddButton" onclick='CreateOrder();'>Create</button>
         </div>
         </div>
      </div>
   </div>

<form id="astock_items_form" name="astock_items_form" action="" method="post" style="display:none">
    <input type="hidden" id="astock_items" name="astock_items"/> 
</form>

<script>

var listData = [];
var id = 0;
var user = 0;
let aborter = null;

<?php
echo "user = " . $_SESSION["user_id"] . ";";
?>

function getData(param) {
	if(aborter) aborter.abort();
	aborter = new AbortController();
	const signal = aborter.signal;
	const url = '/newitemrfqsajax';
	let formData = new FormData();
	formData.append('action', 'get_suggestion');
	formData.append('term', param);

	return fetch(url, {method: 'post', body: formData, signal})
	  .then(res => {return res.json();})
	  .then(resjson => {aborter = null; return resjson;});
}

$(function() {
	window.ETable = $('#dataList').dataTable({
	 
		"lengthChange": false,
		"processing":true,
		"serverSide":true,
		"infoEmpty": "No records available",
		"sProcessing": "DataTables is currently busy",
		"aLengthMenu": [[5, 15, 25, 50,100], [5, 15, 50,100]],
		"iDisplayLength": 25,              
		"order":[],
		
		"ajax":{
			url:"/rfqorderajax",
			type:"POST",
			data: { action:'search'},
			dataType:"json"
		},
		
		"columns" :[                   
			<?php
			if(isset($_FIELDS) && count($_FIELDS)>0) {
				for($i=0;$i<count($_FIELDS);$i++) {                    
					$ad = $_FIELDS[$i];            
					if($ad->data=="rfqo_id")                      
						echo '{"data" : "'.$ad->data.'", "render":function(dat){ return "<a class=stid href=\'/rfqorderitem/"+dat+"\'>"+dat+"</a>";}}'."\r\n";

					else if($ad->data=="rfqo_state") {
						echo '{"data" : "'.$ad->data.'", "render":function(dat,tu,ur){
							if(dat == "On Order"){
								return "<span style=\'width:100px;\' class=\'badge bg-yellow\'>"+(dat||0)+"</span>";
							} else if (dat == "Part Arrived"){
								return "<span style=\'width:100px;\' class=\'badge bg-cyan\'>"+(dat||0)+"</span>";
							}
							else if (dat == "Completed"){
								return "<span style=\'width:100px;\' class=\'badge bg-green\'>"+(dat||0)+"</span>";
							}
							else if (dat == "Cancelled"){
								return "<span style=\'width:100px;\' class=\'badge bg-pink\'>"+(dat||0)+"</span>";
							}
						}'."\r\n".'}'."\r\n";
					}						
					else if($ad->data=="order_value") {
						echo '{"data" : "'.$ad->data.'", "render":function(dat,tu,ur){
							return dat.toLocaleString(\'en-US\', {minimumFractionDigits: 2, maximumFractionDigits: 2});
						}}'."\r\n";
					}
					else if($ad->data=="rfqo_supplier_name") {
						echo '{"data" : "'.$ad->data.'", "render":function(dat,tu,ur){  return "<span style=\'font-weight:600;\'>"+(dat||0)+"</span>";} }'."\r\n";
	
					}
					else if($ad->data=="rfqo_payment") {
						echo '{"data" : "'.$ad->data.'", "render":function(dat,tu,ur){  if(dat == 0) return "<span style=\'width:75px;\' class=\'badge bg-red-lt\'>Unpaid</span>"; else return "<span style=\'width:75px;\' class=\'badge bg-azure-lt\'>Paid</span>";} }'."\r\n";
	
					}
					else if($ad->data=="vat_label") {
						echo '{"data" : "'.$ad->data.'", "render":function(dat,tu,ur){return "<span style=\'width:140px;\' class=\'badge bg-azure-lt\'>"+(dat||"Not Set")+"</span>"}}'."\r\n";
					}

					else if($ad->data[2]=='t') {
						echo '{"data" : "'.$ad->data.'", "render":function(dat,tu,ur){  return "<span class='.$ad->data.'>"+(dat||0)+"</span>";} }'."\r\n";
					}
					else 
						echo '{"data" : "'.$ad->data.'"}'."\r\n";

					if($i !== count($_FIELDS)-1) {
						echo ",";
					}
				}
			}
			?>				 
		]		  		  
	});		
	$('body').on("focus","textarea",function(el){ console.log(el); $(el.currentTarget).width(400); $(el.currentTarget).height(250); });
	$('body').on("blur","textarea",function(el){ console.log(el);  $(el.currentTarget).width(100); $(el.currentTarget).height(50); });

	if ($("#rfqmodal_date").length > 0) {
		new Litepicker({
			element: document.getElementById('rfqmodal_date'),
			format: 'DD/MM/YYYY'
		});
	}

	if (document.querySelector('input[list]') != null) {
		document.querySelector('input[list]').addEventListener('input', function(e) {
			if (e.target.value != "") {
				getData(e.target.value)
				  .then(autocompleteItems => {

					var skus = document.getElementById('skus');
					var input = e.target;
					var list = input.getAttribute('list');
					var options = document.querySelectorAll('#' + list + ' option');
					var hiddenInput = document.getElementById(input.getAttribute('id') + '-hidden');
					var inputValue = input.value;
					var found = false;
					hiddenInput.value = inputValue;
					for (var i = 0; i < options.length; i++) {
						var option = options[i];
						if (option.innerText == inputValue) {
							hiddenInput.value = option.getAttribute('data-value');
							skus.innerHTML = '';
							found == true;
							break;
						}
					}

					if (!found) {
						skus.innerHTML = '';
						autocompleteItems.forEach(function(item){
						   var autocompleteOption = document.createElement('option');
						   autocompleteOption.setAttribute('data-value', item.prodtype + "-" + item.productid);
						   autocompleteOption.innerText = item.sku + "-" + item.name;
						   skus.appendChild(autocompleteOption);
						});
					}
				}).catch(e => console.error('Request failed', e.name, e.message));
			} else {
				if(aborter) aborter.abort();
				var skus = document.getElementById('skus');
				skus.innerHTML = '';
			}
		});
	}
	redrawTable();
});

function showCreateNewRfqModal(){
	$('#newrfqModal').modal('show');

	$('#prmodal-table tbody').empty();
	$('#prmodal-table > tbody:last').append('<tr><th colspan="6" class="text-center"><h3>No Item</h3></th><tr>');

	$('#subtotal').html(0);
	$('#label_fee').val(0);
	$('#ship_fee').val(0);
	$('#bank_fee').val(0);
	$('#surcharge').val(0);
	$('#credit').val(0);
	$('#discount').val(0);
	$('#total').html(0);
}

function CreateOrder() {
    if (!$('#formdatamodal').valid())
        return false;

    if(listData.length == 0){
    	new_toast("warning", "Please add a product!");
        return false;
    }

    var pd = {
        action: "createrfqorder",
        id: id,
        user: user,
        data: {
            date: $('#rfqmodal_date').val(),
            reference: $('#rfqmodal_reference').val(),
            vatType: $('#vat_type').val(),
            currency: $('#rfqmodal_currency').val(),
            supplier: $('#rfqmodal_supplier').val(),
            labelfee : parseInt($('#label_fee').val()),
			shipfee : parseInt($('#ship_fee').val()),
			bankfee : parseInt($('#bank_fee').val()),
			surcharge : parseInt($('#surcharge').val()),
			credit : parseInt($('#credit').val()),
			discount : parseInt($('#discount').val()),
            listData: listData
        }
    };
	$('#newrfqModal').modal('hide');
    $.ajax({
        url: "/newitemrfqsajax",
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
                new_toast("success", "Success.");
                window.ETable.fnDraw();
            } else {
                failSound();
                new_toast("danger", "Error! Reason is " + a.error);
            }
        }
    });
}

async function addNewProduct() {
    var pn = $('[name="sku"]').val();
    var qa = $('#quantity').val();
    var pr = $('#price').val();

    $('#sku').val('');
    $('[name="sku"]').val('');
    $('#quantity').val('');
    $('#price').val('');

    if (pn == "") {
        failSound();
        new_toast("warning", "Please select a product!");
        return false;
    }

    if (pr == "") {
        failSound();
        new_toast("warning", "Please select a product!");
        return false;
    }

    if (qa == "" || qa <= 0) {
        failSound();
        new_toast("warning", "Please enter a valid quantity!");
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

    try {
    	let formData = new FormData();
		formData.append('action', 'get_product_details');
		formData.append('prodtype', parseInt(prodtype));
		formData.append('productid', parseInt(prodID));

	    const config = {
	        method: 'POST',
	        body: formData
	    }

	    const response = await fetch('/newitemrfqsajax', config)

	    if (response.ok) {
	        const json = await response.json();
			listData.push({
	    		id: json["id"],
	    		name: json["name"],
	    		sku: json["sku"],
	    		condition: json["condition"],
	        	prodtype: json['prodtype'],
	    		checked: false,
	        	quantity: qa,
	        	invqty: json["invqty"],
	        	orderqty: json["orderqty"],
	        	magqty: json["magqty"],
	        	price: pr,
	        	suppliercomments: json["suppliercomments"],
	        	product_suppliercomments: json["product_suppliercomments"]
	        });

	        redrawTable();
	        updateTotals();
	    } else {
	        new_toast("danger", "Error! in response!" + error);
	    }
	} catch (error) {
	    new_toast("danger", "Error! Reason is " + error);
	}

    redrawTable();
}

function modalkeyUp(ev, type, prodType, prodID){
	if (type == "label_fee" || type == "ship_fee" || type == "bank_fee" || type == "surcharge" || type == "credit" || type == "discount") {
		updateTotals();
	} else if (type == "price") {
		listData.forEach(item => {
			if (prodType == item['prodtype'] && prodID == item['id']) {
				item['price'] = ev.target.value;
				$('#tot_' + prodType + "-" + prodID).html('<td id="tot_' + item['prodtype'] + '-' +item['id'] + '"><span class="skxbadge badge badge-outline text-pink">' + (item['quantity'] * item['price']).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</span></td>');
			}
		});
		updateTotals();
	} else if (type == "quantity") {
		listData.forEach(item => {
			if (prodType == item['prodtype'] && prodID == item['id']) {
				item['quantity'] = ev.target.value;
				$('#tot_' + prodType + "-" + prodID).html('<td id="tot_' + item['prodtype'] + '-' +item['id'] + '"><span class="skxbadge badge badge-outline text-pink">' + (item['quantity'] * item['price']).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</span></td>');
			}
		});
		updateTotals();
	}
}

function removeItem(e, type, id){
	listData = listData.filter(obj => obj.prodtype != type || obj.id != id);
	redrawTable();
	updateTotals();
}

function  redrawTable(){
	$('#prmodal-table tbody').empty();

	listData.forEach(item => {
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

		var suppliercomments = item['suppliercomments'];
		if (suppliercomments == "") {
			suppliercomments = item['product_suppliercomments'];
		}

		$('#prmodal-table > tbody:last').append('<tr><td><div style="border-left: 6px solid ' + color + '; padding-left: 6px; height: auto;">' + item['sku'] + '</div></td><td class="skxbolder">' + item['name'] + '<br><input type="text" onkeyup="SPkeyUp(event, ' + item['prodtype'] + ', ' +item['id'] + ')" class="comment form-control form-control-sm" value="' + suppliercomments + '"></td><td class="skxcenter">' + item['condition'] + '</td><td><input type="number" onkeyup="modalkeyUp(event, \'quantity\', ' + item['prodtype'] + ', ' +item['id'] + ')" oninput="modalkeyUp(event, \'quantity\', ' + item['prodtype'] + ', ' +item['id'] + ')" class="comment form-control form-control-sm" value="' + item['quantity'] + '"></td><td><input type="number" onkeyup="modalkeyUp(event, \'price\', ' + item['prodtype'] + ', ' +item['id'] + ')" oninput="modalkeyUp(event, \'price\', ' + item['prodtype'] + ', ' +item['id'] + ')" class="comment form-control form-control-sm" value="' + item['price'] + '"></td><td id="tot_' + item['prodtype'] + '-' +item['id'] + '"><span class="skxbadge badge badge-outline text-pink">' + (item['quantity'] * item['price']).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</span></td><td><button class="btn btn-sm btn-danger" type="button" onclick="removeItem(this, ' + item['prodtype'] + ', ' +item['id'] + ')" style="margin-bottom: 10px;">Remove</button></td></tr>');
		
	});
}

function updateTotals(){
	var labelfee = parseInt($('#label_fee').val());
	var shipfee = parseInt($('#ship_fee').val());
	var bankfee = parseInt($('#bank_fee').val());
	var surcharge = parseInt($('#surcharge').val());
	var credit = parseInt($('#credit').val());
	var discount = parseInt($('#discount').val());

	var subtotal = 0;
	listData.forEach(item => {
		subtotal += (item['price'] * item['quantity']);
	});
	$('#subtotal').html(subtotal.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
	var total = subtotal + labelfee + shipfee + bankfee + surcharge + credit + discount;
	$('#total').html(total.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
}
</script>
