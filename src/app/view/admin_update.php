<script src="https://cdnjs.cloudflare.com/ajax/libs/blueimp-md5/2.18.0/js/md5.min.js" integrity="sha512-Hmp6qDy9imQmd15Ds1WQJ3uoyGCUz5myyr5ijainC1z+tP7wuXcze5ZZR3dF7+rkRALfNy7jcfgS5hH8wJ/2dQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<?php



?>
<!-- Modal -->
 <div id="updateModal" class="modal" role="dialog">
       <div class="modal-dialog  modal-dialog-scrollable">

         <!-- Modal content-->
         <div class="modal-content">
<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

           <div class="modal-header">
             <h4 class="modal-title">Update</h4>

           </div>
           <div class="modal-body">

<div class="row">
  <div class="col-md-12">

          <form id="updateForm" >

           <?php

            if(isset($_FIELDS) && count($_FIELDS)>0) {
              for($i=0;$i<count($_FIELDS);$i++) {
                $ad = $_FIELDS[$i];
                
                if($i == 0) continue;

                if($ad->type == 'function') continue;
                if($ad->sName == 'npr_magqty') continue;

                if(strpos($ad->type,'string')!==false || strpos($ad->type,'md5')!==false) {

                  $is_sec = (isset($ad->type) && $ad->type=='md5');
                  $is_req = (isset($ad->required) && $ad->required===true) ? "required" : "";


                  if($ad->sName=="apr_sku" && $part=="accproductmap")  continue;

                  echo '<div class="form-group mb-3">
                        <label class="form-label" for="name" >'.$ad->title.'</label>
                        <input data-encrypt="'.$is_sec.'" type="'.($is_sec==true ? "password":"text").'" autocomplete="off" class="form-control" name="upd_'.$ad->sName.'" id="upd_'.$ad->sName.'" placeholder="Enter '.$ad->title.'" '.$is_req.'> 
                      </div>';
                }                
                else if(isset($ad->foreign)) {
                  if(isset($ad->multiple) && $ad->multiple==true){
                      $multi = "multiple size=5";
                  }
                  else{
                      $multi = '';
                  }
                  echo '<div class="form-group mb-3">
                    <label class="form-label" for="name" >'.$ad->title.'</label>                    
                    <select width="100%" style="width:100%" class="form-control" name="upd_'.$ad->sName.'" id="upd_'.$ad->sName.'" autocomplete="off" required '.$multi.'>
                    <option value="">Select</option>'; 
                    
                    Dropdown($ad->foreign,$ad->foreign_id, $ad->foreign_name,$ad->foreign_filter,null, $ad->foreign_name);
                  echo '</select>
                  </div>';
                }
                else if($ad->type == 'timestamp') {
                  echo '<div class="form-group mb-3">
                  <label class="form-label" for="name" >'.$ad->title.'</label>
                    <input type="text" autocomplete="off" class="form-control datetimepicker" name="upd_'.$ad->sName.'" id="upd_'.$ad->sName.'" placeholder="Enter '.$ad->title.'" required> 
                  </div>';
                }
                else if(strpos($ad->type, 'bool')!==false) {
                  echo '<div class="form-group mt-4 mb-3">               
                    <div>
                      <label class="row">
                        <span class="col">'.$ad->title.'</span>
                        <span class="col-auto">
                          <label class="form-check form-check-single form-switch">
                            <input class="form-check-input" type="checkbox" name="upd_'.$ad->sName.'"  id="upd_'.$ad->sName.'">
                          </label>
                        </span>
                      </label>
                    </div>       
                  </div>';
                }
                else if(strpos($ad->type,'number')!==false) {
                  echo '<div class="form-group mb-3">
                        <label class="form-label" for="name" >'.$ad->title.'</label>
                        <input type="number" autocomplete="off" class="form-control" name="upd_'.$ad->sName.'" id="upd_'.$ad->sName.'" placeholder="Enter '.$ad->title.'" required> 
                      </div>';
                }
                else {
                  echo '<div class="form-group mb-3">
                        <label class="form-label" for="name" >'.$ad->title.'</label>
                        <input type="text" autocomplete="off" class="form-control" name="upd_'.$ad->sName.'" id="upd_'.$ad->sName.'" placeholder="Enter '.$ad->title.'" required> 
                      </div>';
                }                            
                echo "\r\n";
              }
            }           
          ?>         
         </form>
      </div>
    </div>       
</div>
    <div class="modal-footer">
        <input type="hidden" name="upd_id" id="upd_id" value="0">                     
        <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">
        Cancel
    </a>
        
        <button type="button" class="btn btn-primary ms-auto" id="upd_save">Save</button>
        
    </div>
</div>

       </div>
     </div>

     <script>
