<?php
$db = M::db();
include PATH_CONFIG . "/constants.php";
?>

<div class="page-body">
    <div class="container-fluid">
		<div class="col-lg-11" style="margin:0 auto;">
			<div class="card card-lg">
				<div class="card-body" style="padding:3rem 1rem;">

					<div class="row align-items-center">
						<div class="col-auto">
							<h2 style="margin-left:1rem;">Outstanding Returns</h2>
						</div>
                    </div>
					<div class="table-responsive" style="padding:10px;margin:10px;">
                        <div class="col-sm-2" style="height: 25px;"></div>
                        <table id="dataList" class="table card-table table-vcenter hover text-nowrap datatable">
                            <thead>
                                <tr>
                                    <th>Supplier</th>
                                    <th>No. of Items to Return</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$(function() {
    window.ETable = $('#dataList').dataTable({
        "lengthChange": true,
        "processing":true,
        "serverSide":true,
        "bFilter": false,
        "infoEmpty": "No records available",
        "sProcessing": "DataTables is currently busy",
        "aLengthMenu": [[5, 25, 50,100,200], [5, 25, 50,100,200]],
        "iDisplayLength": 25,
        "dom": '<<<lB>f>rt<ip>>',
        "buttons" : [],
        "ajax":{
            url:"/supplierrmacajax",
            type:"POST",
            data: {action: 'search'},
            dataType:"json"
        },
        
        "columns" :[
            {"data" : "sp_name", "searchable":false, "render":function(dat, type, row){
                return "<a href='/supplierrmacitem/"+row.sp_id+"'>"+dat+"</a>";
            }},
            {"data" : "item_count", "searchable":false, "sortable":false,},
        ],
    });
});
</script>