<style>
    select.status { font-weight: bold; width:125px; padding:1px;}
    .stid { font-weight: bolder;}
</style>
<?php

include(PATH_CONFIG."/constants.php");


$_CHANGE_STATUSES = "";

for($i=0;$i<count($_STOCKCONFIG[$part]["status"]);$i++) {

    $data = $_STOCKCONFIG[$part]["status"][$i];
    
    $_CHANGE_STATUSES.="<option value='".$data."' style='color:".$_STATUSES[$data]["Color"]."'>".$_STATUSES[$data]["Name"]."</option>";

}



function MakeStatusText($t){
    if(!$t) return "";
    global $_STATUSES;

    foreach($_STATUSES as $key=>$val) {

        if($key == $t) {

            return "<span>"+$val["Name"]+"</span>";

        }

    }
}


$_TITLE="Laptop Stock";
$_FIELDS = [

    (object)array('sName' => 'st_id', 'title' => 'Stock ID', 'data' => 'st_id', 'type'=>'number' ),    
    
    (object)array('sName' => 'mf_name', 'title' => 'Manufacturer', 'data' => 'mf_name', 'type'=> 'string'),    
    
    
];

if($part=="search" || $part=="red" || $part=="darkblue" ||  $part=="orange" ||  $part=="purple" || $part=="lightgreen" || $part=="sold"|| $part=="black" || $part=="darkgreen" || $part=="gray" ||$part=="actioncmp"|| $part=="action"|| $part=="brown")
    $_FIELDS[] = (object)array('sName' => 'pr_name', 'title' => 'Model', 'data' => 'pr_name', 'type'=> 'string');

$_FIELDS[] = (object)array('sName' => 'ct_name', 'title' => 'Category', 'data' => 'ct_name', 'type'=> 'string');    
$_FIELDS[] = (object)array('sName' => 'st_servicetag', 'title' => 'Serial No', 'data' => 'st_servicetag', 'type'=> 'string');        


if( $part=="darkgreen" || $part=="search" || $part=="orange" || $part=="purple" || $part=="red" || $part=="lightblue" || $part=="lightgreen" || $part=="darkblue" || $part=="black" || $part=="gray"|| $part=="action" || $part=="brown") {
    $_FIELDS[] = (object)array('sName' => 'st_date', 'title' => 'Entered At', 'data' => 'st_date', 'type'=> 'string');
}




if( $part=="darkgreen") {
    //$_FIELDS[] = (object)array('sName' => 'st_retail', 'title' => 'Retail', 'data' => 'st_retail', 'type'=> 'string');    
}

if( $part=="stripped") {
    $_FIELDS[] = (object)array('sName' => 'st_strippeddate', 'title' => 'Date Stripped', 'data' => 'st_strippeddate', 'type'=> 'string');    
}



if($part=="sold" || $part=="darkgreen") {
    $_FIELDS[] = (object)array('sName' => 'st_allocatedto', 'title' => 'Name', 'data' => 'st_allocatedto', 'type'=> 'string');    
    
    if($part=="darkgreen")
        $_FIELDS[] = (object)array('sName' => 'st_advertised', 'title' => 'Advertised', 'data' => 'st_advertised', 'type'=> 'string');
    
    
    $_FIELDS[] = (object)array('sName' => 'st_soldprice', 'title' => 'Price', 'data' => 'st_soldprice', 'type'=> 'string');
    if($part=="sold")
        $_FIELDS[] = (object)array('sName' => 'st_solddate', 'title' => 'Sold Date', 'data' => 'st_solddate', 'type'=> 'string');
    
}
if($part=="brown")
        $_FIELDS[] = (object)array('sName' => 'st_state', 'title' => 'State', 'data' => 'st_state', 'type'=> 'string');


        
