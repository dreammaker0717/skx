<?php
$db = M::db();
include PATH_CONFIG . "/constants.php";
?>
<style type="text/css">
	.prodHighlight{
		background-color:#e3e3e3;
	}
	.prodHighlight:hover{
		background-color:#e4ebf0;
		cursor: pointer;
	}
</style>
<?php
if (isset($id)) {
	$id = intval($id);
	include PATH_CONFIG . "/constants.php";
	$db = M::db();
	$rmac = $db->get("rmac_items", "*", ["rmac_ID" => $id]);
	error_log(print_r($rmac, true));
}
?>
<div class="page-body">
	<div class="container-fluid">

		<div class="col-lg-9" style="margin:0 auto;">
			<div class="card card-lg">

				<div class="card-body">

					<h2>Create Product Return (Not Laptops)</h2>
					<div class="text-muted" style="margin-bottom:50px;">Scan the serial number of the item, and if it exists in the database, some of the required information will be filled in automatically.<br />If it does not exist in the database then you will have to enter the information manually.<br />If the item does not have a serial number then use "N/A" as the serial number to proceed.</div>

					<form id="formdata" autocomplete="off">
						<fieldset class="form-fieldset" style="width:100%; margin:0 auto;padding:2rem;">
							<div class="mb-3" style="width:500px; margin: 0 auto;">
								<div class="row">
									<div class="col-md-4">
										<label class="form-check form-switch">
											<input class="form-check-input" type="checkbox" <?php if (isset($rmac) && $rmac["rmac_servicetag"] == null) {echo "";} else echo "checked";?> onclick='handleUniqueItemCheckClick(this);'/>
											<span class="form-check-label">Unique Item?</span>
										</label>
									</div>
									<div class="col-md-4" align="center">
										<label class="form-label <?php if (isset($rmac) && $rmac["rmac_servicetag"] != null) {echo "required";} else if (!isset($rmac)) echo "required";?>" style="text-align: center;" id="scan_move_label">
										<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
											<path stroke="none" d="M0 0h24v24H0z" fill="none" />
											<path d="M4 7v-1a2 2 0 0 1 2 -2h2" />
											<path d="M4 17v1a2 2 0 0 0 2 2h2" />
											<path d="M16 4h2a2 2 0 0 1 2 2v1" />
											<path d="M16 20h2a2 2 0 0 0 2 -2v-1" />
											<rect x="5" y="11" width="1" height="2" />
											<line x1="10" y1="11" x2="10" y2="13" />
											<rect x="14" y="11" width="1" height="2" />
											<line x1="19" y1="11" x2="19" y2="13" /></svg>
										&nbsp;Serial Number</label>
									</div>
									<div class="col-md-4">
									</div>
								</div>
								<input style="max-width: 500px; display: inline-block; margin: 0 auto; text-align: center;" tabindex=5 type="text" class="form-control" id="scan_move" name="scan_move" placeholder="Scan to see if exists in database" autocomplete="off" <?php if (isset($rmac) && $rmac["rmac_servicetag"] != null) {echo "value='" . $rmac["rmac_servicetag"] . "' required";} else if (!isset($rmac)) echo "required";?> />
							</div>
							<div class="mb-3">
								<div class="row mb-3">
									<div class="col-auto">
										<label class="form-label required" id="orderno_label">Order Number:</label>
										<input list="ordernos" name="orderno" id="orderno" class="form-control" autocomplete="off" placeholder="Start typing Order No." required <?php if (isset($rmac)) {echo "value='" . $rmac["rmac_ordernumber"] . "'";}?> />
										<input type="hidden" name="orderno-hidden" id="orderno-hidden"  class="form-control" <?php if (isset($rmac)) {echo "value='" . $rmac["rmac_ordernumber"] . "'";}?> >
										<datalist id="ordernos">										
										</datalist>
									</div>
								</div>
								<div class="row align-items-center">
									<div class="col-auto">
										<label class="form-label required" id="purchasedon_label">Purchased On:</label>
										<input type="text" class="form-control" autocomplete="off" name="purchasedon" id="purchasedon" placeholder="NDC Website, eBay..." required <?php if (isset($rmac)) {echo "value='" . $rmac["rmac_purchasedon"] . "'";}?> />
									</div>
									<div class="col-auto">
										<label class="form-label required" id="fullname_label">Full Name:</label>
										<input type="text" class="form-control" autocomplete="off" name="fullname" id="fullname" required <?php if (isset($rmac)) {echo "value='" . $rmac["rmac_fullname"] . "'";}?> />
									</div>
									<div class="col-auto">
										<label class="form-label required" id="purchasedate_label">Purchase Date:</label>
										<input type="text" class="form-control" autocomplete="off" name="purchasedate" id="purchasedate" required <?php if (isset($rmac)) {echo "value='" . $rmac["rmac_purchasedate"] . "'";}?> />
									</div>
									<div class="col-auto">
										<label class="form-label required" id="productprice_label">Product Price:</label>
										<input type="text" class="form-control" autocomplete="off" name="productprice" id="productprice" required <?php if (isset($rmac)) {echo "value='" . $rmac["rmac_price"] . "'";}?> />
									</div>
							</div>
							<div style="padding-top:2rem;">
								<div class="row align-items-center">
									<div class="col-auto" style="Width:100%">
										<label class="form-label required">Product Details:</label>
										<input list="products" id="product"  class="form-control" autocomplete="off" placeholder="Start typing SKU or description." required <?php if (isset($rmac)) {echo "value='" . $rmac["rmac_sku"] . "-" . $rmac["rmac_product"] . "'";}?> />
										<input type="hidden" name="product-hidden" id="product-hidden" class="form-control" <?php if (isset($rmac)) {echo "value='" . $rmac["rmac_prodType"] . "-" . $rmac["rmac_productID"] . "'";}?> >
										<input type="hidden" name="product-sku" id="product-sku" class="form-control" <?php if (isset($rmac)) {echo "value='" . $rmac["rmac_sku"] . "'";}?> >
										<input type="hidden" name="product-text" id="product-text" class="form-control" <?php if (isset($rmac)) {echo "value='" . $rmac["rmac_product"] . "'";}?> >
										<datalist id="products">										
										</datalist>
									</div>
								</div>
							</div>							
							<div class="mb-3" style="padding-top:2rem;">
								<div class="row align-items-center">
									<div class="col-auto" style="Width:100%">
										<label class="form-label required">Fault Description:</label>
										<textarea class="form-control" name="fault" id="fault" style="min-height:150px"
										placeholder="Full description of fault as described by customer. &#10;&#10;If there are any parts/components or missing or tampered with, please describe here." required/><?php if (isset($rmac)) echo $rmac["rmac_fault"];?></textarea>
									</div>
								</div>
							</div>								
							<div class="mb-3" style="padding-top:2rem;">
								<div class="row align-items-center">
									<div class="col-auto" style="width:30%">
										  <label class="form-check form-switch">
											<input class="form-check-input" type="checkbox" <?php if (isset($rmac) && $rmac["rmac_isours"] == 0) {echo "";} else echo "checked";?> name="itemisours" id="itemisours">
											<span class="form-check-label">Item is ours?</span>
										  </label>
									</div>
									<div class="col-auto" style="width:30%">
										  <label class="form-check form-switch">
											<input class="form-check-input" type="checkbox" <?php if (isset($rmac) && $rmac["rmac_iscomplete"] == 0) {echo "";} else echo "checked";?> id="itemiscomplete" name="itemiscomplete">
											<span class="form-check-label">Item is complete?</span>
										  </label>
									</div>
									<div class="col-auto" style="width:30%">
										  <label class="form-check form-switch">
											<input class="form-check-input" type="checkbox" <?php if (isset($rmac) && $rmac["rmac_isundamaged"] == 0) {echo "";} else echo "checked";?> name="itemisundamaged" id="itemisundamaged">
											<span class="form-check-label">Item is undamaged/untampered?</span>
										  </label>
									</div>									
								</div>
							</div>					
							
							<div class="mb-3" style="width:400px; margin: 0 auto; padding-top:2rem;">
								<label class="form-label" style="text-align: center;">
									<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="9" cy="7" r="4" /><path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /><path d="M16 3.13a4 4 0 0 1 0 7.75" /><path d="M21 21v-2a4 4 0 0 0 -3 -3.85" /></svg>
									&nbsp;Supplier (if known)</label>
								
								<select class="form-control" aria-describedby="Supplier" name="rmac_supplier" id="rmac_supplier">
											<option value=""> Please Select </option>
											<?php
											   $spp = $db->query("select sp_id,sp_name from suppliers where sp_del=0 order by sp_name asc")->fetchAll();
												foreach ($spp as $k => $v) {
													if (isset($rmac) && $v["sp_id"] == $rmac["rmac_supplier"]) {
												   		echo "<option selected value=" . $v["sp_id"] . ">" . $v["sp_name"] . "</option>";
													} else {
												   		echo "<option value=" . $v["sp_id"] . ">" . $v["sp_name"] . "</option>";
													}
												}
											?>
									</select>
							</div>
							
							<div class="mb-3" style="padding-top:2rem;">
							<div class="row align-items-center">
									<div class="col-auto" style="Width:100%">
										<label class="form-label">Photos:</label>
										<input name="photos[]" type="file" class="file" accept="image/*" data-show-upload="false" data-show-caption="true" multiple>
										<span class="text-muted">Multiple files can be selected</span>
									</div>
								</div>
							</div>

							<div class="row align-items-center">
									<div class="col-auto" style="Width:100%">
										<label class="form-label">Videos:</label>
										<input name="videos[]" type="file" class="file" accept="video/*" data-show-upload="false" data-show-caption="true" multiple>
										<span class="text-muted">Multiple files can be selected</span>
									</div>
								</div>
							</div>
							
							<?php
							if (isset($rmac) && $rmac["rmac_images"] != "") {
								echo "<br>";
								echo "<h2>Images</h2>";
								echo "<div class='row'>";
								$imgs = explode("," ,$rmac["rmac_images"]);
								foreach ($imgs as $img) {
									if ($img != "") {
										echo "<div class='col-md-3'><a href='" . $img . "' style='width: 20%;height: 260px; padding-left: 0px; padding-right: 0px;'><img src='" . $img . "' loading='lazy' class='mx-2 mb-2' style='width: 100%;height: 260px; object-fit: contain; background-color: grey;'/></a></div>";
									}
								}
								echo "</div>";
							}
							if (isset($rmac) && $rmac["rmac_videos"] != "") {
								echo "<br>";
								echo "<h2>Videos</h2>";
								echo "<div class='row'>";
								$vids = explode("," ,$rmac["rmac_videos"]);
								foreach ($vids as $vid) {
									if ($vid != "") {
										echo "<div class='col-md-3'><video controls style='width: 100%;height: 260px;object-fit: contain; background-color: grey;padding-left: 0px; padding-right: 0px;' class='mx-2 mb-2'><source src=" . $vid . "></video></div>";
									}
								}
								echo "</div>";
							}
							?>
							
							<div class="btn-list justify-content-end"style="padding-top:3rem;">
							  <a href="javascript:resetForm()" class="btn">Cancel RMA</a>
							  <?php 
							  	if (isset($rmac)) echo "<a href='javascript:updateRMAP()' class='btn btn-danger'>Update RMA</a>";
							  	else {
							  		echo "<a href='javascript:returnRMAP()' class='btn btn-warning'>Return Item to Stock</a>";
							  		echo "<a href='javascript:createRMAP()' class='btn btn-danger'>Create RMA and Print Label</a>";
							  	}
							  ?>
							</div>
						</fieldset>
					</form>

				</div>

			</div>
		</div>
	</div>
