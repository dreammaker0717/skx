<style>
    .stid { font-weight: bolder;}
	.total_quantity, .total_arrived {
		display:block;
		width:100%;
		text-align:center;
	}
</style>
<?php
	include(PATH_CONFIG."/constants.php");
	$db=M::db();
	$_TITLE="RFQ Orders";
	$_FIELDS = [
		(object)array('sName' => 'rfqo_id', 'title' => 'No', 'data' => 'rfqo_id', 'type'=>'number' ),    
		(object)array('sName' => 'rfqo_date', 'title' => 'Date', 'data' => 'rfqo_date', 'type'=> 'string'),
		(object)array('sName' => 'rfqo_supplier_name', 'title' => 'Supplier', 'data' => 'rfqo_supplier_name', 'type'=> 'string'),
		(object)array('sName' => 'rfqo_reference', 'title' => 'Reference', 'data' => 'rfqo_reference', 'type'=> 'string'),
		(object)array('sName' => 'total_quantity', 'title' => 'Total Ordered', 'data' => 'total_quantity', 'type'=> 'number'),
		(object)array('sName' => 'total_arrived', 'title' => 'Total Arrived', 'data' => 'total_arrived', 'type'=> 'number'),
		(object)array('sName' => 'order_value', 'title' => 'Order Value', 'data' => 'order_value', 'type'=> 'number'),
		(object)array('sName' => 'rfqo_state', 'title' => 'Status', 'data' => 'rfqo_state', 'type'=> 'string'),
		(object)array('sName' => 'rfqo_user_name', 'title' => 'User', 'data' => 'rfqo_user_name', 'type'=> 'string'),
		(object)array('sName' => 'rfq_id', 'title' => 'RFQ ID', 'data' => 'rfq_id', 'type'=> 'string'),
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
					<div class="table-responsive" style="padding:10px;margin:10px;">
						<table id="dataList" class="table hover card-table table-vcenter text-nowrap datatable">
							<thead>
								<tr>                                 
									<?php
									if(isset($_FIELDS) && count($_FIELDS)>0) {
									for($i=0;$i<count($_FIELDS);$i++) {
									$ad = $_FIELDS[$i];
									echo '<th ><span>'.$ad->title.'</span></th>'."\r\n";
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
			"iDisplayLength": 30,              
			"order":[],
			
			"ajax":{
				url:"/rfqorderajax",
				type:"POST",
				data: { action:'search'},
				dataType:"json"
			},
			
			"columns" :[                   
				<?php
				if(isset($_FIELDS) && count($_FIELDS)>0) {
					for($i=0;$i<count($_FIELDS);$i++) {                    
						$ad = $_FIELDS[$i];            
						if($ad->data=="rfqo_id")                      
							echo '{"data" : "'.$ad->data.'", "render":function(dat){ return "<a class=stid href=\'/rfqorderitem/"+dat+"\'>"+dat+"</a>";}}'."\r\n";
						else if($ad->data=="rfqo_state") {
							echo '{"data" : "'.$ad->data.'", "render":function(dat,tu,ur){  return "<span style=\'width:100px;\' class=\' badge '.$ad->data.'\'>"+(dat||0)+"</span>";} }'."\r\n";
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