if($part=="action") {
    $_FIELDS[] = (object)array('sName' => 'st_actionreq_date', 'title' => 'Requested', 'data' => 'st_actionreq_date', 'type'=> 'string');
    $_FIELDS[] = (object)array('sName' => 'st_status_action', 'title' => 'Status', 'data' => 'st_status_action', 'type'=> 'string');   
}
if($part=="actioncmp") {
    $_FIELDS[] = (object)array('sName' => 'st_actioncmp_date', 'title' => 'Completed', 'data' => 'st_actioncmp_date', 'type'=> 'string');
    $_FIELDS[] = (object)array('sName' => 'st_status_action', 'title' => 'Original Status', 'data' => 'st_status_action', 'type'=> 'string');   
}
if($part=="lightblue")
        $_FIELDS[] = (object)array('sName' => 'st_record', 'title' => 'Rec/Ord', 'data' => 'st_record', 'type'=> 'string');


if($part!=="sold")
    $_FIELDS[] = (object)array('sName' => 'st_lastcomment', 'title' => 'Last Comment', 'data' => 'st_lastcomment', 'type'=> 'string');



?>
<style>
    .markdown>table, .table {
    --tblr-table-bg: transparent;
    --tblr-table-accent-bg: #fff;
    }
    .markdown>table>thead, .table>thead {
        background-color: #f4f6fa;
    }
</style>
                                 
<div class="page-body">
<?php
$status_color='btn-'.$part;
switch ($part){
	case "lightblue":
		 $status_color="btn-cyan";
		break;
	case "darkgreen":
		 $status_color="btn-teal";
		break;
	case "lightgreen":
		 $status_color="btn-lime";
		break;
	case "brown":
		 $status_color="btn-pinterest";
		break;
	case "darkblue":
		 $status_color="btn-indigo";
		break;
	case "action":
		 $status_color="btn-dribbble";
		break;
	case "actioncmp":
		 $status_color="btn-rss ";
		break;
	case "sold":
		 $status_color="btn-secondary";
		break;
}

