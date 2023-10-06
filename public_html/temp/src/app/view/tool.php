<?php


include(PATH_CONFIG."/constants.php");
$db=M::db();




if($part=="dell_part_loader") {
    if(isset($_POST["import"]) && isset($_POST["inlineRadioOptions"]) && $_POST["inlineRadioOptions"]=="updatecreate") {
        $fileName = $_FILES["csv_input"]["tmp_name"];
        if ($_FILES["csv_input"]["size"] > 0) {                       
            $file = fopen($fileName, "r");
            while (($column = fgetcsv($file, 10000, ",")) !== FALSE) {
                if($column[0]==="sku") continue;
                $iv = $db->count("dell_part",["dp_sku"=>$column[0] ]);
                if($iv==1) {
                $db->update("dell_part",
                    [                        
                        "dp_name"=>$column[1], 
                        "dp_box_label"=>$column[2],
                        "dp_box_subtitle"=>$column[3],
                        "dp_supplier_stock_code"=>$column[4],
                        "dp_condition"=>$column[5],
                        "dp_keyboard_language"=>$column[6],
                        "dp_image"=>$column[7],
                        "dp_listed"=>$column[8],
                        "dp_mpn"=>$column[9],
                        "dp_category"=>$column[10]
                    ],["dp_sku"=> $column[0]]);
                }
                if($iv==0) {
                    $db->insert("dell_part",
                    [
                        "dp_sku"=> $column[0], 
                        "dp_name"=>$column[1], 
                        "dp_box_label"=>$column[2],
                        "dp_box_subtitle"=>$column[3],
                        "dp_supplier_stock_code"=>$column[4],
                        "dp_condition"=>$column[5],
                        "dp_keyboard_language"=>$column[6],
                        "dp_image"=>$column[7],
                        "dp_listed"=>$column[8],
                        "dp_mpn"=>$column[9],
                        "dp_category"=>$column[10]
                    ]);
                }
            }            
        }       
    }
    if(isset($_POST["import"]) && isset($_POST["inlineRadioOptions"]) && $_POST["inlineRadioOptions"]=="update") {
        $fileName = $_FILES["csv_input"]["tmp_name"];
        if ($_FILES["csv_input"]["size"] > 0) {                       
            $file = fopen($fileName, "r");
            while (($column = fgetcsv($file, 10000, ",")) !== FALSE) {
                if($column[0]==="sku") continue;
                $iv = $db->count("dell_part",["dp_sku"=>$column[0] ]);
                if($iv==1) {
                $db->update("dell_part",
                    [                        
                        "dp_name"=>$column[1], 
                        "dp_box_label"=>$column[2],
                        "dp_box_subtitle"=>$column[3],
                        "dp_supplier_stock_code"=>$column[4],
                        "dp_condition"=>$column[5],
                        "dp_keyboard_language"=>$column[6],
                        "dp_image"=>$column[7],
                        "dp_listed"=>$column[8],
                        "dp_mpn"=>$column[9],
                        "dp_category"=>$column[10]
                    ],["dp_sku"=> $column[0]]);
                }
            }            
        }       
    }
    if(isset($_POST["import"]) && isset($_POST["inlineRadioOptions"]) && $_POST["inlineRadioOptions"]=="createonly") {
        $fileName = $_FILES["csv_input"]["tmp_name"];
        if ($_FILES["csv_input"]["size"] > 0) {                       
            $file = fopen($fileName, "r");
            while (($column = fgetcsv($file, 10000, ",")) !== FALSE) {
                if($column[0]==="sku") continue;
                $iv = $db->count("dell_part",["dp_sku"=>$column[0] ]);
                if($iv==0) {
                    $db->insert("dell_part",
                    [
                        "dp_sku"=> $column[0], 
                        "dp_name"=>$column[1], 
                        "dp_box_label"=>$column[2],
                        "dp_box_subtitle"=>$column[3],
                        "dp_supplier_stock_code"=>$column[4],
                        "dp_condition"=>$column[5],
                        "dp_keyboard_language"=>$column[6],
                        "dp_image"=>$column[7],
                        "dp_listed"=>$column[8],
                        "dp_mpn"=>$column[9],
                        "dp_category"=>$column[10]
                    ]);
                }
            }            
        }       
    }
    if(isset($_POST["import"]) && isset($_POST["inlineRadioOptions"]) && $_POST["inlineRadioOptions"]=="recreate") {


       $sp1c= $db->query("select user_id from users where user_id=:u AND `password`=:p",
        [":p"=> md5( $_POST["sp1"]), ":u"=>$_SESSION["user_id"]  ])->fetchAll();

       
       
       if(count($sp1c) == 1) {
            $fileName = $_FILES["csv_input"]["tmp_name"];
            if ($_FILES["csv_input"]["size"] > 0) {       
                $db->exec("delete from dell_part");
                $file = fopen($fileName, "r");
                while (($column = fgetcsv($file, 10000, ",")) !== FALSE) {
                    if($column[0]==="sku") continue;
                    $db->insert("dell_part",
                        [
                            "dp_sku"=> $column[0], 
                            "dp_name"=>$column[1], 
                            "dp_box_label"=>$column[2],
                            "dp_box_subtitle"=>$column[3],
                            "dp_supplier_stock_code"=>$column[4],
                            "dp_condition"=>$column[5],
                            "dp_keyboard_language"=>$column[6],
                            "dp_image"=>$column[7],
                            "dp_listed"=>$column[8],
                            "dp_mpn"=>$column[9],
                            "dp_category"=>$column[10]
                        ]);
                }            
            }   
        }    
    }
    if(isset($_POST["import"]) && isset($_POST["inlineRadioOptions"]) && $_POST["inlineRadioOptions"]=="download") {
        echo "<form style='display:none;' method=post target='_blank' action='/toolajax/dell_part_finder_export_ajax'><input type='hidden' name=action value='dell_part_finder_export_ajax'/><input id='exp1' type=submit /></form>";
        echo "<script>document.getElementById('exp1').click();</script>";
        
    }
    

    
    echo '<div class="page-body">
    <div class="container-fluid">
        <div class="row row-cards">
            <div class="col-12">
                <div class="card">
                     <div class="card-header">
                        <h3 class="card-title">Dell Part Loader</h3>
                        <div class="ms-2">
                      
                        </div>
                    </div>';
    ?>
    <div class="card-body">
        <div class="row">
            <?php if($_SESSION["user_role"]=="3") { ?>

                <script>
                    function selectedOne(el) {
                        if(el.checked) {
                            if(el.value=="recreate"){
                                    $('.second1').css("display","block");
                                    $('.second1').find("input").attr("required","required");
                            }
                            else {
                                $('.second1').css("display","none");
                                $('.second1').find("input").attr("required",false);
                            }
                        }
                    }
                </script>

            <div class="col-auto">
                <form method="post" enctype="multipart/form-data"  action="/tool/dell_part_loader">
                    <div class="form-group">
                        <div class="form-check">
                        <input class="form-check-input" onchange="selectedOne(this)" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="update">
                        <label class="form-check-label" for="inlineRadio1">Update Existing Products Only</label>
                        </div>
                        <div class="form-check">
                        <input class="form-check-input" onchange="selectedOne(this)" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="updatecreate">
                        <label class="form-check-label" for="inlineRadio2">Update Existing Products and Create New Products</label>
                        </div>
                        <div class="form-check">
                        <input class="form-check-input" onchange="selectedOne(this)" type="radio" name="inlineRadioOptions" id="inlineRadio3" value="createonly">
                        <label class="form-check-label" for="inlineRadio3">Create New Product Only</label>
                        </div>
                        <div class="form-check">
                        <input class="form-check-input"  onchange="selectedOne(this)" type="radio" name="inlineRadioOptions" id="inlineRadio4" value="recreate">
                        <label class="form-check-label" for="inlineRadio4">Delete All Existing Products And Create New</label>
                        </div>
                        <div class="form-check">
                        <input class="form-check-input" onchange="selectedOne(this)" type="radio" name="inlineRadioOptions" id="inlineRadio5" value="download">
                        <label class="form-check-label" for="inlineRadio5">Download Products</label>
                        </div>
                    </div>
                    <div class="form-group second1" style='display:none'>
                        <label class="form-label">Password</label>
                        <input type="password" name="sp1" class="form-control" value="">
                    </div>
                    <div class="form-group">                        
                        <label  class="mr-sm-2" for="csv_input">Upload Bulk CSV</label>
                        <input type="file" name="csv_input" class="form-control form-control-file" id="csv_input">   
                    </div>
                <button type="submit" name="import" class="btn btn-primary mt-3">Upload Bulk CSV</button>                                                                         
                </form>
            </div>
            <?php } ?>
        </div>
    </div>
    <?php                      
                echo "</div>";
            echo "</div>";
        echo "</div>";
    echo "</div>";
echo "</div>";    
}
else if($part == "dell_part_finder") {
?>
<script>
    function PrintLabel(id) {
        window.open("/stocksprintajax/print-dell-part-qr?id="+id)  ;
    }

    function deleteMe(f, t) {
        if(confirm("Do you really want to delete record?")) {
        $.ajax({ 
                url:"/adminajax/dell_part",
                data:{ action:'delete', f:f, id : t, t: 'dell_part' },
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
</script>
<?php

    echo '<div class="page-body">
    <div class="container-fluid">
        <div class="row row-cards">
            <div class="col-12">
                <div class="card">
                     <div class="card-header">
                        <h3 class="card-title">Dell Part Finder</h3>
                        <div class="ms-2">
                        <a href="javascript:createModelShow()" class="btn btn-primary">
                        <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                        Create New
                    </a>
                        </div>
                    </div>';
?>
                <div class="card-body">
                    <div class="row">                                              
                        <div class="col">
                        <form id="form1" method="post" action="/tool/dell_part_finder" onsubmit="return validateForm()" >
                            <div class="row">
                                <div class="col-3">
                                    <div class="mb-3">
                                        <label for="sku" class="form-label">SKU</label>
                                        <input type="text" tabindex="2" class="form-control" id="sku" value="<?php echo @$_POST["sku"];?>" name="sku" placeholder="Enter SKU">
                                    </div>
                                </div>                          
                                <div class="col-3">
                                    <div class="mb-3">
                                        <label for="qty" class="form-label">P/N</label>
                                        <input type="text" tabindex="3" class="form-control" id="pn" maxlength="23" minlength="5" name="pn" placeholder="Enter P/N" value="<?php echo @$_POST["pn"];?>" >
                                    </div>
                                </div>                        
                                <div class="col-3">
                                    <div class="mb-3">
                                        <label for="qty" class="form-label">Palmrest+Keyboard</label>
                                        <input tabindex="4" type="text" class="form-control mb-3" id="plam" maxlength="23" minlength="5" name="plam" placeholder="Enter Palmrest" value="<?php echo @$_POST["plam"];?>" >
                                        <input tabindex="5" type="text" class="form-control" id="keyboard" maxlength="23" minlength="5" name="keyboard" placeholder="Enter Keyboard" value="<?php echo @$_POST["keyboard"];?>" >
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-3">
                                    <div class="mb-3">
                                        <label for="stockcode" class="form-label">Stock Code</label>
                                        <input type="text" class="form-control" id="stockcode" name="stockcode" placeholder="Enter Stock" value="<?php echo @$_POST["stockcode"];?>" >
                                    </div>
                                </div>                          
                                <div class="col-3">
                                    <div class="mb-3">
                                        <label for="ass" class="form-label">Ass.</label>
                                        <input type="text" class="form-control" id="ass" name="ass" placeholder="Enter Ass." value="<?php echo @$_POST["ass"];?>" >
                                    </div>
                                </div>                                                       
                            </div>
                            <div class="col-12">
                                <div class="btn-group" role="group" aria-label="Basic example">
                                    <button type="button" onclick="search()" class="btn btn-primary">Search</button>
                                    <button type="button" onclick="clear_search()" class="btn btn-secondary">Clear</button>
                                </div>
                            </div>
                            </form>
                         </div>
                        
                    </div>
                </div>
                 

                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <table class="table table-sm w-100">
                                <thead>
                                    <tr>
                                    <th>ID</th>
                                        <th>SKU</th>
                                        <th>Image</th>
                                        <th>Print Label</th>
                                        <th>Name</th>
                                        <th>QTY</th>
                                        <th>Low</th>
                                        <th>MPN</th>
                                        <th>Stock Code</th>
                                        <th>Box Label</th>
                                        <th>Box Subtitle</th>
                                        <th>Condition</th>
                                        <th>Category</th>
                                        
                                        <!--<th style="width:130px"></th>-->
                                        <th style="width:50px"></th>
                                    </tr>
                                </thead>
                                <tbody>
                              
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/blueimp-md5/2.18.0/js/md5.min.js" integrity="sha512-Hmp6qDy9imQmd15Ds1WQJ3uoyGCUz5myyr5ijainC1z+tP7wuXcze5ZZR3dF7+rkRALfNy7jcfgS5hH8wJ/2dQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>



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


$_FIELDS = [
    (object)array('sName' => 'dp_id', 'title' => 'No', 'data' => 'dp_id', 'type'=>'number' ),       
                                                              
    (object)array('sName' => 'dp_name', 'title' => 'Name', 'data' => 'dp_name', 'type'=> 'string'),
    (object)array('sName' => 'dp_magqty', 'title' => 'QTY', 'data' => 'dp_magqty', 'type'=> 'string'),
    (object)array('sName' => 'dp_lowstock', 'title' => 'Low', 'data' => 'dp_lowstock', 'type'=> 'string'),
    (object)array('sName' => 'dp_sku', 'title' => 'SKU', 'data' => 'dp_sku', 'type'=> 'string'),                                        
    (object)array('sName' => 'dp_mpn', 'title' => 'MPN', 'data' => 'dp_mpn', 'type'=> 'string'),          
    (object)array('sName' => 'dp_image', 'title' => 'Image', 'data' => 'dp_image', 'type'=> 'image'),          
    (object)array('sName' => 'dp_box_label', 'title' => 'Box Label', 'data' => 'dp_box_label', 'type'=> 'string'),                                        
    (object)array('sName' => 'dp_box_subtitle', 'title' => 'Box Subtitle Label', 'data' => 'dp_box_subtitle', 'type'=> 'string'),            
    (object)array('sName' => 'dp_supplier_stock_code', 'title' => 'Stock Code', 'data' => 'dp_supplier_stock_code', 'type'=> 'string'),
    
    (object)array('sName' => 'dp_condition', 'title' => 'Condition', 'data' => 'dp_condition', 'type'=> 'string'),
    (object)array('sName' => 'dp_category', 'title' => 'Category', 'data' => 'ct_name', 'type'=> 'foreign', 
                                'foreign'=>'categories', 'foreign_id'=>'ct_id', 'foreign_name'=>'ct_name','foreign_filter'=>'ct_del=0'),
];                        



                            if(isset($_FIELDS) && count($_FIELDS)>0) {
                            for($i=0;$i<count($_FIELDS);$i++) {
                                $ad = $_FIELDS[$i];
                                
                                if($i == 0) continue;

                                if($ad->type == 'function') continue;

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
                                        $multi = "multiple size=3";
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
function updateModelShow(f,t) {
  var dat = window.ETable.fnGetData().filter(function(a){return a[f] == t})[0];
  $('#updateModal').find(".modal-title").html("Update Dell Part");
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
    $el.val(value);
  }
  $el.trigger("change");
}

function createModelShow() {
  $('#updateModal').find(".modal-title").html("Create Dell Part");  
    
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
          data[b.name.substr(4)] = b.value;
        }
      });

    
    var pd = { action: is_new ? "insert" :  "update" , data: data, id:$("#upd_id").val() };
    
    $.ajax({ 
        url:"/adminajax/dell_part",
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


$(function() {

    $('#form1 input[type="text"]').keypress(function(event){
            var keycode = (event.keyCode ? event.keyCode : event.which);
            if(keycode == '13'){
                if(this.name=="plam") $('#keyboard').focus();
                else
                    search();
            }
        });

});


function clear_search(){
    $('#form1')[0].reset();
    window.table.fnDraw();

}

function search(){
    if(validateForm())
        window.table.fnDraw();

}

                    $(function(){
                        window.ETable = window.table=$('table.table').dataTable({
                            "aLengthMenu": [[10, 20, 50,100], [10, 20, 50,100]],
                            "iDisplayLength": 20,      
                            "processing":true,
                            "serverSide":true,
                            "ajax":{
                                "url": "/toolajax/dell_part_finder_ajax",
                                "type": "POST",
                                "data" : function(d) {
                                    d.action = "dell_part_finder_ajax";
                                    d.sku = $('#sku').val();
                                    d.pn = $('#pn').val();
                                    d.plam=$('#plam').val();
                                    d.keyboard=$('#keyboard').val();
                                    d.stockcode = $('#stockcode').val();
                                    d.ass = $('#ass').val();

                                }
                            }
                        });                        
                    });
                    

                 

                    function validateForm() {
                        var pn_value = $('#pn').val().trim();
                        if(!(pn_value=="" || pn_value.length==5 || pn_value.length==23)) {
                            alert("Please input valid 5 part number or scan a Dell serial number");
                            return false;
                        }
                        

                        var palm_value = $('#plam').val().trim();
                        if(!(palm_value=="" || palm_value.length==5 || palm_value.length==23)) {
                            alert("Please input valid 5 palmrest number or scan 23 digit code");
                            return false;
                        }

                        var keyboard_value = $('#keyboard').val().trim();
                        if(!(keyboard_value=="" || keyboard_value.length==5 || keyboard_value.length==23)) {
                            alert("Please input valid 5 keyboard number or scan 23 digit code");
                            return false;
                        }

                        return true;

                    }

                    function linkModelImage(el,ev) {
                        $('#imagemodal').modal("show");
                        var rhref = $(el).attr("href");
                        console.log(el, rhref);
                        $("img.modal-content").prop("src",rhref);
                        $("#imagemodal").show();
                        return false;

                    }

function LowkeyUp(ev, dp_id){
    console.log("Event here ",ev);
    if(ev.code=="Enter") {
        var data = {};
        data['dp_lowstock'] = ev.target.value;
        var pd = { action: "update_low", data: data, id: dp_id};

            $.ajax({ 
            url:"/adminajax/dell_part",
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
                </script>
            
            <div class="modal fade " id="imagemodal" tabindex="-1" role="dialog" aria-hidden="true" style="height: 900px">
                <div class="modal-dialog"  style="width: 1200px;height: 900px;max-width: 1200px;">
                    <div class="modal-content">
                        <img class="modal-content" src="" width="1200px" height="900px"/>
                    </div>
                </div>
            </div>


<?php                      
                echo "</div>";
            echo "</div>";
        echo "</div>";
    echo "</div>";
echo "</div>";
}
else if($part=="warehouse_stocktake")
{
        echo '<div class="page-body">
        <div class="container-fluid">
            <div class="row row-cards">
                <div class="col-12">
                    <div class="card">
                         <div class="card-header">
                            <h3 class="card-title">Warehouse Stockade</h3>
                            <div class="ms-2">
                          
                            </div>
                        </div>';
?>
                    <div class="card-body">
                        <div class="row">
                            <div class="col">                                
                                <div class="mb-3">
                                    <label for="sku" class="form-label">Scan</label>
                                    <input type="text" class="form-control" id="scan_item" name="scan_item"  placeholder="Scan" onkeyup='StockKeyUp1(event)' >
                                </div>                                                                                              
                             </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <table style="width:100%" id="stocky" class="table table-hover table-stripped" >
                                    <thead>
                                        <tr><th>Box Label</th><th>SKU</th><th>Description</th><th>Condition</th><th>Total</th><th>Scanned</th></tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>                            
                        </div>
                        <div class="row">
                            <div class="col">
                                
                                <button onclick="download_table_as_csv('stocky')" class="btn btn-primary">CSV</button>
                            </div>
                        </div>
                    </div>
                     
<?php                      
                    echo "</div>";
                echo "</div>";
            echo "</div>";
        echo "</div>";
    echo "</div>";
?>   
<script>
        var BOXLABEL="";
        var QTY = 0;


        function rollup(BOXLABEL){
            $('tr.'+BOXLABEL).find("td.q").html(
                $('tr.'+BOXLABEL).find("div[qty]").toArray().reduce(function(total,el){ return total + parseInt( $(el).attr("qty")); },0)
            );
        }

        function AddTable(label, qty) {
            if(label=="")return ;
            if($('tr.'+label).length==0) {
                

                SendAction({ action:"aproductsfromboxlabel", data: { label:label }},0,function(a,b,c) {
                    
                    if(c.data.length==0)
                        var cd = { apr_box_label:label, apr_sku:'Not in system', apr_description:'n/a', apr_condition:'n/a' };
                    else
                        var cd = c.data[0];
                    
                    $('<tr class="'+label+'"><td>'+cd["apr_box_label"]+'</td><td>'+cd["apr_sku"]+'</td><td>'+cd['apr_description']+'</td><td>'+cd['apr_condition']+'</td><td class="q">'+qty+'</td><td><div qty='+qty+'>'+label+' x '+qty+'</div></td></tr>').appendTo($('#stocky tbody'));
                });


                
            }
            else {
                
                $('tr.'+label).find("td.q").html(  parseInt($('tr.'+label).find("td.q").html()) + qty );
                $('<div qty='+qty+'>\r\n'+label+' x '+qty+'</div>').appendTo($('tr.'+label).find("td").last());
            }
            /*SendAction({ action:"", data: {}},0, function(a,b,c){
                
            });*/
            rollup(label);
        }
        // Quick and simple export target #table_id into a csv
        function download_table_as_csv(table_id, separator = ',') {
            AddTable(BOXLABEL, 1);BOXLABEL="";

            // Select rows from table_id
            var rows = document.querySelectorAll('table#' + table_id + ' tr');
            // Construct csv
            var csv = [];
            for (var i = 0; i < rows.length; i++) {
                var row = [], cols = rows[i].querySelectorAll('td, th');
                for (var j = 0; j < cols.length; j++) {
                    // Clean innertext to remove multiple spaces and jumpline (break csv)
                    var data = cols[j].innerText.replace(/(\r\n|\n|\r)/gm, ' ').replace(/(\s\s)/gm, ' ')
                    // Escape double-quote with double-double-quote (see https://stackoverflow.com/questions/17808511/properly-escape-a-double-quote-in-csv)
                    data = data.replace(/"/g, '""');
                    // Push escaped string
                    row.push('"' + data + '"');
                }
                csv.push(row.join(separator));
            }
            var csv_string = csv.join('\n');
            // Download it
            var filename = 'export_' + table_id + '_' + new Date().toLocaleDateString() + '.csv';
            var link = document.createElement('a');
            link.style.display = 'none';
            link.setAttribute('target', '_blank');
            link.setAttribute('href', 'data:text/csv;charset=utf-8,' + encodeURIComponent(csv_string));
            link.setAttribute('download', filename);
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

    	function StockKeyUp1(ev, pr_id) {
            console.log("Event here ", ev, pr_id);
            if (ev.code == "Enter") {
                
                var scanned = ev.target.value;

                if(isNaN(parseInt(scanned))) {      
                    
                    if(BOXLABEL!=""){

                        var last_td = $('tr.'+BOXLABEL).find("td").last();
                        last_td.find("div").last().remove();
                        $('<div qty='+1+'>'+BOXLABEL+' x '+1+'</div>').appendTo(last_td);

                        rollup(BOXLABEL);
                    }


                    BOXLABEL = scanned;
                    QTY=0;   

                    if(BOXLABEL!="") {
                        AddTable(BOXLABEL,QTY==0 ? 0 : QTY);                        
                    }                                    
                               
                }
                else {
                    if(BOXLABEL!="")
                    {
                        QTY=parseInt(scanned);
                        
                        var last_td = $('tr.'+BOXLABEL).find("td").last();
                        last_td.find("div").last().remove();
                        $('<div qty='+QTY+'>'+BOXLABEL+' x '+QTY+'</div>').appendTo(last_td);
                        rollup(BOXLABEL);
                        QTY=0;
                        BOXLABEL="";
                    }
                }                                          
            ev.target.value="";
            }
	    }

        function SendAction(pd, pr_id, cb) {
		$.ajax({
			url: "/toolajax/" + pr_id,
			data: pd,

			type: 'POST',
			success: function(a) {
				if (typeof(a) == "string")
					a = JSON.parse(a);
				if (cb)
					cb(pd, pr_id, a);
			},
			error: function(a) {
				a = JSON.parse(a.responseJSON);
				if (cb)
					cb(pd, pr_id, a);
			}
		});
	}

</script>

<?php
}
else if($part=="warehouse_barcodes_creator")
{
        echo '<div class="page-body">
        <div class="container-fluid">
            <div class="row row-cards">
                <div class="col-12">
                    <div class="card">
                         <div class="card-header">
                            <h3 class="card-title">Warehouse Barcodes Creator</h3>
                            <div class="ms-2">
                          
                            </div>
                        </div>';
?>
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <form method="post" target="_blank" action="/stocksprintajax/print-sku-qty">
                                <div class="mb-3">
                                <label for="sku" class="form-label">SKU</label>
                                <input type="text" class="form-control" id="sku" name="sku" placeholder="Enter SKU" required>
                                </div>
                                <div class="mb-3">
                                <label for="qty" class="form-label">QTY</label>
                                <input type="text" class="form-control" id="qty" name="qty" placeholder="Enter QTY" required>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">Generate</button>
                                </div>
                                </form>
                             </div>
                         
                        </div>
                    </div>
                     
<?php                      
                    echo "</div>";
                echo "</div>";
            echo "</div>";
        echo "</div>";
    echo "</div>";

?>
   
<?php
}