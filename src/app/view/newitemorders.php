<style>
    select.status { font-weight: bold;}
    .stid { font-weight: bolder;}
    .ast_orange  { color:orange; font-weight: bolder; padding-left:25px;}
    .ast_purple  { color:purple; font-weight: bolder; padding-left:25px;}
    .ast_red  { color:red; font-weight: bolder; padding-left:25px;}
    .ast_greenish  { color:green; font-weight: bolder;padding-left:15px;}
    .ast_sold  { color:gray;font-weight: bolder; padding-left:25px;}
    .ast_black  { color:black; font-weight: bolder; padding-left:25px;}
</style>
<?php
	include(PATH_CONFIG."/constants.php");
	$db=M::db();
	$odg = $db->query("select * from nwp_order_distribution_global")->fetchAll();
	$_TITLE="New Item Orders";
	$_FIELDS = [
		(object)array('sName' => 'nor_id', 'title' => 'No', 'data' => 'nor_id', 'type'=>'number' ),    
		(object)array('sName' => 'nor_date', 'title' => 'Date', 'data' => 'nor_date', 'type'=> 'string'),               
		(object)array('sName' => 'sp_name', 'title' => 'Supplier', 'data' => 'sp_name', 'type'=> 'string'),            
		(object)array('sName' => 'nor_reference', 'title' => 'Reference', 'data' => 'nor_reference', 'type'=> 'string'),            
		(object)array('sName' => 'nor_total_items', 'title' => 'QTY', 'data' => 'nor_total_items', 'type'=> 'string'),   
		(object)array('sName' => 'nor_total_delivered', 'title' => 'Delivered', 'data' => 'nor_total_delivered', 'type'=> 'string'),      
		(object)array('sName' => 'nor_state', 'title' => 'Status', 'data' => 'nor_state', 'type'=> 'string'),            
		(object)array('sName' => 'nst_fix_rate', 'title' => 'Fix Rate', 'data' => 'nst_fix_rate', 'type'=> 'string'),   
		(object)array('sName' => 'nst_orange', 'title' => $odg[0]["orange"], 'data' => 'nst_orange', 'type'=> 'string'), 
		(object)array('sName' => 'nst_purple', 'title' => $odg[0]["purple"], 'data' => 'nst_purple', 'type'=> 'string'), 
		(object)array('sName' => 'nst_red', 'title' => $odg[0]["red"], 'data' => 'nst_red', 'type'=> 'string'),     
		//(object)array('sName' => 'ast_lightgreen', 'title' => 'LGreen ('. $odg[0]["lightgreen"].')', 'data' => 'ast_lightgreen', 'type'=> 'string'), 
		//(object)array('sName' => 'ast_darkgreen', 'title' => 'DGreen ('. $odg[0]["darkgreen"].')', 'data' => 'ast_darkgreen', 'type'=> 'string'), 
		(object)array('sName' => 'nst_greenish', 'title' => ($odg[0]["green"]+$odg[0]["lightgreen"]+$odg[0]["darkgreen"]), 'data' => 'nst_greenish', 'type'=> 'string'), 
		(object)array('sName' => 'nst_sold', 'title' => $odg[0]["sold"], 'data' => 'nst_sold', 'type'=> 'string'),    
		(object)array('sName' => 'nst_black', 'title' => ($odg[0]["black"]+$odg[0]["stripped"]) , 'data' => 'nst_black', 'type'=> 'string'), 		
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
						<div class="col-auto d-none d-md-flex">
							<button type=button class='btn btn-primary' onclick="location.href='/newitemorder'">
							<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
							<path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
							<line x1="12" y1="5" x2="12" y2="19"></line>
							<line x1="5" y1="12" x2="19" y2="12"></line>
							</svg>
							Create New Order</button>
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

									if($ad->data[0]=="a") {
										$color = strtolower(str_replace("nst_","",$ad->data));
									}
									$scolor = "orders btn btn-sm ";
									switch ($ad->data) {
									case "nst_orange":
										$scolor=$scolor.'btn-orange';
										break;
									case "nst_purple":
										$scolor=$scolor.'btn-purple';
										break;
									case "nst_red":
										$scolor=$scolor.'btn-red';
										break;
									case "nst_sold":
										$scolor=$scolor.'btn-secondary';
										break;
									case "nst_black":
										$scolor=$scolor.'btn-dark';
										break;
									case "nst_greenish":
										$scolor=$scolor.'btn-green';
										break;
									default:
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
				</div>
			</div>
		</div>
	</div>
</div>

<form id="astock_items_form" name="astock_items_form" action="" method="post" style="display:none">
    <input type="hidden" id="astock_items" name="astock_items"/> 
</form>

<script>
	$(function() {
		var f1 = "<?php echo $_FIELDS[0]->data;?>";
		window.ETable = $('#dataList').dataTable({
		 
			"lengthChange": false,
			"processing":true,
			"serverSide":true,
			"infoEmpty": "No records available",
			"sProcessing": "DataTables is currently busy",
			"aLengthMenu": [[5, 15, 50,100], [5, 15, 50,100]],
			"iDisplayLength": 15,              
			"order":[],
			
			"ajax":{
				url:"/itemordersajax",
				type:"POST",
				data: { action:'search'},
				dataType:"json"
			},
			
			"columns" :[                   
				<?php
				if(isset($_FIELDS) && count($_FIELDS)>0) {
					for($i=0;$i<count($_FIELDS);$i++) {                    
						$ad = $_FIELDS[$i];            
						if($ad->data=="nor_id")                      
							echo '{"data" : "'.$ad->data.'", "render":function(dat){ return "<a class=stid href=\'/itemorder/"+dat+"\'>"+dat+"</a>";}}'."\r\n";
						else if($ad->data=="nst_fix_rate"){
												
							echo '{"data" : "'.$ad->data.'", "render":function(dat,tu,ur){ var v = !dat ? 0 : dat.split("/")[0].replace("%","").trim(); var cl = v>=90 ? "bg-cyan-lt": v>=80 ? "bg-teal-lt" : v>=70 ? "bg-green-lt" : v>=60 ? "bg-lime-lt" :v>=50 ? "bg-yellow-lt" :v>=40 ? "bg-orange-lt" : v>=30 ? "bg-red-lt" :v>=20 ? "bg-pink-lt" :"bg-purple-lt"; return "<span style=\'color:"+cl+"\' class=\''.$ad->data.'  badge "+cl+" \'>"+(dat||0)+"</span>";} }'."\r\n";                         
						}
						else if($ad->data[2]=='t') {
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
