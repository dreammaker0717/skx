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
	$odg = $db->query("select * from acc_order_distribution_global")->fetchAll();
	$_TITLE="Accessory Orders";
	$_FIELDS = [
		(object)array('sName' => 'aor_id', 'title' => 'No', 'data' => 'aor_id', 'type'=>'number' ),    
		(object)array('sName' => 'aor_date', 'title' => 'Date', 'data' => 'aor_date', 'type'=> 'string'),               
		(object)array('sName' => 'sp_name', 'title' => 'Supplier', 'data' => 'sp_name', 'type'=> 'string'),            
		(object)array('sName' => 'aor_reference', 'title' => 'Reference', 'data' => 'aor_reference', 'type'=> 'string'),            
		(object)array('sName' => 'aor_total_items', 'title' => 'QTY', 'data' => 'aor_total_items', 'type'=> 'string'),   
		(object)array('sName' => 'aor_total_delivered', 'title' => 'Delivered', 'data' => 'aor_total_delivered', 'type'=> 'string'),      
		(object)array('sName' => 'aor_state', 'title' => 'Status', 'data' => 'aor_state', 'type'=> 'string'),            
		(object)array('sName' => 'ast_fix_rate', 'title' => 'Fix Rate', 'data' => 'ast_fix_rate', 'type'=> 'string'),   
		(object)array('sName' => 'ast_orange', 'title' => $odg[0]["orange"], 'data' => 'ast_orange', 'type'=> 'string'), 
		(object)array('sName' => 'ast_purple', 'title' => $odg[0]["purple"], 'data' => 'ast_purple', 'type'=> 'string'), 
		(object)array('sName' => 'ast_red', 'title' => $odg[0]["red"], 'data' => 'ast_red', 'type'=> 'string'),     
		//(object)array('sName' => 'ast_lightgreen', 'title' => 'LGreen ('. $odg[0]["lightgreen"].')', 'data' => 'ast_lightgreen', 'type'=> 'string'), 
		//(object)array('sName' => 'ast_darkgreen', 'title' => 'DGreen ('. $odg[0]["darkgreen"].')', 'data' => 'ast_darkgreen', 'type'=> 'string'), 
		(object)array('sName' => 'ast_greenish', 'title' => ($odg[0]["green"]+$odg[0]["lightgreen"]+$odg[0]["darkgreen"]), 'data' => 'ast_greenish', 'type'=> 'string'), 
		(object)array('sName' => 'ast_sold', 'title' => $odg[0]["sold"], 'data' => 'ast_sold', 'type'=> 'string'),    
		(object)array('sName' => 'ast_black', 'title' => ($odg[0]["black"]+$odg[0]["stripped"]) , 'data' => 'ast_black', 'type'=> 'string'), 		
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
							<button type=button class='btn btn-primary' onclick="location.href='/newaccorder'">
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
										$color = strtolower(str_replace("ast_","",$ad->data));
									}
									$scolor = "orders btn btn-sm ";
									switch ($ad->data) {
									case "ast_orange":
										$scolor=$scolor.'btn-orange';
										break;
									case "ast_purple":
										$scolor=$scolor.'btn-purple';
										break;
									case "ast_red":
										$scolor=$scolor.'btn-red';
										break;
									case "ast_sold":
										$scolor=$scolor.'btn-secondary';
										break;
									case "ast_black":
										$scolor=$scolor.'btn-dark';
										break;
									case "ast_greenish":
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
				url:"/accordersajax",
				type:"POST",
				data: { action:'search'},
				dataType:"json"
			},
			
			"columns" :[                   
				<?php
				if(isset($_FIELDS) && count($_FIELDS)>0) {
					for($i=0;$i<count($_FIELDS);$i++) {                    
						$ad = $_FIELDS[$i];            
						if($ad->data=="aor_id")                      
							echo '{"data" : "'.$ad->data.'", "render":function(dat){ return "<a class=stid href=\'/accorder/"+dat+"\'>"+dat+"</a>";}}'."\r\n";
						else if($ad->data=="ast_fix_rate"){
												
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
