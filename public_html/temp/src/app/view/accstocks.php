<style>
	select.status {
		font-weight: bold;
		width: 125px;
		padding: 1px;
	}

	.stid {
		font-weight: bolder;
	}
    .markdown>table, .table {
    --tblr-table-bg: transparent;
    --tblr-table-accent-bg: #fff;
    }
    .markdown>table>thead, .table>thead {
        background-color: #f4f6fa;
    }
</style>
<?php
	$db=M::db();
	include(PATH_CONFIG."/constants.php");

    //$BATCHED = isset($_GET["BATCHED"]) && $_GET["BATCHED"]=="1" ? 1 : 0;

	$_CHANGE_STATUSES = "";
	for($i=0;$i<count($_ASTOCKCONFIG[$part]["status"]);$i++) {
		$data = $_ASTOCKCONFIG[$part]["status"][$i];    
        
		$_CHANGE_STATUSES.="<option value='".$data."' style='color:".$_ACSTATUSES[$data]["Color"]."'>".$_ACSTATUSES[$data]["Name"]."</option>";
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
	$_TITLE="Accessory Stocks";


    if($BATCHED==0){

        $_FIELDS = [
            (object)array('sName' => 'ast_id', 'title' => 'ID', 'data' => 'ast_id', 'type'=>'number' ),    
            (object)array('sName' => 'apr_image', 'title' => 'Image', 'data' => 'apr_image', 'type'=> 'string'),
            (object)array('sName' => 'apr_sku', 'title' => 'SKU', 'data' => 'apr_sku', 'type'=> 'string'),
            (object)array('sName' => 'apr_name', 'title' => 'Name', 'data' => 'apr_name', 'type'=> 'string'),      
        ];
        

        if(!($part=="rfs"||$part=="rts")) {

            $_FIELDS[] = (object)array('sName' => 'ct_name', 'title' => 'Category', 'data' => 'ct_name', 'type'=> 'string');    
            $_FIELDS[] = (object)array('sName' => 'apr_condition', 'title' => 'Condition', 'data' => 'apr_condition', 'type'=> 'string');        
        }

        $_FIELDS[] = (object)array('sName' => 'ast_servicetag', 'title' => 'Service Tag', 'data' => 'ast_servicetag', 'type'=> 'string');    
        //$_FIELDS[] = (object)array('sName' => 'apr_box_label', 'title' => 'Box Label', 'data' => 'apr_box_label', 'type'=> 'string');        
        //$_FIELDS[] = (object)array('sName' => 'apr_box_subtitle', 'title' => 'Box Subtitle', 'data' => 'apr_box_subtitle', 'type'=> 'string');      
        
        if($part=="rfs"||$part=="rts") {
            $_FIELDS[] = (object)array('sName' => 'aor_date', 'title' => 'Order Date', 'data' => 'aor_date', 'type'=> 'string');        
            $_FIELDS[] = (object)array('sName' => 'sp_name', 'title' => 'Supplier', 'data' => 'sp_name', 'type'=> 'string');        
            $_FIELDS[] = (object)array('sName' => 'aor_reference', 'title' => 'Order Reference', 'data' => 'aor_reference', 'type'=> 'string');        
        }
        

        $_FIELDS[] = (object)array('sName' => 'ast_lastcomment', 'title' => 'Last Comment', 'data' => 'ast_lastcomment', 'type'=> 'string');

    }
    else {
        $_FIELDS = [
            (object)array('sName' => 'apr_id', 'title' => 'ID', 'data' => 'apr_id', 'type'=>'number' ),                
            (object)array('sName' => 'apr_sku', 'title' => 'SKU', 'data' => 'apr_sku', 'type'=> 'string'),
            (object)array('sName' => 'apr_name', 'title' => 'Name', 'data' => 'apr_name', 'type'=> 'string'),      
        ];
        $_FIELDS[] = (object)array('sName' => 'ct_name', 'title' => 'Category', 'data' => 'ct_name', 'type'=> 'string');    
        $_FIELDS[] = (object)array('sName' => 'apr_condition', 'title' => 'Condition', 'data' => 'apr_condition', 'type'=> 'string');                

        $_FIELDS[] = (object)array('sName' => 'apr_listed', 'title' => 'List Status', 'data' => 'apr_listed', 'type'=> 'string');                
        

        $_FIELDS[] = (object)array('sName' => 'counter', 'title' => 'QTY', 'data' => 'counter', 'type'=> 'string');
    }
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
	case "sold":
		 $status_color="btn-secondary";
		break;
	case "black":
		 $status_color="btn-dark";
		break;

}

