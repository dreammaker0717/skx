<?php
$db = M::db();
include PATH_CONFIG . "/constants.php";
?>
<style>
	.apoqty {width:50px;}
    #anmodal-table tbody {display: block;height: 440px;overflow-y: scroll;}
    #anmodal-table thead, #anmodal-table tbody tr {display: table;width: 100%;table-layout: fixed;}
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
                    <div class="row justify-content-between">
                        <div class="col-auto">
                            <label for="category"  class="col-md-12 col-form-label">Category</label>
                            <div class="col-md-12">
                                <select onchange="window.ETable.fnDraw()" class="form-control" id="category" name="category">
                                    <option value="">All</option>
                                    <?php
                                    $cat = $db->query("SELECT categories.ct_id, categories.ct_name FROM all_products LEFT JOIN categories ON all_products.category = categories.ct_id WHERE categories.ct_id IS NOT NULL GROUP BY all_products.category ORDER BY categories.ct_name ASC")->fetchAll();
                                    foreach ($cat as $item) {
                                        echo "<option value=" . $item['ct_id'] . ">" . $item['ct_name'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-auto">
                            <button class="btn btn-primary" onclick="syncProducts()">Sync</button>
                        </div>
                    </div>
					<div class="table-responsive" style="padding:10px;margin:10px;">
                        <div class="col-sm-2" style="height: 25px;"></div>
                        <table id="dataList" class="table card-table table-vcenter hover text-nowrap datatable">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>ID</th>
                                    <th>Prod.<br>ID</th>
                                    <th>SKU</th>
                                    <th>NAME</th>
                                    <th style="text-align:center;">In<br>Progress<br>QTY</th>
                                    <th style="text-align:center;">Sent<br>to FBA<br>QTY</th>
                                    <th style="text-align:center;">Sold<br>QTY</th>
                                    <th style="text-align:center;">SKX<br>QTY</th>
                                    <th style="text-align:center;">MAGENTO<br>QTY</th>
                                    <th style="text-align:center;">Δ</th>
                                    <th style="text-align:center;">ON<br>ORDER</th>
                                    <th style="text-align:center;">LOW<br>STOCK</th>
                                    <th style="text-align:center;">LAST<br>QTY</th>
                                    <th style="text-align:center;">LAST<br>PRICE</th>
                                    <th style="text-align:center;">MAGENTO<br>PRICE</th>
                                    <th style="text-align:center;">AGE</th>
                                    <th style="text-align:center;">ACTION</th>
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
<?php
$filter = null;
if (isset($option)) {
    $filter = $option;
}
echo "var filter = \"" . $filter ."\";\r\n" 
?>
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
        "aLengthMenu": [[5, 25, 50,100,200], [5, 25, 50,100,200]],
        "iDisplayLength": 25,
        "order":[],
        "dom": '<<<lB><"searchholder">>rt<ip>>',
        "ajax":{
            url:"/rfqstocksajax",
            type:"POST",
            data: function (d) {d.category = $('#category').val(); d.filter = filter, d.skuSearch= $('#skuSearch').val(), d.nameSearch= $('#nameSearch').val(), d.action = 'search';},
            dataType:"json"
        },
        "columns" :[
            {"data" : null, "searchable":false, "orderable":false, "render": function ( data, type, row, meta ) { return "<input type='checkbox' onclick='handleSelectClick(this, "+ row.prodtype +", "+ row.productid +")'>" }},
            {"data" : "id", "searchable":false,},
            {"data" : "productid", "searchable":false,},
            {"data" : "sku", "render": function( data, type, row, meta ){
                var color = null;
                var title = null;
                switch (parseInt(row.prodtype)) {
                    case 1:
                        color = "green";
                        title = "NWP Products";
                        break;
                    case 2:
                        color = "blue";
                        title = "NWP2 Products";
                        break;
                    case 3:
                        color = "red";
                        title = "Accessories Products";
                        break;
                    case 4:
                        color = "magenta";
                        title = "Dell Part";
                        break;
                    default:
                        color = "";
                        title = "";
                        break;
                }
                return "<div class='container' style='padding-left: 0px!important;padding-right: 0px!important;'><div style='display: -webkit-flex; display: flex; -webkit-flex-direction: row; flex-direction: row;'><div title='" + title + "' style='border-left: 6px solid " + color + "; padding-left: 6px; height: 1rem;'></div><div>" + data + "</div></div></div>";
            }},
            {"data" : "name", "render": function( data, type, row, meta ){
                return "<span style='font-weight:500;'>"+data + "</span><br><p style='color:grey; margin-bottom: 0px!important;'>" + row.suppliercomments + "</p>";
            }},
            {"data" : "inprogqty", "searchable":false, "render": function ( data, type, row, meta ) {
                return "<span class='apoqty badge bg-dark-lt disabled'>" + data + "</span>";
            }},
            {"data" : "fbaqty", "searchable":false, "render": function ( data, type, row, meta ) {
                return "<span class='apoqty badge bg-green-lt disabled'>" + data + "</span>";
            }},
            {"data" : "soldqty", "searchable":false, "render": function ( data, type, row, meta ) {
                return "<span class='apoqty badge bg-green-lt disabled'>" + data + "</span>";
            }},
            {"data" : "invqty", "searchable":false, "render": function ( data, type, row, meta ) {
		if (parseInt(row.prodtype) == 2) {
                    return "<span class='apoqty badge badge-outline text-lime'>-</span>";
               } else if (parseInt(data) <=0) {
                    return "<span class='apoqty badge bg-red-lt'>" + data + "</span>";
                } else {
                    return "<span class='apoqty badge bg-azure-lt'>" + data + "</span>";
                }
            }},
            {"data" : "magqty", "searchable":false, "render": function ( data, type, row, meta ) {
                if (parseInt(row.magqty) <=0) {
                    return "<span class='apoqty badge bg-orange-lt'>" + row.magqty + "</span>";
                } else {
                    return "<span class='apoqty badge bg-azure-lt'>" + row.magqty + "</span>";
                }
            }},

            {"data" : "delta", "searchable":false, "render": function ( data, type, row, meta ) {   
		if (parseInt(row.prodtype) == 2) {
                    return "<span class='apoqty badge badge-outline text-light'>-</span>";

                } else if (data > 0) {
                    return "<span class='apoqty badge bg-red-lt'>" + data + "</span>";
                } else if (data == 0) {
                    return "<span class='apoqty badge badge-outline text-light'>-</span>";
                }  else {
                    return "<span class='apoqty badge bg-red'>" + data + "</span>";
                }

            }},
            {"data" : "arrived", "searchable":false, "orderable":false, "render": function ( data, type, row, meta ) {
                if (row.status != "") {
                    if (row.status == "On Order" || row.status == "Part Arrived") {
$onorder=parseInt(row.ordqty) - parseInt(data);
if ($onorder==0){
                        return "<span class='apoqty badge badge-outline text-light'>-</span>";
} else {
                        return "<span class='apoqty badge badge-outline text-orange'>" + $onorder + "</span>";
}
                    } else {
                        return "<span class='apoqty badge badge-outline text-light'>-</span>";
                    }
                } else {
                    return "<span class='apoqty badge badge-outline text-lime'>-</span>";
                }
            }},
            {"data" : "lowstock", "searchable":false, "render": function ( data, type, row, meta ) {
                return "<input type=hidden  name='hlow_" + row.id + "'  iden='s" + row.id + "' class='hcomment form-control form-control-sm' value='"+ data +"'><input type=text  name='low_" + row.id + "' onkeyup=\'LowkeyUp(event, " + row.prodtype +", "+ row.productid +")' iden='s" + row.id + "' class='comment form-control form-control-sm' value='"+ data +"'>";
            }},
            {"data" : "lastqty", "searchable":false, "render": function ( data, type, row, meta ) {
                if (parseInt(data) != 0) {
                    return "<span class='apoqty badge badge-outline text-yellow'>" + data +"</span>";
                } else {
                    return "<span class='apoqty badge badge-outline text-yellow'>-</span>";
                }
            }},
            {"data" : "ordprice", "searchable":false, "render": function ( data, type, row, meta ) {
                if (parseFloat(data) != 0) {
                    return "<span class='apoqty badge badge-outline text-yellow'>" + (parseFloat(data).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})) +"</span>";
                } else {
                    return "<span class='apoqty badge badge-outline text-yellow'>-</span>";
                }
            }},
            {"data" : "magprice", "searchable":false, "render": function ( data, type, row, meta ) {
                if (parseFloat(data) != 0) {
                    return "<span class='apoqty badge badge-outline text-purple'>" + (parseFloat(data).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})) +"</span>";
                } else {
                    return "<span class='apoqty badge badge-outline text-lime'>-</span>";
                }
            }},
            {"data" : "orddate", "searchable":false, "render": function ( dat, type, row, meta ) {
                if (dat != "0000-00-00") {
                    return timeAgo(dat);
                } else {
                    return "<span class='apoqty badge badge-outline text-lime'>-</span>";
                }
            }},
            {"data" : "prodtype", "searchable":false, "render": function ( dat, type, row, meta ) {
                var renderData = "<button class='btn btn-sm btn-warning' type='button' onclick='showAnalyze(this, " + dat + ", " + row.productid + ")'>Analyze</button>";
                if (filter == "qty") {
                    renderData += "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class='btn btn-sm btn-info' type='button' onclick='moveToIgnoredDel(" + dat + ", " + row.productid + ")'>Move To Ignore Del</button>";
                }
                return renderData;
            }},
        ],
    });

    $("div.searchholder").addClass("d-flex justify-content-end").html('<label>Search SKU:<input type="search" class="form-control form-control-sm" placeholder="" id="skuSearch" oninput="window.ETable.fnDraw()"></label>&nbsp;&nbsp;<label>Search Name:<input type="search" class="form-control form-control-sm" placeholder="" id="nameSearch" oninput="window.ETable.fnDraw()"></label>');
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

