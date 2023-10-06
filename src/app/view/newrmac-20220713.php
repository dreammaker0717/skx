<?php
$db = M::db();
include PATH_CONFIG . "/constants.php";
?>
<div class="page-body">
	<div class="container-fluid">

		<div class="col-lg-9" style="margin:0 auto;">
			<div class="card card-lg">

				<div class="card-body">

					<h2>Create Product Return (Not Laptops)</h2>
					<div class="text-muted" style="margin-bottom:50px;">Scan the serial number of the item, and if it exists in the database, some of the required information will be filled in automatically.<br />If it does not exist in the database then you will have to enter the information manually.<br />If the item does not have a serial number then use "N/A" as the serial number to proceed.</div>




					<form id="form1" autocomplete="off">
						<fieldset class="form-fieldset" style="width:100%; margin:0 auto;padding:2rem;">
							<div class="mb-3" style="width:400px; margin: 0 auto;">
								<label class="form-label required" style="text-align: center;">
									<!-- Download SVG icon from http://tabler-icons.io/i/barcode -->
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
								<input tabindex=5 type="text" class="form-control" id="scan_move" name="scan_move" style="text-align: center;" placeholder="Scan to see if exists in database" autocomplete="off"/>
							</div>
							<div class="mb-3">
								<div class="row align-items-center">
									<div class="col-auto">
										<label class="form-label required">Purchased On:</label>
										<input type="text" class="form-control" autocomplete="off" name="purchasedon" id="purchasedon" placeholder="NDC Website, eBay..."/>
									</div>
									<div class="col-auto">
										<label class="form-label required">Full Name:</label>
										<input type="text" class="form-control" autocomplete="off" name="fullname" id="fullname"/>
									</div>
									<div class="col-auto">
										<label class="form-label required" >Purchase Date:</label>
										<input type="text" class="form-control" autocomplete="off" name="purchasedate" id="purchasedate"/>
									</div>
									<div class="col-auto">
										<label class="form-label required">Order Number:</label>
										<input type="text" class="form-control" autocomplete="off" name="orderno" id="orderno"/>
									</div>
							</div>
							<input type="hidden" name="product-table" id="product-hidden-table" class="form-control">
							<div class="mb-3" style="padding-top:2rem;">
								<div class="row align-items-center">
									<div class="col-auto" style="Width:70%">
										<label class="form-label required">Product Details:</label>
										<input type="text" class="form-control" name="product-text" id="product" list="allproducts"  autocomplete="off" placeholder="Start typing SKU or description or use the button on the right to add it to the database."/>
										<input type="hidden" name="product" id="product-hidden" class="form-control">
										<input type="hidden" name="product-sku" id="product-sku-hidden" class="form-control">
										<datalist id=allproducts  >
										<?php
											   $spp = $db
												   ->query(
													   "select 'New Item' as tbl, npr_id, npr_name, npr_sku from nwp_products where npr_del=0 
													   union all select 
													   'Accessories' ,apr_id, apr_name, apr_sku from aproducts where apr_del=0
													   union all select 
													   'Dell Part',dp_id, dp_name, dp_sku from dell_part 
													   order by 2 asc"
												   )
												   ->fetchAll();
											   foreach ($spp as $k => $v) {												   
												   echo "<option data-sku-value='".$v["npr_sku"]."' data-table-value='" .$v["tbl"]."' data-value='".$v["npr_id"]."'>" .$v["tbl"]." ".$v["npr_sku"] ." - ". $v["npr_name"] ."</option>";
											   }
											   ?>
										</datalist>

									</div>
									<div class="col-auto" style="padding-top:1.6rem;">
										<!--<button type=button class='btn btn-primary' onclick="popNewProduct()">
										<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
										<path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
										<line x1="12" y1="5" x2="12" y2="19"></line>
										<line x1="5" y1="12" x2="19" y2="12"></line>
										</svg>
										Create New Product</button>-->
									</div>
								</div>
							</div>							
							<div class="mb-3" style="padding-top:2rem;">
								<div class="row align-items-center">
									<div class="col-auto" style="Width:100%">
										<label class="form-label required">Fault Description:</label>
										<textarea class="form-control" name="fault" id="fault" style="min-height:150px"
										placeholder="Full description of fault as described by customer. &#10;&#10;If there are any parts/components or missing or tampered with, please describe here."/></textarea>
									</div>
								</div>
							</div>								
							<div class="mb-3" style="padding-top:2rem;">
								<div class="row align-items-center">
									<div class="col-auto" style="width:30%">
										  <label class="form-check form-switch">
											<input class="form-check-input" type="checkbox" checked name="itemisours" id="itemisours">
											<span class="form-check-label">Item is ours?</span>
										  </label>
									</div>
									<div class="col-auto" style="width:30%">
										  <label class="form-check form-switch">
											<input class="form-check-input" type="checkbox" checked id="itemiscomplete" name="itemiscomplete">
											<span class="form-check-label">Item is complete?</span>
										  </label>
									</div>
									<div class="col-auto" style="width:30%">
										  <label class="form-check form-switch">
											<input class="form-check-input" type="checkbox" checked name="itemisundamaged" id="itemisundamaged">
											<span class="form-check-label">Item is undamaged/untampered?</span>
										  </label>
									</div>									
								</div>
							</div>					
							
							
							<div class="mb-3" style="padding-top:2rem;">
							<div class="row align-items-center">
									<div class="col-auto" style="Width:100%">
										<label class="form-label">Photos:</label>
										<input id="input-2" name="photos[]" type="file" class="file"  data-show-upload="false" data-show-caption="true" multiple>
										<span class="text-muted">Multiple files can be selected</span>
									</div>
								</div>
							</div>


							<div class="mb-3" style="width:400px; margin: 0 auto; padding-top:2rem;">
								<label class="form-label" style="text-align: center;">
									<!-- Download SVG icon from http://tabler-icons.io/i/users -->
									<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="9" cy="7" r="4" /><path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /><path d="M16 3.13a4 4 0 0 1 0 7.75" /><path d="M21 21v-2a4 4 0 0 0 -3 -3.85" /></svg>
									&nbsp;Supplier (if known)</label>
								
								<select class="form-control" aria-describedby="Supplier" name="rmac_supplier" id="rmac_supplier" required>
											<option value=""> Please Select </option>
											<?php
											   $spp = $db
												   ->query(
													   "select sp_id,sp_name from suppliers where sp_del=0 order by sp_name asc"
												   )
												   ->fetchAll();
											   foreach ($spp as $k => $v) {
												   
												   echo "<option  value=" .
													   $v["sp_id"] .
													   ">" .
													   $v["sp_name"] .
													   "</option>";
											   }
											   ?>
										</select>


							</div>
							

							<div class="btn-list justify-content-end"style="padding-top:3rem;">
							  <a href="javascript:resetForm()" class="btn">Cancel RMA</a>
							  <a href="javascript:createRMAP()" class="btn btn-danger">Create RMA and Print Label</a>
							</div>


						</fieldset>
					</form>

				</div>

			</div>
		</div>
	</div>