?>
<script>

function dropDownListed(dat) {
   
   var arr=[];

   arr[0]={ N:'Listed', C:'green' };
   arr[1]={ N:'Unlisted', C:'red' };
    
    
   return makeDropDown(dat,arr,"changeListed(event,this)", true, "","red");
}
</script>
<div class="page-body">
    <div class="container-fluid">
		<div class="col-lg-11" style="margin:0 auto;">
			<div class="card card-lg">
				<div class="card-body" style="padding:3rem 1rem;">
					<div class="row align-items-center">
						<div class="col-auto">
							<h2 style="margin-left:1rem;"><?php echo $_TITLE; ?></h2>
						</div>
					</div>
						
                    <div class="card-body py-3" style="padding-bottom:0 !important; position:sticky;top:0;">

                        <div class="row align-items-center">
                            <div class="col">
                                <div class="page-pretitle"> 
                                    <h2 class="btn btn-square <?php echo $status_color;?>" style="width:100%;"><?php echo $part ." - ". $_ASTOCKCONFIG[$part]["title"]; ?></h2>
                                </div>
                            </div>                           
                        </div>

                        <div class="row align-items-center">
                            <div class="col-3" style="margin:0 auto;width:30%;">                                    
								<p style="margin-bottom:0;">
									<?php
										if($part != "sold") 
										{
											echo '<input autofocus tabindex=5 type="text" class="form-control" style="text-align: center;" placeholder="Scan Serial Number to Move Item Here" id="scan_move" name="scan_move" autocomplete="off"/>';
										}
									?>
								</p>  
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
                                        $_SF = "ast_status=1";
                                        elseif($part=="purple")
                                        $_SF = "ast_status=2";
                                        elseif($part=="red")
                                        $_SF = "ast_status=3";
                                        elseif($part=="lightblue")
                                        $_SF = "ast_status=4";
                                        elseif($part=="darkblue")
                                        $_SF = "ast_status=5";        
                                        elseif($part=="lightgreen")
                                        $_SF = "ast_status=6";
                                        elseif($part=="green")
                                        $_SF = "ast_status=22";    
                                        elseif($part=="darkgreen")
                                        $_SF = "ast_status=7";    
                                        elseif($part=="black")
                                        $_SF = "ast_status=8";    
                                        elseif($part=="stripped")
                                        $_SF = "ast_status=24";   
                                        elseif($part=="gray")
                                        $_SF = "ast_status=9";    
                                        elseif($part=="action")
                                        $_SF = "(ast_status=17)";    
                                        elseif($part=="actioncmp")
                                        $_SF = "ast_status=18";    
                                        elseif($part=="brown")
                                        $_SF = "ast_status=11";    
                                        elseif($part=="sold")
                                        $_SF = "ast_status=16";  
                                        elseif($part=="senttofba")
                                        $_SF = "ast_status=29"; 
                                        elseif($part=="rts")
                                        $_SF = "ast_status=43"; 
                                        elseif($part=="rfs")
                                        $_SF = "ast_status=44"; 

                                        $cxx = M::db()->exec("select apr_category from acc_stock join aproducts on acc_stock.ast_product = aproducts.apr_id where acc_stock.".$_SF." group by apr_category")->fetchAll();
                                        $cx = array();
                                        foreach($cxx as $v)  array_push($cx, $v[0]);


										foreach($categoies as $key=>$value) {
                                            if(in_array($value["ct_id"], $cx))										
										        echo "<option value=".$value["ct_id"].">".$value["ct_name"]."</option>";
										}
									?>
								</select>
							</div>
                            <div class="col-sm-2">
                            
                                <a class="btn btn-indigo" style="width:2.5rem;" href="/accstocks/<?php echo $part; ?>"  data-bs-toggle="tooltip" data-bs-placement="top" title="List View">

