<style>
    .skxbolder { font-weight: 500;}
	.skxcenter {text-align:center;}
	.skxbadge {width:50px; display:block; margin:0 auto;}

	.frmSearch {margin: 2px 0px;padding:0px;border-radius:4px;}
	#supplier-list{float:left;list-style:none;margin-top:-3px;padding:0;width:92%;position: absolute;}
	#supplier-list li{padding: 10px; background: white; border-bottom: #bbb9b9 1px solid;}
	#supplier-list li:hover{background:#ece3d2;cursor: pointer;}
	#email{padding: 10px;border: #a8d4b1 1px solid;border-radius:4px;}
	#anmodal-table tbody {display: block;height: 440px;overflow-y: scroll;}
	#anmodal-table thead, #anmodal-table tbody tr {display: table;width: 100%;table-layout: fixed;}
</style>

<?php
if (isset($id)) {
	$id = intval($id);
}
include PATH_CONFIG . "/constants.php";
$db = M::db();
?>
<div class="page-body">
	<div class="container-fluid">
		<div class="col-lg-11" style="margin:0 auto;">
			<div class="card card-lg">
				<div class="card-body" style="padding:3rem 1rem;">
					<div style="margin-left:1rem">
						<?php if (isset($id)) {
							echo "<h2>RFQ #" . $id . "</h2>";
						} else {
							echo "<h2>Create an Order for New Items</h2>";
							echo '<div class="text-muted">Fill in the details in these options and then use one of the methods below to create an order.<br /></div> ';
						}?>
					</div>
					<fieldset class="form-fieldset" style="width:90%; margin:20px auto;">
						<?php if (isset($id)) { $ord = $db->get("rfq_rfq", "*", ["rfq_id" => $id]); }?>
						<div class="mb-3">
							<form id="formdata">
								<div class="row">
									<?php if (isset($ord)) {?>
									<div class="col-auto">
										<label class="mr-sm-2">State</label>
										<h3 style="padding:0.3rem 0"><?php if (isset($ord)) {echo $ord["rfq_state"];}?></h3>
									</div>
									<?php }?>
									<div class="col-auto">
										<label class="form-label required">Order Date</label>
										<input type="text" required class="form-control" value="<?php if (isset($ord)) {echo $ord["rfq_date"];}?>" aria-describedby="Order Date" id="rfq_date" />
									</div>
									<div class="col-auto">
										<label class="form-label required">Reference</label>
										<input type="text" required class="form-control" value="<?php if (isset($ord)) {echo $ord["rfq_reference"];}?>" aria-describedby="Reference" id="rfq_reference" />
									</div>
									<div class="col-auto">
										<label class="form-label required">Currency</label>
										<select class="form-control" aria-describedby="Currency" name="rfq_currency" id="rfq_currency" required>
											<option value=""> Please Select </option>
											<option value="USD" <?php if (isset($ord) && $ord["rfq_currency"] == "USD") {echo "selected";}?> >US Dollar</option>
											<option value="GBP" <?php if (isset($ord) && $ord["rfq_currency"] == "GBP") {echo "selected";}?> >GBP</option>
											<option value="EUR" <?php if (isset($ord) && $ord["rfq_currency"] == "EUR") {echo "selected";}?> >Euro</option>
											<option value="RMB" <?php if (isset($ord) && $ord["rfq_currency"] == "RMB") {echo "selected";}?> >RMB</option>

										</select>
									</div>
								</div>
							</form>
						</div>
					</fieldset>


					<?php if (!isset($ord) || (isset($ord) && $ord["rfq_state"] !== "Completed")) {?>
					<div style="width:90%; text-align:left;margin:0 auto;">
						<h3 style="margin-bottom:0;margin-top:2rem;";>Supplier RFQ</h3>
						<span class="text-muted">Use this form for creating RFQ's to send to suppliers. Add the products and quantities, then click on the "Create" button at the bottom to create the RFQ. </span>
					</div>
					<fieldset class="form-fieldset" style="width:90%; margin:20px auto;">
					<div class="row g-3">
						<div class="mb-3">
							<form class="row g-3">
								<div class="col-auto" style="width:70%">
									<label for="inputPassword2" class="form-label">SKU </label>
									<input list="skus" id="sku"  class="form-control" placeholder="Start typing SKU or product name..." autocomplete="off">
									<input type="hidden" name="sku" id="sku-hidden"  class="form-control">
									<datalist id="skus">										
									</datalist>
								</div>
								<div class="col-auto" style="width:20%">
									<label for="quantity" class="form-label">Quantity</label>
									<input type="number" class="form-control" id="quantity" placeholder="Quantity Ordered">
								</div>
								<div class="col-auto mt-4">
									<button type="button" onClick="addNewProduct()" class="btn btn-primary" style="margin-top:10px;">Add</button>
								</div>
							</form>
						</div>
					</div>

			<?php }?>
						<div class="row mt-3">
							<div class="col-md-12">
								<div class="card">
									<div class="card-body">
										<div class="table-responsive">
										<table class='table table-vcenter card-table' id="pr-table">
									<thead>
										<tr>
											<th><input type='checkbox' onclick='handleSelectAllClick(this);'></th>
											<th>SKU</th>
											<th>Description</th>
											<th>Condition</th>
											<th style="text-align:center;">Qty</th>
											<th style="text-align:center;">Last<br>Price</th>
											<th style="text-align:center;">SKX<br>STOCK</th>
											<th style="text-align:center;">MAGENTO<br>STOCK</th>
											<th style="text-align:center;">ON<br>ORDER</th>
											<th width="15%">Action</th>
										</tr>
									</thead>
									<tbody>
									</tbody>
								</table>
							</div>
				</div>
			</div>
			</div>
			</div>
			<?php if (isset($ord) && $ord["rfq_state"] !== "Completed") {?>
			<div class="col-auto" style='margin-top:20px'>
				<button class='btn btn-primary' onClick="SaveOrder()" type='button'>Update</button>
				<button class='btn btn-success' onClick="ShowConfirmModal()" type='button'>Create Order</button>
				<button class='btn btn-warning' onClick="DownloadExcel()" type='button'>Download Excel</button>
				<button class='btn btn-warning' onClick="DownloadPdf()" type='button'>Download PDF</button>
				<button class='btn btn-success' onClick="EmailExcelModal()" type='button'>Email To Supplier</button>
				<button class='btn btn-danger' style="float:right;" onclick="DeleteOrder()" type='button'>Delete</button>

			</div>
			<?php }?>
			<?php if (!isset($ord)) {?>
			<div class="col-auto" style='margin-top:20px'>
				<button class='btn btn-primary' onClick="SaveOrder()" type='button'>Save</button>
				<button class='btn btn-success' onClick="ShowConfirmModal()" type='button'>Create Order</button>
			</div>
			<?php }?>
		</fieldset>

			</div>
		</div>
	</div>
