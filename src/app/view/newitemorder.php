<?php
if (isset($id)) {
    $id = intval($id);
}
if (!isset($in)) {
    $in = false;
}

$db = M::db();
include PATH_CONFIG . "/constants.php";
function random_string($n = 10)
{
    $characters =
        "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $randomString = "";

    for ($i = 0; $i < $n; $i++) {
        $index = rand(0, strlen($characters) - 1);
        $randomString .= $characters[$index];
    }

    return $randomString;
}

$PAGE_TOKEN = random_string();
$_SESSION["order_token"] = $PAGE_TOKEN;

$PAGE_TOKEN2 = random_string();
$_SESSION["order_token2"] = $PAGE_TOKEN2;
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
							$ord = $db->get("nwp_orders", "*", ["nor_id" => $id]);
						} ?>
						<?php if ($in === false) { ?>
						<div class="mb-3">
							<form id="formdata">
								<div class="row">
									<?php if (isset($ord)) { ?>
									<div class="col-auto">
										<label class="mr-sm-2">State</label>
										<h3 style="padding:0.3rem 0"><?php if (isset($ord)) {
											  echo $ord["nor_state"];
										  } ?></h3>
									</div>
									<?php } ?>
									<div class="col-auto">
										<label class="form-label required">Order Date</label>
										<input type="text" required class="form-control" value="<?php if (isset($ord)) {
											  echo $ord["nor_date"];
										  } ?>" aria-describedby="Order Date" id="nor_date" />
									</div>
									<div class="col-auto">
										<label class="form-label required">Reference</label>
										<input type="text" required class="form-control" value="<?php if (isset($ord)) {
											  echo $ord["nor_reference"];
										  } ?>" aria-describedby="Reference" id="nor_reference" />
									</div>
									<div class="col-auto">
										<label class="form-label required">Supplier</label>
										<select class="form-control" aria-describedby="Supplier" name="nor_supplier" id="nor_supplier" required>
											<option value=""> Please Select </option>
											<?php
											   $spp = $db
												   ->query(
													   "select sp_id,sp_name from suppliers where sp_del=0 order by sp_name asc"
												   )
												   ->fetchAll();
											   foreach ($spp as $k => $v) {
												   $f =
													   isset($ord) && $v["sp_id"] == $ord["nor_supplier"]
														   ? "selected"
														   : "";
												   echo "<option $f value=" .
													   $v["sp_id"] .
													   ">" .
													   $v["sp_name"] .
													   "</option>";
											   }
											   ?>
										</select>
									</div>
								</div>
							</form>
						</div>
					</fieldset>

					<div style="width:90%; text-align:left;margin:0 auto;">
						<h3 style="margin-bottom:0;margin-top:2rem;";>Dell Products</h3>
						<span class="text-muted">Use this method for creating orders for Dell products ONLY. After setting the date/reference,supplier above, start scanning the serial number of the items. The part numbers will be automatically recognised and assigned to the correct products if they already exist in the database.
						<br /><br />When you have finished, click on the "Create" button at the bottom to finalise the order and have the products created in the Orange state.</span>
					</div>
					<fieldset class="form-fieldset" style="width:90%; margin:20px auto;">
						<?php if (
							  !isset($ord) ||
							  (isset($ord) && $ord["nor_state"] !== "Completed")
						) { ?>
						<div class="row">
							<div class="col-md-12">
								<form class="row g-3">
									<div class="col-auto" style="margin-left:auto;margin-right:auto;">
										<label for="inputPassword2" style="text-align:center;" class="form-label">Scan Serial Number</label>
										<input placeholder="Dell Products Only" style="text-align:center;" type="text" autocomplete="off" class="form-control" name="scan_serial" id="scan_serial" />
									</div>
								</form>
							</div>
						</div>

						<?php } ?>
						<div class="row mt-3">
							<div class="col-md-12">
								<div class="card">
									<div class="card-body">
										<div class="table-responsive">
											<table class='table table-vcenter card-table psr-table'>
												<thead>
													<tr>
														<th>P/N</th>
														<th>Quantity</th>
														<th>Description</th>
														<th>Serial</th>
														<th>Remove</th>
												</thead>
												<tbody>
													<?php if (isset($ord)) {
														 $query = "SELECT GROUP_CONCAT(nop_sn,'') gc,GROUP_CONCAT(nop_sn,',') gcl, SUBSTR(nop_sn,4,5) as pn, npr_name, npr_sku, sum(nop_quantity) qa FROM nwp_orderprod left join nwp_products on nop_product=npr_id WHERE nop_sn!='' AND nop_order=$id group by SUBSTR(nop_sn,4,5) ,npr_name, npr_sku;";
														 $data = $db->query($query)->fetchAll();
														 foreach ($data as $k => $v) {
															 echo "<tr data-serial='" .
																 $v["gc"] .
																 "' class='" .
																 $v["pn"] .
																 "'><td>" .
																 $v["pn"] .
																 "</td><td>" .
																 $v["qa"] .
																 "</td><td key='" .
																 $v["pn"] .
																 "'>" .
																 ($v["apr_name"] == null
																	 ? '<a href="javascript:void(0)" onclick="assign_new(this)">No Product Assigned – Assign</a>'
																	 : $v["npr_sku"] . " - " . $v["npr_name"]) .
																 "</td>";
															 echo "<td>";

															 $sn = explode(",", $v["gc"]);
															 foreach ($sn as $vn) {
																 echo "<div>" .
																	 $vn .
																	 (isset($ord) && $ord["nor_state"] !== "Completed"
																		 ? "<a href=# onclick='trash(event,\"$vn\",164)'>🗑️</a>"
																		 : "") .
																	 "</div>";
															 }

															 echo "</td>";
															 if ($ord["nor_state"] == "??") {
																 echo "<td><button class='btn btn-sm btn-primary' type='button'>Remove</button></td>";
															 } elseif (
																 $in === false &&
																 $ord["nor_state"] != "Completed"
															 ) {
																 echo "<td></td>";
															 }

															 if ($in === true) {
																 $inputed = $db->count("nwp_stock", [
																	 "nst_product" => $v["npr_id"],
																	 "nst_order" => $id,
																 ]);
																 $inputhtml = "";
																 if ($v["nop_delivered"] > 0) {
																	 $input_array = $db
																		 ->query(
																			 "SELECT * FROM nwp_stock WHERE nst_product=" .
																				 $v["npr_id"] .
																				 " AND nst_order=" .
																				 $id
																		 )
																		 ->fetchAll();
																	 foreach ($input_array as $ki => $vi) {
																		 $inputhtml .=
																			 "<span>" .
																			 $vi["ast_servicetag"] .
																			 "</span>";
																	 }
																 }

																 $filled = $inputed == $v["nop_quantity"];

																 echo "<td name='counter' opr='" .
																	 $v["npr_id"] .
																	 "'>" .
																	 $inputed .
																	 "</td>";

																 echo "<td>" .
																	 ($filled
																		 ? ""
																		 : "<input class='form-control form-control-sm' type='text' onkeyup='StockKeyUp(event," .
																			 $v["npr_id"] .
																			 ")' name='service_tag' opr='" .
																			 $v["npr_id"] .
																			 "' />") .
																	 "</td>";
																 echo "<td name='inpt' opr='" .
																	 $v["npr_id"] .
																	 "'>$inputhtml</td>";
															 }
															 if ($ord["nor_state"] == "Completed") {
																 echo "<td>";

																 $gcl = explode(",", $v["gcl"]);
																 foreach ($gcl as $gclitem) {
																	 if ($gclitem == "") {
																		 continue;
																	 }

																	 $inputed = $db
																		 ->query(
																			 "SELECT nst_id,nst_status FROM nwp_stock WHERE nst_servicetag='" .
																				 $gclitem .
																				 "' AND nst_order=" .
																				 $id
																		 )
																		 ->fetchAll();

																	 if ($inputed != null) {
																		 foreach ($inputed as $it) {
																			 //Serial: ".$gclitem."
																			 echo "<div><a href='../itemstock/" .
																				 $it["ast_id"] .
																				 "'>" .
																				 $it["nst_id"] .
																				 "</a> - " .
																				 $_ACSTATUSES[$it["nst_status"]][
																					 "Name"
																				 ] .
																				 " </div>";
																		 }
																	 } else {
																		 echo "<div class='bg-danger'> Stock data not found for " .
																			 $v["npr_id"] .
																			 " </div>";
																	 }
																 }

																 echo "</td>";
															 }

															 echo "</tr>";
														 }
													 } ?>


												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>

					</fieldset>

					<?php if (
					!isset($ord) ||
					(isset($ord) && $ord["nor_state"] !== "Completed")
					) { ?>
					<div style="width:90%; text-align:left;margin:0 auto;">
						<h3 style="margin-bottom:0;margin-top:2rem;";>Non-Dell Products</h3>
						<span class="text-muted">Use this method for creating orders for non-Dell products. Add the products and quantities, then click on the "Create" button at the bottom to create the order. Serial numbers can be entered after creating the order.</span>
					</div>
					<fieldset class="form-fieldset" style="width:90%; margin:20px auto;">	
					<div class="row g-3">
						<div class="mb-3">
							<form class="row g-3">
								<div class="col-auto" style="width:70%">
									<label for="inputPassword2" class="form-label">SKU </label>

									<input list="skus" id="sku"  class="form-control" placeholder="Start typing SKU or product name..." autocomplete="off">
									<input type="hidden" name="sku" id="sku-hidden"  class="form-control">
									<datalist id=skus  >
										<?php
										$spp = $db
										  ->query(
											  "SELECT  npr_id, npr_sku, npr_name, npr_condition FROM nwp_products where npr_del=0 order by npr_sku asc"
										  )
										  ->fetchAll();
										foreach ($spp as $k => $v) {
										  echo "<option $f data-value=" .
											  $v["npr_id"] .
											  ">" .
											  $v["npr_sku"] .
											  " - " .
											  $v["npr_name"] .
											  " (" .
											  $v["npr_condition"] .
											  ")" .
											  "</option>";
										}
										?>
									</datalist>
								</div>
								<div class="col-auto" style="width:20%">
									<label for="inputPassword2" class="form-label">Quantity</label>
									<input type="number" class="form-control" id="quantity" placeholder="Quantity Ordered">
								</div>
								<div class="col-auto mt-4">
									<button type="button" onClick="addNewProduct()" class="btn btn-primary" style="margin-top:10px;">Add</button>
								</div>
							</form>
						</div>
					</div>
	
			<?php } ?>
			<?php } ?>
						<div class="row mt-3">
							<div class="col-md-12">
								<div class="card">
									<div class="card-body">
										<div class="table-responsive">
										<table class='table table-vcenter card-table pr-table'>
									<thead>
										<tr>
											<th>SKU</th>

											<th>Description</th>
											<th>Condition</th>
											<th>Qty</th>
											<?php if ($in === false) {?>
											<th>Arrived</th>
											<th>Awaiting</th>
											<?php }?>
											<?php if ($in === false && isset($ord) && $ord["nor_state"] != "Completed") { ?>
											<th>Remove</th>
											<?php } ?>
											<?php if ($in === true) { ?>
											<th>Inputed</th>
											<th>Serial</th>
											<th>Inputed</th>
											<?php } ?>
											<?php if (isset($ord) && $ord["nor_state"] == "Completed") { ?>
											<th></th>
											<?php } ?>
										</tr>
									</thead>
									<tbody>
										<?php if (isset($ord)) {
											  $query = "SELECT npr_id, npr_name, npr_condition, npr_sku, nop_quantity, nop_delivered FROM nwp_orderprod left join nwp_products on nop_product=npr_id WHERE nop_sn='' AND nop_order=$id";
											  $data = $db->query($query)->fetchAll();
											  foreach ($data as $k => $v) {
												  echo "<tr><td>" .
$v["npr_sku"] ."</td><td>".
$v["npr_name"] . "</td><td>" .
$v["npr_condition"] . "</td><td>" .
													  $v["nop_quantity"] .
													  "</td>";
												  if ($in === false) {
													  echo "<td>".$v["nop_delivered"]."</td>";
													  if ($v["nop_delivered"] < $v["nop_quantity"]) {
													  	echo "<td style='color:red'>".($v["nop_quantity"] - $v["nop_delivered"])."</td>";
													  } else {
													  	echo "<td style='color:green'>".($v["nop_quantity"] - $v["nop_delivered"])."</td>";
													  }
												  }
												  if ($ord["nor_state"] == "??") {
													  echo "<td><button class='btn btn-sm btn-primary' type='button'>Remove</button></td>";
												  } elseif ($in === false && $ord["nor_state"] != "Completed") {
													  echo "<td></td>";
												  }

												  if ($in === true) {
													  $inputed = $db->count("nwp_stock", [
														  "nst_product" => $v["npr_id"],
														  "nst_order" => $id,
													  ]);
													  $inputhtml = "";
													  if ($v["nop_delivered"] > 0) {
														  $input_array = $db
															  ->query(
																  "SELECT * FROM nwp_stock WHERE nst_product=" .
																	  $v["npr_id"] .
																	  " AND nst_order=" .
																	  $id
															  )
															  ->fetchAll();
														  foreach ($input_array as $ki => $vi) {
															  $inputhtml .=
																  "<span>" .
																  $vi["nst_servicetag"] .
																  "</span><br/>";
														  }
													  }

													  $filled = $inputed == $v["nop_quantity"];

													  echo "<td name='counter' opr='" .
														  $v["npr_id"] .
														  "'>" .
														  $inputed .
														  "</td>";

													  echo "<td>" .
														  ($filled
															  ? ""
															  : "<input class='form-control form-control-sm' type='text' onkeyup='StockKeyUp(event," .
																  $v["npr_id"] .
																  ")' name='service_tag' opr='" .
																  $v["npr_id"] .
																  "' />") .
														  "</td>";
													  echo "<td name='inpt' opr='" .
														  $v["npr_id"] .
														  "'>$inputhtml</td>";
												  }
												  if ($ord["nor_state"] == "Completed") {
													  echo "<td>";

													  if ($v["npr_id"] != null) {
														  $inputed = $db
															  ->query(
																  "SELECT * FROM nwp_stock WHERE nst_product=" .
																	  $v["npr_id"] .
																	  " AND nst_order=" .
																	  $id
															  )
															  ->fetchAll();

														  if ($inputed != null) {
															  foreach ($inputed as $it) {
																  echo "<div><a href='../itemstock/" .
																	  $it["nst_id"] .
																	  "'>" .
																	  $it["nst_id"] .
																	  "</a>  - " .
																	  $_ACSTATUSES[$it["nst_status"]]["Name"] .
																	  "</div>";
															  }
														  } else {
															  echo "<div class='bg-danger'> Stock data not found for " .
																  $v["apr_id"] .
																  " </div>";
														  }
													  } else {
														  echo "<div class='bg-danger'> Product data not found for id " .
															  $v["apr_id"] .
															  " </div>";
													  }
													  echo "</td>";
												  }

												  echo "</tr>";
											  }
										  } ?>
									</tbody>
									<tfoot>
										<?php 
										if ($in === false) {
											$totQty = 0;
											$totArrived = 0;
											foreach ($data as $v) {
												$totQty += $v["nop_quantity"];
												$totArrived += $v["nop_delivered"];
											}
											$color = "red";
											if ($totQty == $totArrived) {
												$color = "green";
											}
											echo "<td colspan='3' style='font-weight:600; text-align:right; font-size: 14px;'>Total:  </td><td style='font-weight:600; font-size: 14px; text-align:left;'>".$totQty."</td><td style='font-weight:600; font-size: 14px; text-align:left;'>".$totArrived."</td><td style='font-weight:600; font-size: 14px; text-align:left; color:".$color.";'>".($totQty - $totArrived)."</td><td></td>";
										}
										?>
									</tfoot>
								</table>
							</div>
				</div>
			</div>
			</div>
			</div>
			<div class="col-auto" style='margin-top:20px'>
				<?php if (isset($ord) && $in == false && $ord["nor_state"] !== "Completed") { ?>


				<button class='btn btn-primary' onClick="UpdateOrder()" type='button'>Update</button>
				<button class='btn btn-warning' onClick="StockIn()" id="stockinbut" type='button'>Stock In</button>
				<button class='btn btn-success' onClick="CompleteOrder()" id="completeorderbut" type='button'>Complete Order</button>
				<button class='btn btn-danger' onclick="DeleteOrder()" type='button'>Delete</button>

				<?php } ?>

				<?php if ($in == true) {
        echo "<button class='btn btn-warning' onClick=\"StockInPost()\" type='button'>Stock In Post</button>";
    } ?>
			</div>
			<?php if (!isset($ord)) { ?>
			<div class="col-auto" style='margin-top:20px'>
				<button class='btn btn-primary' onClick="CreateOrder()" type='button'>Create</button>
				<?php } ?>

			</div>
		</div>
	</div>		