</div>

<div class="modal" tabindex="-1" role="dialog" id="serialNotFoundModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Serial Not Found</h5>
        <button type="button" class="btn-close" onclick='resetForm();' aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Serial number not recognized, continue with entered serial number?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick='resetForm();'>No</button>
        <button type="button" class="btn btn-primary" onclick='handleSerialNotFound(this);'>Yes</button>
      </div>
    </div>
  </div>
</div>

<div class="modal" tabindex="-1" role="dialog" id="productNotSoldModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Item Not Sold</h5>
        <button type="button" class="btn-close" onclick='resetForm();' aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Item is not sold, create internal RMA?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick='resetForm();'>No</button>
        <button type="button" class="btn btn-primary" onclick='handleProductNotSold(this);'>Yes</button>
      </div>
    </div>
  </div>
</div>

<div class="modal" tabindex="-1" role="dialog" id="productSelectModal">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Select a product</h5>
        <button type="button" class="btn-close" onclick='closeProductSelectModal();' aria-label="Close"></button>
      </div>
      <div class="modal-body" id="productList">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick='closeProductSelectModal();'>Cancel</button>
      </div>
    </div>
  </div>
</div>


<div class="modal" tabindex="-1" role="dialog" id="uploadProgresstModal">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Uploading...</h5>
      </div>
      <div class="modal-body">
      	<div>
      		<div class="progress">
			  <div id="uploadProgressBar" class="progress-bar" role="progressbar" style="width: 0%" aria-valuemin="0" aria-valuemax="100"></div>
			</div>
			<div id="status"></div>
      	</div>
      </div>
    </div>
  </div>
