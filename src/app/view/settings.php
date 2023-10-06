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
                     <h1 style="margin-left:1rem;">Settings</h1>
                  </div>
                  <div class="col-auto">
                     <button class="btn btn-primary" onclick="location.href='/skualerts'">SKU Alerts</button>
                  </div>
               </div>
               <div class="row justify-content-between">
                  <div class="col-auto">
                     <h2 style="margin-left:1rem;">
                        Printers
                     </h2>
                  </div>
               </div>
               <table class="table" id="printerList">
                  <thead>
                     <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Printer Name</th>
                        <th scope="col">Printer IP</th>
                        <th scope="col">Action</th>
                     </tr>
                  </thead>
                  <tbody id="printersTable">
                  </tbody>
               </table>
               <div class="row justify-content-md-center">
                  <div class="col-auto">
                     <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newPrinterModal">
                     Add New Printer
                     </button>
                  </div>
               </div>
               <div class="row justify-content-between">
                  <div class="col-auto">
                     <h2 style="margin-left:1rem;">
                        Ship From
                     </h2>
                  </div>
               </div>
               <table class="table" id="shipFromList">
                  <thead>
                     <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Ship From Name</th>
                        <th scope="col">Action</th>
                     </tr>
                  </thead>
                  <tbody id="shipFromTable">
                  </tbody>
               </table>
               <div class="row justify-content-md-center">
                  <div class="col-auto">
                     <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newShipFromModal">
                     Add New Ship From
                     </button>
                  </div>
               </div>
               <div class="row justify-content-between">
                  <div class="col-auto">
                     <h2 style="margin-left:1rem;">
                     Service Presets</h2>
                  </div>
               </div>
               <table class="table" id="presetsList">
                  <thead>
                     <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Sort Order</th>
                        <th scope="col">Preset Name</th>
                        <th scope="col">Action</th>
                     </tr>
                  </thead>
                  <tbody id="presetsTable">
                  </tbody>
               </table>
               <div class="row justify-content-md-center">
                  <div class="col-auto">
                     <button type="button" class="btn btn-primary" onclick='presetAddModal(this, false, 0);'>
                     Add New Preset
                     </button>
                  </div>
               </div>
            </div>
            <div class="modal fade" id="newPrinterModal" tabindex="-1" aria-labelledby="newPrinterModalLabel" aria-hidden="true">
               <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content">
                     <div class="modal-header">
                        <h5 class="modal-title" id="newPrinterModalLabel">Add New Printer</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                     </div>
                     <div class="modal-body">
                        <form class="row g-3 needs-validation" id="newPrinterForm" novalidate>
                           <div class="col-md-6">
                              <label for="printerName" class="form-label">Name</label>
                              <input type="text" class="form-control" id="printerName" required>
                              <div class="invalid-feedback">
                                 Please provide a valid name.
                              </div>
                           </div>
                           <div class="col-md-6">
                              <label for="printerIP" class="form-label">IP Address</label>
                              <input type="text" class="form-control" id="printerIP" required>
                              <div class="invalid-feedback">
                                 Please provide a valid IP address.
                              </div>
                           </div>
                        </form>
                     </div>
                     <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" onclick='printerAdd(this);'>Add</button>
                     </div>
                  </div>
               </div>
            </div>
            <div class="modal fade" id="newShipFromModal" tabindex="-1" aria-labelledby="newShipFromModalLabel" aria-hidden="true">
               <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content">
                     <div class="modal-header">
                        <h5 class="modal-title" id="newShipFromModalLabel">Add New Ship From</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                     </div>
                     <div class="modal-body">
                        <form class="row g-6 needs-validation" id="newShipFromForm" novalidate>
                          <div class="row my-2">
                           <div class="col-md-6">
                              <label for="locationName" class="form-label">Location Name</label>
                              <input type="text" class="form-control" id="locationName" required>
                              <div class="invalid-feedback">
                                 Please provide a valid Location Name.
                              </div>
                           </div>
                         </div>
                         <div class="row my-2">
                           <div class="col-md-6">
                              <label for="fullName" class="form-label">Full Name</label>
                              <input type="text" class="form-control" id="fullName" required>
                              <div class="invalid-feedback">
                                 Please provide a valid Full Name.
                              </div>
                           </div>
                           <div class="col-md-6">
                              <label for="companyName" class="form-label">Company</label>
                              <input type="text" class="form-control" id="companyName" required>
                              <div class="invalid-feedback">
                                 Please provide a valid Company Name.
                              </div>
                           </div>
                         </div>
                         <div class="row my-2">
                           <div class="col-md-6">
                              <label for="street1" class="form-label">Street 1</label>
                              <input type="text" class="form-control" id="street1" required>
                              <div class="invalid-feedback">
                                 Please provide a valid Street address.
                              </div>
                           </div>
                           <div class="col-md-6">
                              <label for="street2" class="form-label">Street 2</label>
                              <input type="text" class="form-control" id="street2">
                           </div>
                           </div>
                           <div class="row my-2">
                           <div class="col-md-6">
                              <label for="street3" class="form-label">Street 3</label>
                              <input type="text" class="form-control" id="street3">
                           </div>
                           </div>
                           <div class="row my-2">
                           <div class="col-md-6">
                              <label for="city" class="form-label">City</label>
                              <input type="text" class="form-control" id="city" required>
                              <div class="invalid-feedback">
                                 Please provide a valid City.
                              </div>
                           </div>
                           <div class="col-md-6">
                              <label for="state" class="form-label">State</label>
                              <input type="text" class="form-control" id="state">
                              <div class="invalid-feedback">
                                 Please provide a valid State.
                              </div>
                           </div>
                           </div>
                           <div class="row my-2">
                           <div class="col-md-6">
                              <label for="postalCode" class="form-label">Postal Code</label>
                              <input type="text" class="form-control" id="postalCode" required>
                              <div class="invalid-feedback">
                                 Please provide a valid Postal Code.
                              </div>
                           </div>
                           <div class="col-md-6">
                              <label for="country" class="form-label">Country</label>
                              <input type="text" class="form-control" id="country" required>
                              <div class="invalid-feedback">
                                 Please provide a valid Country.
                              </div>
                           </div>
                           </div>
                           <div class="row my-2">
                           <div class="col-md-6">
                              <label for="phone" class="form-label">Phone</label>
                              <input type="text" class="form-control" id="phone">
                              <div class="invalid-feedback">
                                 Please provide a valid Phone Number.
                              </div>
                           </div>
                           </div>
                           <div class="row my-2">
                           <div class="col-md-6 form-check">
                            <input class="form-check-input" type="checkbox" value="" id="isResidential">
                            <label class="form-check-label" for="isResidential">
                              Residential Address
                            </label>
                          </div>
                          </div>
                     </div>
                     </form>
                     <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" onclick='shipFromAdd(this);'>Add</button>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="modal fade" id="newPresetModal" tabindex="-1" aria-labelledby="newPresetModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
               <div class="modal-content">
                  <div class="modal-header">
                     <h5 class="modal-title" id="newPresetModalLabel">Add New Preset</h5>
                     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                     <form class="needs-validation" id="newPresetForm" novalidate>
                        <div class="row g-3">
                           <div class="col-md-6">
                              <label for="presetName" class="form-label">Name</label>
                              <input type="text" class="form-control" id="presetName" required>
                              <div class="invalid-feedback">
                                 Please provide a valid name.
                              </div>
                           </div>
                        </div>
                        <div class="row g-3 my-2">Shipping Options:</div>
                        <div class="row g-3 my-2">
                           <div class="col-md-6">
                              <label for="selectShipFrom" class="form-label">Ship From</label>
                              <select class="form-select" id="selectShipFrom" aria-label="Ship From" required>
                              </select>
                              <div class="invalid-feedback">
                                 Please select an option.
                              </div>
                           </div>
                        </div>
                        <div class="row g-3 my-2">
                           <div class="col-md-6">
                              <label for="selectService" class="form-label">Service</label>
                              <select class="form-select" id="selectService" aria-label="Service" required>
                              </select>
                              <div class="invalid-feedback">
                                 Please select an option.
                              </div>
                           </div>
                        </div>
                        <div class="row g-3 my-2">
                           <div class="col-md-6">
                              <label for="selectPackage" class="form-label">Package</label>
                              <select class="form-select" id="selectPackage" aria-label="Package" disabled required>
                                 <option selected value="">Select Service First</option>
                              </select>
                              <div class="invalid-feedback">
                                 Please select an option.
                              </div>
                           </div>
                        </div>
                        <div class="row g-3 my-2 align-items-center">
                           <div class="col-md-1" style="margin-right:10px;">Weight</div>
                           <div class="col-md-3">
                              <input type="text" class="form-control" id="weight" required>
                              <div class="invalid-feedback">
                                 Please provide a valid number.
                              </div>
                           </div>
                           <div class="col-md-4">(g)</div>
                        </div>
                        <div class="row g-3 my-2 align-items-center" id="sizeRow">
                        </div>
                        <div class="row my-2">
                        <div class="col-md-6 form-check">
                         <input class="form-check-input" type="checkbox" value="" id="isInternational">
                         <label class="form-check-label" for="isInternational">
                           International
                         </label>
                       </div>
                       </div>
                       <div class="row g-3 my-2 align-items-center">
                          <div class="col semi-narrow-col" style="padding-right: 0px;">Sort Order</div>
                          <div class="col-md-2">
                             <input type="text" class="form-control" id="sort" required>
                             <div class="invalid-feedback">
                                Please provide a valid number.
                             </div>
                          </div>
                          <div class="col narrow-col" style="padding-right: 0px;">Color #</div>
                          <div class="col-md-3">
                             <input type="text" class="form-control" id="color" required>
                             <div class="invalid-feedback">
                                Please provide a valid color code.
                             </div>
                          </div>
                       </div>
                     </form>
                  </div>
                  <div class="modal-footer">
                     <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                     <button type="button" class="btn btn-primary" id="presetAddButton" onclick='presetAdd(this, false);'>Add</button>
                     <button type="button" class="btn btn-primary" id="presetUpdateButton" onclick='presetAdd(this, true);' style="display: none;">Update</button>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<script>