</div>


</div>

<div class="modal fade" tabindex="-1" aria-labelledby="notAddedModalLabel" aria-hidden="true" id="notAddedModal">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="notAddedModalLabel">Add Items</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Add at least one item.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" tabindex="-1" aria-labelledby="notCheckedModalLabel" aria-hidden="true" id="notCheckedModal">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="notCheckedModalLabel">Choose Items</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Choose at least one item.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered  modal-xl">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="confirmModalLabel">Create New Order</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body">
					<fieldset class="form-fieldset">
						<div class="mb-3">
							<form id="formdatamodal">
								<div class="row">
									<?php if (isset($ord)) {?>
									<div class="col-auto">
										<label class="mr-sm-2">State</label>
										<h3 style="padding:0.3rem 0"><?php if (isset($ord)) {echo $ord["rfq_state"];}?></h3>
									</div>
									<?php }?>
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
<?php if(isset($id)){?>
	 <div class="modal fade" id="mailModal" tabindex="-1" aria-labelledby="mailModalLabel" aria-hidden="true">
	    <div class="modal-dialog modal-dialog-centered  modal-xl">
	       <div class="modal-content">
	          <div class="modal-header">
	             <h5 class="modal-title" id="mailModalLabel">Send Excel to Supplier</h5>
	             <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	          </div>
	          <div class="modal-body">
	 					<fieldset class="form-fieldset">
	 						<div class="mb-3">
	 							<form id="formdatamodal1">
	 								<div class="row">
	 									<div class="col-md-12">
	 										<label class="mr-sm-2">Subject</label>
												<?php if (isset($id)) {
													$queryRef = "SELECT rfq_reference FROM rfq_rfq WHERE rfq_id=" . $id;
													$ordrefdata = $db->query($queryRef)->fetchAll();
													$userSql = "SELECT username FROM users WHERE user_id = " . $_SESSION["user_id"];
													$userdata = $db->query($userSql)->fetchAll();
												?>

	 										<input readonly type="text" class="form-control" value="RFQ #<?php echo $id;
											if($ordrefdata[0]['rfq_reference']){
												echo "-".$ordrefdata[0]['rfq_reference'];
											}
											if($userdata[0]['username']){
												echo "-from ".$userdata[0]['username'];
											}
										}
											?>" aria-describedby="Subject" id="subject" />
	 									</div>
	 									<div class="col-md-12">
	 										<label class="mr-sm-2">Content</label>
	 										<textarea cols="50" rows="7" class="form-control" aria-describedby="Content" id="body" ></textarea>
	 									</div>
	 									<div class="col-md-12">
	 										<label class="mr-sm-2">Email</label>
	 										<!--<input type="text" class="form-control" aria-describedby="Email" id="email" />-->
											<div class="frmSearch">
								        <input type="text" class="form-control" id="email"  aria-describedby="Supplier Name or Contact"  />
								        <div id="suggesstion-box"></div>
											</div>
	 									</div>
	 									<div class="col-md-12">
	 										<label class="form-label required">Supplier Group</label>
	 										<select multiple class="form-control" aria-describedby="Supplier" name="groups" id="groups">
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
	 								</div>
	 							</form>
	 						</div>
	 					</fieldset>
						<div id="erro_msg" style="display:none;padding-left:20px;color:red;font-weight:bold;"></div>
	 				 </div>
	 				 <div class="modal-footer">
	 						 <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
	 						 <button type="button" class="btn btn-primary" id="btnSend" onclick='EmailExcelToSupplier();'>Send</button>
	 				 </div>
	 				 </div>
	 			</div>
	 	 </div>
<?php } ?>
<div class="modal fade" id="analyzeModal" tabindex="-1" aria-labelledby="analyzeModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered  modal-xl">
	    <div class="modal-content">
	        <div class="modal-header">
	             <h5 class="modal-title" id="analyzeModalLabel"></h5>
	             <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	        </div>
	        <div class="modal-body" style="padding-top: 0px;">
	        	<div class="card-body">
	        		<div class="row">
	        			<div class="col-md-6">
	        				<div class="text-center"><h2>2 YEAR ANALYSIS</h2></div>
	        				<div id="chart-price" class="chart"></div>
	        				<div id="chart-qty" class="chart"></div>
	        				<div id="chart-sales" class="chart"></div>
	        			</div>
	        			<div class="col-md-6">
	        				<div class="text-center"><h2>ORDER HISTORY</h2></div>
	        				<table class='table table-vcenter card-table' id="anmodal-table">
								<thead>
									<tr>
										<th>Date</th>
										<th>Quantity</th>
										<th>Price</th>
										<th>Supplier</th>
										<th>Arrived</th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
							<div style="margin-left: 200px;">
                                <div class="font-weight-bold" id="orderHigh">High: 0.00</div>
                                <div class="font-weight-bold" id="orderLow">Low: 0.00</div>
                                <div class="font-weight-bold mb-4" id="orderAvg">Average: 0.00</div>
                                <div class="font-weight-bold" id="totOrdered">Total QTY(Ordered to Date): 0</div>
							</div>
	        			</div>
	        		</div>
				</div>
	 		</div>
	 	</div>
	</div>