</div>

<script src="/dist/js/moment.min.js"></script>
<script src="/dist/js/moment-timezone-with-data.min.js"></script>
<script>
var isInternal = false;
<?php if (isset($rmac) && $rmac["is_internal"] == 1) echo "isInternal = true;\r\n"?>

function resetForm() {
	$('form').get(0).reset();
	if ($('#serialNotFoundModal').is(':visible')) {
		$('#serialNotFoundModal').modal('hide');
	}
	if ($('#productNotSoldModal').is(':visible')) {
		$('#productNotSoldModal').modal('hide');
		tempData = null;
	}
	isInternal = false;
	enableOrderFields();
}


function uploadProgressHandler(event) {
    var percent = (event.loaded / event.total) * 100;
    var progress = Math.round(percent);
    $("#uploadProgressBar").css("width", progress + "%");
    $("#status").html(progress + "% uploaded... please wait");
}

function loadHandler(event) {
    $("#status").html(event.target.responseText);
    $("#uploadProgressBar").css("width", "0%");
    $('#uploadProgresstModal').modal('hide');
}

function errorHandler(event) {
    $("#status").html("Upload Failed");
}

function abortHandler(event) {
    $("#status").html("Upload Aborted");
}


function createRMAP() {
	var form = $("#formdata");
	if (form[0].checkValidity()) {
		var datastring = form.serialize() + '&' + $.param({isInternal: isInternal}) + '&' + $.param({action: 'creatermac'});
		$.ajax({
		    type: "POST",
		    url: "/newrmacajax",
		    data: datastring,
		    dataType: "json",
		    success: function(data) {
				if(typeof data == "string") data= JSON.parse(data);
		        if(data.success){
					setCounters();
					if(data.id>0) {
						var photofiles = $('[name="photos[]"]').prop("files");
						var videofiles = $('[name="videos[]"]').prop("files");
						if(photofiles.length>0 || videofiles.length>0) {
							var fd = new FormData();
							fd.append("id", data.id);
							fd.append("action","creatermacmedia");
							for(var i =0;i<photofiles.length;i++) {						
								fd.append('image[]', photofiles[i]);
							}

							for(var i =0;i<videofiles.length;i++) {						
								fd.append('video[]', videofiles[i]);
							}

							$('#uploadProgresstModal').modal('show');

							$.ajax({
								url: "/newrmacajax",
								dataType: 'text',  
								cache: false,
								contentType: false,
								processData: false,
								data: fd,                         
								type: 'POST',
								xhr: function () {
					                var xhr = new window.XMLHttpRequest();
					                xhr.upload.addEventListener("progress",
					                    uploadProgressHandler,
					                    false
					                );
					                xhr.addEventListener("load", loadHandler, false);
					                xhr.addEventListener("error", errorHandler, false);
					                xhr.addEventListener("abort", abortHandler, false);

					                return xhr;
					            },
								success: function(rs){
									successSound();
									new_toast("success", "RMA Saved");
								}
							});
						} else {
							successSound();
							new_toast("success", "RMA Saved");
						}
					}
				}
		    },
		    error: function(a) {
				new_toast("danger", "Error! " + a.error);
		    }
		});
	}
	form[0].classList.add('was-validated');
}