?>


    <div class="container-fluid">
        <div class="row row-cards">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><?php echo $_TITLE; ?></h3>
                    </div>
                    <div class="card-body border-bottom py-3"  style="position:sticky;top:0;">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="page-pretitle">
                                    <h2 class="btn btn-square <?php echo $status_color; ?>" style="width:100%;"><?php echo $part ." - ". $_STOCKCONFIG[$part]["title"]; ?></h2>
                                </div>
                            </div>
                           
                        </div>
                    </div>                 
                      
                    <div class="table-responsive" style="padding:5px;margin:5px;">
                    <div class="form-group row">
                    <label for="category"  class="col-sm-1 col-form-label">Category</label>
                    <div class="col-sm-2">
                            <select onchange="window.ETable.fnDraw()" class="form-control" id="category" name="category">
                                
                                <option value=0>[ -- Category -- ]</option>
                                <?php
                                        $categoies = M::db()->select("categories",["ct_id","ct_name"], ["ORDER" => "ct_name"]);
                                       





                                             //stock filters   
                                        if($part=="orange")
                                        $_SF = "st_actionreq=0 AND pr_part=0 AND st_status=1";
                                    elseif($part=="purple")
                                        $_SF = "st_actionreq=0 AND pr_part=0 AND st_status=2";
                                    elseif($part=="red")
                                        $_SF = "st_actionreq=0 AND pr_part=0 AND st_status=3";
                                    elseif($part=="lightblue")
                                        $_SF = "st_actionreq=0 AND pr_part=0 AND st_status=4";
                                    elseif($part=="darkblue")
                                        $_SF = "st_actionreq=0 AND pr_part=0 AND st_status=5";        
                                    elseif($part=="lightgreen")
                                        $_SF = "st_actionreq=0 AND pr_part=0 AND st_status=6";    
                                    elseif($part=="darkgreen")
                                        $_SF = "st_actionreq=0 AND pr_part=0 AND st_status=7";    
                                    elseif($part=="black")
                                        $_SF = "st_actionreq=0 AND pr_part=0 AND st_status=8";    
                                    elseif($part=="stripped")
                                        $_SF = "st_actionreq=0 AND pr_part=0 AND st_status=24";   
                                    elseif($part=="gray")
                                        $_SF = "st_actionreq=0 AND pr_part=0 AND st_status=9";    
                                    elseif($part=="action")
                                        $_SF = "((st_actionreq=1 AND pr_part=0) OR st_status=17)";    
                                    elseif($part=="actioncmp")
                                        $_SF = "st_actionreq=0 AND pr_part=0 AND st_status=18";    
                                    elseif($part=="brown")
                                        $_SF = "st_actionreq=0 AND pr_part=0 AND st_status=11";    
                                    elseif($part=="sold")
                                        $_SF = "st_actionreq=0 AND pr_part=0 AND st_status=16";    
				else $_SF =" 1= 1 ";

                                        $cxx = M::db()->exec("select pr_category from stock join products on stock.st_product = products.pr_id where ".$_SF." group by pr_category")->fetchAll();
                                        $cx = array();
                                        foreach($cxx as $v)  array_push($cx, $v[0]);





                                    
										foreach($categoies as $key=>$value) {
                                            if(in_array($value["ct_id"], $cx))

                                            echo "<option value=".$value["ct_id"].">".$value["ct_name"]."</option>";
                                        
                                        }
                                ?>
                            </select>
                                    </div>
                            </div>
                        <table id="dataList" class="table stripe card-table table-vcenter hover text-nowrap datatable table-sm">
                            <thead>
                                <tr>
                                    <th class='th_checkbox'></th>
                              
                              
                                <?php
                                if(isset($_FIELDS) && count($_FIELDS)>0) {
                                    for($i=0;$i<count($_FIELDS);$i++) {                                        
                                    $ad = $_FIELDS[$i];                
                                    
                                    if(  ( $_STOCKCONFIG[$part]["comment_status"] === true && 
                                        !($part=="action" || $part=="darkgreen") && $ad->data=="st_lastcomment") || ($ad->data=="st_retail" && $part=="darkgreen") ) {  
                                        echo "<th class='th_st_status'>Status</th>";
                                    }
                                    if($part=="search" && $ad->data=="st_lastcomment") {
                                        echo "<th class='th_st_status'>Status</th>";
                                    }

                                    if($part=="darkgreen")
                                        if($ad->sName=="st_lastcomment" )
                                            continue;
                  
                                    
                                    echo '<th class="th_'.$ad->sName.'" >'.$ad->title.'</th>'."\r\n";
                                    }
                                }
                                ?>   
                                  <?php
                                    if($part=="action") {
                                        echo "<th style='width:95px'></th><th style='width:95px'></th>";
                                    }
                                    if($part=="actioncmp") {
                                        echo "<th style='width:95px'></th>";
                                    }
                                    if($part=="darkgreen") {
                                        echo "<th></th>";
                                    }
                                ?>                             
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="card-footer d-flex align-items-center">
                        <div class="btn-group" role="group" aria-label="Basic example">
                         
                            <?php
                                    if($part!="sold" && $part!="search")
                                        echo '<button class="btn btn-sm btn-primary" onclick="updateChanges()">Update</button>';

                                    if($part=="orange") {
                                        echo '<button class="btn btn-sm btn-muted" onclick="PrintBoxLabels()">Print Box Labels</button>';
                                        echo '<button class="btn btn-sm btn-muted" onclick="PrintTrackingSheet()">Print Tracking Sheet</button>';
                                    }
                                    if($part=="lightgreen") {
                                        echo '<button class="btn btn-sm btn-muted" onclick="PrintBoxLabels()">Print Box Labels</button>';
                                        echo '<button class="btn btn-sm btn-muted" onclick="PrintBoxZlabels()">Print Z Labels</button>';
                                        echo '<button class="btn btn-sm btn-muted" onclick="PrintTrackingSheet()">Print Tracking Sheet</button>';
                                    }
                                    if($part=="gray") {
                                        echo '<button class="btn btn-sm btn-warning" onclick="ReturnedForRefund()">Returned For Refund</button>';                                        
                                    }
                                    if($part=="sold") {
                                        echo '<button class="btn btn-sm btn-success" onclick="DispatchChanges()">Dispatch</button>';
                                        echo '<button class="btn btn-sm btn-warning" onclick="BuyerCancelled()">Buyer Cancelled</button>';                                        
                                    }
                                    if($part=="red") {
                                        echo '<button class="btn btn-sm btn-muted" onclick="PrintBoxLabels()">Print Box Labels</button>';                                        
                                    } 
                                    if($part=="purple" ||$part=="brown")
                                        echo '<button class="btn btn-sm btn-success" onclick="PrintServiceLabels()">Print Service Labels</button>';    
                                    if($part=="darkgreen") {
                                        //echo '<button class="btn btn-sm btn-success" onclick="AllocateChanges()">Allocate</button>';
                                        echo '<button class="btn btn-sm btn-warning" onclick="SoldChanges()">Sold</button>';
                                        //echo '<button class="btn btn-sm btn-muted" onclick="PrintSaleSlips()">Print Sales Slip(s)</button>';
                                        echo '<button class="btn btn-sm btn-muted" onclick="PrintBoxLabels()">Print Box Labels</button>';
                                        echo '<button class="btn btn-sm btn-muted" onclick="PrintBoxZlabels()">Print Z Labels</button>';
                                        //echo '<button class="btn btn-sm btn-muted" onclick="StatusChanges()">Change Status</button>';                                                                            
                                    }

                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<form id="stock_items_form" name="stock_items_form" action="" method="post" style="display:none">
    <input type="hidden" id="stock_items" name="stock_items"/> 