function deleteMe(f, t) {
  console.log(f+"  "+t);
  console.log('<?php echo $_TABLE; ?>');
  if(confirm("Do you really want to delete record?")) {
  $.ajax({ 
        url:"/adminajax/<?php echo $_TABLE;?>",
        data:{ action:'delete', f:f, id : t, t: '<?php echo $_TABLE; ?>' },
        type:'POST', 
        success:function(a) {
          a = JSON.parse(a);          
          if(a.success)
            new_toast("success","Deleted successfully.");                               
          else 
            new_toast("danger","Error! Reason is "+a.error);
          window.ETable.fnDraw();          
        } 
    });
  }
}
function updateModelShow(f,t) {
  var dat = window.ETable.fnGetData().filter(function(a){return a[f] == t})[0];
  $('#updateModal').find(".modal-title").html("Update <?php echo $_TITLE; ?>");
  $('#updateModal').find('input,select,textare').val(null).trigger("change");

  set_field( $("#upd_id") , dat[f]);
  set_field( $("[name='upd_active']") ,dat.active);
  console.log("dat",dat);

<?php

  if(isset($_FIELDS) && count($_FIELDS)>0) {
    for($i=0;$i<count($_FIELDS);$i++) {
      $ad = $_FIELDS[$i];                 
      echo "\r\n".'set_field( $("#upd_'.$ad->sName.'") ,dat.'.( isset($ad->foreign) ? $ad->sName : $ad->data).');';
    }
  }
  else {

      echo "\r\n".'set_field($("#upd_name"),dat.name);';

  }
?>

  $(document).trigger("updateModalShow");
  $('#updateModal').modal("show");
}

function set_field($el, value=null) {
  console.log("set_field",$el.attr("name"), value, "checkbox",$el.is("input[type=checkbox]"));
  if($el.is("input[type=checkbox]")) {
    value = (value=="1" || value==true) ? true : null;
    console.log("set_field","checkbox","next",value);
    $el.prop("checked",value);
  }
  else if($el.is("input[type]") || $el.is("select") || $el.is("textarea")) {
    if($el.attr("multiple")=="multiple" && $el.is("select")){
      if(value!=null){
        $el.val(value.split(","));
      }
    }
    else{
      $el.val(value);
    }
  }
  $el.trigger("change");
}

function createModelShow() {
  $('#updateModal').find(".modal-title").html("Create <?php echo $_TITLE; ?>");  
    
  <?php
  if(isset($_FIELDS) && count($_FIELDS)>0) {
    for($i=0;$i<count($_FIELDS);$i++) {
      $ad = $_FIELDS[$i];
      echo "\r\n".'set_field( $("#upd_'.$ad->sName.'"), null);';      
    }
  }
  else {      
      echo "\r\n".'set_field( $("#upd_name") ,null);';
  }
  ?>
  set_field( $("[name='upd_active']") ,  true);
  set_field( $("#upd_id") , "0");
  $(document).trigger("insertModalShow");
  $('#updateModal').modal("show");
}
window.onload = function() {
  $('#upd_save').bind("click",function() {
    if(!$('#updateForm').valid()) {
      alert("Please fill the form!");
      return;
    }
    var data = {};
    var is_new = isNaN(parseInt($("#upd_id").val())) || parseInt($("#upd_id").val())==0;
    
    $('#updateModal').find("textarea,input,select,checkbox").each(function(a,b) {  

        var encrypt = $(b).data("encrypt")=="1";

        var old = window.ETable.fnGetData().filter(function(a){return a.user_id == parseInt($("#upd_id").val()); })[0];
      

        if(b.type=="checkbox") {
          data[b.name.substr(4)] = b.checked===true ? 1 : 0;
        }
        else {
          if(encrypt && !is_new) {

            if(old[b.name.substr(4)]==b.value) {              
              return;
            }
            else b.value =  md5(b.value);

          }
          else if(encrypt) {
            b.value =  md5(b.value);
          }
          if(b.multiple){
            data[b.name.substr(4)] = $(b).val().toString();
          }
          else{
            data[b.name.substr(4)] = b.value;
          }
        }
      });

    
    var pd = { action: is_new ? "insert" :  "update" , data: data, id:$("#upd_id").val() };

    $.ajax({ 
        url:"/adminajax/<?php echo $_TABLE;?>",
        data:pd,
        type:'POST', 
        error: function(a) {
          new_toast("danger","Error!");
          
        },
        success:function(a) {
          a = JSON.parse(a);
                                   
          if(a.success) {            
            new_toast("success","Success.");          
            $('#updateModal').modal("hide");
          }
          else 
            new_toast("danger","Error! Reason is "+a.error);
          window.ETable.fnDraw();
          
        } 
    });    
  });



$('.autcomplete').each(function(b,a) {
  $a=$(a);
  $a.select2({
      ajax: {
        url: $a.data("url"),
        dataType: 'json',
        maximumSelectionLength: 10
      }
    });
});
   
$(".datetimepicker").flatpickr({
    enableTime: true,
    dateFormat: "Y-m-d H:i:ss",
});
   
};
</script>

