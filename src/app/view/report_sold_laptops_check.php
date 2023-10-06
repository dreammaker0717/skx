<div class="page-body">
    <div class="container-fluid">
		<div class="col-lg-11" style="margin:0 auto;">
			<div class="card card-lg">
				<div class="card-body" style="padding:20px;margin:20px;">
					<div class="row align-items-center">
						<div class="col-auto">
							<h2>Sold Laptops Check</h2>
						</div>
					</div>
					<div class="form-group row align-items-end">
						<div class="col-md-8">
							<label for="formTextarea" class="form-label">Please enter comma separated Stock ID's</label>
							<textarea class="form-control" aria-label="formTextarea" id="formTextarea"></textarea>
						</div>
						<div class="col-auto ml-2">
							<button class="btn btn-primary align-bottom" onClick="loadLaptopsCheck()">Submit</button>
						</div>
					</div>
                    <div class="table-responsive mt-2">
						<table id="dataList" class="table stripe card-table table-vcenter hover text-nowrap datatable table-sm">
							<thead>
								<tr>
									<th>ID</th>
									<th>Order Number</th>
									<th>Sales Channel</th>
									<th>Servicetag</th>
									<th>Name</th>
									<th>Last Comment</th>
									<th>Cost</th>
									<th>Sold Price</th>
									<th>Allocated To</th>
									<th>Country</th>
									<th>Sold Date</th>
								</tr>
							</thead>
						</table>
                    </div>
                </div>
            </div>
            <div class="card card-lg mt-2" id="notFoundCard" style="display: none;">
				<div class="card-body" style="padding:20px;margin:20px;">
					<div class="row">
						<h2>Stock IDs not found(Or Not Sold):</h2>
					</div>
					<h4 id="notFoundList"></h4>
				</div>
			</div>
        </div>
    </div>
</div>

<script type="text/javascript">
var stockList = null;
$(function() {
    window.ETable = $('#dataList').dataTable({
        "lengthChange": true,
        "processing":true,
        "serverSide":true,
        "infoEmpty": "No records available",
        "sProcessing": "DataTables is currently busy",
        "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50,100, 'All']],
        "deferLoading": 0,
        "iDisplayLength": 50,
        "buttons": [
            {
                extend: 'csvHtml5',
                exportOptions: { orthogonal: 'export',
                    modifier: {
                    order: 'current',
                    page: 'all',
                    selected: null,
                } }
            },
            {
                extend: 'excelHtml5',
                exportOptions: { orthogonal: 'export',
                    modifier: {
                    order: 'current',
                    page: 'all',
                    selected: null,
                } }
            }
        ],
        "dom": '<<<lB>f>rt<ip>>',
        "order":[],
        "search": { "search" : new URLSearchParams(window.location.search).get("search")},
        "ajax":{
            url:"/soldlaptopreportajax",
            type:"POST",
            data: function(d){d.action = 'sold_laptops_check'; d.stocklist = stockList;},
            dataType:"json"
        },
        "drawCallback": function(settings){
	        if(isSubmitted){
				$.ajax({ 
			        url:"/soldlaptopreportajax",
			        data: {action: 'stockid_exists_check', stocklist: stockList},
			        type:'POST', 
			        success:function(a) {
				        a = JSON.parse(a);
				        if(a.success) {
				        	if (a.notFoundList.length > 0) {
				        		var notFoundText = "";
					        	a.notFoundList.forEach(item => {
					        		notFoundText += (item + ", ");
					        	});
					        	document.getElementById("notFoundCard").style.display = "block";
					            document.getElementById("notFoundList").innerHTML = notFoundText.replace(/,\s*$/, "");
					        } else {
					        	document.getElementById("notFoundCard").style.display = "none";
					        }
				        }
				        else 
				            new_toast("danger","Error! Reason is "+a.error); 
			        }
			    });
			}
	    },
        "columns" :[          
 			{"data" : "st_id", "searchable":false, "orderable":false},
 			{"data" : "order_number", "searchable":false, "orderable":false},
 			{"data" : "sales_channel", "searchable":false, "orderable":false},
 			{"data" : "st_servicetag", "searchable":false, "orderable":false},
 			{"data" : "pr_name", "searchable":false, "orderable":false},
 			{"data" : "st_lastcomment", "searchable":false, "orderable":false},
 			{"data" : "st_cost", "searchable":false, "orderable":false},
 			{"data" : "st_soldprice", "searchable":false, "orderable":false},
 			{"data" : "st_allocatedto", "searchable":false, "orderable":false},
 			{"data" : "country", "searchable":false, "orderable":false},
 			{"data" : "st_solddate", "searchable":false, "orderable":false},
        ],
    });
});

var isSubmitted = false;
function loadLaptopsCheck(){
	stockList = document.getElementById("formTextarea").value.replace(/\s/g, '');
	if(stockList == ""){
	    new_toast("danger","Please enter comma separated Stock ID's");
	    return;
	}
	isSubmitted = true;
	window.ETable.fnDraw();
}
</script>