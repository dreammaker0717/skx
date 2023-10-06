<?php
$db = M::db();
include PATH_CONFIG . "/constants.php";
?>
<link href="/dist/libs/daterangepicker/distribute/daterangepicker.css" rel="stylesheet"/>
<div class="page-body">
    <div class="container-fluid">
        <div class="col-lg-11" style="margin:0 auto;">
            <div class="card card-lg">
                <div class="card-body" style="padding:3rem 1rem;">
                    <div style="margin-left:1rem">
                        <h2>Laptop Sales by Date</h2>
                    </div>
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <div class="d-flex justify-content-between">
                                            <div class="row">
                                        <div class="col-auto">
            <span class="align-middle"><h3>Select Date Range</h3></span>
            <button class="btn btn-primary" type="button" id="btndaterange">
                <svg fill="#ffffff" width="22px" height="22px" viewBox="0 0 22 22" xmlns="http://www.w3.org/2000/svg"><path d="M14.25 2.5h-.75v-1h-1.25v1h-8.5v-1H2.5v1h-.75A1.25 1.25 0 0 0 .5 3.75v9.5a1.25 1.25 0 0 0 1.25 1.25h12.5a1.25 1.25 0 0 0 1.25-1.25v-9.5a1.25 1.25 0 0 0-1.25-1.25zM1.75 3.75h12.5V5H1.75V3.75zm0 9.5v-7h12.5v7z"/><path d="M3 8h1.1v1.2H3zm0 2.4h1.1v1.2H3zM11.8 8h1.1v1.2h-1.1zm0 2.4h1.1v1.2h-1.1zM9.6 8h1.1v1.2H9.6zm0 2.4h1.1v1.2H9.6zM7.4 8h1.1v1.2H7.4zm0 2.4h1.1v1.2H7.4zM5.2 8h1.1v1.2H5.2zm0 2.4h1.1v1.2H5.2z"/></svg>
                <span id="selectedrange"></span>
            </button>
        </div>

                                        <div class="col-auto">
        <span class="align-middle"><h3>VAT Type</h3></span>
                                                <select class='form-control' id='vat_type' onchange='window.ETable.draw()'>
                                                    <option value='All'>All</option>
                                                    <option value='Export'>Export</option>
                                                    <option value='Margin'>Margin</option>
                                                    <option value='Standard'>Standard</option>
                                                </select>
        </div>
        <div class="col-auto">
        <span class="align-middle"><h3>Status</h3></span>
                                                <select class='form-control' id='status' onchange='window.ETable.draw()'>
                                                    <option value='All'>All</option>
                                                    <option value='shipped'>Shipped</option>
                                                    <option value='cancelled'>Cancelled Before Shipment</option>
                                                    <option value='refunded_not_like'>Refunded – Didn’t Like it</option>
                                                    <option value='refunded_faulty'>Refunded – Issue with Laptop</option>
                                                    <option value='refunded_undelivered'>Refunded - Undelivered</option>
                                                    <option value='refunded_other'>Refunded - other reason</option>
                                                    <option value='refunded_all'>All Refunded</option>
                                                </select>
        </div>
        </div>
        <div>
         <button class='btn btn-primary' onClick="downloadExcel()" type='button'>Download Excel</button>
      </div>
        </div>

                                        <div class="col-sm-2" style="height: 35px;"></div>
                                        <table class='table card-table table-vcenter hover datatable'  id="dataList">
                                            <thead>
                                                <tr>
                                                    <th style="width:5%;">Date</th>
                                                    <th style="width:7%;">Order Number</th>
                                                    <th style="width:3%;">Customer Name</th>
                                                    <th style="width:3%;">State</th>
                                                    <th style="width:17%;">SKU</th>
                                                    <th style="width:10%;">Service Tag</th>
                                                    <th style="width:4%;">Cost Price</th>
                                                    <th style="width:3%;">Sale Gross</th>
                                                    <th style="width:3%;">Vat</th>
                                                    <th style="width:3%;">Sale Net</th>
                                                    <th style="width:4%;">Profit</th>
                                                    <th style="width:3%;">Vat Type</th>
                                                    <th style="width:15%;">Supplier</th>
                                                    <th style="width:20%;">Status</th>
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
        </div>
    </div>
