<style>
    .stid { font-weight: bolder;}
</style>
<?php
	include(PATH_CONFIG."/constants.php");
	$db=M::db();
	$_TITLE="INVOICES";
	$_FIELDS = [
		(object)array('sName' => 'inv_id', 'title' => 'No', 'data' => 'inv_id', 'type'=>'number' ),
		(object)array('sName' => 'inv_date', 'title' => 'Date', 'data' => 'inv_date', 'type'=> 'string'),
		(object)array('sName' => 'c_name', 'title' => 'User', 'data' => 'c_name', 'type'=> 'string'),
		(object)array('sName' => 'inv_status', 'title' => 'Status', 'data' => 'inv_status', 'type'=> 'string'),
		(object)array('sName' => 'payment_type', 'title' => 'Payment Type', 'data' => 'payment_type', 'type'=> 'string'),
    (object)array('sName' => 'update', 'title' => 'Action', 'data' => 'No', 'type'=>'string' ),
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
							<button type=button class='btn btn-primary' onclick="location.href='/newinvoice'">
							<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
							<path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
							<line x1="12" y1="5" x2="12" y2="19"></line>
							<line x1="5" y1="12" x2="19" y2="12"></line>
							</svg>
							Create New INVOICE</button>
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
                  //if($ad->title=='update')
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
				url:"/invoicesajax",
				type:"POST",
				data: { action:'search'},
				dataType:"json"
			},

			"columns" :[
				<?php
				if(isset($_FIELDS) && count($_FIELDS)>0) {
					for($i=0;$i<count($_FIELDS);$i++) {
						$ad = $_FIELDS[$i];

						if($ad->data=="inv_id")
							echo '{"data" : "'.$ad->data.'", "render":function(dat){ return "<a class=stid href=\'/invoiceitem/"+dat+"\'>"+dat+"</a>";}}'."\r\n";
            else if($ad->data=="No")
            	echo '{"data" : "null", "render":function(data, type, row, meta){ return "<a class=\'btn btn-sm btn-primary\' href=\'/newinvoiceitem/"+row.inv_id+"\'>Update</a>";}}'."\r\n";
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