</div>

<script>
var listData = [];
var id = 0;
var user = 0;
let aborter = null;
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

<?php
$data = array();
if (isset($ord)) {
    $typeQuery = "SELECT id, rfq_prodtype, rfq_product, rfq_quantity, rfq_price FROM rfq_items WHERE rfq_id=" . $id;
    $typeData = $db->query($typeQuery)->fetchAll();
    $price = 0;
    $index = 0;
    $supplierComments = null;
    foreach ($typeData as $type) {
        $lastRfqQuery = "SELECT rfq_items.rfq_price, rfq_items.rfq_suppliercomments FROM rfq_items LEFT JOIN rfq_rfq ON rfq_items.rfq_id = rfq_rfq.rfq_id WHERE rfq_product=" . $type['rfq_product'] . " AND rfq_prodtype=" . $type['rfq_prodtype'] . " ORDER BY rfq_rfq.rfq_date DESC";

	    $lastRfqIntermediate = $db->query($lastRfqQuery);
	    if ($lastRfqIntermediate) {
	        $lastRfqData = $lastRfqIntermediate->fetchAll();
	        $price = $lastRfqData[0]['rfq_price'];
	        $supplierComments = $lastRfqData[0]['rfq_suppliercomments'];
	    }
        $partQuery = null;
        if ($type['rfq_prodtype'] == 1) {
            $partQuery = "SELECT npr_name, npr_condition, npr_sku, npr_magqty, npr_suppliercomments FROM nwp_products WHERE npr_id=" . $type['rfq_product'];
            $partData = $db->query($partQuery)->fetchAll();
            $nwpStockQuery = "SELECT COUNT(*) as c FROM nwp_stock WHERE (nst_status = 7 OR nst_status = 22 OR nst_status = 6) AND nst_product = " . $type['rfq_product'];
            $nwpStockData = $db->query($nwpStockQuery)->fetchAll();
            $quantity = 0;
            $arrived = 0;
            $nwpOrderQuery = "SELECT rfq_orderproducts.rfqop_quantity, rfq_orderproducts.rfqop_arrived, rfq_orders.rfqo_state FROM rfq_orderproducts LEFT JOIN rfq_orders ON rfq_orderproducts.rfqo_id = rfq_orders.rfqo_id WHERE rfq_orderproducts.rfqop_prodtype = 1 AND rfq_orderproducts.rfqop_product = " . $type['rfq_product'];
            if ($nwpOrderData = $db->query($nwpOrderQuery)->fetchAll()) {
                foreach ($nwpOrderData as $entry) {
                    if ($entry['rfqo_state'] == "On Order" || $entry['rfqo_state'] == "Part Arrived") {
                        $quantity+= $entry['rfqop_quantity'];
                        $arrived+= $entry['rfqop_arrived'];
                    }
                }
            }
            $newArr = ['name' => $partData[0]['npr_name'], 'condition' => $partData[0]['npr_condition'], 'sku' => $partData[0]['npr_sku'], 'quantity' => $type['rfq_quantity'], 'invqty' => $nwpStockData[0]['c'], 'magqty' => $partData[0]['npr_magqty'], 'orderqty' => ($quantity - $arrived), 'price' => $price, "prodtype" => 1, "id" => $type['rfq_product'], "suppliercomments" => $supplierComments, "product_suppliercomments" => $partData[0]['npr_suppliercomments']];
            $data[$index] = $newArr;
            $index++;
        } else if ($type['rfq_prodtype'] == 2) {
            $partQuery = "SELECT npr2_name, npr2_condition, npr2_sku, npr2_magqty, npr2_suppliercomments FROM nwp_products2 WHERE npr2_id=" . $type['rfq_product'];
            $partData = $db->query($partQuery)->fetchAll();
            $quantity = 0;
            $arrived = 0;
            $nwp2OrderQuery = "SELECT rfq_orderproducts.rfqop_quantity, rfq_orderproducts.rfqop_arrived, rfq_orders.rfqo_state FROM rfq_orderproducts LEFT JOIN rfq_orders ON rfq_orderproducts.rfqo_id = rfq_orders.rfqo_id WHERE rfq_orderproducts.rfqop_prodtype = 2 AND rfq_orderproducts.rfqop_product = " . $type['rfq_product'];
            if ($nwp2OrderData = $db->query($nwp2OrderQuery)->fetchAll()) {
                foreach ($nwp2OrderData as $entry) {
                    if ($entry['rfqo_state'] == "On Order" || $entry['rfqo_state'] == "Part Arrived") {
                        $quantity+= $entry['rfqop_quantity'];
                        $arrived+= $entry['rfqop_arrived'];
                    }
                }
            }
            $newArr = ['name' => $partData[0]['npr2_name'], 'condition' => $partData[0]['npr2_condition'], 'sku' => $partData[0]['npr2_sku'], 'quantity' => $type['rfq_quantity'], 'invqty' => 0, 'magqty' => $partData[0]['npr2_magqty'], 'orderqty' => ($quantity - $arrived), 'price' => $price, "prodtype" => 2, "id" => $type['rfq_product'], "suppliercomments" => $supplierComments, "product_suppliercomments" => $partData[0]['npr2_suppliercomments']];
            $data[$index] = $newArr;
            $index++;
        } else if ($type['rfq_prodtype'] == 3) {
            $partQuery = "SELECT apr_name, apr_condition, apr_sku, apr_magqty, apr_suppliercomments FROM aproducts WHERE apr_id=" . $type['rfq_product'];
            $partData = $db->query($partQuery)->fetchAll();
            $aprStockQuery = "SELECT COUNT(*) as c FROM acc_stock WHERE (ast_status = 7 OR ast_status = 22 OR ast_status = 6) AND ast_product = " . $type['rfq_product'];
            $aprStockData = $db->query($aprStockQuery)->fetchAll();
            $quantity = 0;
            $arrived = 0;
            $aprOrderQuery = "SELECT rfq_orderproducts.rfqop_quantity, rfq_orderproducts.rfqop_arrived, rfq_orders.rfqo_state FROM rfq_orderproducts LEFT JOIN rfq_orders ON rfq_orderproducts.rfqo_id = rfq_orders.rfqo_id WHERE rfq_orderproducts.rfqop_prodtype = 3 AND rfq_orderproducts.rfqop_product = " . $type['rfq_product'];
            if ($aprOrderData = $db->query($aprOrderQuery)->fetchAll()) {
                foreach ($aprOrderData as $entry) {
                    if ($entry['rfqo_state'] == "On Order" || $entry['rfqo_state'] == "Part Arrived") {
                        $quantity+= $entry['rfqop_quantity'];
                        $arrived+= $entry['rfqop_arrived'];
                    }
                }
            }
            $newArr = ['name' => $partData[0]['apr_name'], 'condition' => $partData[0]['apr_condition'], 'sku' => $partData[0]['apr_sku'], 'quantity' => $type['rfq_quantity'], 'invqty' => $aprStockData[0]['c'], 'magqty' => $partData[0]['apr_magqty'], 'orderqty' => ($quantity - $arrived), 'price' => $price, "prodtype" => 3, "id" => $type['rfq_product'], "suppliercomments" => $supplierComments, "product_suppliercomments" => $partData[0]['apr_suppliercomments']];
            $data[$index] = $newArr;
            $index++;
        } else if ($type['rfq_prodtype'] == 4) {
            $partQuery = "SELECT dp_name, dp_condition, dp_sku, dp_magqty, dp_suppliercomments FROM dell_part WHERE dp_id=" . $type['rfq_product'];
            $partData = $db->query($partQuery)->fetchAll();
            $dpStockQuery = "SELECT COUNT(*) as c FROM dco_stock WHERE (dst_status = 7 OR dst_status = 22 OR dst_status = 6) AND dst_product = " . $type['rfq_product'];
            $dpStockData = $db->query($dpStockQuery)->fetchAll();
            $quantity = 0;
            $arrived = 0;
            $dpOrderQuery = "SELECT rfq_orderproducts.rfqop_quantity, rfq_orderproducts.rfqop_arrived, rfq_orders.rfqo_state FROM rfq_orderproducts LEFT JOIN rfq_orders ON rfq_orderproducts.rfqo_id = rfq_orders.rfqo_id WHERE rfq_orderproducts.rfqop_prodtype = 4 AND rfq_orderproducts.rfqop_product = " . $type['rfq_product'];
            if ($dpOrderData = $db->query($dpOrderQuery)->fetchAll()) {
                foreach ($dpOrderData as $entry) {
                    if ($entry['rfqo_state'] == "On Order" || $entry['rfqo_state'] == "Part Arrived") {
                        $quantity+= $entry['rfqop_quantity'];
                        $arrived+= $entry['rfqop_arrived'];
                    }
                }
            }
            $newArr = ['name' => $partData[0]['dp_name'], 'condition' => $partData[0]['dp_condition'], 'sku' => $partData[0]['dp_sku'], 'quantity' => $type['rfq_quantity'], 'invqty' => $dpStockData[0]['c'], 'magqty' => $partData[0]['dp_magqty'], 'orderqty' => ($quantity - $arrived), 'price' => $price, "prodtype" => 4, "id" => $type['rfq_product'], "suppliercomments" => $supplierComments, "product_suppliercomments" => $partData[0]['dp_suppliercomments']];
            $data[$index] = $newArr;
            $index++;
        }
    }
}

