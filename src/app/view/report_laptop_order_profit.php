<style>
    select.status { font-weight: bold;}
    .stid { font-weight: bolder;}
    .st_orange  { color:orange; font-weight: bolder; padding-left:25px;}
    .st_purple  { color:purple; font-weight: bolder; padding-left:25px;}
    .st_red  { color:red; font-weight: bolder; padding-left:25px;}
    .st_lightblue  { color:lightblue; font-weight: bolder; padding-left:25px;}
    .st_darkblue  { color:darkblue; font-weight: bolder; padding-left:25px;}
    .st_action  { color:darkgray; font-weight: bolder; padding-left:25px;}
    .st_brown  { color:brown; font-weight: bolder; padding-left:25px;}
    .st_lightgreen  { color:lightgreen; font-weight: bolder; padding-left:25px;}
    .st_darkgreen  { color:darkgreen; font-weight: bolder; padding-left:25px;}
    .st_sold  { color:gray;font-weight: bolder;  padding-left:25px;}
    .st_black  { color:black; font-weight: bolder; padding-left:25px;}
</style>
<?php

include(PATH_CONFIG."/constants.php");

$db=M::db();

$odg = $db->query("select * from order_distribution_global")->fetchAll();

$_TITLE="Laptop Profit Report";
$_FIELDS = [

    (object)array('sName' => 'or_id', 'title' => 'No', 'data' => 'or_id', 'type'=>'number' ),    
    (object)array('sName' => 'or_date', 'title' => 'Date', 'data' => 'or_date', 'type'=> 'string'),            
    (object)array('sName' => 'sp_name', 'title' => 'Supplier', 'data' => 'sp_name', 'type'=> 'string'), 
    (object)array('sName' => 'or_reference', 'title' => 'Reference', 'data' => 'or_reference', 'type'=> 'string'),   
    (object)array('sName' => 'vat_label', 'title' => 'VAT Type', 'data' => 'vat_label', 'type'=> 'string'),
    (object)array('sName' => 'or_total_delivered', 'title' => 'Arrived', 'data' => 'or_total_delivered', 'type'=> 'string'),  
    (object)array('sName' => 'st_total_cost', 'title' => 'Total Cost', 'data' => 'st_total_cost', 'type'=> 'string'),  

    (object)array('sName' => 'st_fix_rate', 'title' => 'Fix Rate', 'data' => 'st_fix_rate', 'type'=> 'string'),  
    (object)array('sName' => 'st_sell_through_rate', 'title' => 'Sell Through Rate', 'data' => 'st_sell_through_rate', 'type'=> 'string'), 

    (object)array('sName' => 'st_black', 'title' => ($odg[0]["black"]+$odg[0]["stripped"]), 'data' => 'st_black', 'type'=> 'string'), 
    (object)array('sName' => 'st_total_writtenoff_price', 'title' => 'Total<br>Written Off<br>Price', 'data' => 'st_total_writtenoff_price', 'type'=> 'string'),   
    
    (object)array('sName' => 'st_total_soldnetprice', 'title' => 'Total Sold<br>Net', 'data' => 'st_total_soldnetprice', 'type'=> 'string'),   
    (object)array('sName' => 'st_total_soldvat', 'title' => 'Total Sold<br>Vat', 'data' => 'st_total_soldvat', 'type'=> 'string'),   
    (object)array('sName' => 'st_total_soldprice', 'title' => 'Total Sold', 'data' => 'st_total_soldprice', 'type'=> 'string'),   
    (object)array('sName' => 'st_total_projected_sold', 'title' => 'Total<br>Projected<br>Sold', 'data' => 'st_total_projected_sold', 'type'=> 'string'),   
    (object)array('sName' => 'st_total_profit', 'title' => 'Total<br>Profit', 'data' => 'st_total_profit', 'type'=> 'string'),   
    (object)array('sName' => 'st_total_projected_profit', 'title' => 'Total<br>Projected<br>Profit', 'data' => 'st_total_projected_profit', 'type'=> 'string'),    
];
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
    <div class="container-fluid">
<div class="col-lg-11" style="margin:0 auto;">

                <div class="card card-lg">
<div class="card-body" style="padding:3rem 1rem;">
 
<div class="row align-items-center">
<div class="col-auto">
   <h2 style="margin-left:1rem;"><?php echo $_TITLE; ?></h2>