</form>

<script>


function statusText(st) {

    if(!st || st=="") return "";
    var arr=[];
    <?php
    global $_STATUSES;
    foreach($_STATUSES as $key=>$val) {
       echo "\tarr[".$key."] = '<span style=\"color:".$val["Color"]."\">".$val["Name"]."</span>';\r\n";
    }
    ?>
    return  arr[st];
}

function dropDownRecOrd(dat) {
       
   var arr=[];
   <?php
   global $_RECORD;
   foreach($_RECORD as $key=>$val) {
      echo "\tarr[".$key."] = '$val';\r\n";
   }
   ?>
   return makeDropDown(dat,arr,"changeRecOrd(event,this)",false);
}

function dropDownState(dat) {
   
   var arr=[];
   <?php
   global $_STATE;
   foreach($_STATE as $key=>$val) {
      echo "\tarr[".$key."] = { N:'".$val["Name"]."', C:'".$val["Color"]."' };\r\n";
   }
   ?>
   return makeDropDown(dat,arr,"changeState(event,this)",false);
}
function dropDownAdvertised(dat) {
   
    var arr=[];
    <?php
    global $_ADVERTISED;
    foreach($_ADVERTISED as $key=>$val) {
       echo "\tarr[".$key."] = { N:'".$val["Name"]."', C:'".$val["Color"]."' };\r\n";
    }
    ?>
    return makeDropDown(dat,arr,"changeAdvertised(event,this)", true, "Unlisted","red");
}

function changeColor(el) {
    el.style.color = "black";
    if(el.options[el.selectedIndex].style.color) {
        el.style.color =el.options[el.selectedIndex].style.color;
    }
}

function makeDropDown(dat, arr, onchange='void(0)', needEmpty, emptyName="Empty", emptyColor="black") {
    if(typeof needEmpty == "undefined") needEmpty=true;
    var selectedColor="";
    var r = "<select class='form-control form-control-sm' onchange='changeColor(this); "+onchange+"'>";
    if(needEmpty==true) r+="<option value='' style='color:"+emptyColor+"'>"+emptyName+"</option>";
    arr.forEach(function( v,i){        
        if(typeof v =="object") {
            r+="<option style='color:"+v.C+"' value="+i+" "+( i==dat ?"selected" :"" )+">"+v.N+"</option>";
            if(i==dat) selectedColor=v.C || "black";
        }
        else {
            r+="<option style='color:black' value="+i+" "+( i==dat ?"selected" :"" )+">"+v+"</option>";
            if(i==dat) selectedColor=v.C || "black";
        }
    });

    if(selectedColor=="") {
        selectedColor=emptyColor;
    }

    r  = r.replace("select class","select style='color:"+selectedColor+"' class");

    r+="</select>";
    return r;
}