foreach ($data as $value) {
    echo "listData.push({
	    		id:" . $value["id"] . ", prodtype:" . $value["prodtype"] . ", checked: false" . ", name:\"" . addslashes($value["name"]) . "\", condition:\"" . $value["condition"] . "\", sku:\"" . addslashes($value["sku"]) . "\", quantity:" . $value["quantity"] . ", invqty:" . $value["invqty"]. ", magqty:" . $value["magqty"] . ", orderqty:" . $value["orderqty"] . ", price:" . $value["price"] . ", suppliercomments:\"" . addslashes($value["suppliercomments"]) . "\", product_suppliercomments:\"" . addslashes($value["product_suppliercomments"]) . "\"});\r\n";
}
if (isset($id)) {
    echo "id = " . $id . ";\r\n";
}
echo "user = " . $_SESSION["user_id"] . ";\r\n";
?>

	if (sessionStorage.getItem('listData') != null) {
		var tempListData = JSON.parse(sessionStorage.getItem("listData"));
		sessionStorage.clear();
		tempListData.forEach(function(listEntry){
			$.ajax({
            	url:"/newitemrfqsajax",
            	data:{ action: "get_product_details", prodtype: listEntry['prodtype'], productid: listEntry['id']},
            	type:'POST', 
            	success:function(res) {
            	resJson = JSON.parse(res);                                   
				listData.push({
		    		id: resJson["id"],
		    		name: resJson["name"],
		    		sku: resJson["sku"],
		    		condition: resJson["condition"],
		        	prodtype: resJson['prodtype'],
		    		checked: false,
		        	quantity: 10,
		        	invqty: resJson["invqty"],
		        	orderqty: resJson["orderqty"],
		        	magqty: resJson["magqty"],
		        	price: resJson["price"],
		        	suppliercomments: resJson["suppliercomments"],
		        	product_suppliercomments: resJson["product_suppliercomments"]
		        });
		        redrawTable();
            	}
        	});
		});
	}

	if ($("#rfq_date").length > 0) {
		new Litepicker({
			element: document.getElementById('rfq_date'),
			format: 'DD/MM/YYYY'
		});
	}

	if ($('.pr-table tbody tr').length > 0) {
		$('#completeorderbut').css("display", "none");
	}

	document.title = "Order # <?php if (isset($id)) {echo $id;}?>" + document.title;


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
							found = true;
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

	$("#email").keyup(function() {
		var pd = {
				action: "gtsupplier",
				data: {keyword: $('#email').val(),}
		};
		$.ajax({
				url: "/getSupplierajax",
				data: pd,
				type: 'POST',
				beforeSend: function() {
					$("#email").css("background", "#FFF");
				},
				error: function(a) {
						a = JSON.parse(a.responseJSON);
						if (!a.success) {
								failSound();
								new_toast("danger", "Error! Reason is " + a.error);
						}
				},
				success: function(a) {
					$("#suggesstion-box").show();
					$("#suggesstion-box").html(a);
					$("#email").css("background", "#FFF");
				}
		});
	});
});

