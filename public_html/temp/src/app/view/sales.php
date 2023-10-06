<style>
  .tooltip-wrap {
    position: relative;
  }
  .tooltip-wrap .tooltip-content {
    display: none;
    position: absolute;
    bottom: 5%;
    left: 5%;
    right: 5%;
    background-color: #fff7de;
    padding: .5em;
    min-width: 15rem;
    max-height: 100px;
    overflow-y: auto;
  }
  .tooltip-wrap:hover .tooltip-content {
    display: block;
  }
</style>

<script src="/dist/js/moment.min.js"></script>
<script src="/dist/js/moment-timezone-with-data.min.js"></script>

<div class="page-body">
  <div class="container-fluid">
    <div class="col-lg-11" style="margin:0 auto;">
      <div class="card card-lg">
        <div class="card-body" style="padding:3rem 1rem;">
          <div class="row justify-content-end" style="margin-bottom: 20px;">
            <div class="col-auto">
              <button class="btn btn-primary" onclick="location.href='/settings'">Settings</button>
            </div>
          </div>
          <div class="row justify-content-between">
            <div class="col-auto">
              <h2 style="margin-left:1rem;">Sales</h2>
            </div>
            <div class="col-auto">
              <button class="btn btn-primary" onclick="reloadOrders()">Reload</button>
            </div>
          </div>
          <div class="dropdown">

            <div class="table-responsive" style="padding:5px;margin:5px;">

              <div class="form-group row">
                <label for="status"  class="col-sm-1 col-form-label">Status</label>
                <div class="col-sm-2">
                  <select onchange="window.ETable.fnDraw()" class="form-control" id="status" name="category">
                    <option value=0>Awaiting Shipment</option>
                    <option value=1>Shipped</option>
                  </select>
                </div>

                <label for="country"  class="col-sm-1 col-form-label">Country</label>
                <div class="col-sm-2">
                  <select onchange="window.ETable.fnDraw()" class="form-control" id="country" name="country">
                    <option value="">All</option>
                  </select>
                </div>

                <label for="marketplace"  class="col-sm-1 col-form-label">Market Place</label>
                <div class="col-sm-2">
                  <select onchange="window.ETable.fnDraw()" class="form-control" id="marketplace" name="marketplace">
                    <option value="">All</option>
                  </select>
                </div>
              </div>

              <table id="dataList" class="table stripe card-table table-vcenter hover text-nowrap datatable table-sm">
                <thead>
                  <tr>
                    <th class="th_order_date" >Order Date</th>
                    <th class="th_order_number" >Order #</th>
                    <th class="th_sales_channel" >Marketplace</th>
                    <th class="th_recipient" >Recipient</th>
                    <th class="th_requested_sku" data-orderable="false">Requested SKU</th>
                    <th class="th_skuWithSerial" data-orderable="false">Pick SKU</th>
                    <th class="th_qty" data-orderable="false">Pick Qty</th>
                    <th class="th_order_total">Order Total</th>
                    <th class="th_shipping_name">Shipping</th>
                    <th class="th_time_printed">Shipping Time</th>
                    <th class="th_country">Country</th>
                    <th class="th_tracking_number">Tracking #</th>
                    <th class="th_user">User</th>
                  </tr>
                </thead>
              </table>
            </div>
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
        "infoEmpty": "No records available",
        "sProcessing": "DataTables is currently busy",
        "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50,100, 'All']],
        buttons: [],
        "iDisplayLength": 50,
        "order":[],
        "dom": '<<<lB>f>rt<ip>>',
        "search": { "search" : new URLSearchParams(window.location.search).get("search")},
        "ajax":{
          url:"/salesajax/",
          type:"POST",
          data: function (d) {d.status=$('#status').val(); d.country=$('#country').val(); d.marketplace=$('#marketplace').val(); d.action='search';},
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
        {"data" : "order_number", "render":function(dat,type,row){
          var content = "";
          var array = dat.split(',');
          if (array.length == 1) {
            content = "<div>"+array[0]+"</div>";
          } else {
            content = "<div>"+array[0]+"</div>";
            for (var i = 1; i < array.length; i++) {
              if (array[i] == 4194) {
                content += "<div class=\"tooltip-wrap\" style=\"background-color:#008000;width:20px;height:5px;display:inline-block;margin-right:5px;\"><div class=\"tooltip-content\">Amazon Premium service order</div></div>";
              }

              if (array[i] == 4042) {
                content += "<div class=\"tooltip-wrap\" style=\"background-color:#FF0000;width:20px;height:5px;display:inline-block;margin-right:5px;\"><div class=\"tooltip-content\">Amazon Prime Order</div></div>";
              }

              if (array[i] == 4068) {
                content += "<div class=\"tooltip-wrap\" style=\"background-color:#3366FF;width:20px;height:5px;display:inline-block;margin-right:5px;\"><div class=\"tooltip-content\">Intenational Order</div></div>";
              }

              if (array[i] == 4660) {
                content += "<div class=\"tooltip-wrap\" style=\"background-color:#FF00FF;width:20px;height:5px;display:inline-block;margin-right:5px;\"><div class=\"tooltip-content\">Laptop</div></div>";
              }

              if (array[i] == 4246) {
                content += "<div class=\"tooltip-wrap\" style=\"background-color:#00FF00;width:20px;height:5px;display:inline-block;margin-right:5px;\"><div class=\"tooltip-content\">Website Order</div></div>";
              }
            }
          }
          return content;
        }
      },
      {"data" : "sales_channel"},
      {"data" : "recipient"},
      {"data" : "requested_sku"},
      {"data" : "skuWithSerial", "render":function(dat,type,row){
        var content = "";
        for (var i = 0; i < dat.length; i++) {
          if (dat[i]['serial'] !== undefined) {
            var serials = "";
            for (var j = 0; j < dat[i]['serial'].length; j++) {
              serials += dat[i]['serial'][j];
              if (j < (dat[i]['serial'].length - 1)) {
                serials += "<br/>";
              }
            }
            if (dat[i]['scanned'] == 2) {
              content += "<div class=\"tooltip-wrap\" style=\"background-color:pink;\">"+dat[i]['sku']+"<div class=\"tooltip-content\">"+serials+"</div></div>";
            } else if (dat[i]['scanned'] == 1) {
              content += "<div class=\"tooltip-wrap\" style=\"background-color:GreenYellow;\">"+dat[i]['sku']+"<div class=\"tooltip-content\">"+serials+"</div></div>";
            } else if (dat[i]['scanned'] == 0) {
              content += "<div class=\"tooltip-wrap\">"+dat[i]['sku']+"<div class=\"tooltip-content\">"+serials+"</div></div>";
            }
          } else {
            if (dat[i]['scanned'] == 2) {
              content += "<div style=\"background-color:pink;\">"+dat[i]['sku']+"</div>";
            } else if (dat[i]['scanned'] == 1) {
              content += "<div style=\"background-color:GreenYellow;\">"+dat[i]['sku']+"</div>";
            } else if (dat[i]['scanned'] == 0) {
              content += "<div>"+dat[i]['sku']+"</div>";
            }
          }
        }
        return content;
      }
    },
    {"data" : "qty"},
    {"data" : "order_total", "render": function (data, type, full, meta) {
      if (data != 0) {
        return "<div style=\"white-space: normal;width: 100px;\">£" + data + "</div>";
      } else {
        return "";
      }
    }},
    {"data" : "shipping_name", "render": function (data, type, full, meta) {
      return "<div style=\"white-space: normal;width: 150px;\">" + data + "</div>";
    }},
    {"data" : "time_printed", "render":function(dat,type,row){
      if (dat != 0) {
        return moment.tz(dat * 1000, 'Europe/London').format('DD/MM/YYYY HH:mm');
      } else {
        return "";
      }
    }},
    {"data" : "country", "render":function(dat,type,row){
      var content = "";
      content = "<div class=\"tooltip-wrap\"><img src=\"/flags/" + dat.toLowerCase() + ".jpg\" class=\"img-responsive\" style=\"padding-top: 0%!important;width:50px;height:30px;\"/><div class=\"tooltip-content\" style=\"min-width:3rem;!important\">"+dat+"</div></div>";
      return content;
    }
  },
  {"data" : "tracking_number"},
  {"data" : "user"},
  ],
  });

  $.ajax({
    url: "/salesajax",
    type:'POST',
    data: { action: "all_countries"},
    success:function(response) {
      for (var i = 0; i < response.length; i++) {
        if (response[i]['country'] != "") {
          $('#country').append('<option value="' + response[i]['country'] + '">'+ response[i]['country'] +'</option>');
        }
      }
    },
    error: function (response) {
      new_toast("danger","Error Occured");
    }
  });

  $.ajax({
    url: "/salesajax",
    type:'POST',
    data: { action: "all_marketplaces"},
    success:function(response) {
      for (var i = 0; i < response.length; i++) {
        if (response[i]['sales_channel'] != "") {
          $('#marketplace').append('<option value="' + response[i]['sales_channel'] + '">'+ response[i]['sales_channel'] +'</option>');
        }
      }
    },
    error: function (response) {
      new_toast("danger","Error Occured");
    }
  });
});

function reloadOrders() {
  $("#loading").attr("style", "display: block !important");
  $.ajax({
    url: "/salesajax/",
    type:'POST',
    data: { action: "reload"},
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
</script>
