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
            <?php
            if (isset($id)) {
						?>
<h2>Update Supplier</h2>
            <?php
						}else{
              ?>
<h2>Create New Supplier</h2>
              <?php
            }
             ?>

					</div>
					<fieldset class="form-fieldset" style="width:90%; margin:20px auto;">
						<?php

            if (isset($id)) {
							$ord = $db->get("suppliers", "*", ["sp_id" => $id]);
						} ?>
						<?php //if ($in === false) { ?>
						<div class="col-md-12">
							<form id="formdata">
								<div class="row">

									<div class="col-md-12">
										<label class="form-label required">Name</label>
										<input type="text" required class="form-control" value="<?php if (isset($ord)) {
											  echo $ord["sp_name"];
										  } ?>" aria-describedby="Suppler Name" id="sp_name" />
									</div>
									<div class="col-md-12">
										<label class="form-label required">Contact</label>
										<input type="text" required class="form-control" value="<?php if (isset($ord)) {
											  echo $ord["sp_contact"];
										  } ?>" aria-describedby="Enter Contact" id="sp_contact" />
									</div>
                  <div class="col-md-12">
										<label class="form-label required">e-Mail</label>
										<input type="text" required class="form-control" value="<?php if (isset($ord)) {
											  echo $ord["sp_email"];
										  } ?>" aria-describedby="Email" id="sp_email" />
									</div>
									<div class="col-md-12">
										<label class="form-label required">Supplier Groups</label>
										<select multiple class="form-control" aria-describedby="Supplier" name="groups" id="groups" required>
											<option value=""> Please Select </option>
											<?php
											   $spp = $db
												   ->query(
													   "select * from groups"
												   )
												   ->fetchAll();
											   foreach ($spp as $k => $v) {

                           $sgroup = $db->query("select * from supplier_groups_new where sg_supplier_id=".$id." and g_id=".$v["gs_id"])->fetch();

												   $f =
													   isset($sgroup) && $v["gs_id"] == $sgroup["g_id"]
														   ? "selected"
														   : "";
												   echo "<option $f value=" .
													   $v["gs_id"] .
													   ">" .
													   $v["gs_name"] .
													   "</option>";
											   }
											   ?>
										</select>
									</div>
                  <?php if (!isset($ord)) { ?>
                  <div class="col-md-12" style='margin-top:20px;text-align:right;'>
                    <button class='btn btn-primary' onClick="CreateSupplier()" type='button'>Save</button>
                      </div>
                  <?php }else{
                    ?>
                    <div class="col-md-12" style='margin-top:20px;text-align:right;'>
                      <button class='btn btn-primary' onClick="UpdateSupplier()" type='button'>Update</button>
                        </div>

                    <?php
                  } ?>


								</div>
							</form>
						</div>
					</fieldset>



					<fieldset class="form-fieldset" style="width:90%; margin:20px auto;">



			<?php// } ?>

				</div>
			</div>
			</div>
			</div>
<?php
    //} ?>
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
               "SELECT  dp_id, dp_sku, dp_name FROM dell_part  order by dp_sku,dp_name asc"
           )
           ->fetchAll();
       foreach ($spp as $k => $v) {
           echo "<option $f value=" .
               $v["dp_id"] .
               ">" .
               $v["dp_sku"] .
               " - " .
               $v["dp_name"] .
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
     "select dp_id, dp_name from dell_part order by dp_name asc"
 );
 foreach ($data as $k => $v) {
     echo "products.push({ id: " .
         $v["dp_id"] .
         ",  name:'" .
         addslashes($v["dp_name"]) .

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
			url: "/componentordersajax",
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

	function CreateSupplier() {
		if (!$('#formdata').valid())
			return false;


		function cb_post() {
			var id = 0;
			var pd = {
				action: "addsupplier",
				data: {
					name: $('#sp_name').val(),
					contact: $('#sp_contact').val(),
          email: $('#sp_email').val(),
					groups: $('#groups').val(),
					token: token,
					token2: token2,
					id: id
				}
			};

			$.ajax({
				url: "/supplierajax",
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
						location.href = "https://www.skx-online.com/supplier/" + a.id;
					} else{
            failSound();

						new_toast("danger", "Error4! Reason is " + a.error);
          }
}
			});
		}

		cb_post();


	}

	function UpdateSupplier() {

		var id = <?php echo isset($id) ? $id : 0; ?>;
		var pd = {
			action: "addsupplier",
			data: {
        name: $('#sp_name').val(),
        contact: $('#sp_contact').val(),
        email: $('#sp_email').val(),
        groups: $('#groups').val(),
				token2: token2,
				token: token,
				id: id
			}
		};
		$.ajax({
			url: "/supplierajax",
			data: pd,
			type: 'POST',
			success: function(a) {
				a = JSON.parse(a);
				if (a.success) {
					new_toast("success", "Success5.");
            location.href = "https://www.skx-online.com/supplier/";

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
			url: "/componentordersajax",
			data: pd,
			type: 'POST',
			success: function(a) {
				a = JSON.parse(a);
				if (a.success) {
					new_toast("success", "Success4.");
					location.href = "/componentorders";
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

	function ReopenOrder() {


		var id = <?php echo isset($id) ? $id : 0; ?>;
		var pd = {
			action: "reopenorder",
			data: {
				id: id
			}
		};
		SendAction(pd, id, function(a, b, c) {
			console.log(a, b, c);
			window.location.reload();
		});



	}

	function StockIn() {


		if ($('.pr-table tbody tr').length > 0) {
			var id = <?php echo isset($id) ? $id : 0; ?>;
			location.href = "../componentorderin/" + id;
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
			url: "/componentordersajax",
			data: pd,
			type: 'POST',
			success: function(a) {
				a = JSON.parse(a);
				if (a.success) {
					successSound();
					new_toast("success", "Item successfully removed from order.");

					$.post("/componentordersajax", {
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
			url: "/componentordersajax",
			data: pd,
			type: 'POST',
			success: function(a) {
				a = JSON.parse(a);
				if (a.success) {
					successSound()
					new_toast("success", "Item has been added to the order.");
setTimeout(() => { sku.value = '';quantity.value = ''; }, 500);

					$.post("/componentordersajax", {
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
			url: "/componentordersajax/" + pr_id,
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

		if ($("#dor_date").length > 0) {
			new Litepicker({
				element: document.getElementById('dor_date'),
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