function updateRMAP() {
	var form = $("#formdata");
	if (form[0].checkValidity()) {
		var datastring = form.serialize() + '&' + $.param({isInternal: isInternal}) + '&' + $.param({action: 'updatermac', id: <?php if (isset($id)) echo $id; else echo 0;?>});
		$.ajax({
		    type: "POST",
		    url: "/newrmacajax",
		    data: datastring,
		    dataType: "json",
		    success: function(data) {
				if(typeof data == "string") data= JSON.parse(data);
		        if(data.success){
					if(data.id>0) {
						var photofiles = $('[name="photos[]"]').prop("files");
						var videofiles = $('[name="videos[]"]').prop("files");
						if(photofiles.length>0 || videofiles.length>0) {
							var fd = new FormData();
							fd.append("id", data.id);
							fd.append("action","creatermacmedia");
							for(var i =0;i<photofiles.length;i++) {						
								fd.append('image[]', photofiles[i]);
							}

							for(var i =0;i<videofiles.length;i++) {						
								fd.append('video[]', videofiles[i]);
							}

							$('#uploadProgresstModal').modal('show');

							$.ajax({
								url: "/newrmacajax",
								dataType: 'text',  
								cache: false,
								contentType: false,
								processData: false,
								data: fd,                         
								type: 'POST',
								xhr: function () {
					                var xhr = new window.XMLHttpRequest();
					                xhr.upload.addEventListener("progress",
					                    uploadProgressHandler,
					                    false
					                );
					                xhr.addEventListener("load", loadHandler, false);
					                xhr.addEventListener("error", errorHandler, false);
					                xhr.addEventListener("abort", abortHandler, false);

					                return xhr;
					            },
								success: function(rs){
									successSound();
									new_toast("success", "RMA Saved");
								}
							});
						} else {
							successSound();
							new_toast("success", "RMA Saved");
						}
					}
				}
		    },
		    error: function(a) {
				new_toast("danger", "Error! " + a.error);
		    }
		});
	}
	form[0].classList.add('was-validated');
}