</div>
<form id="TheForm" method="post" action="test.asp" target="TheWindow">
	<input type="hidden" name="stock_items" id="stock_items" value="" />
</form>
<script>

function resetForm() {
	$('form').get(0).reset();
}

function createRMAP() {
	var form = $("#form1");

	var datastring = form.serialize() + '&' + $.param({action: 'creatermap'});;

	$.ajax({
    type: "POST",
    url: "/newrmacajax/creatermap",
    data: datastring,
    dataType: "json",
    success: function(data) {
	
		if(typeof data == "string") data= JSON.parse(data);
		
        if(data.success)
		{
			if(data.id>0) {



				var files = $('[name="photos[]"]').prop("files");
				if(files.length>0) {
					var fd = new FormData();
					fd.append("id", data.id);
					fd.append("action","creatermapimages");
					for(var i =0;i<files.length;i++) {						
						fd.append('image[]', files[i]);						
					}

					$.ajax({
						url: "/newrmacajax/creatermapimages",
						dataType: 'text',  
						cache: false,
						contentType: false,
						processData: false,
						data: fd,                         
						type: 'POST',
						success: function(rs1){
							//ok
							console.log(rs1);
						}
					});
				}



				$('#TheForm').attr("action", "/rmacprintajax/rmac-box-labels");
				$('#stock_items').val(data.id);
				window.open('', 'TheWindow');
				document.getElementById('TheForm').submit();
			}
		}
    },
    error: function() {
        alert('error handling here');
    }
});
}

$(function(){
		if(document.querySelector('input[list]')!= null) {
			document.querySelector('input[list]').addEventListener('input', function(e) {
				var input = e.target,
					list = input.getAttribute('list'),
					options = document.querySelectorAll('#' + list + ' option'),
					hiddenInput = document.getElementById(input.getAttribute('id') + '-hidden'),
					hiddenInputSKU = document.getElementById(input.getAttribute('id') + '-sku-hidden'),
					hiddenInputTable = document.getElementById(input.getAttribute('id') + '-hidden-table'),
					inputValue = input.value;

				hiddenInput.value = inputValue;
				hiddenInputSKU.value = "";
				hiddenInputTable.value="";


				for(var i = 0; i < options.length; i++) {
					var option = options[i];

					if(option.innerText === inputValue) {
						hiddenInput.value = option.getAttribute('data-value');
						hiddenInputSKU.value = option.getAttribute('data-sku-value');
						hiddenInputTable.value = option.getAttribute('data-table-value');
						break;
					}
				}
			});
		}
	});

	function popNewProduct() {
		//window.open("admin/newitemproducts","_tar","");
	}
	$(function() {
		

		$('#scan_move').keydown(function(event) {
			var $el = $('#scan_move');
			if (event.keyCode == 13) {
				event.preventDefault();
			
				var pd = {
					action: "getinfo",
					data: {						
						serial: $el.val()
					}
				};

				$.ajax({
					url: "/newrmacajax/getinfo",
					data: pd,
					type: 'POST',
					error: function(a) {
						var err = JSON.parse(a.responseJSON).error;
						failSound();
				
					},
					success: function(a) {
					
						if (a.success) {
						
							if(a.data.length>0) {
								successSound();
								//scan_move.value = '';
							
								$('#rmac_supplier').val(a.data[0]["aor_supplier"]);
								$('#orderno').val( a.data[0]["ast_order"]);
								$('#purchasedate').val(a.data[0]["ast_solddate"]);
								$('#product-hidden').val(a.data[0]["apr_id"]);
								$('#product').val(a.data[0]["apr_name"]);
								$('#product-sku-hidden').val(a.data[0]["apr_sku"]);
								$('#product-hidden-table').val(a.data[0]["tbl"]);
								new_toast("success", "Item Found");
							}
							else {
								//scan_move.value = '';
								new_toast("danger", "Error! Item not found!");
							}

							
						} else
							new_toast("danger", "Error! " + a.error);
						
					}
				});
				return false;
			}

		});

	});
</script>