function handleSelectAllClick(cb) {
  if (cb.checked) {
  	listData.forEach(entry => {
  		entry.checked = true;
  	});
  } else {
  	listData.forEach(entry => {
  		entry.checked = false;
  	});
  }
  redrawTable();
}

function selectSupplier(val) {
	$("#email").val(val);
	$("#suggesstion-box").hide();
}

function ShowConfirmModal(){
	if (!$('#formdata').valid()){
    	new_toast("warning", "Please fill the form!");
        return false;
	}
    if(listData.length == 0){
    	$('#notAddedModal').modal('show');
        return false;
    }

    var isChecked = false;
    listData.forEach(item => {
    	if (item['checked'] == true) {
    		isChecked = true;
    	}
    });

    if(!isChecked){
    	$('#notCheckedModal').modal('show');
        return false;
    }

	$('#confirmModal').modal('show');

	$('#rfqmodal_date').val($('#rfq_date').val());
	$('#rfqmodal_reference').val($('#rfq_reference').val());
	$('#rfqmodal_currency').val($('#rfq_currency').val());

	$('#prmodal-table tbody').empty();

	$('#subtotal').html(0);
	$('#label_fee').val(0);
	$('#ship_fee').val(0);
	$('#bank_fee').val(0);
	$('#surcharge').val(0);
	$('#credit').val(0);
	$('#discount').val(0);
	$('#total').html(0);


	listData.forEach(item => {
		if (item['checked'] == true) {
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

			$('#prmodal-table > tbody:last').append('<tr><td><div style="border-left: 6px solid ' + color + '; padding-left: 6px; height: auto;">' + item['sku'] + '</div></td><td>' + item['name'] + '<br>' + suppliercomments + '</td><td>' + item['condition'] + '</td><td><input type="number" onkeyup="modalkeyUp(event, \'quantity\', ' + item['prodtype'] + ', ' +item['id'] + ')" oninput="modalkeyUp(event, \'quantity\', ' + item['prodtype'] + ', ' +item['id'] + ')" class="comment form-control form-control-sm" value="' + item['quantity'] + '"></td><td><input type="number" onkeyup="modalkeyUp(event, \'price\', ' + item['prodtype'] + ', ' +item['id'] + ')" oninput="modalkeyUp(event, \'price\', ' + item['prodtype'] + ', ' +item['id'] + ')" class="comment form-control form-control-sm" value="' + item['price'] + '"></td><td id="tot_' + item['prodtype'] + '-' +item['id'] + '">' + (item['quantity'] * item['price']).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</td></tr>');
				
		}
	});

	updateTotals();
}


function SaveOrder(){
	if (!$('#formdata').valid())
        return false;
    if(listData.length == 0){
    	new_toast("warning", "Please add products!");
        return false;
    }

    var pd = {
        action: "saverfqorder",
        id: id,
        user: user,
        data: {
            date: $('#rfq_date').val(),
            reference: $('#rfq_reference').val(),
            currency: $('#rfq_currency').val(),
            listData: listData
        }
    };

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
                new_toast("success", "Success6.");
                location.href = "/itemrfq/" + a.id;
            } else {
                failSound();
                new_toast("danger", "Error! Reason is " + a.error);
            }
        }
    });
}