function returnRMAP() {
	if (document.getElementById("scan_move").value != "") {
		var pd = {
				action: "reset_status",
				data: document.getElementById("scan_move").value
			};
		$.ajax({
		    type: "POST",
		    url: "/newrmacajax",
		    data: pd,
		    dataType: "json",
		    success: function(data) {
				if(typeof data == "string") data= JSON.parse(data);
		        if(data.success){
					successSound();
					new_toast("success", "RMA Updated");
				}
		    },
		    error: function(a) {
				new_toast("danger", "Error! " + a.error);
		    }
		});
	} else {
		var form = $("#formdata");
		if (form[0].checkValidity()) {
			var datastring = form.serialize() + '&' + $.param({action: 'createreturnrmac'});
			$.ajax({
			    type: "POST",
			    url: "/newrmacajax",
			    data: datastring,
			    dataType: "json",
			    success: function(data) {
					if(typeof data == "string") data= JSON.parse(data);
			        if(data.success){
						successSound();
						new_toast("success", "RMA Updated");
					}
			    },
			    error: function(a) {
					new_toast("danger", "Error! " + a.error);
			    }
			});
		}
		form[0].classList.add('was-validated');
	}
}

let ordernoAborter = null;
function getOrdernoData(param) {
	if(ordernoAborter) ordernoAborter.abort();
	ordernoAborter = new AbortController();
	const signal = ordernoAborter.signal;
	const url = '/newrmacajax';
	let formData = new FormData();
	formData.append('action', 'get_orderno_suggestion');
	formData.append('term', param);

	return fetch(url, {method: 'post', body: formData, signal})
	  .then(res => {return res.json();})
	  .then(resjson => {ordernoAborter = null; return resjson;});
}

