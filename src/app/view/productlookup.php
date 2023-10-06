<?php

include PATH_CONFIG . "/constants.php";
?>
<div class="page-body">
	<div class="container-fluid">

		<div class="col-lg-9" style="margin:0 auto;">
			<div class="card card-lg">

				<div class="card-body">

					<h2>Product Lookup</h2>
					<form id="form1" style="margin-bottom:10px; " autocomplete="off">
						<fieldset class="form-fieldset" style="width:600px; margin:0 auto;">

							<div class="mb-3">
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
									&nbsp;Scan Serial Number</label>
								<input tabindex=5 type="text" autofocus class="form-control" id="scan_move" name="scan_move" style="text-align: center;" placeholder="Accessories Only" />
							</div>
						</fieldset>
					</form>
					<div class="row">
						<div class="col-md-6">
					<table id="data" class="table table-striped">
							<thead><tr><th>Detail</th><th>Value</th></thead>
							<tbody></tbody>
					</table>
					</div>
					<div class="col-md-6">
					<table id="data2" class="table table-striped">
							<thead><tr><th>Status</th><th>Count</th></thead>
							<tbody></tbody>
					</table>
					</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>



<script>

	function statusToMap(p){
		var st = [];

		<?php
			foreach($_ACSTATUSES as $k => $v) {
				
			  echo 'st['.$k.'] = "'.$v["Name"].'";';
			}

		?>

		return st[p];


	}

	function mapToTitle(k){
		var mapObject = { 
							'ast_id' : 'Stock Id',
							'ast_product' : 'Product Id',
'apr_sku' : 'Product Sku',
							'apr_name' : 'Product Name',

							'ast_subcat' : '',
							'ast_subsubcat' : '',
							'ast_order' : 'Order Id',
							'ast_servicetag' : 'Serial',
							'ast_partnumber' : '',
							'ast_specs' : '',
							'ast_status' : 'Status',
							'ast_date' : 'Date',
							'ast_addedby' : '',
							'ast_archived' : '',
							'ast_requested' : '',
							'ast_lastcomment' : 'Last Comment',
							'ast_cost' : '',
							'ast_retail' : '',
							'ast_trade' : '',
							'ast_rrp' : '',
							'ast_allocated' : '',
							'ast_allocatedto' : '',
							'ast_allocatedemail' : '',
							'ast_actionreq' : '',
							'ast_onsale' : '',
							'ast_onoffer' : '',
							'ast_soldprice' : '',
							'ast_solddate' : '',
							'ast_gbaseid' : '',
						
							'ast_ebaysubtitle' : '',
							'ast_tracking_sheet_printed' : '',
							'ast_laptop_label_printed' : '',
							'ast_defect_image1' : '',
							'ast_defect_image2' : '',
							'ast_defect_image3' : '',
							'ast_defect_description' : '',
							'ast_defected' : '',
							'ast_owner' : '',
							'ast_action' : '',
							'ast_save_tag' : '',
							'ast_ebay_thumb_url' : '',
							'ast_advertised' : '',
							'ast_strippeddate' : '',
							'ast_despatcheddate' : '',
							'ast_status_action' : '',
							'ast_actionreq_date' : '',
							'ast_state' : '',
							'ast_actioncmp_date' : '',
							'ast_record' : '',
							'aor_id' : 'Order Id',
							'aor_date' : 'Order Date',
							'aor_supplier' : 'Supplier Id',
							'aor_state' : 'Order State',
							'aor_total_items' : 'Order Total Items',
							'aor_total_delivered' : 'Order Total Delivered',
							'aor_fix_rate' : 'Order Fix Rate',
							'aor_reference' : 'Order Reference',
							'sp_id' : 'Supplier Id',
							'sp_name' : 'Supplier Name',
							'sp_del' : '',
							'apr_id' : '',
							
							'apr_condition' : 'Product Condition',
							'apr_box_label' : 'Product Box Label',
							'apr_box_subtitle' : 'Product Box Subtitle',
							'apr_image' : 'Product Image',
							'apr_description' : 'Product Description',
							'apr_del' : '',
							'apr_category' : 'Product Category',
							'apr_listed' : 'Product Listed' 
		};

		return mapObject[k];

	}


	function showData(d){
		var el =$('#data>tbody');

		if(d==""){ el.empty(); return;}

		Object.keys(d[0]).forEach(key => {
			var tit = mapToTitle(key);
			if(tit!=null && tit!="") {		
				if(key.indexOf("image")>-1)
					$('<tr><td>'+tit+'</td><td><img src="https://www.ndc.co.uk/pub/media/catalog/product/'+d[0][key]+'" width=100 /></td></tr>').appendTo(el);					
				else 
					$('<tr><td>'+tit+'</td><td>'+ (tit=="Status" ? statusToMap(d[0][key]) : d[0][key] )    +'</td></tr>').appendTo(el);
			}

		});

	}
	function showData2(d){
		var el =$('#data2>tbody');
		if(d==""){ el.empty(); return;}

		for(var i=0;i<d.length;i++){
			console.log(d[i]["ast_status"],d[i]["c"], el);
			$('<tr><td>'+statusToMap(d[i]["ast_status"])+'</td><td>'+d[i]["c"]+'</td></tr>').appendTo(el);
		};
	}
	function failSound() {
		let src = 'fail.wav';
		let audio = new Audio(src);
		audio.play();
	}

	function successSound() {
		let src = 'success.wav';
		let audio = new Audio(src);
		audio.play();
	}

	$(function() {


		$('#scan_move').keydown(function(event) {

			var $el = $('#scan_move');
			if (event.keyCode == 13) {
				event.preventDefault();
				var order = "";

				//Green/LGreen/Dgreen
				//accprint-box-labels.php
				//Else
				//accprint-z-box-labels.php



				var pd = {
					action: "get",
					data: {
						serial: $el.val()
					}
				};

				$.ajax({
					url: "/productlookupajax/get",
					data: pd,
					type: 'POST',
					error: function(a) {
						var err = JSON.parse(a.responseJSON).error;
						failSound();
						new_toast("danger", "Error - " + err);
						scan_move.value = '';
						showData("");
						showData2("");

					},
					success: function(a) {

						if (a.success) {

							
							showData(a.data);
							showData2(a.data2);

						} else {
							
							showData("");
							showData2("");
							scan_move.value = '';
						}

					}
				});




				return false;
			}

		});

	});
</script>
