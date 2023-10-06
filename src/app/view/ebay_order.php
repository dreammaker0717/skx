<?php
if (isset($id)) {
    $id = intval($id);
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
$_SESSION["ebay_order_token"] = $PAGE_TOKEN;
?>

<style type="text/css">
.table-hover tbody tr:hover td, .table-hover tbody tr:hover th {
   background-color: #ececec;
}
</style>

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
						<div class="mb-3">

									<form id="formdata">
										<div class="row">
											<?php if (isset($ord)) { ?>
											<div class="col-auto">
												<label class="mr-sm-2">State</label>
												<h3 style="padding:0.3rem 0"><?php if (isset($ord)) {
                            echo $ord["or_state"];
                        }?>
                        </h3>
											</div>
											<?php } ?>
											<div class="col-auto">
												<label class="mr-sm-2">Order Date</label>
												<h3 style="padding:0.3rem 0"><?php if (isset($ord)) {
                          echo date("d/m/Y", strtotime($ord["or_date"]));
                        }?>
                        </h3>
											</div>
											<div class="col-auto">
												<label class="mr-sm-2">Supplier</label>
                        <h3 style="padding:0.3rem 0">
												  <?php if (isset($ord)) {
                           $spp = $db->query("select sp_id,sp_name from suppliers where sp_del=0 and sp_id=".$ord["or_supplier"])->fetchAll();
                           echo $spp[0]['sp_name'];
                          }?>
												</h3>
											</div>
											<div class="col-auto">
												<label class="mr-sm-2">VAT Type</label>
												<?php if (isset($ord)) {
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
												<label class="mr-sm-2">Reference</label>
												<input type="text" required class="form-control" aria-describedby="Reference" id="or_reference" />
											</div>
											<?php } else { ?>
											<div class="col-auto">
												<label class="mr-sm-2">Reference</label>
												<h3 style="padding:0.3rem 0"><?php if (isset($ord)) {if ($ord["or_reference"] != null) echo $ord["or_reference"];} ?></h3>
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
                  <label for="inputPassword2" class="form-label">Product</label>
                  <input list="products" id="product"  class="form-control" placeholder="Start typing product name..." autocomplete="off">
                  <input type="hidden" name="product" id="product-hidden"  class="form-control">
                  <datalist id="products">                    
                  </datalist>
										</div>
                    <div class="col-auto">
                      <label for="inputPassword1" class="form-label">Date</label>
                      <input type="text" class="form-control" id="date" placeholder="">
                    </div>
										<div class="col-auto">
											<label for="inputPassword2" class="form-label">Seller ID</label>
											<input type="text" class="form-control" id="sellerid" placeholder="">
										</div>
                    <div class="col-auto">
                      <label for="inputPassword3" class="form-label">Item ID</label>
                      <input type="text" class="form-control" id="itemid" placeholder="">
                    </div>
                    <div class="col-auto">
                      <label for="inputPassword4" class="form-label">Order ID</label>
                      <input type="text" class="form-control" id="orderid" placeholder="">
                    </div>
                    <div class="col-auto">
                      <label for="inputPassword5" class="form-label">Service Tag</label>
                      <input type="text" class="form-control" id="service_tag" placeholder="">
                    </div>
                    <div class="col-auto">
                      <label for="inputPassword6" class="form-label">Cost</label>
                      <input type="text" class="form-control" id="cost" placeholder="">
                    </div>
										<div class="col-auto mt-4">
											<button type="button" onClick="addNewProduct()" class="btn btn-primary" style="margin-top:10px;">Add</button>
										</div>
									</form>

						</div>


						<?php } ?>
						<div class="col-md-12">
							<div class="card">
								<div class="card-body">
									<div class="table-responsive">
                    <table class="table table-vcenter card-table pr-table table-hover">
											<thead>
												<tr>
                          <th>Date</th>
													<th></th>
                          <th>Stock ID</th>
                          <th>Name</th>
                          <th>Service Tag</th>
													<th>Seller ID</th>
                          <th>Item ID</th>
                          <th>Order ID</th>
                          <th>Cost</th>
                          <th>Comment</th>
                          <th>State</th>
                          <th>RTS</th>
												</tr>
											</thead>
											<tbody>
												<?php if (isset($ord)) {
                          $query = "SELECT st_date,pr_id,pr_name,mf_name,st_ebay_seller,st_ebay_itemid,st_ebay_orderid,st_servicetag,st_cost,st_id,st_lastcomment,st_status FROM stock left join products ON st_product=pr_id left join manufacturers on  pr_manufacturer=mf_id left join categories on pr_category=ct_id WHERE st_order=$id ORDER BY st_date DESC";
                          $data = $db->query($query)->fetchAll();
                          foreach ($data as $k => $v) {
                            if ($v["st_status"] != 8) {
                                $cont = "<td style='width:50px;'><button class='btn btn-sm btn-warning' onClick='rtsUpdate(".$v["st_id"].")'>RTS</button></td>";
                            } else{
                                $cont = "-";
                            }
                              echo "<tr><td>" .
                                  date("d/m/Y", strtotime($v["st_date"])) .
                                  "</td><td></a>&nbsp&nbsp<a href=\"javascript:PrintBoxLabels(". $v["st_id"] .")\"><img src=\"https://img.icons8.com/material-outlined/24/000000/print.png\"></a></td><td><a href='../stock/" . $v["st_id"] . "'>". $v["st_id"] ."</a></td><td>" .
                                  $v["pr_name"] .
                                  "</td><td>" .
                                  $v["st_servicetag"] .
                                  "</td><td>" .
                                  $v["st_ebay_seller"] .
                                  "</td><td>" .
                                  $v["st_ebay_itemid"] .
                                  "</td><td>" .
                                  $v["st_ebay_orderid"] .
                                  "</td><td>" .
                                  $v["st_cost"] .
                                  "</td><td>" .
                                  "<input type=text onkeyup=\"CommentkeyUp(event,".$v["st_id"].")\" lass=\"form-control form-control-sm\" value=\"".$v["st_lastcomment"]."\">" .
                                  "</td><td>".$_STATUSES[$v["st_status"]]["Name"]."</td>";
                              echo $cont."</tr>";
                            }
                          }?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>

						<div class="col-auto" style='margin-top:20px'>
							<?php if (isset($ord) && $ord["or_state"] !== "Completed") { ?>
							<button class='btn btn-primary' onClick="UpdateOrder()" type='button'>Update</button>
							<?php } ?>
						</div>
					</fieldset>
				</div>
			</div>
		</div>
	</div>

<div class="modal fade" id="rtsConfirmModal" tabindex="-1" aria-labelledby="rtsConfirmModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="rtsConfirmModalLabel">Return To Seller</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to send to seller?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onClick="rtsConfirm()">Confirm</button>
      </div>
    </div>
  </div>
</div>

<form id="stock_items_form" name="stock_items_form" action="" method="post" style="display:none">
    <input type="hidden" id="stock_items" name="stock_items"/> 
</form>
	<script>
		var token = "<?php echo $_SESSION["ebay_order_token"]; ?>";

    let aborter = null;
    function getData(param) {
      if(aborter) aborter.abort();
      aborter = new AbortController();
      const signal = aborter.signal;
      const url = '/ebayordersajax';
      let formData = new FormData();
      formData.append('action', 'get_suggestion');
      formData.append('term', param);

      return fetch(url, {method: 'post', body: formData, signal})
        .then(res => {return res.json();})
        .then(resjson => {aborter = null; return resjson;});
    }

    $(function() {
      var currentTime = new Date() 
      var minDate = new Date(currentTime.getFullYear(), currentTime.getMonth(), +1);
      var maxDate =  new Date(currentTime.getFullYear(), currentTime.getMonth() +1, +0);
      new Litepicker({
        element: document.getElementById('date'),
        format: 'DD/MM/YYYY',
        minDate: minDate, 
        maxDate: maxDate
      });


      document.title = "Order # <?php if (isset($id)) { echo $id; } ?> " + document.title;

        if (document.querySelector('input[list]') != null) {
          document.querySelector('input[list]').addEventListener('input', function(e) {
            if (e.target.value != "") {
              getData(e.target.value)
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
                    products.innerHTML = '';
                    found = true;
                    break;
                  }
                }

                if (!found) {
                  products.innerHTML = '';
                  autocompleteItems.forEach(function(item){
                     var autocompleteOption = document.createElement('option');
                     autocompleteOption.setAttribute('data-value', item.pr_id);
                     autocompleteOption.innerText = item.pr_name;
                     products.appendChild(autocompleteOption);
                  });
                }
              }).catch(e => console.error('Request failed', e.name, e.message));
            } else {
              if(aborter) aborter.abort();
              var products = document.getElementById('products');
              products.innerHTML = '';
            }
          });
        }

    });

		function UpdateOrder() {
			var id = <?php echo isset($id) ? $id : 0; ?>;
			var pd = {
				action: "putorderitems",
				data: {
					token: token,
					id: id
				}
			};
			$.ajax({
				url: "/ebayordersajax",
				data: pd,
				type: 'POST',
				success: function(a) {
					a = JSON.parse(a);
					if (a.success) {
						new_toast("success", "Success.");

						$.post("/ebayordersajax", {
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
      var dt = $('#date').val();
			var pr = $('[name="product"]').val();
      var si = $('#sellerid').val();
      var it = $('#itemid').val();
      var oi = $('#orderid').val();
      var st = $('#service_tag').val();
      var ct = $('#cost').val();
			var id = <?php echo isset($id) ? $id : 0; ?>;

      if (pr == "" || pr !== String(Number(pr))) {
        new_toast("danger", "Error! Please select a valid product.");
        return;
      }

			var pd = {
				action: "addproduct",
				data: {
					token: token,
					dt: dt,
          pr: pr,
          si: si,
          it: it,
          oi: oi,
          st: st,
          ct: ct,
					id: id
				}
			};
			$.ajax({
				url: "/ebayordersajax",
				data: pd,
				type: 'POST',
				success: function(a) {
					a = JSON.parse(a);
					if (a.success) {
            successSound();
						new_toast("success", "Item successfully added to order");

						$.post("/ebayordersajax", {
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

    function RemoveItem(st) {
      var id = <?php echo isset($id) ? $id : 0; ?>;
      var pd = {
        action: "removeproduct",
        data: {
          token: token,
          st: st,
          id: id
        }
      };
      $.ajax({
        url: "/ebayordersajax",
        data: pd,
        type: 'POST',
        success: function(a) {
          a = JSON.parse(a);
          if (a.success) {
successSound();
            new_toast("success", "Item removed from order");

            $.post("/ebayordersajax", {
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

  function PrintBoxLabels(id) {
      $('#stock_items').val(id);
      $('#stock_items_form').attr("action","../stocksprintajax/print-box-labels");
      $('#stock_items_form').attr("target","_blank");
      $('#stock_items_form').submit();
  }

  function CommentkeyUp(ev, st_id){
    console.log("Event here ",ev);
    if(ev.code=="Enter") {
      $.ajax({ 
        url:"/ebayordersajax",
        data:{ action: "update_comment", data: ev.target.value, st_id: st_id},
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
  }

  var rtsSTID = null;
  function rtsUpdate(st_id){
    rtsSTID = st_id;
    $('#rtsConfirmModal').modal('show');
  }

  function rtsConfirm(){
    $('#rtsConfirmModal').modal('hide');
    $.ajax({ 
        url:"/ebayordersajax",
        data:{ action: "update_rts", st_id: rtsSTID},
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