let productAborter = null;
function getProductData(param) {
	if(productAborter) productAborter.abort();
	productAborter = new AbortController();
	const signal = productAborter.signal;
	const url = '/newitemrfqsajax';
	let formData = new FormData();
	formData.append('action', 'get_suggestion');
	formData.append('term', param);

	return fetch(url, {method: 'post', body: formData, signal})
	  .then(res => {return res.json();})
	  .then(resjson => {productAborter = null; return resjson;});
}

var tempData = null;
var isProductSet = false;
$(function() {
	$('#scan_move').keydown(function(event) {
		var $el = $('#scan_move');
		if (event.keyCode == 13) {
			event.preventDefault();
			enableOrderFields();
			isInternal = false;

			var pd = {
				action: "getinfo",
				data: {						
					serial: $el.val()
				}
			};

			$.ajax({
				url: "/newrmacajax",
				data: pd,
				type: 'POST',
				error: function(a) {
					failSound();
					new_toast("danger", "Error! " + a.error);
				},
				success: function(a) {
					if (a.success) {
						if (a.status == 16) {
							successSound();
							if (a.data != null) {
								var date = null;
							if (a.data["order_date"] != 0) {
						        date = moment.tz(a.data["order_date"] * 1000, 'Europe/London').format('YYYY/MM/DD');
							    } else {
							        date = "";
							    }
								$('#purchasedon').val(a.data["sales_channel"]);
								$('#fullname').val(a.data["recipient"]);
								$('#purchasedate').val(date);
								$('#orderno').val(a.data["order_number"]);
								$('#productprice').val(a.data["order_total"]);
							}

							$('#product').val(a.sku + "-" + a.name);
							if (a.supplier != 0) {
								$('#rmac_supplier').val(a.supplier);
							}
							$('#product-hidden').val(a.producttype + "-" + a.productid);
							$('#product-sku').val(a.sku);
							$('#product-text').val(a.name);
							isProductSet = true;
							new_toast("success", "Item Found");
						} else {
							failSound();
							tempData = a;
							$('#productNotSoldModal').modal('show');
						}
					} else{
						failSound();
						$('#serialNotFoundModal').modal('show');
					}
				}
			});
			return false;
		}
	});

	document.getElementById('orderno').addEventListener('input', function(e) {
		if (e.target.value != "") {
			getOrdernoData(e.target.value)
			  .then(autocompleteItems => {
				var ordernos = document.getElementById('ordernos');
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
						ordernos.innerHTML = '';
						found = true;
						fetchOrderDetails(hiddenInput.value);
						break;
					}
				}

				for (var i = 0; i < autocompleteItems.length; i++) {
					if (autocompleteItems[i].order_number == inputValue) {
						hiddenInput.value = autocompleteItems[i].id;
						ordernos.innerHTML = '';
						found = true;
						fetchOrderDetails(hiddenInput.value);
						break;
					}
				}

				if (!found) {
					ordernos.innerHTML = '';
					autocompleteItems.forEach(function(item){
					   var autocompleteOption = document.createElement('option');
					   autocompleteOption.setAttribute('data-value', item.id);
					   autocompleteOption.innerText = item.order_number;
					   ordernos.appendChild(autocompleteOption);
					});
				}
			}).catch(e => console.error('Request failed', e.name, e.message));
		} else {
			if(ordernoAborter) ordernoAborter.abort();
			var ordernos = document.getElementById('ordernos');
			ordernos.innerHTML = '';
		}
	});


	document.getElementById('product').addEventListener('input', function(e) {
		if (e.target.value != "") {
			getProductData(e.target.value)
			  .then(autocompleteItems => {

				var products = document.getElementById('products');
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
						document.getElementById('product-sku').value = option.getAttribute('data-sku');
						document.getElementById('product-text').value = option.getAttribute('data-name');
						products.innerHTML = '';
						found = true;
						isProductSet = true;
						break;
					} else {
						isProductSet = false;
					}
				}

				if (!found) {
					products.innerHTML = '';
					autocompleteItems.forEach(function(item){
					   var autocompleteOption = document.createElement('option');
					   autocompleteOption.setAttribute('data-value', item.prodtype + "-" + item.productid);
					   autocompleteOption.setAttribute('data-sku', item.sku);
					   autocompleteOption.setAttribute('data-name', item.name);
					   autocompleteOption.innerText = item.sku + "-" + item.name;
					   products.appendChild(autocompleteOption);
					});
				}
			}).catch(e => console.error('Request failed', e.name, e.message));
		} else {
			if(productAborter) productAborter.abort();
			var products = document.getElementById('products');
			products.innerHTML = '';
		}
	});

	if (isInternal) disableOrderFields();
});