</div>		
		</fieldset>


</div>
</div>







<div class="modal fade" id="mapmodel" tabindex="-1" role="dialog" aria-labelledby="mapmodel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="mapmodelLabel">New Product Map</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form>
					<div class="form-group">
						<label for="product-name" id="productname" class="col-form-label">Product:</label>
						<select id=skumap name=skumap class="form-control">
							<?php
       $spp = $db
           ->query(
               "SELECT  npr_id, npr_sku, npr_name FROM nwp_products where npr_del=0 order by npr_sku,npr_name asc"
           )
           ->fetchAll();
       foreach ($spp as $k => $v) {
           echo "<option $f value=" .
               $v["npr_id"] .
               ">" .
               $v["npr_sku"] .
               " - " .
               $v["npr_name"] .
               "</option>";
       }
       ?>
						</select>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" onclick="dismiss()" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary" onclick="assign_new_map()">Map</button>
			</div>
		</div>
	</div>
</div>


<script>
	var products = [];
	var token = "<?php echo $_SESSION["order_token"]; ?>";
	var token2 = "<?php echo $_SESSION["order_token2"]; ?>";

	<?php
 $data = $db->query(
     "select npr_id,npr_name from nwp_products where npr_del=0 order by npr_name asc"
 );
 foreach ($data as $k => $v) {
     echo "products.push({ id: " .
         $v["npr_id"] .
         ",  name:'" .
         str_replace(array("\r", "\n"), '', addslashes($v["npr_name"])) .

         "' });\r\n";
 }
 ?>


	function dismiss() {
		$('#mapmodel').modal("hide");
		$('#skumap')[0].selectedIndex = 0;
	}

	function assign_new_map() {
		var v = $('#skumap').val();
		var vm = $('#mapmodel').data("pn");

		var id = <?php echo isset($id) ? $id : 0; ?>;
		var pd = {
			action: "maproduct",
			data: {
				mappedv: v,
				mappedvm: vm,
				id: id
			}
		};
		$.ajax({
			url: "/itemordersajax",
			data: pd,
			type: 'POST',
			success: function(a) {
				a = JSON.parse(a);
				if (a.success) {
					new_toast("success", "Success7.");
					$('#mapmodel').modal("hide");
					$('#skumap')[0].selectedIndex = 0;
					$('#mapmodel').data("el").parent().html(a.description);
				} else{
failSound();

					new_toast("danger", "Error2! Reason is " + a.error);
}
			}
		});



	}

	function assign_new(el) {
		var el = $(el);
		var pn = el.parent().parent().find("td")[0].innerText;
		$('#mapmodel').data("pn", pn);
		$('#mapmodel').data("el", el);
		$('#mapmodel').modal("show");
	}

	function CreateOrder() {
		if (!$('#formdata').valid())
			return false;


		function cb_post() {
			var id = 0;
			var pd = {
				action: "putorderitems",
				data: {
					reference: $('#nor_reference').val(),
					date: $('#nor_date').val(),
					supplier: $('#nor_supplier').val(),
					token: token,
					token2: token2,
					id: id
				}
			};

			$.ajax({
				url: "/itemordersajax",
				data: pd,
				type: 'POST',
				error: function(a) {
					a = JSON.parse(a.responseJSON);
					if (!a.success) {
failSound();

						new_toast("danger", "Error3! Reason is " + a.error);
					}
				},
				success: function(a) {
					a = JSON.parse(a);
					if (a.success) {
						new_toast("success", "Success6.");
						location.href = "/itemorder/" + a.id;
					} else{
failSound();

						new_toast("danger", "Error4! Reason is " + a.error);
}				
}
			});
		}

		cb_post();


	}

	function UpdateOrder() {

		var id = <?php echo isset($id) ? $id : 0; ?>;
		var pd = {
			action: "putorderitems",
			data: {
				date: $('#nor_date').val(),
				reference: $('#nor_reference').val(),
				supplier: $('#nor_supplier').val(),
				token2: token2,
				token: token,
				id: id
			}
		};
		$.ajax({
			url: "/itemordersajax",
			data: pd,
			type: 'POST',
			success: function(a) {
				a = JSON.parse(a);
				if (a.success) {
					new_toast("success", "Success5.");

					$.post("/itemordersajax", {
							"action": "getorderitems",
							data: {
								token: token,
								id: id
							}
						})
						.then(b => {
							$('.pr-table>tbody').html(b);
						});
				} else {
failSound();
					new_toast("danger", "Error5! Reason is " + a.error);}
			}
		});

	}

	function DeleteOrder() {
		if (!confirm("Do you really want to delete order?")) return false;
		var id = <?php echo isset($id) ? $id : 0; ?>;
		var pd = {
			action: "deleteOrder",
			data: {
				token: token,
				id: id
			}
		};
		$.ajax({
			url: "/itemordersajax",
			data: pd,
			type: 'POST',
			success: function(a) {
				a = JSON.parse(a);
				if (a.success) {
					new_toast("success", "Success4.");
					location.href = "/itemorders";
				} else
					new_toast("danger", "Error6! Reason is " + a.error);
			}
		});
	}

	function CompleteOrder() {

		if ($('.psr-table tbody tr').length > 0) {
			var id = <?php echo isset($id) ? $id : 0; ?>;
			var pd = {
				action: "completeorder",
				data: {
					id: id
				}
			};
			SendAction(pd, id, function(a, b, c) {
				console.log(a, b, c);
				window.location.reload();
			});

		} else {
			new_toast("danger", "There is no input!");
		}
	}

	function StockIn() {


		if ($('.pr-table tbody tr').length > 0) {
			var id = <?php echo isset($id) ? $id : 0; ?>;
			location.href = "../itemorderin/" + id;
		} else {
			new_toast("danger", "There is no input!");
		}


	}

	function RemoveItem(pr) {
		var id = <?php echo isset($id) ? $id : 0; ?>;
		var pd = {
			action: "removeproduct",
			data: {
				token: token,
				pr: pr,
				id: id
			}
		};
		$.ajax({
			url: "/itemordersajax",
			data: pd,
			type: 'POST',
			success: function(a) {
				a = JSON.parse(a);
				if (a.success) {
					successSound();
					new_toast("success", "Item successfully removed from order.");

					$.post("/itemordersajax", {
							"action": "getorderitems",
							data: {
								token: token,
								id: id
							}
						})
						.then(b => {
							$('.pr-table>tbody').html(b);
						});
				} else
					new_toast("danger", "Error9! Reason is " + a.error);
			}
		});

	}


	$(function() {


		//$('#completeorderbut').hide();




		$('#scan_serial').keydown(function(event) {

			if (event.keyCode == 13) {
				event.preventDefault();


				if ($('.pr-table tbody tr').length > 0) {
failSound();
scan_serial.value = '';
					new_toast("warning", "Either use add item or scan barcode!");
					return false;
				}



				var key = event.target.value.trim();
				var keyl = event.target.value.trim();
				if ((key.length < 20) || (key.length > 30)) {
failSound();
					new_toast("danger", "Serial Number Format Seems Invalid");
scan_serial.value = '';
					return false;

				}

				var tbody = $('.psr-table').find("tbody");
				key = key.substring(3, 8);


				//lookup product link
				var id = <?php echo isset($id) ? $id : 0; ?>;
				SendAction({
					action: "checkproduct",
					data: {
						token2: token2,
						id: id,
						serial: keyl.trim()
					}
				}, id, function(rpd, rid, rsp) {
					event.target.value = "";
					if (rsp.success) {
						if (typeof $('tr.' + key).data("serial") == "string")
							$('tr.' + key).data("serial", $('tr.' + key).data("serial").split(','))

						if (tbody.find("tr." + key).length == 1) {
							if ($('tr.' + key).data("serial").indexOf(keyl.trim()) == -1) {
								$('tr.' + key).find("td")[1].innerHTML = parseInt($('tr.' + key).find("td")[1].innerHTML) + 1;

								var snx = "<div>" + keyl.trim() + '<a href=# onclick="trash(event,\'' + keyl.trim() + '\',650)">🗑️</a></div>';

								$(snx).appendTo($('tr.' + key).find("td")[3]);

								$('tr.' + key).data("serial").push(keyl.trim());
							} else {
								new_toast("danger", "Serial " + keyl.trim() + " must be unique !");
							}
						} else {
							var snx = "<div>" + keyl.trim() + '<a href=# onclick="trash(event,\'' + keyl.trim() + '\',661)">🗑️</a></div>';
							$('<tr class="' + key + '"><td>' + key + '</td><td>1</td><td key="' + key + '">' + rsp.description + '</td><td>' + snx + '</td><td></td></tr>').appendTo(tbody);
							$('tr.' + key).data("serial", []);
							$('tr.' + key).data("serial").push(keyl.trim());
						}
					} 
				});

				return false;
			}

		});
	});

	function addNewProductInput(event) {

		if (event.which == 13) {
			$el = $(el.target);
			val = $el.val();
			if (val.length < 20) {
				new_toast("Error", "Minimum length at least 20");
				return false;
			}

			return false;
		}
		return false;

	}

	function trash(event, a) {

		console.log("deleting", a);
		var key = a.substring(3, 8);
		console.log("key", key);
		var id = <?php echo isset($id) ? $id : 0; ?>;
		SendAction({
			action: "delproduct",
			data: {
				token2: token2,
				id: id,
				serial: a.trim()
			}
		}, id, function(rpd, rid, rsp) {
			if (rsp.success) {

				var getl = $('tr.' + key).data("serial");

				if (typeof getl == "string") getl = getl.split(",");

				var cx1 = getl.filter(function(b) {
					return b !== a
				});
				$('tr.' + key).data("serial", cx1);
				//new_toast("success", "Removed");

				if (cx1.length == 0)
					$(event.target).parent().parent().parent().remove();
				else {
					$('tr.' + key).find("td")[1].innerHTML = cx1.length;
					$(event.target).parent().remove();

				}

			} else {
				new_toast("danger", rsp.error);
			}
		});




	}


	function addNewProduct() {
		var pn = $('[name="sku"]').val();
		var qa = $('#quantity').val();
		var id = <?php echo isset($id) ? $id : 0; ?>;

		if(qa=="" || qa<=0) {
failSound()
                new_toast("warning","Please enter a valid quantity!");
setTimeout(() => {quantity.value = ''; }, 500);
                return false;
            }

		if ($('.psr-table tbody tr').length > 0) {

			new_toast("warning", "Either scan board or use this!");
			return false;
		}

		var pd = {
			action: "addproduct",
			data: {
				token: token,
				pn: pn,
				qa: qa,
				id: id
			}
		};
		$.ajax({
			url: "/itemordersajax",
			data: pd,
			type: 'POST',
			success: function(a) {
				a = JSON.parse(a);
				if (a.success) {
					successSound()
					new_toast("success", "Item has been added to the order.");
setTimeout(() => { sku.value = '';quantity.value = ''; }, 500);
					
					$.post("/itemordersajax", {
							"action": "getorderitems",
							data: {
								token: token,
								id: id
							}
						})
						.then(b => {
							$('.pr-table>tbody').html(b);
						});
				} else
					new_toast("danger", "ErrorA! Reason is " + a.error);
			}
		});

	}


	function trashin(pr_id,serial) {

		var id = <?php echo isset($id) ? $id : 0; ?>;
		var pd = {
				action: "orderinrem",
				data: {
					serial: serial,
					pr_id: pr_id,
					id: id,

					token: token
				}
			};
			SendAction(pd, pr_id, function(pd, pr_id, r) {
				if(r.success) {

					pd.action = "orderin";
					var ap = $('[name="inpt"][opr=' + pr_id + ']');
					var cc = $('[name="counter"][opr=' + pr_id + ']');

					ap.empty();
					SendAction(pd, pr_id, function(c, b, a) {
						cc.html(a.count);
						var tary = (a.remain == 0);
						$('[name="service_tag"][opr="' + pr_id + '"]').attr("enabled", !tary).attr("disabled", tary);



						for (var i = 0; i < a.data.length; i++) {
							$('<div>' + a.data[i]["serial"] + ' <a href="javascript:void(0)" onclick="trashin(' + pr_id + ', \''+a.data[i]["serial"]+'\')">🗑️</a> </div>').appendTo(ap);
						}

					})

				}
			});

	}

	function StockKeyUp(ev, pr_id) {
		console.log("Event here ", ev, pr_id);
		if (ev.code == "Enter") {
			var id = <?php echo isset($id) ? $id : 0; ?>;

			if(ev.target.value.length<5) {
				new_toast("warning","Please set serial number!");
				return;
			}

			var pd = {
				action: "orderinadd",
				data: {
					serial: ev.target.value,
					pr_id: pr_id,
					id: id,

					token: token
				}
			};


			$('[name="service_tag"][opr=' + pr_id + ']').val("");


			SendAction(pd, pr_id, function(pd, pr_id, r) {
				
				console.log(pd, pr_id,r);
				
				
				if(r.success==false) {
					
					new_toast("warning",r.error);
					return;
				}
				
				pd.action = "orderin";
				var ap = $('[name="inpt"][opr=' + pr_id + ']');
				var cc = $('[name="counter"][opr=' + pr_id + ']');

				ap.empty();
				SendAction(pd, pr_id, function(c, b, a) {
					cc.html(a.count);
					var tary = (a.remain == 0);
					$('[name="service_tag"][opr="' + pr_id + '"]').attr("enabled", !tary).attr("disabled", tary);



					for (var i = 0; i < a.data.length; i++) {
						$('<div>' + a.data[i]["serial"] + ' <a href="javascript:void(0)" onclick="trashin(' + pr_id + ', \''+a.data[i]["serial"]+'\')">🗑️</a> </div>').appendTo(ap);
					}

				})

			});
		}
	}


	function StockInPost() {
		var id = <?php echo isset($id) ? $id : 0; ?>;
		var pd = {
			action: "orderinpost",
			data: {
				id: id,
				token: token
			}
		};
		SendAction(pd, id);
	}

	function SendAction(pd, pr_id, cb) {
		$.ajax({
			url: "/itemordersajax/" + pr_id,
			data: pd,

			type: 'POST',
			success: function(a) {
				if (typeof(a) == "string")
					a = JSON.parse(a);
				if (a.success) {
successSound();
					if(pd.action && pd.action=="orderinadd")
						new_toast("success", "Item successfully added.");
				} else {
failSound();
					new_toast("danger", "Error - " + a.error);
				}
				if (cb)
					cb(pd, pr_id, a);
			},
			error: function(a) {
				a = JSON.parse(a.responseJSON);
				if (cb)
					cb(pd, pr_id, a);
			}
		});
	}

	function filterProducts() {
		var cats = $('#categories').val();
		var mans = $('#manufacturers').val();
		$('#products').val("");
		$('#products > option').remove();
		var nw = products.filter(function(e, i) {
			return (cats == "" && mans == "") ||
				(((mans != "" && e.man == mans) || mans == "") && ((cats != "" && e.cat == cats) || cats == ""))
		});
		var at = "";
		nw.forEach(function(e) {
			at += ("<option value=" + e.id + ">" + e.name + "</option>");
		});
		$('#products').append(at);
	}

	$(function() {
		if ($("#nor_date").length > 0) {
			new Litepicker({
				element: document.getElementById('nor_date'),
				format: 'DD/MM/YYYY'
			});
		}



		if ($('.psr-table tbody tr').length > 0) {
			$('#completeorderbut').css("display", "inline-flex");
			$('#stockinbut').css("display", "none");
		}
		if ($('.pr-table tbody tr').length > 0) {
			$('#completeorderbut').css("display", "none");
			$('#stockinbut').css("display", "inline-flex");
		}



		document.title = "Order # <?php if (isset($id)) {
      echo $id;
  } ?> " + document.title;

		$.validator.addMethod("valueNotEquals", function(value, element, arg) {
			return arg !== value;
		}, "Value must not equal arg.");

		// configure your validation
		$("#formdata").validate({
			rules: {
				or_supplier: {
					valueNotEquals: ""
				}
			},
			messages: {
				or_supplier: {
					valueNotEquals: "Please select an item!"
				}
			}
		});

	});

	$(function(){
		if(document.querySelector('input[list]')!= null) {
			document.querySelector('input[list]').addEventListener('input', function(e) {
				var input = e.target,
					list = input.getAttribute('list'),
					options = document.querySelectorAll('#' + list + ' option'),
					hiddenInput = document.getElementById(input.getAttribute('id') + '-hidden'),
					inputValue = input.value;

				hiddenInput.value = inputValue;

				for(var i = 0; i < options.length; i++) {
					var option = options[i];

					if(option.innerText === inputValue) {
						hiddenInput.value = option.getAttribute('data-value');
						break;
					}
				}
			});
		}
	});
</script>