window.onload = (event) => {
  loadPrinters();
  loadShipFrom();
  loadPresets();
};

function loadPrinters() {
  $('#printerList tbody').empty();
  $.ajax({
       url: "/settingsajax",
       type: "post",
       data: {action:"get_printers"},
       success: function (response) {
          var dataRow = "";
          for (printer of response) {
            dataRow += "<tr><td scope=\"row\">" + printer['id'] + "</td><td>" + printer['printer_name'] + "</td><td>" + printer['printer_ip'] + "</td><td><button type=\"button\" onclick='printerDelete(this);' class=\"btn btn-secondary\"><svg xmlns=\"http://www.w3.org/2000/svg\" width=\"16\" height=\"16\" fill=\"currentColor\" class=\"bi bi-x-square\" viewBox=\"0 0 16 16\"><path d=\"M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z\"></path><path d=\"M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z\"></path></svg></button></td></tr>";
          }
          $('#printersTable').append(dataRow);
       },
       error: function(jqXHR, textStatus, errorThrown) {
          console.log(textStatus, errorThrown);
       }
   });
}

function printerDelete(ctl) {
    var tableRow = $(ctl).parents("tr");
    var id = $(":first-child", tableRow).text();
    tableRow.remove();
    $.ajax({
         url: "/settingsajax",
         type: "post",
         data: {printerID: id, action:"delete_printer"},
         success: function (restResponse) {
           var response = JSON.parse(restResponse);
           if(response.success) {
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

function printerAdd(ctl) {
    const form = document.getElementById("newPrinterForm");
    var name = document.getElementById("printerName").value;
    var ip = document.getElementById("printerIP").value;
    if (form.checkValidity()) {
    $('#newPrinterModal').modal('hide');
    document.getElementById("printerName").value = "";
    document.getElementById("printerIP").value = "";
    form.classList.remove('was-validated');
      $.ajax({
           url: "/settingsajax",
           type: "post",
           data: {printerName: name, printerIP: ip, action:"add_printer"},
           success: function (restResponse) {
             var response = JSON.parse(restResponse);
             if(response.success) {
               loadPrinters();
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

function loadShipFrom() {
  $('#shipFromList tbody').empty();
  $.ajax({
       url: "/settingsajax",
       type: "post",
       data: {action:"get_ship_from"},
       success: function (response) {
          var dataRow = "";
          for (shipFrom of response) {
            dataRow += "<tr><td scope=\"row\">" + shipFrom['id'] + "</td><td>" + shipFrom['ship_from_name'] + "</td><td><button type=\"button\" onclick='shipFromDelete(this);' class=\"btn btn-secondary\"><svg xmlns=\"http://www.w3.org/2000/svg\" width=\"16\" height=\"16\" fill=\"currentColor\" class=\"bi bi-x-square\" viewBox=\"0 0 16 16\"><path d=\"M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z\"></path><path d=\"M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z\"></path></svg></button></td></tr>";
          }
          $('#shipFromTable').append(dataRow);
       },
       error: function(jqXHR, textStatus, errorThrown) {
          console.log(textStatus, errorThrown);
       }
   });
}

function shipFromDelete(ctl) {
    var tableRow = $(ctl).parents("tr");
    var id = $(":first-child", tableRow).text();
    tableRow.remove();
    $.ajax({
         url: "/settingsajax",
         type: "post",
         data: {shipFromID: id, action:"delete_ship_from"},
         success: function (restResponse) {
           var response = JSON.parse(restResponse);
           if(response.success) {
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

function shipFromAdd(ctl) {
    const form = document.getElementById("newShipFromForm");
    var locationName = document.getElementById("locationName").value;
    var fullName = document.getElementById("fullName").value;
    var companyName = document.getElementById("companyName").value;
    var street1 = document.getElementById("street1").value;
    var street2 = document.getElementById("street2").value;
    var street3 = document.getElementById("street3").value;
    var city = document.getElementById("city").value;
    var state = document.getElementById("state").value;
    var postalCode = document.getElementById("postalCode").value;
    var country = document.getElementById("country").value;
    var phone = document.getElementById("phone").value;
    var isResidential = false;
    if (document.getElementById("isResidential").checked) {
      isResidential = true;
    }

    if (form.checkValidity()) {

    $('#newShipFromModal').modal('hide');
    document.getElementById("locationName").value = "";
    document.getElementById("fullName").value = "";
    document.getElementById("companyName").value = "";
    document.getElementById("street1").value = "";
    document.getElementById("street2").value = "";
    document.getElementById("street3").value = "";
    document.getElementById("city").value = "";
    document.getElementById("state").value = "";
    document.getElementById("postalCode").value = "";
    document.getElementById("country").value = "";
    document.getElementById("phone").value = "";
    document.getElementById("isResidential").checked = false;

    form.classList.remove('was-validated');
      $.ajax({
           url: "/settingsajax",
           type: "post",
           data: {locationName: locationName, fullName: fullName, companyName: companyName, street1: street1, street2: street2, street3: street3, city: city, state: state, postalCode: postalCode, country: country, phone: phone, isResidential: isResidential, action:"add_ship_from"},
           success: function (restResponse) {
             var response = JSON.parse(restResponse);
             if(response.success) {
               loadShipFrom();
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

var editPresetID = null;
var presetData = null;
function presetAddModal(ctl, isEdit, presetID){
  $("#loading").attr("style", "display: block !important");
  $.ajax({
      url: "/settingsajax",
      type:'POST',
      data: { action: "get_preset_data"},
      success:function(response) {
        presetData = response;
        $("#loading").attr("style", "display: none !important");
        document.getElementById("presetName").value = "";
        document.getElementById("weight").value = "";
        document.getElementById("sort").value = "";
        document.getElementById("color").value = "";
        document.getElementById("isInternational").checked = false;
        document.getElementById('sizeRow').innerHTML = '';
        $("#newPresetModal").modal('show');
        $('#selectService')
          .empty()
          .append('<option selected="selected" value="">Select item</option>');
        for (var i = 0; i < response['services'].length; i++) {
          if (response['services'][i]['isCarrierName']) {
            $('#selectService').append('<option value="" disabled="disabled" style="font-size: 18px;">'+ response['services'][i]['carrierName'] +'</option>');
          } else {
            $('#selectService').append('<option value="' + i + '">'+ response['services'][i]['serviceName'] +'</option>');
          }
        }
        $('#selectShipFrom')
          .empty()
          .append('<option selected="selected" value="">Select item</option>');
        $('#selectPackage')
          .empty()
          .append('<option selected="selected" value="">Select Service First</option>');
        for (var i = 0; i < response['shipFrom'].length; i++) {
          $('#selectShipFrom').append('<option value="' + i + '">'+ response['shipFrom'][i]['ship_from_name'] +'</option>');
        }

        if (isEdit) {
          for (preset of loadedPreset) {
            if (preset['id'] == presetID) {
              editPresetID = presetID;
              document.getElementById("presetName").value = preset['preset_name'];
              if (preset['sort_order'] != 0) {
                document.getElementById("sort").value = preset['sort_order'];
              }
              document.getElementById("color").value = preset['color'];
              for(var i = 0; i < presetData['shipFrom'].length; i++){
                if (presetData['shipFrom'][i]['ship_from_name'] == preset['ship_from_name']) {
                  document.getElementById("selectShipFrom").value = '' + i;
                }
              }
              var serviceID = null;
              for(var i = 0; i < presetData['services'].length; i++){
                if (presetData['services'][i]['serviceCode'] == preset['service_code']) {
                  serviceID = i;
                  document.getElementById("selectService").value = '' + i;
                }
              }
              $('#selectPackage').prop('disabled', false);
              $('#selectPackage')
                .empty()
                .append('<option selected="selected" value="">Select item</option>');
              for (var i = 0; i < presetData['packages'].length; i++) {
                if (presetData['services'][serviceID]['carrierCode'] == presetData['packages'][i]['carrierCode'] && !presetData['packages'][i]['isCarrierName']) {
                  $('#selectPackage').append('<option value="' + i + '">'+ presetData['packages'][i]['packageName'] +'</option>');
                }
              }
              for(var i = 0; i < presetData['packages'].length; i++){
                if (presetData['packages'][i]['packageCode'] == preset['package_code'] && presetData['packages'][i]['carrierCode'] == preset['carrier_code']) {
                  document.getElementById("selectPackage").value = '' + i;
                }
              }
              document.getElementById("weight").value = preset['weight'];
              if (preset['international'] == 1) {
                document.getElementById("isInternational").checked = true;
              }
              var div = document.getElementById('sizeRow');
              if (presetData['services'][serviceID]['carrierCode'] != "royal_mail"){
                div.innerHTML += "<div class=\"col-md-1\" style=\"margin-right:10px;\">Size</div><div class=\"col-md-10\"><div class=\"row align-items-center\"><div class=\"col-md-2\"><input type=\"text\" class=\"form-control\" id=\"length\" required><div class=\"invalid-feedback\">Required*</div></div><div class=\"col-md-1\">L</div><div class=\"col-md-2\"><input type=\"text\" class=\"form-control\" id=\"width\" required><div class=\"invalid-feedback\">Required*</div></div><div class=\"col-md-1\">W</div><div class=\"col-md-2\"><input type=\"text\" class=\"form-control\" id=\"height\" required><div class=\"invalid-feedback\">Required*</div></div><div class=\"col-md-1\">H</div><div class=\"col-md-1\">(inches)</div></div></div>";
                document.getElementById("length").value = preset['length'];
                document.getElementById("width").value = preset['width'];
                document.getElementById("height").value = preset['height'];
              } else {
                div.innerHTML = '';
              }
              document.getElementById("presetAddButton").style.display = 'none';
              document.getElementById("presetUpdateButton").style.display = 'block';
              document.getElementById("newPresetModalLabel").innerHTML = "Edit Preset";
            }
          }
        } else {
          document.getElementById("presetAddButton").style.display = 'block';
          document.getElementById("presetUpdateButton").style.display = 'none';
          document.getElementById("newPresetModalLabel").innerHTML = "Add New Preset";
        }
      },
      error: function (response) {
          new_toast("danger","Error Occured");
          $("#loading").attr("style", "display: none !important");
      }
  });
}

var loadedPreset = null;
function loadPresets() {
  $('#presetsList tbody').empty();
  $.ajax({
       url: "/settingsajax",
       type: "post",
       data: {action:"get_presets"},
       success: function (response) {
          loadedPreset = response;
          var dataRow = "";
          for (preset of response) {
            dataRow += "<tr><td scope=\"row\">" + preset['id'] + "</td><td scope=\"row\">" + preset['sort_order'] + "</td><td>" + preset['preset_name'] + "</td><td><button type=\"button\" onclick='presetDelete(this);' class=\"btn btn-secondary\"><svg xmlns=\"http://www.w3.org/2000/svg\" width=\"16\" height=\"16\" fill=\"currentColor\" class=\"bi bi-x-square\" viewBox=\"0 0 16 16\"><path d=\"M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z\"></path><path d=\"M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z\"></path></svg></button><button type=\"button\" onclick='presetAddModal(this, true, " + preset['id'] + ");' class=\"btn btn-secondary\" style=\"margin-left: 30px;\"><svg enable-background=\"new 0 0 19 19\" height=\"19px\" id=\"Layer_1\" version=\"1.1\" viewBox=\"0 0 19 19\" width=\"19px\" xml:space=\"preserve\" xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\"><g><path d=\"M8.44,7.25C8.348,7.342,8.277,7.447,8.215,7.557L8.174,7.516L8.149,7.69   C8.049,7.925,8.014,8.183,8.042,8.442l-0.399,2.796l2.797-0.399c0.259,0.028,0.517-0.007,0.752-0.107l0.174-0.024l-0.041-0.041   c0.109-0.062,0.215-0.133,0.307-0.225l5.053-5.053l-3.191-3.191L8.44,7.25z\" fill=\"#FFFFFF\"/><path d=\"M18.183,1.568l-0.87-0.87c-0.641-0.641-1.637-0.684-2.225-0.097l-0.797,0.797l3.191,3.191l0.797-0.798   C18.867,3.205,18.824,2.209,18.183,1.568z\" fill=\"#FFFFFF\"/><path d=\"M15,9.696V17H2V2h8.953l1.523-1.42c0.162-0.161,0.353-0.221,0.555-0.293   c0.043-0.119,0.104-0.18,0.176-0.287H0v19h17V7.928L15,9.696z\" fill=\"#FFFFFF\"/></g></svg></button></td></tr>";
          }
          $('#presetsTable').append(dataRow);
       },
       error: function(jqXHR, textStatus, errorThrown) {
          console.log(textStatus, errorThrown);
       }
   });
}

function presetDelete(ctl) {
    var tableRow = $(ctl).parents("tr");
    var id = $(":first-child", tableRow).text();
    tableRow.remove();
    $.ajax({
         url: "/settingsajax",
         type: "post",
         data: {presetID: id, action:"delete_preset"},
         success: function (restResponse) {
           var response = JSON.parse(restResponse);
           if(response.success) {
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

function presetAdd(ctl, isEdit) {
  const form = document.getElementById("newPresetForm");
  var presetName = document.getElementById("presetName").value;
  var sortOrder = document.getElementById("sort").value;
  var color = document.getElementById("color").value;
  var selectShipFrom = document.getElementById("selectShipFrom").value;
  var selectService = document.getElementById("selectService").value;
  var selectPackage = document.getElementById("selectPackage").value;
  var weight = document.getElementById("weight").value;

  if (form.checkValidity()) {
  $('#newPresetModal').modal('hide');
  form.classList.remove('was-validated');

  var carrierCode = presetData['services'][selectService]['carrierCode'];
  var carrierName = presetData['services'][selectService]['carrierName'];
  var serviceCode = presetData['services'][selectService]['serviceCode'];
  var serviceName = presetData['services'][selectService]['serviceName'];
  var packageCode = presetData['packages'][selectPackage]['packageCode'];
  var packageName = presetData['packages'][selectPackage]['packageName'];

  var length = 0;
  var width = 0;
  var height = 0;
  if (carrierCode != "royal_mail"){
    var length = document.getElementById("length").value;
    var width = document.getElementById("width").value;
    var height =document.getElementById("height").value;
  }
  var isInternational = false;
  if (document.getElementById("isInternational").checked) {
    isInternational = true;
  }

  var locationName = presetData['shipFrom'][selectShipFrom]['ship_from_name'];
  var fullName = presetData['shipFrom'][selectShipFrom]['name'];
  var companyName = presetData['shipFrom'][selectShipFrom]['company'];
  var street1 = presetData['shipFrom'][selectShipFrom]['street1'];
  var street2 = presetData['shipFrom'][selectShipFrom]['street2'];
  var street3 = presetData['shipFrom'][selectShipFrom]['street3'];
  var city = presetData['shipFrom'][selectShipFrom]['city'];
  var state = presetData['shipFrom'][selectShipFrom]['state'];
  var postalCode = presetData['shipFrom'][selectShipFrom]['postal_code'];
  var country = presetData['shipFrom'][selectShipFrom]['country'];
  var phone = presetData['shipFrom'][selectShipFrom]['phone'];
  var isResidential = presetData['shipFrom'][selectShipFrom]['residential'];

  if(!isEdit){
    $.ajax({
         url: "/settingsajax",
         type: "post",
         data: {presetName: presetName, sortOrder: sortOrder, color: color, weight: weight, length: length, width: width, height: height, carrierCode: carrierCode, carrierName: carrierName, serviceCode: serviceCode, serviceName: serviceName, packageCode: packageCode, packageName: packageName, locationName: locationName, fullName: fullName, companyName: companyName, street1: street1, street2: street2, street3: street3, city: city, state: state, postalCode: postalCode, country: country, phone: phone, isInternational: isInternational, isResidential: isResidential, action:"add_preset"},
         success: function (restResponse) {
           var response = JSON.parse(restResponse);
           if(response.success) {
             loadPresets();
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
    $.ajax({
         url: "/settingsajax",
         type: "post",
         data: {presetID: editPresetID, presetName: presetName, sortOrder: sortOrder, color: color, weight: weight, length: length, width: width, height: height, carrierCode: carrierCode, carrierName: carrierName, serviceCode: serviceCode, serviceName: serviceName, packageCode: packageCode, packageName: packageName, locationName: locationName, fullName: fullName, companyName: companyName, street1: street1, street2: street2, street3: street3, city: city, state: state, postalCode: postalCode, country: country, phone: phone, isInternational: isInternational, isResidential: isResidential, action:"update_preset"},
         success: function (restResponse) {
           var response = JSON.parse(restResponse);
           if(response.success) {
             loadPresets();
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

  } else {
    form.classList.add('was-validated');
  }
}

$('#selectService').change(function(){
  $('#selectPackage').prop('disabled', false);
  $('#selectPackage')
    .empty()
    .append('<option selected="selected" value="">Select item</option>');
  for (var i = 0; i < presetData['packages'].length; i++) {
    if (presetData['services'][$(this).val()]['carrierCode'] == presetData['packages'][i]['carrierCode'] && !presetData['packages'][i]['isCarrierName']) {
      $('#selectPackage').append('<option value="' + i + '">'+ presetData['packages'][i]['packageName'] +'</option>');
    }
  }
  var div = document.getElementById('sizeRow');
  if (presetData['services'][$(this).val()]['carrierCode'] != "royal_mail"){
    div.innerHTML = "<div class=\"col-md-1\" style=\"margin-right:10px;\">Size</div><div class=\"col-md-10\"><div class=\"row align-items-center\"><div class=\"col-md-2\"><input type=\"text\" class=\"form-control\" id=\"length\" required><div class=\"invalid-feedback\">Required*</div></div><div class=\"col-md-1\">L</div><div class=\"col-md-2\"><input type=\"text\" class=\"form-control\" id=\"width\" required><div class=\"invalid-feedback\">Required*</div></div><div class=\"col-md-1\">W</div><div class=\"col-md-2\"><input type=\"text\" class=\"form-control\" id=\"height\" required><div class=\"invalid-feedback\">Required*</div></div><div class=\"col-md-1\">H</div><div class=\"col-md-1\">(inches)</div></div></div>";
  } else {
    div.innerHTML = '';
  }
});
</script>