$(function() {
    var f1 = "<?php echo $_FIELDS[0]->data;?>";
    window.ETable = $('#dataList').dataTable({
     
        "lengthChange": false,
        "processing":true,
        "serverSide":true,
        "infoEmpty": "No records available",
        "sProcessing": "DataTables is currently busy",
        "aLengthMenu": [[10, 25, 50,100], [10, 25, 50,100]],
        "iDisplayLength": 50,              
        "order":[],
        "dom": '<l<<"extra">f>rt<ip>>',
        "search": { "search" : new URLSearchParams(window.location.search).get("search")},
        "ajax":{
            url:"/stocksajax/<?php echo $part; ?>",
            type:"POST",
            data: function (d) { d.category=$('#category').val(); d.action='search';},
            dataType:"json"
        },
        
        "columns" :[           

            { "data":null, sortable:false, searchable:false, "render":function(data, type, row){ 
                return "<input  class='form-check-input dts' type=checkbox iden='s"+row.st_id+"' />"; } },
         
            

            <?php
            if(isset($_FIELDS) && count($_FIELDS)>0) {
                for($i=0;$i<count($_FIELDS);$i++) {                    
                    $ad = $_FIELDS[$i];      
                    
                    if(  ( $_STOCKCONFIG[$part]["comment_status"] === true && !($part=="action" || $part=="darkgreen") && $ad->data=="st_lastcomment") || ($ad->data=="st_retail" && $part=="darkgreen") ) {  
                    
                        ?>
                        { "data":null, sortable:false, searchable:false, 
                                "render":function(data, type, row){ 
                                        return "<select name='status_"+row.st_id+"' iden='s"+row.st_id+"' onChange='this.style.color = this.options[this.selectedIndex].style.color;' style='width:125px;text-align:center' class='form-control status'><?php echo $_CHANGE_STATUSES; ?></select>"                                         
                                } 
                        },
                    <?php                             
                    }
                    if($part=="search" &&  $ad->sName=="st_lastcomment") {
                        echo '{"data" : "'.$ad->data.'", "render":function(data,type,row){ return statusText(row.st_status); } },'."\r\n";
                    }
                    

                    if($ad->data=="st_id")                      
                        echo '{"data" : "'.$ad->data.'", "render":function(dat){ return "<a class=stid href=\'/stock/"+dat+"?s='.$part.'\'>"+dat+"</a>";}},'."\r\n";

                    else if($ad->data=="st_date" || $ad->data=="st_actionreq_date" || $ad->data=="st_actioncmp_date") {
                        echo '{"data" : "'.$ad->data.'", "render":function(dat){ return timeAgo(dat); }},'."\r\n";
                    }
                    else if( ($ad->data=="st_status" || $ad->data =="st_status_action")) {
                        echo '{"data" : "'.$ad->data.'", "render":function(dat){ return statusText(dat); }},'."\r\n";
                    }
                    else if($ad->data=="st_advertised") {
                        echo '{"data" : "'.$ad->data.'", "render":function(dat,type,row){ return "<div data-id="+row.st_id+" >"+dropDownAdvertised(dat)+"</div>"; }},'."\r\n";
                    }
                    else if($ad->data=="st_state") {
                        echo '{"data" : "'.$ad->data.'", "render":function(dat,type,row){ return "<div data-id="+row.st_id+" >"+dropDownState(dat)+"</div>"; }},'."\r\n";
                    }
                    else if($ad->data=="st_record") {
                        echo '{"data" : "'.$ad->data.'", "render":function(dat,type,row){ return "<div data-id="+row.st_id+" >"+dropDownRecOrd(dat)+"</div>"; }},'."\r\n";
                    }                    
                    else  if( ( $part=="darkgreen") &&  $ad->sName=="st_lastcomment")
                            continue;
                    else if($part!=="search"  && $ad->data=="st_lastcomment") {                                     
                        echo '{"data" : "'.$ad->data.'", "render":function(dat,type,row){ return "<input type=hidden  name=\"hcomment_"+row.st_id+"\"  iden=\"s"+row.st_id+"\" class=\"hcomment form-control form-control-sm\" value=\'"+dat+"\'><input type=text  name=\"comment_"+row.st_id+"\" onkeyup=\'CommentTokeyUp(event,"+row["st_id"]+")\' iden=\"s"+row.st_id+"\" class=\"comment form-control form-control-sm\" value=\'"+dat+"\'>";  }},'."\r\n";
                    }
                    else if( $part=="darkgreen" && ( $ad->data=="st_allocatedto" )) {
                        echo '{"data" : "'.$ad->data.'", "render":function(dat,type,row){ if(row["st_allocated"]=="1") return dat; else return "<input style=\'width:190px\' iden="+row["st_id"]+"  type=text onkeyup=\'AllocatedTokeyUp(event,"+row["st_id"]+")\' class=\'form-control form-control-sm "+row["st_id"]+" '.$ad->data.'\' />" }},'."\r\n";
                    }
                    else if ($part=="darkgreen" && $ad->data=="st_soldprice") {
                        echo '{"data" : "'.$ad->data.'", "render":function(dat,type,row){ if(row["st_allocated"]=="1") return dat; else return "<input style=\'width:50px\' type=text class=\'form-control form-control-sm "+row["st_id"]+" '.$ad->data.'\' />" }},'."\r\n";
                    }
                    else                         
                        echo '{"data" : "'.$ad->data.'"},'."\r\n";
                }
            }
            ?>

            <?php
                if($part=="action") {
            ?>
                
                { "data":null, sortable:false, searchable:false, "render":function(data, type, row){ return "<button class='btn btn-sm btn-success' onClick='actionCompleted("+row["st_id"]+")'>Complete</button>"; } },                                
            <?php 
                }
            ?>

            <?php
                if($part=="action" || $part=="actioncmp") {
                ?>
                { "data":null, sortable:false, searchable:false, "render":function(data, type, row){ return "<button class='btn btn-sm btn-warning' onClick='actionCancelled("+row["st_id"]+")'>Cancel</button>"; } },                
            <?php 
            }
            ?>

            <?php
                if($part=="darkgreen") {
            ?>
                
                { "data":null, sortable:false, searchable:false, "render":function(data, type, row){ if(row.st_allocated==0) return ""; else  return "<button class='btn btn-sm btn-primary' onClick='unallocateStock("+row["st_id"]+","+row["st_advertised"]+")'>Unallocate</button>"; } },                                
            <?php 
                }
            ?>

            
            
             
        ],
      
      
    });		


    $('body').on("focus","textarea",function(el){ console.log(el); $(el.currentTarget).width(400); $(el.currentTarget).height(250); });
    $('body').on("blur","textarea",function(el){ console.log(el);  $(el.currentTarget).width(100); $(el.currentTarget).height(25); });


});