function moveToIgnoredDel(type, id){
    data = {prodtype: type, id: id};
    var pd = { action: "move_to_ignored_del", data: data};
        $.ajax({
        url:"/rfqstocksajax",
        data:pd,
        type:'POST',
        success:function(a) {
        a = JSON.parse(a);
        if(a.success) {
            new_toast("success","Success.");
            window.ETable.fnDraw();
        }
        else
            new_toast("danger","Error! Reason is "+a.error);
        }
    });
}

function createNewRFQ(){
    if (listData.length != 0) {
        sessionStorage.setItem('listData', JSON.stringify(listData));
        location.href = "/newitemrfq";
    } else {
        new_toast("warning","Please select a product!");
    }
}

function syncProducts() {
  $("#loading").attr("style", "display: block !important");
  $.ajax({
    url: "/rfqstocksajax/",
    type:'POST',
    data: { action: "sync"},
    success:function(restResponse) {
      var response = JSON.parse(restResponse);
      if(response.success) {
        new_toast("success","Success");
      }
      else {
        new_toast("danger","Error: "+response.error);
      }
      $("#loading").attr("style", "display: none !important");
      window.ETable.fnDraw();
    },
    error: function (restResponse) {
      new_toast("danger","Error Occured");
      $("#loading").attr("style", "display: none !important");
    }
  });
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
            document.getElementById('analyzeModalLabel').innerHTML = "PRODUCT ANALYSIS FOR " + response.sku + "-" + response.name;
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
                        offsetX: 0,
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
</script>