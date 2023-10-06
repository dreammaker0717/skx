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
	$odg = $db->query("SELECT * FROM suppliers")->fetchAll();
  //suppliers.sp_id,suppliers.sp_name,suppliers.sp_contact,suppliers.sp_email FROM suppliers LEFT JOIN supplier_groups ON suppliers.sp_id = supplier_groups_new.sg_supplier_id LEFT JOIN groups ON supplier_groups_new.g_id = groups.gs_id
	$_TITLE="Suppliers";
	$_FIELDS = [
    (object)array('sName' => 'sp_id', 'title' => 'No', 'data' => 'sp_id', 'type'=>'number' ),
    (object)array('sName' => 'sp_name', 'title' => 'Name', 'data' => 'sp_name', 'type'=> 'string'),
    (object)array('sName' => 'sp_contact', 'title' => 'Contact', 'data' => 'sp_contact', 'type'=> 'string'),
    (object)array('sName' => 'sp_email', 'title' => 'e-Mail', 'data' => 'sp_email', 'type'=> 'string'),
    (object)array('sName' => 'gs_name', 'title' => 'Supplier Groups', 'data' => 'gs_name', 'type'=> 'string'),
    (object)array('sName' => 'update', 'title' => 'Action', 'data' => 'No', 'type'=>'string' )

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
							<button type=button class='btn btn-primary' onclick="location.href='/newsupplier/0'">
							<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
							<path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
							<line x1="12" y1="5" x2="12" y2="19"></line>
							<line x1="5" y1="12" x2="19" y2="12"></line>
							</svg>
							Create New Supplier</button>
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
<?php
echo "ss".count($_FIELDS);
?>

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
			"order": [],

			"ajax":{
				url:"/supplierajax",
				type:"POST",
				data: { action:'search'},
				dataType:"json"
			},

			"columns" :[
            { "data": "sp_id", "orderable": true},
            { "data": "sp_name", "orderable": true  },
            { "data": "sp_contact", "orderable": true  },
            { "data": "sp_email","orderable": true  },
            { "data": "gs_name", "orderable": false  },
            {
                "data": null,
                "orderable": false,
                "render": function ( data, type, row, meta ) {
                          return '<a class="btn btn-sm btn-primary" href="https://www.skx-online.com/newsupplier/'+row.sp_id+'">Update</a>';  // Column will display firstname lastname
                }
            }



			]
		});
		$('body').on("focus","textarea",function(el){ console.log(el); $(el.currentTarget).width(400); $(el.currentTarget).height(250); });
		$('body').on("blur","textarea",function(el){ console.log(el);  $(el.currentTarget).width(100); $(el.currentTarget).height(50); });
	});
</script>