function unallocateStock(st_id, st_advertised) { 
    if(st_advertised!=0&&st_advertised!=4) {
        alert("Only allowed when advertising status is unlisted!");
        return;
    }
    var pd = { action: "unallocate"  };
    SendAction(pd,st_id);       
    
}


function CommentTokeyUp(ev, st_id) {
    console.log("Event here ",ev);
    if(ev.code=="Enter") {
        var pd = { action: "comment", data: { 
            cm: ev.target.value            
         } };
        SendAction(pd,st_id);       
    }
}

function AllocatedTokeyUp(ev, st_id) {
    console.log("Event here ",ev);
    if(ev.code=="Enter") {
        var pd = { action: "allocate", data: { 
            allocatedto: ev.target.value            
         } };
        SendAction(pd,st_id);       
    }
}
function ReturnedForRefund() {
    var pd = { action: "returnedforrefund", data: {} };
    $('.dts:checked').each(function(i,el) {
        $el = $(el);
        var st_id = $el.attr("iden").replace("s","");
        var alto  = el.value;                    
        if(alto !="" ){
            pd.data = {            
                             
            };
            SendAction(pd,st_id);   
        }        
    });     
}
function BuyerCancelled() {
    var pd = { action: "buyercancelled", data: {} };
    $('.dts:checked').each(function(i,el) {
        $el = $(el);
        var st_id = $el.attr("iden").replace("s","");
        var alto  = el.value;                    
        if(alto !="" ){
            pd.data = {            
                             
            };
            SendAction(pd,st_id);   
        }        
    });     
}
function DispatchChanges() {
    var pd = { action: "dispatch", data: {} };
    $('.dts:checked').each(function(i,el) {
        $el = $(el);
        var st_id = $el.attr("iden").replace("s","");
        var alto  = el.value;                    
        if(alto !="" ){
            pd.data = {            
                             
            };
            SendAction(pd,st_id);   
        }        
    });     
}