function handleUniqueItemCheckClick(cb){
	if (!cb.checked) {
		document.getElementById('scan_move').disabled = true;
		document.getElementById('scan_move_label').classList.remove("required");
		document.getElementById('scan_move').required = false;
	} else {
		document.getElementById('scan_move').disabled = false;
		document.getElementById('scan_move_label').classList.add("required");
		document.getElementById('scan_move').required = true;
	}
}

function handleSerialNotFound(et){
	$('#serialNotFoundModal').modal('hide');
	document.getElementById('itemisours').checked = false;
}

function handleProductNotSold(et){
	$('#productNotSoldModal').modal('hide');
	disableOrderFields();
	$('#product').val(tempData.sku + "-" + tempData.name);
	if (tempData.supplier != 0) {
		$('#rmac_supplier').val(tempData.supplier);
	}
	$('#product-hidden').val(tempData.producttype + "-" + tempData.productid);
	$('#product-sku').val(tempData.sku);
	$('#product-text').val(tempData.name);
	isInternal = true;
	tempData = null;
}

function disableOrderFields(){
	document.getElementById('orderno').required = false;
	document.getElementById('orderno').disabled = true;
	document.getElementById('orderno_label').classList.remove("required");
	document.getElementById('orderno-hidden').required = false;
	document.getElementById('orderno-hidden').disabled = true;
	document.getElementById('purchasedon').required = false;
	document.getElementById('purchasedon').disabled = true;
	document.getElementById('purchasedon_label').classList.remove("required");
	document.getElementById('purchasedate').required = false;
	document.getElementById('purchasedate').disabled = true;
	document.getElementById('purchasedate').value = "";
	document.getElementById('purchasedate_label').classList.remove("required");
	document.getElementById('productprice').required = false;
	document.getElementById('productprice').disabled = true;
	document.getElementById('productprice').value = "";
	document.getElementById('productprice_label').classList.remove("required");
	document.getElementById('fullname').value = "Internally Tested Faulty";
}

