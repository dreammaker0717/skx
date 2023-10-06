<div class="page-body">
	<div class="container-fluid">

		<div class="col-lg-9" style="margin:0 auto;">
			<div class="card card-lg">

				<div class="card-body">

					<h2>Print Barcode Labels for Accessories</h2>
<div class="text-muted" style="margin-bottom:50px;">Use this form to print box labels for accessories based off the serial number.<br />Please ensure items are in the correct states before printing labels.</div>





					<form id="form1" style="margin-bottom:300px; " autocomplete="off">
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

				</div>
			</div>
		</div>
	</div>
</div>



<script>
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
					action: "printLabel",
					data: {
						serial: $el.val()
					}
				};

				$.ajax({
					url: "/labelajax/printLabel",
					data: pd,
					type: 'POST',
					error: function(a) {
						var err = JSON.parse(a.responseJSON).error;
						failSound();
						new_toast("danger", "Error - " + err);
						scan_move.value = '';

					},
					success: function(a) {

						if (a.success) {

							if (
								(a.data[0]["ast_status"] == 6 && a.data[0]["apr_condition"] == "Refurbished (Grade B)") ||
								(a.data[0]["ast_status"] == 22 && a.data[0]["apr_condition"] == "Refurbished") ||
								(a.data[0]["ast_status"] == 7 && (a.data[0]["apr_condition"] == "New" || a.data[0]["apr_condition"] == "New - Open Box" || a.data[0]["apr_condition"] == "Brown - Open Box"))) {
								successSound();
								new_toast("success", a.data[0]["apr_condition"] + " Item Label Created");

								$('#TheForm').attr("action", "/accstocksprintajax/accprint-box-labels");
								$('#stock_items').val(a.data[0]["ast_id"]);
								$('#t').val(a.data[0]["tar"]);
								window.open('', 'TheWindow');
								document.getElementById('TheForm').submit();

							} else if (
								a.data[0]["ast_status"] == 6 ||
								a.data[0]["ast_status"] == 7 ||
								a.data[0]["ast_status"] == 22) {
								ohnoSound();


								alert("The SKU and the condition of the item do not match. Please change the SKU or change the status of the product before printing label");
								scan_move.value = '';
								return false;

							} else {
								successSound();
								new_toast("cyan", "Faulty Item Label Created");
								$('#TheForm').attr("action", "/accstocksprintajax/accprint-z-box-labels");
								$('#stock_items').val(a.data[0]["ast_id"]);
								$('#t').val(a.data[0]["tar"]);
								window.open('', 'TheWindow');
								document.getElementById('TheForm').submit();
							}
							scan_move.value = '';

						} else
							<!--	failSound(); -->
							<!-- new_toast("danger", "Error! Reason is " + a.error); -->
							scan_move.value = '';

					}
				});




				return false;
			}

		});

	});
</script>

<form id="TheForm" method="post" action="test.asp" target="TheWindow">
	<input type="hidden" name="stock_items" id="stock_items" value="" />
	<input type="hidden" name="t" id="t" value="" />
</form>