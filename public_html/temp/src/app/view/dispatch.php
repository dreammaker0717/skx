<div class="page-body">
	<div class="container-fluid">

		<div class="col-lg-9" style="margin:0 auto;">
			<div class="card card-lg">

				<div class="card-body">

					<h2>Dispatch Items (Not Laptops)</h2>
					<div class="text-muted" style="margin-bottom:50px;">Scan the order slip and the serial number of the product to ship the item.<br />ALL products with the new type of barcodes need to be scanned out.</div>




					<form id="form1" style="margin-bottom:300px;" autocomplete="off">
						<fieldset class="form-fieldset" style="width:600px; margin:0 auto;">
							<div class="mb-3">
								<label class="form-label required" style="text-align: center;">
									<!-- Download SVG icon from http://tabler-icons.io/i/list -->
									<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
										<path stroke="none" d="M0 0h24v24H0z" fill="none" />
										<line x1="9" y1="6" x2="20" y2="6" />
										<line x1="9" y1="12" x2="20" y2="12" />
										<line x1="9" y1="18" x2="20" y2="18" />
										<line x1="5" y1="6" x2="5" y2="6.01" />
										<line x1="5" y1="12" x2="5" y2="12.01" />
										<line x1="5" y1="18" x2="5" y2="18.01" /></svg>
									&nbsp;Scan Order Number</label>
								<input tabindex=4 type="text" autofocus class="form-control" id="scan_order" name="scan_order" style="text-align: center;" />
							</div>
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
								<input tabindex=5 type="text" class="form-control" id="scan_move" name="scan_move" style="text-align: center;" />
							</div>
						</fieldset>
					</form>

				</div>

			</div>
		</div>
	</div>
</div>



<script>
	$(function() {
		$('#form1 input[type="text"]').keypress(function(event) {
			var keycode = (event.keyCode ? event.keyCode : event.which);
			if (keycode == '13') {
				if (this.name == "scan_order") $('#scan_move').focus();
			}
		});


		$('#scan_move').keydown(function(event) {
			var $el = $('#scan_move');
			if (event.keyCode == 13) {
				event.preventDefault();
				var order = "";
				if ($('#scan_order').val() == "") {
					ohnoSound();
					$('#scan_order').focus();
					alert("Please scan/enter order first");
					scan_move.value = '';
					return false;
				} else {
					order = $('#scan_order').val();
				}


				var pd = {
					action: "move",
					data: {
						order: order,
						serial: $el.val()
					}
				};

				$.ajax({
					url: "/dispatchajax/sold",
					data: pd,
					type: 'POST',
					error: function(a) {
						var err = JSON.parse(a.responseJSON).error;
						failSound();

						scan_move.value = '';
						new_toast("danger", "Error! " + err);
						window.ETable.fnDraw();
					},
					success: function(a) {
						a = JSON.parse(a);
						if (a.success) {
							successSound();

							scan_move.value = '';
							scan_order.value = '';
							$('#scan_order').focus();
							new_toast("success", "Item Shipped");
						} else
							new_toast("danger", "Error! " + a.error);
						window.ETable.fnDraw();
					}
				});
				return false;
			}

		});

	});
</script>