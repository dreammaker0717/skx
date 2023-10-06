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
          echo "<h2>Create New Laptop Order</h2>";
          echo '<div class="text-muted">
Create a new order by selecting date, supplier and CSV file and clicking on "Create" button below. <br />The format of the CSV must be "model,stag,price" with headers.
<br /><br />
or
<br /><br />
Create a new order by selecting date, supplier above and then adding the product and count below. Finalise the order by pressing the "Create" button.<br />Once the order has been created, use the "Stock In" function to enter individual serial numbers/service tags.<br />


</div> ';
      } ?>
					</div>
					
					
					
					<fieldset class="form-fieldset" style="width:90%; margin:20px auto;">



						<?php if (isset($id)) {
          $ord = $db->get("orders", "*", ["or_id" => $id]);
      } ?>


						<?php if ($in === false) { ?>



						<div class="mb-3">

									<form id="formdata">
										<div class="row">
											<?php if (isset($ord)) { ?>
											<div class="col-auto">
												<label class="mr-sm-2">State</label>
												<h3 style="padding:0.3rem 0"><?php if (isset($ord)) {
                echo $ord["or_state"];
            } ?></h3>
											</div>
											<?php } ?>
											<div class="col-auto">
												<label class="form-label required">Order Date</label>
												<input type="text" required class="form-control" value="<?php if (isset($ord)) {
                echo $ord["or_date"];
            } ?>" aria-describedby="Order Date" id="or_date" />
											</div>
											<div class="col-auto">
												<label class="form-label required">Supplier</label>
												<select class="form-control" aria-describedby="Supplier" name="or_supplier" id="or_supplier" required>
													<option value=""> Please Select </option>
													<?php
             $spp = $db
                 ->query(
                     "select sp_id,sp_name from suppliers where sp_del=0 order by sp_name asc"
                 )
                 ->fetchAll();
             foreach ($spp as $k => $v) {
                 $f =
                     isset($ord) && $v["sp_id"] == $ord["or_supplier"]
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
											<div class="col-auto">
												<label class="form-label required">VAT Type</label>
												<?php if (!isset($ord)){
													echo "<select class='form-control' id='vat_type'>";
													$vats = $db->query("SELECT * from vat_rates")->fetchAll();
													foreach ($vats as $vat) {
														echo "<option value='" . $vat['vat_id'] . "'>" . $vat['vat_label'] . "</option>";
													}
													echo "</select>";
												} else {
        											$vat = $db->query("SELECT * from vat_rates WHERE vat_type = '" . $ord['or_vat_type'] . "' AND vat_percent = " . $ord['or_vat_rate'])->fetchAll();
        											if ($vat) {
        												echo "<h3 style=\"padding:0.3rem 0\">" . $vat[0]['vat_label'] . "</h3>";
        											} else {
        												echo "<h3 style=\"padding:0.3rem 0\">Not Set</h3>";
        											}
												}
												?>
											</div>
											<?php if (!isset($ord)) { ?>
											<div class="col-auto">
												<label class="form-label required">Reference</label>
												<input type="text" required class="form-control" aria-describedby="Reference" id="or_reference" />
											</div>
											<?php } else { ?>
											<div class="col-auto">
												<label class="mr-sm-2">Reference</label>
												<h3 style="padding:0.3rem 0"><?php if (isset($ord)) {if ($ord["or_reference"] != null) echo $ord["or_reference"];} ?></h3>
											</div>
											<?php } ?>
											<?php if ($in === false && !isset($id)) { ?>


											<div class="col-auto">

												<label class="form-label">Upload Bulk CSV</label>
												<input type="file" class="form-control form-control-file" id="csv_input">

											</div>


											<?php } ?>


										</div>
									</form>




						</div>
					</fieldset>
<fieldset class="form-fieldset" style="width:90%; margin:20px auto;">

						<?php if (
          !isset($ord) ||
          (isset($ord) && $ord["or_state"] !== "Completed")
      ) { ?>


						<div class="mb-3">
									<h3>
										Add Item
									</h3>
									<form class="row g-3">
										<div class="col-auto">
											<label for="staticEmail2" class="form-label">Category</label>
											<select class='form-control' id="categories" onChange="filterProducts()">
												<option value=''>All</option>
												<?php dropDown(
                "categories",
                "ct_id",
                "ct_name",
                "ct_del=0",
                null,
                "ct_name"
            ); ?>
											</select>
										</div>
										<div class="col-auto">
											<label for="inputPassword2" class="form-label">Manufacturer</label>


											<select class='form-control' id="manufacturers" onChange="filterProducts()">
												<option value=''>All</option>
												<?php dropDown(
                "manufacturers",
                "mf_id",
                "mf_name",
                "mf_del=0",
                null,
                "mf_name"
            ); ?>
											</select>
										</div>
										<div class="col-auto">
											<label for="inputPassword2" class="form-label">Product</label>



<select class='form-control' id="products">
	<option value=""></option>				

	<?php
            $data = $db->query(
                "select pr_id,pr_name,pr_manufacturer,pr_category from products where pr_del=0 order by pr_name asc"
            );
            foreach ($data as $k => $v) {
                echo "<option value='" .
                    $v["pr_id"] .
                    "' man='" .
                    $v["pr_manufacturer"] .
                    "' cat='" .
                    $v["pr_category"] .
                    "' >" .
                    $v["pr_name"] .
                    "</option>";
            }
            ?>
</select>
										</div>
										<div class="col-auto">
											<label for="inputPassword2" class="form-label">Quantity</label>
											<input type="number" class="form-control" id="quantity" placeholder="">
										</div>
										<div class="col-auto mt-4">
											<button type="button" onClick="addNewProduct()" class="btn btn-primary" style="margin-top:10px;">Add</button>
										</div>
									</form>

						</div>


						<?php } ?>
						<?php } ?>
						<div class="col-md-12">
							<div class="card">
								<div class="card-body">
									<div class="table-responsive">
										<table class='table table-vcenter card-table pr-table'>
											<thead>
												<tr>
													<th>ID</th>
													<th>Name</th>
													<th>Product</th>
													<th>Manufacturer</th>
													<th>Category</th>
													<th>Quantity</th>
													<?php if ($in === false && isset($ord) && $ord["or_state"] != "Completed") { ?>
													<th>Remove</th>
													<?php } ?>
													<?php if ($in === true) { ?>
													<th>Inputed</th>
													<th>Cost Price</th>
													<th>Service Tag</th>
													<th>Inputed</th>
													<?php } ?>
													<?php if (isset($ord) && $ord["or_state"] == "Completed") { ?>
													<th></th>
													<?php } ?>
												</tr>
											</thead>
											<tbody>
												<?php if (isset($ord)) {
                $query = "SELECT pr_id,pr_name,pr_title,mf_name,ct_name, op_quantity,op_delivered FROM orderprod left join  products on op_product=pr_id left join manufacturers on  pr_manufacturer=mf_id left join categories on pr_category=ct_id WHERE op_order=$id";
                $data = $db->query($query)->fetchAll();
                foreach ($data as $k => $v) {
                    echo "<tr><td>" .
                        $v["pr_id"] .
                        "</td><td>" .
                        $v["pr_name"] .
                        "</td><td>" .
                        $v["pr_title"] .
                        "</td><td>" .
                        $v["mf_name"] .
                        "</td><td>" .
                        $v["ct_name"] .
                        "</td><td>" .
                        $v["op_quantity"] .
                        "</td>";
                    if ($ord["or_state"] == "??") {
                        echo "<td><button class='btn btn-sm btn-primary' type='button'>Remove</button></td>";
                    } elseif (
                        $in === false &&
                        $ord["or_state"] != "Completed"
                    ) {
                        echo "<td></td>";
                    }

                    if ($in === true) {
                        $inputed = $db->count("stock", [
                            "st_product" => $v["pr_id"],
                            "st_order" => $id,
                        ]);
                        $inputhtml = "";
                        if ($v["op_delivered"] > 0) {
                            $input_array = $db
                                ->query(
                                    "SELECT * FROM stock WHERE st_product=" .
                                        $v["pr_id"] .
                                        " AND st_order=" .
                                        $id
                                )
                                ->fetchAll();
                            foreach ($input_array as $ki => $vi) {
                                $inputhtml .=
                                    "<span>" .
                                    $vi["st_servicetag"] .
                                    " - " .
                                    $vi["st_cost"] .
                                    "</span>";
                            }
                        }

                        $filled = $inputed == $v["op_quantity"];

                        echo "<td name='counter' opr='" .
                            $v["pr_id"] .
                            "'>" .
                            $inputed .
                            "</td>";
                        echo "<td>" .
                            ($filled
                                ? ""
                                : "<input class='form-control form-control-sm' type='currency' size=6 name='cost_price' opr='" .
                                    $v["pr_id"] .
                                    "' /> ") .
                            "</td>";
                        echo "<td>" .
                            ($filled
                                ? ""
                                : "<input class='form-control form-control-sm' type='text' onkeyup='StockKeyUp(event," .
                                    $v["pr_id"] .
                                    ")' name='service_tag' opr='" .
                                    $v["pr_id"] .
                                    "' />") .
                            "</td>";
                        echo "<td name='inpt' opr='" .
                            $v["pr_id"] .
                            "'>$inputhtml</td>";
                    }
                    if ($ord["or_state"] == "Completed") {
                        echo "<td>";

                        if ($v["pr_id"] != null) {
                            $inputed = $db
                                ->query(
                                    "SELECT * FROM stock WHERE st_product=" .
                                        $v["pr_id"] .
                                        " AND st_order=" .
                                        $id
                                )
                                ->fetchAll();

                            if ($inputed != null) {
                                foreach ($inputed as $it) {
                                    echo "<div>Stock: <a href='../stock/" .
                                        $it["st_id"] .
                                        "'>" .
                                        $it["st_id"] .
                                        "</a>  (" .
                                        $_STATUSES[$it["st_status"]]["Name"] .
                                        ") Price: " .
                                        $it["st_cost"] .
                                        "</div>";
                                }
                            } else {
                                echo "<div class='bg-danger'> Stock data not found for " .
                                    $v["pr_id"] .
                                    " </div>";
                            }
                        } else {
                            echo "<div class='bg-danger'> Product data not found for id " .
                                $v["pr_id"] .
                                " </div>";
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

						<div class="col-auto" style='margin-top:20px'>
							<?php if (isset($ord) && $in == false && $ord["or_state"] !== "Completed") { ?>


							<button class='btn btn-primary' onClick="UpdateOrder()" type='button'>Update</button>
							<button class='btn btn-warning' onClick="StockIn()" type='button'>Stock In</button>
							<button class='btn btn-danger' type='button'>Delete</button>

							<?php } ?>

							<?php if ($in == true) {
           echo "<button class='btn btn-warning' onClick=\"StockInPost()\" type='button'>Submit</button>";
       } ?>
						</div>
						<?php if (!isset($ord)) { ?>
						<div class="col-auto" style='margin-top:20px'>
							<button class='btn btn-primary' onClick="CreateOrder()" type='button'>Create</button>
							<?php } ?>

						</div>

					</fieldset>
				</div>
			</div>
		</div>
	</div>
	<script>
		var products = [];
		var token = "<?php echo $_SESSION["order_token"]; ?>";

		<?php
  $data = $db->query(
      "select pr_id,pr_name,pr_manufacturer,pr_category from products where pr_del=0 order by pr_name asc"
  );
  foreach ($data as $k => $v) {
      echo "products.push({ id: " .
          $v["pr_id"] .
          ",  name:'" .
          str_replace(array("\r", "\n"), '', addslashes($v["pr_name"])) .
          "', man:" .
          $v["pr_manufacturer"] .
          ", cat:" .
          $v["pr_category"] .
          " });\r\n";
  }
  ?>

		function CreateOrder() {


			if (!$('#formdata').valid())
				return false;


			function cb_post(text_content) {
				var id = 0;
				var pd = {
					action: "putorderitems",
					data: {
						csv: text_content,
						date: $('#or_date').val(),
						supplier: $('#or_supplier').val(),
						reference: $('#or_reference').val(),
						vatType: $('#vat_type').val(),
						token: token,
						id: id
					}
				};

				$.ajax({
					url: "/ordersajax",
					data: pd,
					type: 'POST',
					error: function(a) {
						a = JSON.parse(a.responseJSON);
						if (!a.success) {
							new_toast("danger", "Error! Reason is " + a.error);
						}
					},
					success: function(a) {
						a = JSON.parse(a);
						if (a.success) {
							new_toast("success", "Success.");
							location.href = "/order/" + a.id;
						} else
							new_toast("danger", "Error! Reason is " + a.error);
					}
				});
			}

			var t = $('#csv_input')[0];
			if (t.files.length == 1) {
				t.files[0].text()
					.then(function(content) {
						cb_post(content);
					});
			} else {
				cb_post();
			}


		}

		function UpdateOrder() {

			var id = <?php echo isset($id) ? $id : 0; ?>;
			var pd = {
				action: "putorderitems",
				data: {
					date: $('#or_date').val(),
					supplier: $('#or_supplier').val(),
					token: token,
					id: id
				}
			};
			$.ajax({
				url: "/ordersajax",
				data: pd,
				type: 'POST',
				success: function(a) {
					a = JSON.parse(a);
					if (a.success) {
						new_toast("success", "Success.");

						$.post("/ordersajax", {
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
						new_toast("danger", "Error! Reason is " + a.error);
				}
			});

		}

		function StockIn() {
			var id = <?php echo isset($id) ? $id : 0; ?>;
			location.href = "../orderin/" + id;
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
				url: "/ordersajax",
				data: pd,
				type: 'POST',
				success: function(a) {
					a = JSON.parse(a);
					if (a.success) {
successSound();
						new_toast("success", "Item removed from order");

						$.post("/ordersajax", {
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
						new_toast("danger", "Error! Reason is " + a.error);
				}
			});

		}

		function addNewProduct() {
			var pr = $('#products').val();
			var qa = $('#quantity').val();
			var id = <?php echo isset($id) ? $id : 0; ?>;
			if(qa=="" || qa<=0) {
 failSound();
               new_toast("warning","Please enter a valid quantity");
                return false;
            }

			var pd = {
				action: "addproduct",
				data: {
					token: token,
					pr: pr,
					qa: qa,
					id: id
				}
			};
			$.ajax({
				url: "/ordersajax",
				data: pd,
				type: 'POST',
				success: function(a) {
					a = JSON.parse(a);
					if (a.success) {
successSound();
						new_toast("success", "Item successfully added to order");

						$.post("/ordersajax", {
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
						new_toast("danger", "Error! Reason is " + a.error);
				}
			});

		}

		function StockKeyUp(ev, pr_id) {
			console.log("Event here ", ev, pr_id);
			if (ev.code == "Enter") {
				var id = <?php echo isset($id) ? $id : 0; ?>;
				var cp = $('[name="cost_price"][opr=' + pr_id + ']').val();
				if (cp == '') {
					alert("Please set cost field.");
					return false;
				}


				var pd = {
					action: "orderinadd",
					data: {
						serial: ev.target.value,
						pr_id: pr_id,
						id: id,
						cost: cp,
						token: token
					}
				};

				$('[name="cost_price"][opr=' + pr_id + ']').val("");
				$('[name="service_tag"][opr=' + pr_id + ']').val("");


				SendAction(pd, pr_id, function(pd, pr_id) {
					console.log(pd, pr_id);
					pd.action = "orderin";
					var ap = $('[name="inpt"][opr=' + pr_id + ']');
					var cc = $('[name="counter"][opr=' + pr_id + ']');

					ap.empty();
					SendAction(pd, pr_id, function(c, b, a) {
						cc.html(a.count);
						var tary = (a.remain == 0);
						$('[name="service_tag"][opr="' + pr_id + '"]').attr("enabled", !tary).attr("disabled", tary);
						$('[name="cost_price"][opr="' + pr_id + '"]').attr("enabled", !tary).attr("disabled", tary);


						for (var i = 0; i < a.data.length; i++) {
							$('<div>' + a.data[i]["serial"] + ' - ' + a.data[i]["cost"] + '</div>').appendTo(ap);
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
				url: "/ordersajax/" + pr_id,
				data: pd,

				type: 'POST',
				success: function(a) {
					a = JSON.parse(a);
					if (a.success) {
						new_toast("success", "Success.");
					} else {
						new_toast("danger", "Error! Reason is " + a.error);
					}
					if (cb)
						cb(pd, pr_id, a);
				},
				error: function(a) {
					a = JSON.parse(a);
					f(cb)
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
			if ($("#or_date").length > 0) {
				new Litepicker({
					element: document.getElementById('or_date'),
					format: 'DD/MM/YYYY'
				});
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



	function updateVATType(){
		var data = $('#vat_type').val();
		var id = <?php echo isset($id) ? $id : 0; ?>;
		$.ajax({ 
			url:"/ordersajax",
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
	</script>