function DownloadExcel(){
		window.location="/rfqexcelajax/" + <?php echo isset($id) ? $id : 0; ?>;
}

function DownloadPdf(){
		window.location="/rfqpdfajax/" + <?php echo isset($id) ? $id : 0; ?>;
}

function EmailExcelModal(){
	$('#mailModal').modal('show');
	$("#body").val("");
	$("#email").val("");
	return false;
}

function EmailExcelToSupplier() {
	var isSelected = false;
	if ($("#groups").val().length != 0) {
		if ($("#groups").val()[0] != "") {
			isSelected = true;
		}
	}
	if($("#email").val()=="" && !isSelected){
		$("#erro_msg").css("display","block");
		$("#erro_msg").html("Please search email or select supplier group.");
		return false;
	}
	var id = <?php echo isset($id) ? $id : 0; ?>;
	var data = {
        subject: $('#subject').val(),
		body: $('#body').val(),
        email: $('#email').val(),
        groups: $('#groups').val(),
		id: id
    };

    $.ajax({
        url: "/sendToSupplerajax/"+id,
        data: data,
        type: 'POST',
        error: function(a) {
        	console.log(a);
            a = JSON.parse(a.responseJSON);
            if (!a.success) {
                failSound();
                new_toast("danger", "Error! Reason is " + a.error);
            }
        },
        success: function(a) {
			a = JSON.parse(a);
            if (a.success) {
				$('#mailModal').modal('hide');
                new_toast("success", "Mail sent successfully.");
            } else {
                failSound();
                new_toast("danger", "Error! Reason is " + a.error);
            }
        }
    });
}

function CreateOrder() {
    if (!$('#formdatamodal').valid())
        return false;
    var pd = {
        action: "createorder",
        id: id,
        user: user,
        data: {
            date: $('#rfqmodal_date').val(),
            reference: $('#rfqmodal_reference').val(),
            currency: $('#rfqmodal_currency').val(),
            supplier: $('#rfqmodal_supplier').val(),
            labelfee : parseInt($('#label_fee').val()),
			shipfee : parseInt($('#ship_fee').val()),
			bankfee : parseInt($('#bank_fee').val()),
			surcharge : parseInt($('#surcharge').val()),
			credit : parseInt($('#credit').val()),
			discount : parseInt($('#discount').val()),
            listData: listData.filter(obj => obj.checked == true)
        }
    };

	$('#confirmModal').modal('hide');

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
                new_toast("success", "Success6.");
                location.href = "/itemrfq/" + a.id;
            } else {
                failSound();
                new_toast("danger", "Error! Reason is " + a.error);
            }
        }
    });
}

function DeleteOrder() {
    if (!confirm("Do you really want to delete order?")) return false;
    var id = <?php echo isset($id) ? $id : 0; ?> ;
    var pd = {
        action: "deleteorder",
        data: {
            id: id
        }
    };
    $.ajax({
        url: "/newitemrfqsajax",
        data: pd,
        type: 'POST',
        success: function(a) {
            a = JSON.parse(a);
            if (a.success) {
                new_toast("success", "Success");
                location.href = "/newitemrfqs";
            } else
                new_toast("danger", "Error! Reason is " + a.error);
        }
    });
}

async function addNewProduct() {
    var pn = $('[name="sku"]').val();
    var qa = $('#quantity').val();

    $('#sku').val('');
    $('[name="sku"]').val('');
    $('#quantity').val('');

    if (pn == "") {
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
	        	price: json["price"],
	        	suppliercomments: json["suppliercomments"],
	        	product_suppliercomments: json["product_suppliercomments"]
	        });

	        redrawTable();
	    } else {
	        new_toast("danger", "Error! in response!" + error);
	    }
	} catch (error) {
	    new_toast("danger", "Error! Reason is " + error);
	}

    redrawTable();
}

function handleSelectClick(e, type, id){
	listData.forEach(item => {
		if (item['prodtype'] == type && item['id'] == id) {
			if (e.checked) {
				item['checked'] = true;
			} else {
				item['checked'] = false;
			}
		}
	});
}

function SPkeyUp(ev, prodType, prodID){
    listData.forEach(item => {
    	if (item['prodtype'] == prodType && item['id'] == prodID) {
    		item['suppliercomments'] = ev.target.value;
    	}
    });
}

