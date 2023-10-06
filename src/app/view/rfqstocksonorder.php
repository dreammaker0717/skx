<style>
    .stid { font-weight: bolder;}
	.total_quantity, .total_arrived {
		display:block;
		width:100%;
		text-align:center;
	}
    .markdown>table, .table {
    --tblr-table-bg: transparent;
    --tblr-table-accent-bg: #fff;
    }
    .markdown>table>thead, .table>thead {
        background-color: #f4f6fa;
    }
</style>

<div class="page-body">
	<div class="container-fluid">
		<div class="col-lg-11" style="margin:0 auto;">
			<div class="card card-lg">
				<div class="card-body" style="padding:3rem 1rem;">
					<div class="row align-items-center">
						<div class="col-auto">
							<h2 style="margin-left:1rem;">Products on Order</h2>
						</div>
					</div>
					<div class="table-responsive" style="padding:10px;margin:10px;">
						<table id="dataList" class="table hover card-table table-vcenter text-nowrap datatable display nowrap">
							<thead>
								<tr>                                 
									<th>Order ID</th>
									<th>Product</th>
									<th>Description</th>
									<th>Qty on Order</th>
									<th>Date of Order</th>
									<th>Price</th>
									<th>Supplier</th>
								</tr>
							</thead>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<form id="astock_items_form" name="astock_items_form" action="" method="post" style="display:none">
    <input type="hidden" id="astock_items" name="astock_items"/> 
</form>

<script>
	var tempid = 1;
	$(function() {
		window.ETable = $('#dataList').dataTable({
			"lengthChange": false,
			"processing":true,
			"serverSide":true,
			"infoEmpty": "No records available",
			"sProcessing": "DataTables is currently busy",
			"aLengthMenu": [[5, 15, 25, 50,100], [5, 15, 50,100]],
			"iDisplayLength": 25,
			"search": { "search" : new URLSearchParams(window.location.search).get("search")},

			"ajax":{
				url:"/rfqstocksonorderajax",
				type:"POST",
				data: { action:'search'},
				dataType:"json"
			},

            "rowGroup": {
            	"dataSrc": "rfqo_id",
	            startRender: function(rows, group) {
					var isPaid = null;
					if (rows.data()[0].rfqo_payment == 0) {
						var isPaid = "Unpaid";
					} else {
						var isPaid = "Paid";
					}
	                return $('<tr class="group group-start"><td colspan="5" style="background-color: #f8f7ff; font-size: 1rem !important; font-weight: bold;">' + "RFQ ID: " + group + '</td><td colspan="1" style="background-color: #f8f7ff; font-size: 0.8rem !important; font-weight: bold;">' + "Payment Status: " + isPaid + '</td></tr>');
	            }
            },

			"columns" :[
				{"data" : "rfqo_id", "searchable":false, "orderable":false},
				{"data" : "sku", "orderable":false, "render": function( data, type, row, meta ){
	                var color = null;
	                switch (parseInt(row.rfqop_prodtype)) {
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
	                return "<div style='border-left: 6px solid " + color + "; padding-left: 6px; height: auto;'>" + data + "</div>";
	            }},
	            {"data" : "name", "orderable":false, "render": function( dat, type, row, meta ){
	            	if (row.rfqop_suppliercomments != "") {
	            		return "<span style='font-weight:500;'>"+ dat + "</span><br><p style='color:grey; margin-bottom: 0px!important;'>" + row.rfqop_suppliercomments + "</p>";
	            	} else {
	            		if (row.suppliercomments != "") {
	            			return "<span style='font-weight:500;'>"+ dat + "</span><br><p style='color:grey; margin-bottom: 0px!important;'>" + row.suppliercomments + "</p>";
	            		} else {
	            			return "<span style='font-weight:500;'>"+ dat + "</span>";
	            		}
	            	}
	                
	            }},
				{"data" : "rfqop_quantity", "searchable":false, "orderable":false},
				{"data" : "rfqo_date", "searchable":false, "orderable":false},
				{"data" : "rfqop_price", "searchable":false, "orderable":false},
				{"data" : "sp_name", "searchable":false, "orderable":false},
			],

            "columnDefs": [
                {
                    "targets": [0],
                    "visible": false,
                    "searchable": false
                }
            ]
		});
	});
</script>