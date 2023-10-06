<?php
if (isset($id)) {
    $id = intval($id);
}
if (!isset($in)) {
    $in = false;
}

$db = M::db();
include PATH_CONFIG . "/constants.php";
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
          $ord = $db->get("acc_orders", "*", ["aor_id" => $id]);
      } ?>



						<div class="mb-3">

									<form id="formdata">
										<div class="row">
											<?php if (isset($ord)) { ?>
											<div class="col-auto">
												<label class="mr-sm-2">State</label>
												<h3 style="padding:0.3rem 0"><?php if (isset($ord)) {
                echo $ord["aor_state"];
            } ?></h3>
											</div>
											<?php } ?>
											<div class="col-auto">
												<label class="mr-sm-2">Order Date</label>
												<h3 style="padding:0.3rem 0"><?php if (isset($ord)) {
                echo $ord["aor_date"];
            } ?></h3>
											</div>
											<div class="col-auto">
												<label class="mr-sm-2">Supplier</label>
												<h3 style="padding:0.3rem 0"><?php
             $spp = $db
                 ->query(
                     "select sp_name from suppliers where sp_id = " . $ord["aor_supplier"]
                 )
                 ->fetchAll();
             echo $spp[0]["sp_name"];?></h3>
											</div>

										</div>
									</form>




						</div>
					</fieldset>
