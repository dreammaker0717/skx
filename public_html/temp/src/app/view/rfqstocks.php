<?php
$db = M::db();
include PATH_CONFIG . "/constants.php";
?>
<style>
	.apoqty {width:50px;}
</style>


<div class="page-body">
    <div class="container-fluid">
		<div class="col-lg-11" style="margin:0 auto;">
			<div class="card card-lg">
				<div class="card-body" style="padding:3rem 1rem;">

					<div class="row align-items-center">
						<div class="col-auto">
							<h2 style="margin-left:1rem;">All Products</h2>
						</div>
                    </div>
                        <label for="category"  class="col-sm-1 col-form-label">Category</label>
                        <div class="col-sm-2">
                            <select onchange="window.ETable.fnDraw()" class="form-control" id="category" name="category">
                                <option value="">All</option>
                                <<?php 

                                $cat = $db->query("SELECT ct_id, ct_name FROM categories")->fetchAll();
                                foreach ($cat as $item) {
                                    echo "<option value=" . $item['ct_id'] . ">" . $item['ct_name'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
					<div class="table-responsive" style="padding:10px;margin:10px;">
                        <div class="col-sm-2" style="height: 25px;"></div>
                        <table id="dataList" class="table card-table table-vcenter hover text-nowrap datatable">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>ID</th>
                                    <th>SKU</th>
                                    <th>NAME</th>
                                    <th style="text-align:center;">SKX<br>QTY</th>
                                    <th style="text-align:center;">MAGENTO<br>QTY</th>
                                    <th style="text-align:center;">Δ</th>
                                    <th style="text-align:center;">ON<br>ORDER</th>
                                    <th style="text-align:center;">LOW<br>STOCK</th>
                                    <th style="text-align:center;">LAST<br>QTY</th>
                                    <th style="text-align:center;">LAST<br>PRICE</th>
                                    <th style="text-align:center;">ORDER<br>DATE</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="col-auto d-none d-md-flex">
                        <a href="javascript:createNewRFQ()" class="btn btn-primary">
                            <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                            Create New RFQ
                        </a>
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

        buttons: [
           // 'copy', 'csv', 'excel', 'pdf', 'print'
           {
                extend: 'copyHtml5',
                exportOptions: { orthogonal: 'export',
                    modifier: {
                    order: 'current',
                    page: 'all',
                    selected: null,
                } }
            },
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
            },
            {
                extend: 'pdfHtml5',
                exportOptions: { orthogonal: 'export',
                    modifier: {
                    order: 'current',
                    page: 'all',
                    selected: null,
                } }
            }
        ],

        "infoEmpty": "No records available",
        "sProcessing": "DataTables is currently busy",
        "aLengthMenu": [[5, 25, 50,100], [5, 25, 50,100]],
        "iDisplayLength": 25,
        "order":[],
        "dom": '<<<lB>f>rt<ip>>',
        "search": { "search" : new URLSearchParams(window.location.search).get("search")},
        "ajax":{
            url:"/rfqstocksajax",
            type:"POST",
            data: function (d) {d.category = $('#category').val(); d.action = 'search';},
            dataType:"json"
        },
        "columns" :[
            {"data" : null, "searchable":false, "orderable":false, "render": function ( data, type, row, meta ) { return "<input type='checkbox' onclick='handleSelectClick(this, "+ row.prodtype +", "+ row.productid +")'>" }},
            {"data" : "id", "searchable":false,},
            {"data" : "sku", "render": function( data, type, row, meta ){
                var color = null;
                switch (row.prodtype) {
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
            {"data" : "name", "render": function( data, type, row, meta ){
                return data + "<br><p style='color:grey; margin-bottom: 0px!important;'>" + row.suppliercomments + "</p>";
            }},
            {"data" : "invqty", "searchable":false, "render": function ( data, type, row, meta ) {
                if (row.invqty <=0) {
                    return "<span class='apoqty badge bg-orange-lt'>" + row.invqty + "</span>";
                } else {
                    return "<span class='apoqty badge bg-azure-lt'>" + row.invqty + "</span>";
                }
	}},
            {"data" : "magqty", "searchable":false, "render": function ( data, type, row, meta ) {
                if (row.magqty <=0) {
                    return "<span class='apoqty badge bg-orange-lt'>" + row.magqty + "</span>";
                } else {
                    return "<span class='apoqty badge bg-azure-lt'>" + row.magqty + "</span>";
                }
	}},

            {"data" : null, "searchable":false, "orderable":false, "render": function ( data, type, row, meta ) {
                if (row.invqty != "*") {
                    
	if (row.invqty - row.magqty==0) {
		return "<span class='apoqty badge bg-lime-lt'>0</span>";
	} else if (row.invqty - row.magqty>0) {
		return "<span class='apoqty badge bg-red-lt'>" + (row.invqty - row.magqty) + "</span>";
	} else {
		return "<span class='apoqty badge bg-red'>" + (row.invqty - row.magqty) + "</span>";
	}
	

                } else {
                    return "*";
                }
            }},
            {"data" : "arrived", "searchable":false, "orderable":false, "render": function ( data, type, row, meta ) {
                if (row.status == "On Order" || row.status == "Part Arrived") {
 			return "<span class='apoqty badge badge-outline text-pink'>" + (row.ordqty - data) + "</span>";
                } else {
                    return "<span class='apoqty badge badge-outline text-lime'>-</span>";                }
            }},
            {"data" : "lowstock", "searchable":false, "render": function ( data, type, row, meta ) {
                return "<input type=hidden  name='hlow_" + row.id + "'  iden='s" + row.id + "' class='hcomment form-control form-control-sm' value='"+ data +"'><input type=text  name='low_" + row.id + "' onkeyup=\'LowkeyUp(event, " + row.prodtype +", "+ row.productid +")' iden='s" + row.id + "' class='comment form-control form-control-sm' value='"+ data +"'>";
            }},
            {"data" : "ordqty", "searchable":false},
            {"data" : "ordprice", "searchable":false},
            {"data" : "orddate", "searchable":false},
        ],


    });
});

function LowkeyUp(ev, prodtype, prodid){
    console.log("Event here ",ev);
    if(ev.code=="Enter") {
        data = {value: ev.target.value, prodtype: prodtype, id: prodid};
        var pd = { action: "update_low", data: data};
            $.ajax({
            url:"/rfqstocksajax",
            data:pd,
            type:'POST',
            success:function(a) {
            a = JSON.parse(a);
            if(a.success) {
                new_toast("success","Success.");
            }
            else
                new_toast("danger","Error! Reason is "+a.error);
            window.ETable.fnDraw();

            }
        });
    }
}


var listData = [];
function handleSelectClick(e, type, id){
    if (e.checked) {
        listData.push({prodtype: type, id: id});
    } else {
        listData = listData.filter(function( obj ) {
            return obj.prodtype != type || obj.id != id;
        });
    }
}

function createNewRFQ(){
    if (listData.length != 0) {
        sessionStorage.setItem('listData', JSON.stringify(listData));
        location.href = "/newitemrfq";
    } else {
        new_toast("warning","Please select a product!");
    }
}
</script>