function enableOrderFields(){
	document.getElementById('orderno').required = true;
	document.getElementById('orderno').disabled = false;
	document.getElementById('orderno_label').classList.add("required");
	document.getElementById('orderno-hidden').required = true;
	document.getElementById('orderno-hidden').disabled = false;
	document.getElementById('purchasedon').required = true;
	document.getElementById('purchasedon').disabled = false;
	document.getElementById('purchasedon_label').classList.add("required");
	document.getElementById('purchasedate').required = true;
	document.getElementById('purchasedate').disabled = false;
	document.getElementById('purchasedate_label').classList.add("required");
	document.getElementById('productprice').required = true;
	document.getElementById('productprice').disabled = false;
	document.getElementById('productprice_label').classList.add("required");
	document.getElementById('fullname').value = "";
}

var tempProdData = null;
function fetchOrderDetails(ordid){
	var pd = {
		action: "get_order_details",
		id: ordid
	};
	$.ajax({
		url: "/newrmacajax",
		data: pd,
		type: 'POST',
		error: function(a) {
			failSound();
			new_toast("danger", "Error! " + a.error);
		},
		success: function(a) {
			if (a.success) {
					var date = null;
					if (a.orderdata["order_date"] != 0) {
				        date = moment.tz(a.orderdata["order_date"] * 1000, 'Europe/London').format('YYYY/MM/DD');
				    } else {
				        date = "";
				    }
					$('#purchasedon').val(a.orderdata["sales_channel"]);
					$('#fullname').val(a.orderdata["recipient"]);
					$('#purchasedate').val(date);
					$('#productprice').val(a.orderdata["order_total"]);
					if (!isProductSet) {
						if (a.partdata.length > 0 && a.partdata.length < 2) {
							$('#product').val(a.partdata[0]["sku"] + "-" + a.partdata[0]["name"]);
							$('#product-hidden').val(a.partdata[0]["prodtype"] + "-" + a.partdata[0]["prodid"]);
							$('#product-sku').val(a.partdata[0]["sku"]);
							$('#product-text').val(a.partdata[0]["name"]);
							if (a.partdata[0].suppliers != null) {
								$('#rmac_supplier')
								    .empty()
								    .append('<option value=""> Please Select </option>');
								a.partdata[0].suppliers.forEach(item => {
									$('#rmac_supplier').append('<option value="' + item.nor_supplier + '">' + item.sp_name + '</option>');
								});
							}
						} else if(a.partdata.length > 1) {
							$('#productList').empty();
							for(var i = 0; i < a.partdata.length; i++){
								$('#productList').append("<div class='col-md-12 prodHighlight mb-2 p-3' onclick='setSelectedProd(" + i + ");'><strong>" + a.partdata[i]['sku'] + "-" + a.partdata[i]['name'] + "<strong></div>");
							}
							
							tempProdData = a.partdata;
							$('#productSelectModal').modal('show');
						}
					}
			} else{
				failSound();
				new_toast("danger", "Error occured.");
			}
		}
	});
	return false;
}

function setSelectedProd(index){
	$('#product').val(tempProdData[index]["sku"] + "-" + tempProdData[index]["name"]);
	$('#product-hidden').val(tempProdData[index]["prodtype"] + "-" + tempProdData[index]["prodid"]);
	$('#product-sku').val(tempProdData[index]["sku"]);
	$('#product-text').val(tempProdData[index]["name"]);
	if (tempProdData[index].suppliers != null) {
		$('#rmac_supplier')
		    .empty()
		    .append('<option value=""> Please Select </option>');
		tempProdData[index].suppliers.forEach(item => {
			$('#rmac_supplier').append('<option value="' + item.nor_supplier + '">' + item.sp_name + '</option>');
		});
	}
	tempProdData = null;
	$('#productSelectModal').modal('hide');
}

function closeProductSelectModal(){
	tempProdData = null;
	$('#productSelectModal').modal('hide');
}
</script>