function modalkeyUp(ev, type, prodType, prodID){
	if (type == "label_fee" || type == "ship_fee" || type == "bank_fee" || type == "surcharge" || type == "credit" || type == "discount") {
		updateTotals();
	} else if (type == "price") {
		listData.forEach(item => {
			if (prodType == item['prodtype'] && prodID == item['id']) {
				item['price'] = ev.target.value;
				$('#tot_' + prodType + "-" + prodID).html((item['price'] * item['quantity']).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
			}
		});
		updateTotals();
	} else if (type == "quantity") {
		listData.forEach(item => {
			if (prodType == item['prodtype'] && prodID == item['id']) {
				item['quantity'] = ev.target.value;
				$('#tot_' + prodType + "-" + prodID).html((item['price'] * item['quantity']).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
			}
		});
		updateTotals();
	}
}

function removeItem(e, type, id){
	listData = listData.filter(obj => obj.prodtype != type || obj.id != id);
	redrawTable();
}

var priceChart = null;
var qtyChart = null;
var salesChart = null;
function showAnalyze(e, type, id){
	$("#loading").attr("style", "display: block !important");
	$.ajax({
	    url: "/rfqanalyzeajax/",
	    type:'POST',
	    data: { type: type, id: id},
	    success:function(restResponse) {
	      var response = JSON.parse(restResponse);
	      if(response.success) {
	        $('#analyzeModal').modal('show');
	        listData.forEach(item => {
	        	if (item.prodtype == type && item.id == id) {
	        		document.getElementById('analyzeModalLabel').innerHTML = "PRODUCT ANALYSIS FOR " + item.sku + "-" + item.name;
	        	}
	        });
	        if (priceChart) {
	        	priceChart.destroy();
	        }
	        if (qtyChart) {
	        	qtyChart.destroy();
	        }
	        if (salesChart) {
	        	salesChart.destroy();
	        }
			priceChart = new ApexCharts(document.getElementById('chart-price'), {
		  		title: {
				    text: "Price",
				    align: 'center',
				    margin: 0,
				    offsetX: 0,
				    offsetY: 0,
				    floating: false,
				    style: {
				      fontSize:  '18px',
				      fontWeight:  'bold',
				      fontFamily:  'Arial, sans-serif',
				      color:  '#263238'
				    },
				},
		  		chart: {
		  			type: "line",
		  			fontFamily: 'inherit',
		  			height: 200,
		  			parentHeightOffset: 0,
		  			toolbar: {
		  				show: false,
		  			},
		  			animations: {
		  				enabled: false
		  			},
		  		},
		  		fill: {
		  			opacity: 1,
		  		},
		  		stroke: {
		  			width: 2,
		  			lineCap: "round",
		  			curve: "straight",
		  		},
		  		series: [{
		  			name: "Price",
		  			data: response.price
		  		}],
		  		tooltip: {
		  			theme: 'dark'
		  		},
		  		grid: {
		  			padding: {
		  				top: -20,
		  				right: 0,
		  				left: -4,
		  				bottom: -4
		  			},
		  			strokeDashArray: 4,
		  		},
		  		xaxis: {
		  			labels: {
		  				padding: 0,
		  			},
		  			tooltip: {
		  				enabled: false
		  			},
		  			type: 'datetime',
		  		},
		  		yaxis: {
		  			labels: {
		  				padding: 4
		  			},
		  		},
		  		labels: response.dates,
		  		colors: ['#F44336'],
		  		legend: {
		  			show: true,
		  			position: 'bottom',
		  			offsetY: 12,
		  			markers: {
		  				width: 10,
		  				height: 10,
		  				radius: 100,
		  			},
		  			itemMargin: {
		  				horizontal: 8,
		  				vertical: 8
		  			},
		  		},
		  	});
		  	priceChart.render();

		  	qtyChart = new ApexCharts(document.getElementById('chart-qty'), {
		  		title: {
				    text: "Quantity",
				    align: 'center',
				    margin: 0,
				    offsetX: 0,
				    offsetY: 0,
				    floating: false,
				    style: {
				      fontSize:  '18px',
				      fontWeight:  'bold',
				      fontFamily:  'Arial, sans-serif',
				      color:  '#263238'
				    },
				},
		  		chart: {
		  			type: "line",
		  			fontFamily: 'inherit',
		  			height: 200,
		  			parentHeightOffset: 0,
		  			toolbar: {
		  				show: false,
		  			},
		  			animations: {
		  				enabled: false
		  			},
		  			offsetX: 10,
		  		},
		  		fill: {
		  			opacity: 1,
		  		},
		  		stroke: {
		  			width: 2,
		  			lineCap: "round",
		  			curve: "straight",
		  		},
		  		series: [{
		  			name: "Quantity",
		  			data: response.qty
		  		}],
		  		tooltip: {
		  			theme: 'dark'
		  		},
		  		grid: {
		  			padding: {
		  				top: -20,
		  				right: 0,
		  				left: -4,
		  				bottom: -4
		  			},
		  			strokeDashArray: 4,
		  		},
		  		xaxis: {
		  			labels: {
		  				padding: 0,
		  			},
		  			tooltip: {
		  				enabled: false
		  			},
		  			type: 'datetime',
		  		},
		  		yaxis: {
		  			labels: {
		  				padding: 4
		  			},
		  			offsetX: 10,
		  		},
		  		labels: response.dates,
		  		colors: ['#F44336'],
		  		legend: {
		  			show: true,
		  			position: 'bottom',
		  			offsetY: 12,
		  			markers: {
		  				width: 10,
		  				height: 10,
		  				radius: 100,
		  			},
		  			itemMargin: {
		  				horizontal: 8,
		  				vertical: 8
		  			},
		  		},
		  	});
		  	qtyChart.render();

		  	salesChart = new ApexCharts(document.getElementById('chart-sales'), {
		  		title: {
				    text: "Sales",
				    align: 'center',
				    margin: 0,
				    offsetX: 0,
				    offsetY: 0,
				    floating: false,
				    style: {
				      fontSize:  '18px',
				      fontWeight:  'bold',
				      fontFamily:  'Arial, sans-serif',
				      color:  '#263238'
				    },
				},
		  		chart: {
		  			type: "line",
		  			fontFamily: 'inherit',
		  			height: 200,
		  			parentHeightOffset: 0,
		  			toolbar: {
		  				show: false,
		  			},
		  			animations: {
		  				enabled: false
		  			},
		  			offsetX: 10,
		  		},
		  		fill: {
		  			opacity: 1,
		  		},
		  		stroke: {
		  			width: 2,
		  			lineCap: "round",
		  			curve: "straight",
		  		},
		  		series: [{
		  			name: "Sales",
		  			data: response.sales
		  		}],
		  		tooltip: {
		  			theme: 'dark'
		  		},
		  		grid: {
		  			padding: {
		  				top: -20,
		  				right: 0,
		  				left: -4,
		  				bottom: -4
		  			},
		  			strokeDashArray: 4,
		  		},
		  		xaxis: {
		  			labels: {
		  				padding: 0,
		  			},
		  			tooltip: {
		  				enabled: false
		  			},
		  			type: 'datetime',
		  		},
		  		yaxis: {
		  			labels: {
		  				padding: 4
		  			},
		  			offsetX: 10,
		  		},
		  		labels: response.dates,
		  		colors: ['#F44336'],
		  		legend: {
		  			show: true,
		  			position: 'bottom',
		  			offsetY: 12,
		  			markers: {
		  				width: 10,
		  				height: 10,
		  				radius: 100,
		  			},
		  			itemMargin: {
		  				horizontal: 8,
		  				vertical: 8
		  			},
		  		},
		  	});
		  	salesChart.render();

		  	$('#anmodal-table tbody').empty();
			response.prodData.forEach(item => {
				var hasArrived = "N";
				if (item.rfqop_quantity == item.rfqop_arrived) {
					hasArrived = "Y";
				}
				$('#anmodal-table > tbody:last').append('<tr><td><a href="/rfqorderitem/' + item.rfqo_id + '" target="_blank">' + item.rfqo_date + '</a></td><td>' + item.rfqop_quantity + '</td><td>' + item.rfqop_price + '</td><td>' + item.sp_name + '</td><td>' + hasArrived + '</td></tr>');
			});

			var orderHigh = 0;
			var orderLow = 0;
			var orderAvg = 0;
			var avgCount = 0;
			for(var i = 0; i < response.prodData.length; i++){
				if (orderHigh == 0) {
					orderHigh = Number(response.prodData[i].rfqop_price);
				} else {
					if (orderHigh < Number(response.prodData[i].rfqop_price)) {
						orderHigh = Number(response.prodData[i].rfqop_price);
					}
				}
				if (orderLow == 0) {
					orderLow = Number(response.prodData[i].rfqop_price);
				} else {
					if (orderLow > Number(response.prodData[i].rfqop_price)) {
						orderLow = Number(response.prodData[i].rfqop_price);
					}
				}
				orderAvg += Number(response.prodData[i].rfqop_price);
				avgCount++;
			}
			orderAvg = orderAvg/avgCount;
			document.getElementById('orderHigh').innerHTML = "High: " + orderHigh;
			document.getElementById('orderLow').innerHTML = "Low: " + orderLow;
			document.getElementById('orderAvg').innerHTML = "Average: " + orderAvg.toFixed(2);

            var totOrdered = 0;
            response.prodData.forEach(item => {
                totOrdered += Number(item.rfqop_quantity);
            });
            document.getElementById('totOrdered').innerHTML = "Total QTY(Ordered to Date): " + totOrdered;
	      } else {
	        new_toast("danger","Error: "+response.error);
	      }
	      $("#loading").attr("style", "display: none !important");
	    },
	    error: function (restResponse) {
	      new_toast("danger","Error Occured");
	      $("#loading").attr("style", "display: none !important");
	    }
	  });
}

function  redrawTable(){
	$('#pr-table tbody').empty();

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

		if (item['invqty']==0) {
			$invcolour="bg-orange-lt";
		} else {
			$invcolour="bg-blue-lt";
		}
		if (item['magqty']==0) {
			$magcolour="bg-orange-lt";
		} else {
			$magcolour="bg-azure-lt";
		}

		if (item['orderqty']==0) {
			$ordcolour="<span class='skxbadge badge badge-outline text-lime'>-</span>";
		} else {
			$ordcolour="<span class='skxbadge badge badge-outline text-pink'>" + item['orderqty'] + "</span>";
		}

		var checkedString = "";
		if (item.checked) {
			checkedString = "checked";
		}

		$('#pr-table > tbody:last').append('<tr><td><input type="checkbox" onclick="handleSelectClick(this, ' + item['prodtype'] + ', ' +item['id'] + ')"' + checkedString + '></td><td><div style="border-left: 6px solid ' + color + '; padding-left: 6px; height: auto;">' + item['sku'] + '</div></td><td class="skxbolder">' + item['name'] + '<br><input type="text" onkeyup="SPkeyUp(event, ' + item['prodtype'] + ', ' +item['id'] + ')" class="comment form-control form-control-sm" value="' + suppliercomments + '"></td><td class="skxcenter">' + item['condition'] + '</td><td><span class="skxbadge badge bg-green-lt">' + item['quantity'] + '</span></td><td><span class="skxbadge badge badge-outline text-purple">' + item['price'].toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})  + '</span></td><td><span class="skxbadge badge '+$invcolour+'">' + item['invqty'] + '</span></td><td><span class="skxbadge badge ' + $magcolour + '">' + item['magqty'] + '</span></td><td>' + $ordcolour + '</td><td><button class="btn btn-sm btn-danger" type="button" onclick="removeItem(this, ' + item['prodtype'] + ', ' +item['id'] + ')">Remove</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class="btn btn-sm btn-warning" type="button" onclick="showAnalyze(this, ' + item['prodtype'] + ', ' +item['id'] + ')">Analyze</button></td></tr>');
		
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
		if (item['checked'] == true) {
			subtotal += (item['price'] * item['quantity']);
		}
	});
	$('#subtotal').html(subtotal.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
	var total = subtotal + labelfee + shipfee + bankfee + surcharge + credit + discount;
	$('#total').html(total.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
}
</script>