</div>				
    </div>
        </div>             
                    <div class="table-responsive" style="padding:10px;margin:10px;">
                        <table id="dataList" class="table hover card-table table-vcenter text-nowrap datatable">
                            <thead>
                                <tr>                                 
                                <?php
                                if(isset($_FIELDS) && count($_FIELDS)>0) {
                                    for($i=0;$i<count($_FIELDS);$i++) {
                                    $ad = $_FIELDS[$i];
                                    $color="inherit";
                                    
                                    if($ad->data[0]=="s") {
                                        $color = strtolower(str_replace("st_","",$ad->data));
                                    }
$scolor = "orders btn btn-sm ";
if ($ad->data == "st_black") {
		$scolor=$scolor.'btn-dark';
} else {
        $scolor="";
}

                                    echo '<th ><span class="'.$scolor.'">'.$ad->title.'</span></th>'."\r\n";
                                    }
                                }
                                ?>                                
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="card-footer d-flex align-items-center">
                        <div class="btn-group" role="group" aria-label="Basic example">
                            <button class="btn btn-sm btn-primary" onclick="updateChanges()">Update</button>
                           
                        </div>
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
    var f1 = "<?php echo $_FIELDS[0]->data;?>";
    window.ETable = $('#dataList').dataTable({
     
        "lengthChange": false,
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
        "aLengthMenu": [[5, 15, 50,100], [5, 15, 50,100]],
        "iDisplayLength": 25,
        "order":[],
        "dom": '<<<lB>f>rt<ip>>',
        
        "ajax":{
            url:"/laptoporderprofitreportajax",
            type:"POST",
            data: { action:'search'},
            dataType:"json"
        },
        
        "columns" :[                   
            <?php
            if(isset($_FIELDS) && count($_FIELDS)>0) {
                for($i=0;$i<count($_FIELDS);$i++) {                    
                    $ad = $_FIELDS[$i];            
                    if($ad->data=="or_id")                      
                        echo '{"data" : "'.$ad->data.'", "render":function(dat){ return "<a class=stid href=\'/laptopprofit/"+dat+"\'>"+dat+"</a>";}}'."\r\n";
                    else if($ad->data=="vat_label") {
                        echo '{"data" : "'.$ad->data.'", "render":function(dat,tu,ur){if(dat != null)return "<span style=\'width:140px;\' class=\'badge bg-azure-lt\'>"+dat+"</span>"; else return "<span style=\'width:140px;\' class=\'badge bg-azure-lt\'>Not Set</span>";} }'."\r\n";
    
                    }
                    else if($ad->data=="st_total_cost" || $ad->data=="st_total_soldnetprice" || $ad->data=="st_total_soldvat" || $ad->data=="st_total_soldprice" || $ad->data=="st_total_projected_sold" || $ad->data=="st_total_writtenoff_price") {
                        echo '{"data" : "'.$ad->data.'", "render":function(dat,tu,ur){ return nf.format(dat); }}'."\r\n";
    
                    }
                    else if($ad->data=="st_total_profit" || $ad->data=="st_total_projected_profit") {
                        echo '{"data" : "'.$ad->data.'", "render":function(dat,tu,ur){ if(dat!="-")return nf.format(dat); else return dat;}}'."\r\n";
    
                    }
                    else if($ad->data=="st_fix_rate"){
                                            
                        echo '{"data" : "'.$ad->data.'", "render":function(dat,tu,ur){
                            var v = !dat ? 0 : dat.split("/")[1].replace("%","").trim(); 
                            var cl = v>=90 ? "bg-cyan-lt": v>=80 ? "bg-teal-lt" : v>=70 ? "bg-green-lt" : v>=60 ? "bg-lime-lt" :v>=50 ? "bg-yellow-lt" :v>=40 ? "bg-orange-lt" : v>=30 ? "bg-red-lt" :v>=20 ? "bg-pink-lt" :"bg-purple-lt"; 
                            return "<span style=\'color:"+cl+";width:140px;\' class=\''.$ad->data.'  badge "+cl+" \'>"+(dat||0)+"</span>";} }'."\r\n";                        
                    }
                    else if($ad->data=="st_sell_through_rate"){
                                            
                        echo '{"data" : "'.$ad->data.'", "render":function(dat,tu,ur){
                            var v = !dat ? 0 : dat.replace("%","").trim(); 
                            var cl = v>=90 ? "bg-cyan-lt": v>=80 ? "bg-teal-lt" : v>=70 ? "bg-green-lt" : v>=60 ? "bg-lime-lt" :v>=50 ? "bg-yellow-lt" :v>=40 ? "bg-orange-lt" : v>=30 ? "bg-red-lt" :v>=20 ? "bg-pink-lt" :"bg-purple-lt"; 
                            return "<span style=\'color:"+cl+";width:120px;\' class=\''.$ad->data.'  badge "+cl+" \'>"+(dat||0)+"</span>";} }'."\r\n";                        
                    }
                    else if($ad->data[1]=='t') {
                        echo '{"data" : "'.$ad->data.'", "render":function(dat,tu,ur){  return "<span class='.$ad->data.'>"+(dat||0)+"</span>";} }'."\r\n";
                    }
                    else 
                        echo '{"data" : "'.$ad->data.'"}'."\r\n";

                    if($i !== count($_FIELDS)-1) {
                        echo ",";
                    }
                }
            }
            ?>
            
             
        ]
      
      
    });		


    $('body').on("focus","textarea",function(el){ console.log(el); $(el.currentTarget).width(400); $(el.currentTarget).height(250); });
    $('body').on("blur","textarea",function(el){ console.log(el);  $(el.currentTarget).width(100); $(el.currentTarget).height(50); });


});
</script>
