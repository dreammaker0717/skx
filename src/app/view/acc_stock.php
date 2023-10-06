<style type="text/css">
   .pull-right{float:right!important; padding-top: 5px; padding-bottom: 5px;}
</style>
<div class="page-body">
   <div class="container-fluid">
      <div class="col-lg-11" style="margin:0 auto;">
         <div class="card card-lg">
            <div class="card-body" style="padding:3rem 1rem;">
               <div class="row justify-content-between">
                  <div class="col-auto">
                     <h1 style="margin-left:1rem;">Accessories Stock</h1>
                  </div>
               </div>
               <div class="table-responsive d-flex justify-content-center" style="padding:10px;margin:10px;">
                  <table class="table hover card-table table-vcenter text-nowrap datatable" style="width: 700px;" id="stockList">
                     <thead>
                        <tr>
                           <th style="width: 200px;">SKU</th>
                           <th style="text-align:center; width: 100px;">Qty</th>
                           <th style="text-align:center; width: 100px;">Cost Price</th>
                           <th style="text-align:center; width: 100px;">Sale Price</th>
                           <th style="text-align:center; width: 100px;">Profit £</th>
                           <th style="text-align:center; width: 100px;">Profit %</th>
                        </tr>
                     </thead>
                     <tbody>
                     </tbody>
                  </table>
               </div>
               <div class="table-responsive d-flex justify-content-center" style="padding:10px;margin:10px;">
                  <table class="table hover card-table table-vcenter text-nowrap datatable" style="width: 700px;" id="totals">
                     <thead>
                        <tr>
                           <th style="width: 200px;">Total</th>
                           <th style="text-align:center; width: 100px;">Qty</th>
                           <th style="text-align:center; width: 100px;">Cost Price</th>
                           <th style="text-align:center; width: 100px;">Sale Price</th>
                           <th style="text-align:center; width: 100px;">Profit £</th>
                           <th style="text-align:center; width: 100px;">Profit %</th>
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
<script>
let nf = new Intl.NumberFormat('en-US', {
  style: 'currency',
  currency: 'GBP',
});
$(function() {
   window.ETable = $('#stockList').dataTable({
      "lengthChange": true,
      "processing":true,
      "serverSide":true,
      "infoEmpty": "No records available",
      "sProcessing": "DataTables is currently busy",
      "aLengthMenu": [[5, 15, 50,100], [5, 15, 50,100]],
      "iDisplayLength": 50,              
      "order":[],
      "dom": '<<"pull-right"<"pull-right"l>f>rt<ip>>',
      "search": { "search" : new URLSearchParams(window.location.search).get("search")},

      "ajax":{
         url:"/accstockreportajax",
         type:"POST",
         data: { action:'search'},
         dataType:"json"
      },
      "columnDefs": [
         {"className": 'text-left', targets: [0]},
         {"className": 'text-center', targets: [1,2,3,4,5]},
      ],
      "columns" :[
         {"data": "sku"},          
         {"data": "c"},
         {"data": "cost", "render":function(data, type, row, meta){
            return nf.format(data);
         }},
         {"data": "price", "render":function(data, type, row, meta){
            if (data != null) {
               return nf.format(data);
            } else {
               return 0;
            }
         }},
         {"data": null, "render":function(data, type, row, meta){
            if (row.price != null) {
               return nf.format(row.price - row.cost);
            } else {
               return 0;
            }
         }},
         {"data": null, "render":function(data, type, row, meta){
            if (row.price != null && row.cost > 0) {
               return (Math.round((100*(row.price - row.cost)/row.cost) * 100) / 100).toFixed(2);
            } else {
               return 0;
            }
         }},
      ]
   });
});

$('#stockList').on('draw.dt', function (){
   $.ajax({
      url: "/accstockreportajax/",
      type:'POST',
      data: {action: "total_values"},
      success:function(restResponse) {
         var response = JSON.parse(restResponse);
         console.log(response);
         var profitPercent = 0;
         if (response.totalcost > 0) {
            profitPercent = 100*(response.totalprice - response.totalcost)/response.totalcost;
         }
         $("#totals tbody").empty();
         $('#totals').find('tbody').append("<tr><td><span style=\"font-weight:bold;\"></span></td><td style=\"text-align:center;\"><span style=\"font-weight:bold;\">" + response.totalcount + "</span></td><td style=\"text-align:center;\"><span style=\"font-weight:bold;\">" + nf.format(response.totalcost) + "</span></td><td style=\"text-align:center;\"><span style=\"font-weight:bold;\">" + nf.format(response.totalprice) + "</span></td><td style=\"text-align:center;\"><span style=\"font-weight:bold;\">" + nf.format(response.totalprice - response.totalcost) + "</span></td><td style=\"text-align:center;\"><span style=\"font-weight:bold;\">" + (Math.round(profitPercent * 100) / 100).toFixed(2) + "</span></td></tr>");
      },
      error: function (restResponse) {
         new_toast("danger","Error Occured");
      }
   });
 });
</script>