<!-- Download SVG icon from http://tabler-icons.io/i/menu-2 -->
	<svg xmlns="http://www.w3.org/2000/svg" class="icon" style="margin:0" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="4" y1="6" x2="20" y2="6" /><line x1="4" y1="12" x2="20" y2="12" /><line x1="4" y1="18" x2="20" y2="18" /></svg>
</a>  

<a class="btn  btn-purple" style="width:2.5rem;" href="/accstocks_batch/<?php echo $part; ?>"  data-bs-toggle="tooltip" data-bs-placement="top" title="Batch View">

<!-- Download SVG icon from http://tabler-icons.io/i/box-multiple -->
	<svg xmlns="http://www.w3.org/2000/svg" class="icon" style="margin:0" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="7" y="3" width="14" height="14" rx="2" /><path d="M17 17v2a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2v-10a2 2 0 0 1 2 -2h2" /></svg>

</a>
                                        
                            </div>
						</div>
						<table id="dataList" class="table stripe card-table table-vcenter hover text-nowrap datatable table-sm">
							<thead>
								<tr>
								<!--	<th class='th_checkbox'>A</th> -->
									<?php
										if(isset($_FIELDS) && count($_FIELDS)>0) {
										for($i=0;$i<count($_FIELDS);$i++) {                                        
										$ad = $_FIELDS[$i];                

										if(  $ad->data=="ast_lastcomment" && $part=="black" /*( $_ASTOCKCONFIG[$part]["comment_status"] === true && 
										!($part=="action" || $part=="darkgreen") && $ad->data=="ast_lastcomment") || ($ad->data=="ast_retail" && $part=="darkgreen") */) {  
										echo "<th class='th_ast_status'>Status1</th>";
										}
										if($part=="search" && $ad->data=="ast_lastcomment" && $part!=="sold") {
										echo "<th class='th_ast_status'>Status2</th>";
										}

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
										//echo "<th></th>";
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

								if($part=="sold")
									echo 'Returned from Customer <input tabindex=5 type="text" class="form-control" id="scan_return" name="scan_return" />';
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

function condition_check(r){

    var part = "<?php echo $part;?>";

    if(part == "lightgreen" && r.apr_condition != "Refurbished (Grade B)")
        return "<span title='Condition and SKU mismatch!'>⚠️</span>";
    if(part == "green" && r.apr_condition != "Refurbished")
        return "<span title='Condition and SKU mismatch!'>⚠️</span>";
    if(part == "darkgreen" && r.apr_condition != "New" && r.apr_condition!='New - Open Box' && r.apr_condition !='New - Brown Box')
        return "<span title='Condition and SKU mismatch!'>⚠️</span>";
        
    return "";

};


$(function() {
    var f1 = "<?php echo $_FIELDS[0]->data;?>";
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
        "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50,100, 'All']],
        
        "iDisplayLength": 50,              
        "order":[],
        "dom": '<<<lB>f>rt<ip>>',
        "search": { "search" : new URLSearchParams(window.location.search).get("search")},
        "ajax":{
            url:"/accstocksajax/<?php echo $part; ?>",
            type:"POST",
            data: function (d) { d.BATCHED=<?php echo $BATCHED;?>;  d.category=$('#category').val(); d.action='search';},
            dataType:"json"
        },
        
        "columns" :[           

 <!--           { "data":null, sortable:false, searchable:false, "render":function(data, type, row){  -->
  <!--              return "<input  class='form-check-input dts' type=checkbox iden='s"+row.ast_id+"' />"; } }, -->
         
            

            <?php
            if(isset($_FIELDS) && count($_FIELDS)>0) {
                for($i=0;$i<count($_FIELDS);$i++) {                    
                    $ad = $_FIELDS[$i];      
                    

                    if($part=="search" &&  $ad->sName=="st_lastcomment"  && $part!=="black") {
                        echo '{"data" : "'.$ad->data.'", "render":function(data,type,row){ return statusText(row.st_status); } },'."\r\n";
                    }
                    

                    if($ad->data=="ast_id")                      
                        echo '{"data" : "'.$ad->data.'", "render":function(dat,type,row){ return condition_check(row)+ "<a class=stid href=\'/accstock/"+dat+"?s='.$part.'\'>"+dat+"</a>";}},'."\r\n";

                    else if($ad->data=="apr_image") 
                        echo '{"data" : "'.$ad->data.'", "render":function(dat,type,row){ return "<a href=\'javascript:PrintBoxLabels("+row.ast_id+")\'><img src=\'https://img.icons8.com/material-outlined/24/000000/print.png\'></a> <a href=\'https://www.ndc.co.uk/pub/media/catalog/product"+dat+"\' onClick=\'return linkModelImage(this,event)\' target=_blank><img src=\'https://img.icons8.com/material-outlined/24/000000/image.png\'/></i></a>"  }},'."\r\n";                        
                    else if($ad->data=="st_date" || $ad->data=="st_actionreq_date" || $ad->data=="st_actioncmp_date") {
                        echo '{"data" : "'.$ad->data.'", "render":function(dat){ return timeAgo(dat); }},'."\r\n";
                    }
                    else if( ($ad->data=="st_status" || $ad->data =="st_status_action")) {
                        echo '{"data" : "'.$ad->data.'", "render":function(dat){ return statusText(dat); }},'."\r\n";
                    }
                    else if($ad->data=="apr_sku") {
                        echo '{"data" : "'.$ad->data.'", "render":function(dat,type,row){ return "<div data-id="+row.ast_id+" >"+ (dat==null ? "<a href=\"javascript:void(0)\" onclick=\"assign_new(this)\">No Product Assigned – Assign</a>" : dat+" <a onclick=\'change_sku(\""+row.ast_servicetag+"\",\""+row.apr_id+"\")\' href=#>📇</a>") + "</div>"; }},'."\r\n";
                    }
                    else if($ad->data=="st_state") {
                        echo '{"data" : "'.$ad->data.'", "render":function(dat,type,row){ return "<div data-id="+row.ast_id+" >"+dropDownState(dat)+"</div>"; }},'."\r\n";
                    }
                    else if($ad->data=="apr_listed") {
                        echo '{"data" : "'.$ad->data.'", "render":function(dat,type,row){ return "<div data-id="+row.apr_id+" >"+dropDownListed(dat)+"</div>"; }},'."\r\n";
                    }
                    else if($ad->data=="st_record") {
                        echo '{"data" : "'.$ad->data.'", "render":function(dat,type,row){ return "<div data-id="+row.ast_id+" >"+dropDownRecOrd(dat)+"</div>"; }},'."\r\n";
                    }                    
                    else  if( ( $part=="darkgreen") &&  $ad->sName=="st_lastcomment")
                            continue;
                    else if($part!=="search"  && $ad->data=="ast_lastcomment") {                                     
                        echo '{"data" : "'.$ad->data.'", "render":function(dat,type,row){ return type=="export" ? dat :  "<input type=hidden  name=\"hcomment_"+row.ast_id+"\"  iden=\"s"+row.ast_id+"\" class=\"hcomment form-control form-control-sm\" value=\'"+dat+"\'><input type=text  name=\"comment_"+row.ast_id+"\" onkeyup=\'CommentTokeyUp(event,"+row["ast_id"]+")\' iden=\"s"+row.ast_id+"\" class=\"comment form-control form-control-sm\" value=\'"+dat+"\'>";  }},'."\r\n";
                    }
                    
                    else                         
                        echo '{"data" : "'.$ad->data.'"},'."\r\n";
                }
            }
            ?>

            <?php
                if($part=="action") {
            ?>
                
                { "data":null, sortable:false, searchable:false, "render":function(data, type, row){ return "<button class='btn btn-sm btn-success' onClick='actionCompleted("+row["ast_id"]+")'>Complete</button>"; } },                                
            <?php 
                }
            ?>

            <?php
                if($part=="action" || $part=="actioncmp") {
                ?>
                { "data":null, sortable:false, searchable:false, "render":function(data, type, row){ return "<button class='btn btn-sm btn-warning' onClick='actionCancelled("+row["ast_id"]+")'>Cancel</button>"; } },                
            <?php 
            }
            ?>

         
            
            
             
        ],
      
      
    });		


    $('body').on("focus","textarea",function(el){ console.log(el); $(el.currentTarget).width(400); $(el.currentTarget).height(250); });
    $('body').on("blur","textarea",function(el){ console.log(el);  $(el.currentTarget).width(100); $(el.currentTarget).height(25); });


});