<fieldset class="form-fieldset" style="width:90%; margin:20px auto;">
						<div class="col-md-12">
							<div class="card">
								<div class="card-body">
									<div class="table-responsive">
										<table class='table table-vcenter card-table pr-table'>
											<thead>
												<tr>
													<th style="width:10%;">ID</th>
													<th style="width:10%;">Name</th>
													<th style="width:5%;">Quantity</th>
													<th style="width:10%;">Stock</th>
													<th style="width:15%;">Status</th>
                                                    <th style="width:15%;">SKU</th>
													<th style="width:7%;">Cost Price</th>
                                                    <th style="width:7%;">Sale Net</th>
                                                    <th style="width:7%;">Vat</th>
                                                    <th style="width:7%;">Gross</th>
                                                    <th style="width:7%;">Vat Type</th>
												</tr>
											</thead>
											<tbody>
												<?php if (isset($ord)) {
                                                    $query = "SELECT apr_id,apr_name,apr_sku, count(ast_id) as quantity FROM aproducts left join acc_stock on ast_product=apr_id AND ast_order=$id WHERE ast_order=$id GROUP BY apr_id";
                                                    $data = $db->query($query)->fetchAll();
                                                    foreach ($data as $k => $v) {
                                                        echo "<tr><td>" .
                                                            $v["apr_id"] .
                                                            "</td><td>" .
                                                            $v["apr_name"] .
                                                            "</td><td>" .
                                                            $v["quantity"] .
                                                            "</td>";
                                                    echo "<td>";

                                                    if ($v["apr_id"] != null) {
                                                    $inputed = $db
                                                            ->query(
                                                                "SELECT * FROM acc_stock WHERE ast_product=" .
                                                                    $v["apr_id"] .
                                                                    " AND ast_order=" .
                                                                    $id
                                                            )
                                                            ->fetchAll();
                                                    }

                                                    if ($v["apr_id"] != null) {
                                                        if ($inputed != null) {
                                                            foreach ($inputed as $it) {
                                                                echo "<div><a href='../accstock/" .
                                                                    $it["ast_id"] .
                                                                    "'>" . $it["ast_id"] . 
                                                                    "</div>";
                                                            }
                                                        }
                                                    }
                                                    echo "</td>";
                                                    echo "<td>";

                                                    if ($v["apr_id"] != null) {
                                                        if ($inputed != null) {
                                                            foreach ($inputed as $it) {
                                                                echo "<div>" . $_ACSTATUSES[$it["ast_status"]]["Name"] . "</div>";
                                                            }
                                                        }
                                                    }
                                                    echo "</td>";
                                                    echo "<td>";

                                                    echo $v["apr_sku"];

                                                    echo "</td>";
                                                    echo "<td>";

                                                    if ($v["apr_id"] != null) {
                                                        if ($inputed != null) {
                                                            foreach ($inputed as $it) {
                                                                echo "<div>" .
                                                                    $it["ast_cost"] .
                                                                    "</div>";
                                                            }
                                                        }
                                                    }
                                                    echo "</td>";

                                                    echo "<td>";
                                                    if ($v["apr_id"] != null) {
                                                        if ($inputed != null) {
                                                            foreach ($inputed as $it) {
                                                                $requesteditem = $db->query("SELECT soldprice, sales_orderitems_id FROM sales_orderserials WHERE serial_number='" . $it["ast_servicetag"] . "'")->fetchAll();
                                                                if ($requesteditem != null) {
                                                                    $requestedproduct = $db->query("SELECT sales_order_id FROM sales_orderitems WHERE id=".$requesteditem[0]['sales_orderitems_id'])->fetchAll();
                                                                    if ($requestedproduct != null) {
                                                                        $laptoporder = $db->query("SELECT country FROM sales_orders WHERE id=".$requestedproduct[0]['sales_order_id'])->fetchAll();
                                                                        if ($laptoporder != null) {
                                                                        if ($laptoporder[0]['country'] == "GB") {
                                                                                if ($it['ast_vat_type'] == "Standard" || $it['ast_vat_type'] == "Import") {
                                                                                    $vat = $requesteditem[0]['soldprice']-$requesteditem[0]['soldprice']/(1+($it['ast_vat_rate']/100));
                                                                                    $net = $requesteditem[0]['soldprice'] - $vat;
                                                                                } else if($it['ast_vat_type'] == "Margin"){
                                                                                    $vat = ($requesteditem[0]['soldprice']-$it['ast_cost'])/6;
                                                                                    $net = $requesteditem[0]['soldprice'] - $vat;
                                                                                } else {
                                                                                    $vat = 0.00;
                                                                                    $net = $requesteditem[0]['soldprice'];
                                                                                }
                                                                                echo "<div>" . $net . "</div>";
                                                                            } else{
                                                                                echo "<div>" . $requesteditem[0]['soldprice'] . "</div>";
                                                                            }
                                                                        } else{
                                                                            echo "<div>" . $requesteditem[0]['soldprice'] . "</div>";
                                                                        }
                                                                    } else{
                                                                        echo "<div>0.00</div>";
                                                                    }
                                                                } else{
                                                                    echo "<div>0.00</div>";
                                                                }
                                                            }
                                                        }
                                                    }
                                                    echo "</td>";

                                                    echo "<td>";
                                                    if ($v["apr_id"] != null) {
                                                        if ($inputed != null) {
                                                            foreach ($inputed as $it) {
                                                                $requesteditem = $db->query("SELECT soldprice, sales_orderitems_id FROM sales_orderserials WHERE serial_number='" . $it["ast_servicetag"] . "'")->fetchAll();
                                                                if ($requesteditem != null) {
                                                                    $requestedproduct = $db->query("SELECT sales_order_id FROM sales_orderitems WHERE id=".$requesteditem[0]['sales_orderitems_id'])->fetchAll();
                                                                    if ($requestedproduct != null) {
                                                                        $laptoporder = $db->query("SELECT country FROM sales_orders WHERE id=".$requestedproduct[0]['sales_order_id'])->fetchAll();
                                                                        if ($laptoporder != null) {
                                                                        if ($laptoporder[0]['country'] == "GB") {
                                                                                if ($it['ast_vat_type'] == "Standard" || $it['ast_vat_type'] == "Import") {
                                                                                    $vat = $requesteditem[0]['soldprice']-$requesteditem[0]['soldprice']/(1+($it['ast_vat_rate']/100));
                                                                                } else if($it['ast_vat_type'] == "Margin"){
                                                                                    $vat = ($requesteditem[0]['soldprice']-$it['ast_cost'])/6;
                                                                                } else {
                                                                                    $vat = 0.00;
                                                                                }
                                                                                echo "<div>" . $vat . "</div>";
                                                                            } else{
                                                                                echo "<div>0.00</div>";
                                                                            }
                                                                        } else {
                                                                            echo "<div>0.00</div>";
                                                                        }
                                                                    } else {
                                                                        echo "<div>0.00</div>";
                                                                    }
                                                                } else{
                                                                    echo "<div>0.00</div>";
                                                                }
                                                            }
                                                        }
                                                    }
                                                    echo "</td>";

                                                    echo "<td>";
                                                    if ($v["apr_id"] != null) {
                                                        if ($inputed != null) {
                                                            foreach ($inputed as $it) {
                                                                $requesteditem = $db->query("SELECT soldprice, sales_orderitems_id FROM sales_orderserials WHERE serial_number='" . $it["ast_servicetag"] . "'")->fetchAll();
                                                                if ($requesteditem != null) {
                                                                    $requestedproduct = $db->query("SELECT sales_order_id FROM sales_orderitems WHERE id=".$requesteditem[0]['sales_orderitems_id'])->fetchAll();
                                                                    if ($requestedproduct != null) {
                                                                        $laptoporder = $db->query("SELECT country FROM sales_orders WHERE id=".$requestedproduct[0]['sales_order_id'])->fetchAll();
                                                                        if ($laptoporder != null) {
                                                                        if ($laptoporder[0]['country'] == "GB") {
                                                                                echo "<div>" . $requesteditem[0]['soldprice'] . "</div>";
                                                                            } else{
                                                                                echo "<div>" . $requesteditem[0]['soldprice'] . "</div>";
                                                                            }
                                                                        } else {
                                                                            echo "<div>0.00</div>";
                                                                        }
                                                                    } else {
                                                                        echo "<div>0.00</div>";
                                                                    }
                                                                } else{
                                                                    echo "<div>0.00</div>";
                                                                }
                                                            }
                                                        }
                                                    }
                                                    echo "</td>";

                                                    echo "<td>";
                                                    if ($v["apr_id"] != null) {
                                                        if ($inputed != null) {
                                                            foreach ($inputed as $it) {
                                                                $requesteditem = $db->query("SELECT soldprice, sales_orderitems_id FROM sales_orderserials WHERE serial_number='" . $it["ast_servicetag"] . "'")->fetchAll();
                                                                if ($requesteditem != null) {
                                                                    $requestedproduct = $db->query("SELECT sales_order_id FROM sales_orderitems WHERE id=".$requesteditem[0]['sales_orderitems_id'])->fetchAll();
                                                                    if ($requestedproduct != null) {
                                                                        $laptoporder = $db->query("SELECT country FROM sales_orders WHERE id=".$requestedproduct[0]['sales_order_id'])->fetchAll();
                                                                        if ($laptoporder != null) {
                                                                        if ($laptoporder[0]['country'] == "GB") {
                                                                                if ($it['ast_vat_type'] == "Standard" || $it['ast_vat_type'] == "Import") {
                                                                                    $vattype = "Standard";
                                                                                } else if($it['ast_vat_type'] == "Margin"){
                                                                                    $vattype = "Margin";
                                                                                } else {
                                                                                    $vattype = "Not Set";
                                                                                }
                                                                                echo "<div>" . $vattype . "</div>";
                                                                            } else{
                                                                                echo "<div>Not Set</div>";
                                                                            }
                                                                        } else {
                                                                            echo "<div>Not Set</div>";
                                                                        }
                                                                    } else {
                                                                        echo "<div>Not Set</div>";
                                                                    }
                                                                } else{
                                                                    echo "<div>Not Set</div>";
                                                                }
                                                            }
                                                        }
                                                    }
                                                    echo "</td>";
                                                    
                                                    echo "</tr>";
                                                    }
                                                } ?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
                            <div class="card" style="margin-top:1rem;">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                        <table class='table table-vcenter card-table' id="rt-table">
                                            <tbody>
                                                <?php if (isset($ord)) {
                                                    $query = "SELECT apr_id,apr_name, count(ast_id) as quantity FROM aproducts left join acc_stock on ast_product=apr_id AND ast_order=$id WHERE ast_order=$id GROUP BY apr_id";
                                                    $data = $db->query($query)->fetchAll();
                                                    $costTotal = 0.0;
                                                    foreach ($data as $k => $v) {
                                                        $inputed = $db
                                                            ->query(
                                                                "SELECT * FROM acc_stock WHERE ast_product=" .
                                                                    $v["apr_id"] .
                                                                    " AND ast_order=" .
                                                                    $id
                                                            )
                                                            ->fetchAll();
                                                        if ($inputed != null) {
                                                            foreach ($inputed as $it) {
                                                                $costTotal += $it["ast_cost"];
                                                            }
                                                        }
                                                    }
                                                    echo "<tr><td style=\"width:50%;\"></td><td style=\"width:15%;\"><span style=\"font-weight:600;\">Total: </span></td><td style=\"width:7%;\">" . $costTotal . "</td><td style=\"width:28%;\"></td></tr>";
                                                }
                                                ?>
                                            </tbody>
                                </table>
                            </div>
                </div>
						</div>
					</fieldset>
				</div>
			</div>
		</div>
	</div>