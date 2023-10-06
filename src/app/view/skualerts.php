<style>
.tableFixHead    { overflow: auto; height: 0px; }
.tableFixHead th { position: sticky; top: 0; }
.semi-narrow-col {
    -ms-flex: 0 0 80px;
    flex: 0 0 80px;
}
.narrow-col {
    -ms-flex: 0 0 60px;
    flex: 0 0 60px;
}
</style>

<div class="page-body">
   <div class="container-fluid">
      <div class="col-lg-11" style="margin:0 auto;">
         <div class="card card-lg">
            <div class="card-body" style="padding:3rem 1rem;">
               <div class="row justify-content-between">
                  <div class="col-auto">
                     <h1 style="margin-left:1rem;">SKU Alerts</h1>
                  </div>
                  <div class="col-auto">
                     <button class="btn btn-primary" onclick="location.href='/skualertsmessages'">SKU Alerts Messages</button>
                  </div>
               </div>
               <table id="alertList" class="table stripe card-table table-vcenter hover text-nowrap datatable table-sm">
                <thead>
                  <tr>
                    <th class="th_id" >ID</th>
                    <th class="th_sku" >SKU</th>
                    <th class="th_message" >Message</th>
                    <th class="th_action" >Action</th>
                  </tr>
                </thead>
              </table>
               <div class="row justify-content-md-center">
                  <div class="col-auto">
                     <button type="button" class="btn btn-primary" onclick='addAlert(this);'>
                     Add New SKU Alert
                     </button>
                  </div>
               </div>
            </div>
         </div>
         <div class="modal fade" id="newSKUAlertModal" tabindex="-1" aria-labelledby="newSKUAlertModalLabel" aria-hidden="true">
               <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content">
                     <div class="modal-header">
                        <h5 class="modal-title" id="newSKUAlertModalLabel">Add New SKU Alert</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                     </div>
                     <div class="modal-body">
                        <form class="row g-3 needs-validation" id="newSKUAlertForm" novalidate>
                           <div class="col-md-6">
                              <label for="sku" class="form-label">SKU</label>
                              <input type="text" class="form-control" id="sku" required>
                              <div class="invalid-feedback">
                                 Please provide a valid SKU.
                              </div>
                           </div>
                           <div class="col-md-6">
                              <label for="selectMessage" class="form-label">Message</label>
                              <select class="form-select" id="selectMessage" aria-label="Message" required>
                                 <option selected="selected" value="">Select Message</option>
                                 <?php
include PATH_CONFIG . "/constants.php";
$db = M::db();
$query = "select id, message from skualerts_messages";
$data = $db->query($query)->fetchAll();
for ($i = 0; $i < count($data); $i++) {
    echo '<option value="' . $data[$i]['id'] . '">' . $data[$i]['message'] . '</option>';
}
?>
                              </select>
                              <div class="invalid-feedback">
                                 Please select an option.
                              </div>
                           </div>
                        </form>
                     </div>
                     <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button id="skuAlertAddButton" type="button" class="btn btn-primary" onclick='skuAlertAdd(this);'>Add</button>
                        <button id="updateAlertButton" type="button" class="btn btn-primary" style="display: none;">Update</button>
                     </div>
                  </div>
               </div>
            </div>
      </div>
   </div>
</div>
<script type="text/javascript">
$(function() {
   window.ETable = $('#alertList').dataTable({
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
         url:"/skualertsajax/",
         type:"POST",
         data: function (d) {d.action='get_list';},
         dataType:"json"
      },
      "columns" :[
         {"data" : "id"},
         {"data" : "sku"},
         {"data" : "message"},
         {"data" : "message_id", "render":function(dat,type,row){
            return "<a href='javascript:editAlert("+row.id+", \""+row.sku+"\", "+dat+")'><img src='https://img.icons8.com/ios-glyphs/24/000000/edit--v1.png'></a><a href='javascript:deleteAlert("+row.id+")'><img src='https://img.icons8.com/material-rounded/24/000000/filled-trash.png'></a>"}
         },
         ],
   });
});

function addAlert(ctl){
    $("#newSKUAlertModal").modal('show');
    document.getElementById("sku").value = "";
    document.getElementById("selectMessage").value = "";
    document.getElementById("skuAlertAddButton").style.display = 'block';
    document.getElementById("updateAlertButton").style.display = 'none';
}


function skuAlertAdd(ctl) {
    const form = document.getElementById("newSKUAlertForm");
    var sku = document.getElementById("sku").value;
    var messageID = document.getElementById("selectMessage").value;
    if (form.checkValidity()) {
    $('#newSKUAlertModal').modal('hide');
    document.getElementById("sku").value = "";
    document.getElementById("selectMessage").value = "";
    form.classList.remove('was-validated');
      $.ajax({
           url: "/skualertsajax",
           type: "post",
           data: {sku: sku, messageID: messageID, action:"add_sku_alert"},
           success: function (restResponse) {
             var response = JSON.parse(restResponse);
             if(response.success) {
               window.ETable.fnDraw();
               new_toast("success","Success");
             }
             else {
               new_toast("danger","Error: "+response.error);
             }
           },
           error: function(jqXHR, textStatus, errorThrown) {
              new_toast("danger","Error Occured");
           }
       });
    } else {
      form.classList.add('was-validated');
    }
}

function editAlert(id, sku, messageID){
    $("#newSKUAlertModal").modal('show');
    document.getElementById("sku").value = sku;
    document.getElementById("selectMessage").value = messageID;
    document.getElementById("skuAlertAddButton").style.display = 'none';
    document.getElementById("updateAlertButton").style.display = 'block';
    document.getElementById("updateAlertButton").setAttribute('onclick','updateAlert(' + id + ')');
}

function updateAlert(ctl) {
    const form = document.getElementById("newSKUAlertForm");
    var sku = document.getElementById("sku").value;
    var messageID = document.getElementById("selectMessage").value;
    if (form.checkValidity()) {
    $('#newSKUAlertModal').modal('hide');
    document.getElementById("sku").value = "";
    document.getElementById("selectMessage").value = "";
    form.classList.remove('was-validated');
      $.ajax({
           url: "/skualertsajax",
           type: "post",
           data: {skuAlertID: ctl, sku: sku, messageID: messageID, action:"update_sku_alert"},
           success: function (restResponse) {
             var response = JSON.parse(restResponse);
             if(response.success) {
               window.ETable.fnDraw();
               new_toast("success","Success");
             }
             else {
               new_toast("danger","Error: "+response.error);
             }
           },
           error: function(jqXHR, textStatus, errorThrown) {
              new_toast("danger","Error Occured");
           }
       });
    } else {
      form.classList.add('was-validated');
    }
}

function deleteAlert(ctl) {
    $.ajax({
         url: "/skualertsajax",
         type: "post",
         data: {skuAlertID: ctl, action:"delete_sku_alert"},
         success: function (restResponse) {
            console.log(restResponse);
           var response = JSON.parse(restResponse);
           if(response.success) {
             window.ETable.fnDraw();
             new_toast("success","Success");
           }
           else {
             new_toast("danger","Error: "+response.error);
           }
         },
         error: function(jqXHR, textStatus, errorThrown) {
            new_toast("danger","Error Occured");
         }
     });
}
</script>