function unallocateStock(ast_id, st_advertised) { 
    if(st_advertised!=0&&st_advertised!=4) {
        alert("Only allowed when advertising status is unlisted!");
        return;
    }
    var pd = { action: "unallocate"  };
    SendAction(pd,ast_id);       
    
}



function CommentTokeyUp(ev, ast_id) {
    console.log("Event here ",ev);
    if(ev.code=="Enter") {
        var pd = { action: "comment", data: { 
            cm: ev.target.value            
         } };
        SendAction(pd,ast_id);       
    }
}

function AllocatedTokeyUp(ev, ast_id) {
    console.log("Event here ",ev);
    if(ev.code=="Enter") {
        var pd = { action: "allocate", data: { 
            allocatedto: ev.target.value            
         } };
        SendAction(pd,ast_id);       
    }
}
function ReturnedForRefund() {
    var pd = { action: "returnedforrefund", data: {} };
    $('.dts:checked').each(function(i,el) {
        $el = $(el);
        var ast_id = $el.attr("iden").replace("s","");
        var alto  = el.value;                    
        if(alto !="" ){
            pd.data = {            
                             
            };
            SendAction(pd,ast_id);   
        }        
    });     
}
function BuyerCancelled() {
    var pd = { action: "buyercancelled", data: {} };
    $('.dts:checked').each(function(i,el) {
        $el = $(el);
        var ast_id = $el.attr("iden").replace("s","");
        var alto  = el.value;                    
        if(alto !="" ){
            pd.data = {            
                             
            };
            SendAction(pd,ast_id);   
        }        
    });     
}
function DispatchChanges() {
    var pd = { action: "dispatch", data: {} };
    $('.dts:checked').each(function(i,el) {
        $el = $(el);
        var ast_id = $el.attr("iden").replace("s","");
        var alto  = el.value;                    
        if(alto !="" ){
            pd.data = {            
                             
            };
            SendAction(pd,ast_id);   
        }        
    });     
}

