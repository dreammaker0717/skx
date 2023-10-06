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
                     <h1 style="margin-left:1rem;">SKU Alert Messages</h1>
                  </div>
               </div>
               <table class="table" id="messagesList">
                  <thead>
                     <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Message</th>
                        <th scope="col">Action</th>
                     </tr>
                  </thead>
                  <tbody id="messagesTable">
                  </tbody>
               </table>
               <div class="row justify-content-md-center">
                  <div class="col-auto">
                     <button type="button" class="btn btn-primary" onclick='addMessage(this);'>
                     Add New Message
                     </button>
                  </div>
               </div>

            </div>
            <div class="modal fade" id="newMessageModal" tabindex="-1" aria-labelledby="newMessageModalLabel" aria-hidden="true">
               <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content">
                     <div class="modal-header">
                        <h5 class="modal-title" id="newMessageModalLabel">Add New Message</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                     </div>
                     <div class="modal-body">
                        <form class="row g-3 needs-validation" id="newMessageForm" novalidate>
                           <div class="col-md-12">
                              <label for="message" class="form-label">Message</label>
                              <input type="text" class="form-control" id="message" required>
                              <div class="invalid-feedback">
                                 Please provide a valid message.
                              </div>
                           </div>
                        </form>
                     </div>
                     <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="messageAddButton" onclick='messageAdd(this, false);'>Add</button>
                        <button type="button" class="btn btn-primary" id="messageUpdateButton" style="display: none;">Update</button>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<script type="text/javascript">
window.onload = (event) => {
   loadMessages();
};


function loadMessages() {
   $('#messagesList tbody').empty();
   $.ajax({
      url: "/skualertsmessagesajax",
      type: "post",
      data: {action:"get_messages"},
      success: function (response) {
         var dataRow = "";
         for (message of response) {
            dataRow += "<tr><td scope=\"row\">" + message['id'] + "</td><td scope=\"row\">" + message['message'] + "</td><td><button type=\"button\" onclick='messageDelete(this);' class=\"btn btn-secondary\"><svg xmlns=\"http://www.w3.org/2000/svg\" width=\"16\" height=\"16\" fill=\"currentColor\" class=\"bi bi-x-square\" viewBox=\"0 0 16 16\"><path d=\"M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z\"></path><path d=\"M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z\"></path></svg></button><button type=\"button\" onclick='editMessage("+message['id']+", \""+message['message']+"\");' class=\"btn btn-secondary\" style=\"margin-left: 30px;\"><svg enable-background=\"new 0 0 19 19\" height=\"19px\" id=\"Layer_1\" version=\"1.1\" viewBox=\"0 0 19 19\" width=\"19px\" xml:space=\"preserve\" xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\"><g><path d=\"M8.44,7.25C8.348,7.342,8.277,7.447,8.215,7.557L8.174,7.516L8.149,7.69   C8.049,7.925,8.014,8.183,8.042,8.442l-0.399,2.796l2.797-0.399c0.259,0.028,0.517-0.007,0.752-0.107l0.174-0.024l-0.041-0.041   c0.109-0.062,0.215-0.133,0.307-0.225l5.053-5.053l-3.191-3.191L8.44,7.25z\" fill=\"#FFFFFF\"/><path d=\"M18.183,1.568l-0.87-0.87c-0.641-0.641-1.637-0.684-2.225-0.097l-0.797,0.797l3.191,3.191l0.797-0.798   C18.867,3.205,18.824,2.209,18.183,1.568z\" fill=\"#FFFFFF\"/><path d=\"M15,9.696V17H2V2h8.953l1.523-1.42c0.162-0.161,0.353-0.221,0.555-0.293   c0.043-0.119,0.104-0.18,0.176-0.287H0v19h17V7.928L15,9.696z\" fill=\"#FFFFFF\"/></g></svg></button></td></tr>";
         }
         $('#messagesTable').append(dataRow);
      },
      error: function(jqXHR, textStatus, errorThrown) {
         console.log(textStatus, errorThrown);
      }
   });
}


function addMessage(ctl){
   $("#newMessageModal").modal('show');
   document.getElementById("message").value = "";
   document.getElementById("messageAddButton").style.display = 'block';
   document.getElementById("messageUpdateButton").style.display = 'none';
}


function messageAdd(ctl) {
   const form = document.getElementById("newMessageForm");
   var message = document.getElementById("message").value;
   if (form.checkValidity()) {
      $('#newMessageModal').modal('hide');
      document.getElementById("message").value = "";
      form.classList.remove('was-validated');
      $.ajax({
         url: "/skualertsmessagesajax",
         type: "post",
         data: {message: message, action:"add_message"},
         success: function (restResponse) {
            var response = JSON.parse(restResponse);
            if(response.success) {
               loadMessages();
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


function editMessage(id, message){
   $("#newMessageModal").modal('show');
   document.getElementById("message").value = message;
   document.getElementById("messageAddButton").style.display = 'none';
   document.getElementById("messageUpdateButton").style.display = 'block';
   document.getElementById("messageUpdateButton").setAttribute('onclick','updateMessage(' + id + ')');
}

function updateMessage(ctl) {
   const form = document.getElementById("newMessageForm");
   var message = document.getElementById("message").value;
   if (form.checkValidity()) {
      $('#newMessageModal').modal('hide');
      document.getElementById("message").value = "";
      form.classList.remove('was-validated');
      $.ajax({
         url: "/skualertsmessagesajax",
         type: "post",
         data: {messageID: ctl, message: message, action:"update_message"},
         success: function (restResponse) {
            var response = JSON.parse(restResponse);
            if(response.success) {
               loadMessages();
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

function messageDelete(ctl) {
   var tableRow = $(ctl).parents("tr");
   var id = $(":first-child", tableRow).text();
   tableRow.remove();
   $.ajax({
      url: "/skualertsmessagesajax",
      type: "post",
      data: {messageID: id, action:"delete_message"},
      success: function (restResponse) {
         var response = JSON.parse(restResponse);
         if(response.success) {
            new_toast("success","Success");
         } else {
            new_toast("danger","Error: "+response.error);
         }
      },
      error: function(jqXHR, textStatus, errorThrown) {
         new_toast("danger","Error Occured");
      }
   });
}
</script>