function SoldChanges() {
    var pd = { action: "sold", data: {} };
    $('.st_allocatedto').each(function(i,el) {
        $el = $(el);
        var st_id = $el.attr("iden");
        var alto  = el.value;
        
        var pric = $('.st_soldprice.'+st_id).val();
        var checked = $('input[iden="'+st_id+'"]:checked').length;        
        if(alto !="" && pric !=""){
            pd.data = {            
                allocatedto: alto,                    
                allocateprice : pric
            };
            SendAction(pd,st_id);   
        }        
    });        
}

function SendAction(pd,st_id) {
    $.ajax({ 
            url:"/stocksajax/"+st_id,
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

function actionCompleted(st_id) {
    actionTo(st_id,'actioncompleted');
}
function actionCancelled(st_id) {
    actionTo(st_id,'actioncancelled');
}
function actionTo(st_id, action) {
    var pd = { action: action  };
    $.ajax({ 
        url:"/stocksajax/"+st_id,
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
function PrintSaleSlips(){
    PrintSection("print-slips");
}
function PrintServiceLabels(){
    PrintSection("print-service-labels");
}
function PrintTrackingSheet() {
    PrintSection("print-tracking-sheet");
}
function PrintBoxZlabels() {
    PrintSection("print-box-zlabels");
}
function PrintBoxLabels() {
    PrintSection("print-box-labels");
}
function PrintSection(type) {
    var stock_items = []; 
    $('input[type="checkbox"]:checked').each(function(a,b){ stock_items.push($(b).attr("iden").replace("s",""));}); 
    console.log(stock_items.join(","));
    $('#stock_items').val(stock_items.join(","));
    if(stock_items.length>0){
        
        $('#stock_items_form').attr("action","../stocksprintajax/"+type);
        $('#stock_items_form').attr("target","_blank");
        $('#stock_items_form').submit();
        
    }
}

function changeState(ev,el) {
    var data = {};
    $el = $(el); 
    var alto  = el.value;
    data = {
        stt: alto
    }

    SendAction({ action:"stated", data:  data}, $el.parent().data("id"));
        
}

function changeAdvertised(ev,el) {
    var data = {};
    $el = $(el); 
    var alto  = el.value;
    data = {
        adv: alto
    }

    SendAction({ action:"advertised", data:  data}, $el.parent().data("id"));
        
}


function changeRecOrd(ev,el) {
    var data = {};
    $el = $(el); 
    var alto  = el.value;
    data = {
        rec: alto
    }

    SendAction({ action:"record", data:  data}, $el.parent().data("id"));
        
}


function AllocateChanges() {
    var data = {};
    $('.st_allocatedto').each(function(i,el) {
        $el = $(el);
        var st_id = $el.attr("iden");
        var alto  = el.value;
        
        var pric = $('.st_soldprice.'+st_id).val();
        var checked = $('input[iden="'+st_id+'"]:checked').length;
    
        if(checked=="1") {
            if(alto !=""){
                data[st_id] = {
                    alto: alto,                    
                    pric : pric
                }
            }
        }

    });
}

function updateChanges() {
    var data = {};
    $('input.comment').each(function(i,el) {
        var iden = $(el).attr("iden");
        var pel = $('input.hcomment[iden="'+iden+'"]')[0];
        if(el.value != pel.value) {
            var pd = data[iden] || { };
            pd["cm"] = el.value;
            data[iden] = pd;
        }
    });
    $('select.status').each(function(i,el) {
        var iden = $(el).attr("iden");
        if(el.value!="" && el.value!="0" && el.value!=null) {
            var pd = data[iden] || { };
            pd["st"] = el.value;
            data[iden] = pd;
            
        }
    });


    var pd = { action: "update", data: data };

    $.ajax({ 
        url:"/stocksajax/<?php echo $part;?>",
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
</script>
<?php