<script src="/dist/js/moment.min.js"></script>
<script src="/dist/js/moment-timezone-with-data.min.js"></script>
<script src="/dist/libs/daterangepicker/distribute/daterangepicker.min.js"></script>
<script>
let startDateSelected = null;
let endDateSelected = null;
let nf = new Intl.NumberFormat('en-US', {
  style: 'currency',
  currency: 'GBP',
});
function columnToLetter(column){
    var temp, letter = '';
    while (column > 0){
        temp = (column - 1) % 26;
        letter = String.fromCharCode(temp + 65) + letter;
        column = (column - temp - 1) / 26;
    }
    return letter;
}
$(function() {
    let startDate = moment(0);
    let endDate = moment();
    $('#selectedrange').html(startDate.format('MMMM D, YYYY') + ' - ' + endDate.format('MMMM D, YYYY'));
    startDateSelected = startDate.tz("Europe/London").unix();
    endDateSelected = endDate.tz("Europe/London").unix();

    window.ETable = $('#dataList').DataTable({
        "lengthChange": true,
        "processing":true,
        "serverSide":true,
        "infoEmpty": "No records available",
        "sProcessing": "DataTables is currently busy",
        "aLengthMenu": [[5, 25, 50,100,200,-1], [5, 25, 50,100,200,"All"]],
        "iDisplayLength": 200,
        "order":[],
        "dom": '<<<l>f>rt<ip>>',
        "search": { "search" : new URLSearchParams(window.location.search).get("search")},
        "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
          if (aData['status'] == "awaiting_shipment") {
            $('td', nRow).css('background-color', 'LightYellow');
          } else if (aData['status'] == "cancelled" || aData['status'] == "refunded" || aData['status'] == "part_refunded") {
            $('td', nRow).css('background-color', 'Pink');
          }
        },
        "ajax":{
            url:"/laptopsalesdatereportajax",
            type:"POST",
            data: function (d){d.action = 'search'; d.startDate = startDateSelected; d.endDate = endDateSelected; d.vatType = $('#vat_type').val(); d.status = $('#status').val();},
            dataType:"json"
        },
        
        "columns" :[
            {"data" : "order_date", "render":function(dat,type,row){
              if (dat != 0) {
                return moment.tz(dat * 1000, 'Europe/London').format('DD/MM/YYYY HH:mm');
              } else {
                return "";
              }
            }},
            {"data" : "order_number"},
            {"data" : "recipient"},
            {"data" : "country"},
            {"data" : "sku"},
            {"data" : "servicetag"},
            {"data" : "cost", "render":function(dat,type,row){
                if (dat == null) {dat = "0.00";}
                let prices = dat.split('<br>');
                let returndata = [];
                prices.forEach(item => {
                   returndata.push(nf.format(item));
                });
                return returndata.join('<br>');
            }},
            {"data" : "gross", "render":function(dat,type,row){
                if (dat == null) {dat = "0.00";}
                let prices = dat.split('<br>');
                let returndata = [];
                prices.forEach(item => {
                    returndata.push(nf.format(item));
                });
                return returndata.join('<br>');
            }},
            {"data" : "vat", "render":function(dat,type,row){
                if (dat == null) {dat = "0.00";}
                let prices = dat.split('<br>');
                let returndata = [];
                prices.forEach(item => {
                    returndata.push(nf.format(item));
                });
                return returndata.join('<br>');
            }},
            {"data" : "net", "render":function(dat,type,row){
                if (dat == null) {dat = "0.00";}
                let prices = dat.split('<br>');
                let returndata = [];
                prices.forEach(item => {
                    returndata.push(nf.format(item));
                });
                return returndata.join('<br>');
            }},
            {"data" : "profit", "render":function(dat,type,row){
                if (dat == null) {dat = "0.00";}
                let prices = dat.split('<br>');
                let returndata = [];
                prices.forEach(item => {
                    returndata.push(nf.format(item));
                });
                return returndata.join('<br>');
            }},
            {"data" : "vattype"},
            {"data" : "supplier"},
            {"data" : "item_status", "render":function(dat,type,row){
                if (dat == null) {dat = "Not Set";}
                let statuses = dat.split('<br>');
                let returndata = [];
                statuses.forEach(item => {
                    switch (item){
                        case "shipped":
                            returndata.push("Shipped");
                            break;
                        case "cancelled":
                            returndata.push("Cancelled Before Shipment");
                            break;
                        case "refunded_not_like":
                            returndata.push("Refunded – Didn’t Like it");
                            break;
                        case "refunded_faulty":
                            returndata.push("Refunded – Issue with Laptop");
                            break;
                        case "refunded_undelivered":
                            returndata.push("Refunded - Undelivered");
                            break;
                        case "refunded_other":
                            returndata.push("Refunded - other reason");
                            break;
                        default:
                            break;
                    }
                });
                return returndata.join('<br>');
            }},
        ]
    });

    $('body').on("focus","textarea",function(el){ console.log(el); $(el.currentTarget).width(400); $(el.currentTarget).height(250); });
    $('body').on("blur","textarea",function(el){ console.log(el);  $(el.currentTarget).width(100); $(el.currentTarget).height(50); });

    $('#btndaterange').daterangepicker({
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
            'All': [moment(0), moment()]
        },
        startDate: moment().subtract(29, 'days'),
        endDate: moment()
        },
        function(start, end) {
            $('#selectedrange').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            startDateSelected = start.tz("Europe/London").unix();
            endDateSelected = end.tz("Europe/London").unix();
            window.ETable.draw();
        }
    );
});
$('#dataList').on('draw.dt', function (){
    let totalCost = 0.00;
    let totalGross = 0.00;
    let totalVat = 0.00;
    let totalNet = 0.00;
    let totalProfit = 0.00;
    var data = window.ETable.rows().data();
    data.each(function (value, index) {
        if (value['status'] == "shipped" || value['status'] == "") {
            if (value['cost'] != null) {
                let costprices = value['cost'].split('<br>');
                costprices.forEach(entry => {
                    if (entry != "") {totalCost += parseFloat(entry);}
                });
            }
            if (value['gross'] != null) {
                let grossprices = value['gross'].split('<br>');
                grossprices.forEach(entry => {
                    if (entry != "") {totalGross += parseFloat(entry);}
                });
            }
            if (value['vat'] != null) {
                let vatprices = value['vat'].split('<br>');
                vatprices.forEach(entry => {
                    if (entry != "") {totalVat += parseFloat(entry);}
                });
            }
            if (value['net'] != null) {
                let netprices = value['net'].split('<br>');
                netprices.forEach(entry => {
                    if (entry != "") {totalNet += parseFloat(entry);}
                });
            }
            if (value['profit'] != null) {
                let profitprices = value['profit'].split('<br>');
                profitprices.forEach(entry => {
                    if (entry != "") {totalProfit += parseFloat(entry);}
                });
            }
        }
    });
    $('#dataList tbody').append('<tr><td></td><td></td><td></td><td></td><td></td><td style="text-align: right; font-weight: bold;">Totals: </td><td style="font-weight: bold;">'+nf.format(totalCost)+'</td><td style="font-weight: bold;">'+nf.format(totalGross)+'</td><td style="font-weight: bold;">'+nf.format(totalVat)+'</td style="font-weight: bold;"><td style="font-weight: bold;">'+nf.format(totalNet)+'</td><td style="font-weight: bold;">'+nf.format(totalProfit)+'</td><td></td><td></td><td></td></tr>');
});

function postForm(path, params, method) {
    method = method || 'post';

    var form = document.createElement('form');
    form.setAttribute('method', method);
    form.setAttribute('action', path);

    for (var key in params) {
        if (params.hasOwnProperty(key)) {
            var hiddenField = document.createElement('input');
            hiddenField.setAttribute('type', 'hidden');
            hiddenField.setAttribute('name', key);
            hiddenField.setAttribute('value', params[key]);
            form.appendChild(hiddenField);
        }
    }

    document.body.appendChild(form);
    form.submit();
}

function downloadExcel(){
    var vatType = $('#vat_type').val();
    var status = $('#status').val();
    var search = window.ETable.search();
    postForm('/laptopsalesdateexcelajax/', {search: search, startDate: startDateSelected, endDate: endDateSelected, vatType: vatType, status: status});
}
</script>