function SoldChanges() {
    var pd = { action: "sold", data: {} };
    $('.st_allocatedto').each(function(i,el) {
        $el = $(el);
        var ast_id = $el.attr("iden");
        var alto  = el.value;
        
        var pric = $('.st_soldprice.'+ast_id).val();
        var checked = $('input[iden="'+ast_id+'"]:checked').length;        
        if(alto !="" && pric !=""){
            pd.data = {            
                allocatedto: alto,                    
                allocateprice : pric
            };
            SendAction(pd,ast_id);   
        }        
    });        
}

function SendAction(pd,ast_id) {
    $.ajax({ 
            url:"/accstocksajax/"+ast_id,
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

function actionCompleted(ast_id) {
    actionTo(ast_id,'actioncompleted');
}
function actionCancelled(ast_id) {
    actionTo(ast_id,'actioncancelled');
}
function actionTo(ast_id, action) {
    var pd = { action: action  };
    $.ajax({ 
        url:"/accstocksajax/"+ast_id,
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
function PrintBoxLabels(d) {
    var part = "<?php echo $part;?>";

    if(part=="orange")
        PrintSection("print-orange-box-labels",d);
    else if(part=="red")
        PrintSection("print-red-box-labels",d);
    else if(part=="purple")
        PrintSection("print-purple-box-labels",d);
    else 
        PrintSection("print-box-labels",d);
}
function PrintSection(type, id) {
    var stock_items = []; 
    if(id)
    {
        stock_items.push(id);
    }
    else 
        $('input[type="checkbox"]:checked').each(function(a,b){ stock_items.push($(b).attr("iden").replace("s",""));}); 

    console.log(stock_items.join(","));
    $('#stock_items').val(stock_items.join(","));
    if(stock_items.length>0){
        
        $('#stock_items_form').attr("action","../accstocksprintajax/"+type);
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

function changeListed(ev,el) {
    var data = {};
    $el = $(el); 
    var alto  = el.value;
    data = {
        adv: alto
    }

    SendAction({ action:"listed", data:  data}, $el.parent().data("id"));
        
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
        var ast_id = $el.attr("iden");
        var alto  = el.value;
        
        var pric = $('.st_soldprice.'+ast_id).val();
        var checked = $('input[iden="'+ast_id+'"]:checked').length;
    
        if(checked=="1") {
            if(alto !=""){
                data[ast_id] = {
                    alto: alto,                    
                    pric : pric
                }
            }
        }

    });
}

$(function() {


    $('#scan_return').keydown(function(event) {

        var part ="<?php echo $part;?>";
        var $el = $('#scan_return');
        if(event.keyCode==13) {
            event.preventDefault();


                var pd = { action: "moveback", data: {  serial : $el.val() } };

                $.ajax({ 
                    url:"/accstocksajax/<?php echo $part;?>",
                    data:pd,
                    type:'POST', 
                    error:function(a) {
                        var err = JSON.parse(a.responseJSON).error;
 
failSound();
setTimeout(() => { scan_return.value = ''; }, 500);; 
                       new_toast("danger","Error - "+err);
                        window.ETable.fnDraw();            
                    },
                    success:function(a) {
                    a = JSON.parse(a);                                   
                    if(a.success) {            
successSound();
setTimeout(() => { scan_return.value = ''; }, 500);; 


                        new_toast("success","Item succesfully Returned Back to Orange.");                      
                    }
                    else {
failSound();
setTimeout(() => { scan_return.value = ''; }, 500);; 
 
                       new_toast("danger","Error! Reason is "+a.error);}
                        window.ETable.fnDraw();                    
                    } 
                });   
            return false;

        }
    });        

    $('#scan_move').keydown(function(event) {
        var part ="<?php echo $part;?>";
        var $el = $('#scan_move');
        if(event.keyCode==13) {
            event.preventDefault();
            var order="";
            if(part=="sold") {

                if($('#scan_order').val()=="") {
                    alert("Please scan/enter order first");
                    return false;
                }
                else { order = $('#scan_order').val(); }
            }


                var pd = { action: "move", data: { order:order, serial : $el.val() } };

                $.ajax({ 
                    url:"/accstocksajax/<?php echo $part;?>",
                    data:pd,
                    type:'POST', 
                    error:function(a) {
                        var err = JSON.parse(a.responseJSON).error;
failSound();
setTimeout(() => { scan_move.value = ''; }, 500);   
                        new_toast("danger","Error - "+err); 
                       window.ETable.fnDraw();            
                    },
                    success:function(a) {
                    a = JSON.parse(a);                                   
                    if(a.success) {  
          successSound();
                        new_toast("success","Item Successfully Moved to Current State");   
 
setTimeout(() => { scan_move.value = ''; }, 500);
$('#scan_move').focus();                 
                    }
                    else {
failSound();
setTimeout(() => { scan_move.value = ''; }, 500); 
                        new_toast("danger","Error - "+a.error);}
                        window.ETable.fnDraw();                    
                    } 
                });   
            return false;
        }
        
    });

});


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
        url:"/accstocksajax/<?php echo $part;?>",
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


function change_sku_post(sn,new_apr) {
    var data = {};
    data["serial"] =sn;
    data["apr"] = new_apr;
    var pd = { action: "updateapr", data: data };

    $.ajax({ 
        url:"/accstocksajax/<?php echo $part;?>",
        data:pd,
        type:'POST', 
        success:function(a) {
                                      
        if(a.success) {            
successSound(); 
 
           new_toast("success","Product SKU has been changed.");     
            $('#snmodal').modal("hide");
        }
        else 
            new_toast("danger","Error! Reason is "+a.error);
        window.ETable.fnDraw();
        
        } 
    });   
}

function change_sku(sn, apr_id) {

    var pd = { action: "loadmodels", data:  { serial: sn, apr_id:apr_id } };

    $.ajax({ 
        url:"/accstocksajax/<?php echo $part;?>",
        data:pd,
        type:'POST', 
        success:function(a) {
                       
        if(a.success) {            
           //new_toast("success","Success."+a.data.length);  
            var hm = $('#snmodal').find(".modal-body > div");
            hm.empty();
            
            a.data.forEach(function(ac,bc){
                $('<button class="btn btn-outline-warning" style="white-space:normal; border-color:var(--tblr-btn-color);margin-top:15px; font-size:0.8rem;" type="button" onclick="change_sku_post(\''+sn+'\','+ac.apr_id+')">'+ac.apr_sku+'<br />'+ac.apr_name+'<br />'+ac.apr_condition+'</button>').appendTo(hm);
            });


             $('#snmodal').modal("show");

        }
        else 
            new_toast("danger","Error! Reason is "+a.error);                
        } 
    });   

}

function linkModelImage(el,ev) {
    $('#imagemodal').modal("show");
    var rhref = $(el).attr("href");
    console.log(el, rhref);
    $("img.modal-content").prop("src",rhref);
    $("#imagemodal").show();
    return false;

}

function assign_new(el) {
    var el = $(el);    
    var pn = el.parent().parent().parent().find("td")[6].innerText.substr(3,5);   
    
    $('#mapmodel').data("pn",pn);
    $('#mapmodel').data("el",el);
    $('#mapmodel').modal("show");
}    

function dismiss() {
    $('#mapmodel').modal("hide");    
    $('#skumap')[0].selectedIndex=0;
}

function assign_new_map() {
    var v = $('#skumap').val();
    var vm = $('#mapmodel').data("pn");

    var id = <?php echo isset($id) ? $id  : 0; ?>;
    var pd = { action: "maproduct", data: { mappedv:v, mappedvm:vm,  id:id } };
        $.ajax({ 
            url:"/accordersajax",
            data:pd,
            type:'POST', 
            success:function(a) {
            a = JSON.parse(a);                                   
            if(a.success) {            
                new_toast("success","Success.");                      
                $('#mapmodel').modal("hide");
                $('#skumap')[0].selectedIndex=0;
                $('#mapmodel').data("el").parent().html(a.description);
            }
            else 
                new_toast("danger","Error! Reason is "+a.error);                
            } 
        }); 


    
}    

</script>




<div class="modal fade" id="mapmodel" tabindex="-1" role="dialog" aria-labelledby="mapmodel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="mapmodelLabel">New Product Map</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form>
          <div class="form-group">
            <label for="product-name" id="productname" class="col-form-label">Product:</label>
            <select id=skumap name=skumap class="form-control">
            <?php
                $spp = $db->query("SELECT  apr_id, apr_sku, apr_name FROM aproducts where apr_del=0 order by apr_sku,apr_name asc")->fetchAll();
                foreach($spp as $k=>$v) {    
                    echo "<option  value=".$v["apr_id"].">".$v["apr_sku"]." - ".$v["apr_name"]."</option>";
                }
            ?>
            </select>
          </div>          
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="dismiss()" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="assign_new_map()">Map</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade " id="snmodal" tabindex="-1" role="dialog" aria-hidden="true" style="height: 600px">
<div class="modal-dialog modal-lg" role="document">
 

<div class="modal-content">
	<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	<div class="modal-status bg-danger"></div>
		<div class="modal-body text-center py-4">
			<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-alert-triangle mb-2 text-danger icon-lg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
  			<path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
  			<path d="M12 9v2m0 4v.01"></path>
  			<path d="M5 19h14a2 2 0 0 0 1.84 -2.75l-7.1 -12.25a2 2 0 0 0 -3.5 0l-7.1 12.25a2 2 0 0 0 1.75 2.75"></path>
			</svg>
			<h3>Are you sure?</h3>
			<span class="text-muted">Do you really want to change the SKU of this product? <br />This action cannot be undone.</span>
  			<div class="list-group" style="margin-top:20px;"></div>
		</div>
<div class="row" style="width:40%; margin: 0 auto 20px;">
          <div class="col"><a href="#" class="btn btn-danger w-100" data-bs-dismiss="modal">
              Cancel
            </a></div>
        </div>

    </div>
  </div>
</div>

 

<div class="modal fade " id="imagemodal" tabindex="-1" role="dialog" aria-hidden="true" style="height: 900px">
    <div class="modal-dialog"  style="width: 1200px;height: 900px;max-width: 1200px;">
        <div class="modal-content">
            <img class="modal-content" src="" width="1200px" height="900px"/>
        </div>
    </div>
</div>
<?php
 