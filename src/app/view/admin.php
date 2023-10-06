<?php
use Medoo\Medoo;
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
                    $part = $vars["part"];

                    //define tables here

                    if($part=="manufacturers") {
                        $_TITLE="Manufacturers";
                        $_TABLE ="manufacturers";
                        $_FIELDS = [
                            (object)array('sName' => 'mf_id', 'title' => 'No', 'data' => 'mf_id', 'type'=>'number' ),
                            (object)array('sName' => 'mf_name', 'title' => 'Name', 'data' => 'mf_name', 'type'=> 'string'),
                            (object)array('sName' => 'mf_del', 'title' => 'Deleted', 'data' => 'mf_del', 'type'=> 'bool'),
                        ];
                    }

                    if($part=="accproductmap") {
                        $_TITLE="Acc Product Mapping";
                        $_TABLE ="aproducts_map";
                        $_FIELDS = [
                            (object)array('sName' => 'apm_id', 'title' => 'No', 'data' => 'apm_id', 'type'=>'number' ),
                            (object)array('sName' => 'apm_pn', 'title' => 'Part Number', 'data' => 'apm_pn', 'type'=> 'string'),
                            (object)array('sName' => 'apr_sku', 'title' => 'SKU', 'data' => 'apr_sku', 'type'=> 'string'),
                            (object)array('sName' => 'apm_aproducts_id', 'title' => 'Acc Product', 'data' => 'apr_name_sku', 'type'=> 'foregin',
                                'foreign'=>'aproducts', 'foreign_id'=>'apr_id',

                                'foreign_name'=> [ "apr_name_sku" => Medoo::raw("CONCAT(apr_sku,' - ',apr_name,' (',apr_condition,')')") ],

                                'foreign_filter'=>'apr_del=0')
                        ];
                    }

                    if($part=="laptopspecs") {
                        $_TITLE="Laptop Specs";
                        $_TABLE ="laptopspecs";
                        $_FIELDS = [
                            (object)array('sName' => 'spec_id', 'title' => 'No', 'data' => 'spec_id', 'type'=>'number' ),
                            (object)array('sName' => 'spec_categ', 'title' => 'Category', 'data' => 'ct_name', 'type'=> 'foreign',
                                'foreign'=>'categories', 'foreign_id'=>'ct_id', 'foreign_name'=>'ct_name','foreign_filter'=>'ct_del=0'
                            ),
                            (object)array('sName' => 'spec_name', 'title' => 'Name', 'data' => 'spec_name', 'type'=> 'string'),
                            (object)array('sName' => 'spec_comment', 'title' => 'Comment', 'data' => 'spec_comment', 'type'=> 'string'),
                            (object)array('sName' => 'spec_del', 'title' => 'Deleted', 'data' => 'spec_del', 'type'=> 'bool'),
                        ];
                    }
                    if($part=="desktopspecs") {
                        $_TITLE="Desktop Specs";
                        $_TABLE ="desktopspecs";
                        $_FIELDS = [
                            (object)array('sName' => 'spec_id', 'title' => 'No', 'data' => 'spec_id', 'type'=>'number' ),
                            (object)array('sName' => 'spec_categ', 'title' => 'Category', 'data' => 'ct_name', 'type'=> 'foreign',
                                'foreign'=>'categories', 'foreign_id'=>'ct_id', 'foreign_name'=>'ct_name','foreign_filter'=>'ct_del=0'
                            ),
                            (object)array('sName' => 'spec_name', 'title' => 'Name', 'data' => 'spec_name', 'type'=> 'string'),
                            (object)array('sName' => 'spec_comment', 'title' => 'Comment', 'data' => 'spec_comment', 'type'=> 'string'),
                            (object)array('sName' => 'spec_del', 'title' => 'Deleted', 'data' => 'spec_del', 'type'=> 'bool'),
                        ];
                    }
                    if($part=="users") {
                        $_TITLE="Users";
                        $_TABLE ="users";
                        $_FIELDS = [
                            (object)array('sName' => 'user_id', 'title' => 'No', 'data' => 'user_id', 'type'=>'number' ),
                            (object)array('sName' => 'username', 'title' => 'Username', 'data' => 'username', 'type'=> 'string'),
                            (object)array('sName' => 'fullname', 'title' => 'FullName', 'data' => 'fullname', 'type'=> 'string'),
                            (object)array('sName' => 'email', 'title' => 'Email', 'data' => 'email', 'type'=> 'string'),
                            (object)array('sName' => 'pin', 'title' => 'Pin', 'data' => 'pin', 'type'=> 'string'),
                            (object)array('sName' => 'password', 'title' => 'Password', 'data' => 'password', 'type'=> 'md5'),
                            (object)array('sName' => 'email_password', 'title' => 'Email Password', 'data' => 'email_password', 'type'=> 'string'),
                            (object)array('sName' => 'user_role', 'title' => 'Role', 'data' => 'ur_name', 'type'=> 'foreign',
                                'foreign'=>'userroles', 'foreign_id'=>'ur_id', 'foreign_name'=>'ur_name','foreign_filter'=>'1=1'
                            ),
                            (object)array('sName' => 'active', 'title' => 'Active', 'data' => 'active', 'type'=> 'bool'),
                            //(object)array('sName' => 'deleted', 'title' => 'Deleted', 'data' => 'deleted', 'type'=> 'bool'),
                        ];
                    }
                    if($part=="customers") {
                        $_TITLE="Customers";
                        $_TABLE ="customers";
                        $_FIELDS = [
                            (object)array('sName' => 'customer_id', 'title' => 'No', 'data' => 'customer_id', 'type'=>'number' ),
                            (object)array('sName' => 'c_name', 'title' => 'Customer Name', 'data' => 'c_name', 'type'=> 'string'),
                            (object)array('sName' => 'c_email', 'title' => 'Email', 'data' => 'c_email', 'type'=> 'string'),
                          ];
                    }
                    if($part=="suppliers") {
                        $_TITLE="Suppliers";
                        $_TABLE ="suppliers";
                        $_FIELDS = [
                            (object)array('sName' => 'sp_id', 'title' => 'No', 'data' => 'sp_id', 'type'=>'number' ),
                            (object)array('sName' => 'sp_name', 'title' => 'Name', 'data' => 'sp_name', 'type'=> 'string'),
                            (object)array('sName' => 'sp_contact', 'title' => 'Contact', 'data' => 'sp_contact', 'type'=> 'string'),
                            (object)array('sName' => 'sp_email', 'title' => 'e-Mail', 'data' => 'sp_email', 'type'=> 'string'),


//                         (object)array('sName' => 'sp_del', 'title' => 'Deleted', 'data' => 'sp_del', 'type'=> 'bool'),
                        ];
                    }
                    if($part=="groups") {
                        $_TITLE="Supplier Groups";
                        $_TABLE ="groups";
                        $_FIELDS = [
                            (object)array('sName' => 'gs_id', 'title' => 'No', 'data' => 'gs_id', 'type'=>'number' ),
                            (object)array('sName' => 'gs_name', 'title' => 'Name', 'data' => 'gs_name', 'type'=> 'string'),

//                         (object)array('sName' => 'sp_del', 'title' => 'Deleted', 'data' => 'sp_del', 'type'=> 'bool'),
                        ];
                    }

                    if($part=="imap") {
                        $_TITLE="Imap Setting";
                        $_TABLE ="imap";
                        $_FIELDS = [
                            (object)array('sName' => 'imp_id', 'title' => 'No', 'data' => 'imp_id', 'type'=>'number' ),
                            (object)array('sName' => 'host', 'title' => 'Host', 'data' => 'host', 'type'=> 'string'),
                            (object)array('sName' => 'username', 'title' => 'Username', 'data' => 'username', 'type'=> 'string'),
                            (object)array('sName' => 'password', 'title' => 'Password', 'data' => 'password', 'type'=> 'string'),
                            (object)array('sName' => 'port', 'title' => 'Port', 'data' => 'port', 'type'=> 'string')

//                         (object)array('sName' => 'sp_del', 'title' => 'Deleted', 'data' => 'sp_del', 'type'=> 'bool'),
                        ];
                    }


                    if($part=="supplier_groups") {
                        $_TITLE="Supplier Groups";
                        $_TABLE ="supplier_groups";
                        $_FIELDS = [
                            (object)array('sName' => 'sg_id', 'title' => 'No', 'data' => 'sg_id', 'type'=>'number' ),
                            (object)array('sName' => 'sg_name', 'title' => 'Name', 'data' => 'sg_name', 'type'=> 'string', 'required' => true),
                            (object)array('sName' => 'sg_supplier_id', 'title' => 'Suppliers', 'data' => 'sp_name', 'type'=> 'foreign',
                                'foreign'=>'suppliers', 'foreign_id'=>'sp_id', 'foreign_name'=>'sp_name','foreign_filter'=>'sp_del=0', 'multiple'=>true
                            ),
                        ];
                    }

                    if($part=="categories") {
                        $_TITLE="Categories";
                        $_TABLE ="categories";
                        $_FIELDS = [
                            (object)array('sName' => 'ct_id', 'title' => 'No', 'data' => 'ct_id', 'type'=>'number' ),
                            (object)array('sName' => 'ct_name', 'title' => 'Name', 'data' => 'ct_name', 'type'=> 'string'),
                            (object)array('sName' => 'ct_del', 'title' => 'Deleted', 'data' => 'ct_del', 'type'=> 'bool'),
                        ];
                    }
                    if($part=="subcategories") {
                        $_TITLE="Subcategories";
                        $_TABLE ="subcategories";
                        $_FIELDS = [
                            (object)array('sName' => 'sc_id', 'title' => 'No', 'data' => 'sc_id', 'type'=>'number' ),
                            (object)array('sName' => 'sc_category', 'title' => 'Category', 'data' => 'ct_name', 'type'=> 'foreign',
                                'foreign'=>'categories', 'foreign_id'=>'ct_id', 'foreign_name'=>'ct_name','foreign_filter'=>'ct_del=0'
                            ),
                            (object)array('sName' => 'sc_name', 'title' => 'Name', 'data' => 'sc_name', 'type'=> 'string'),
                            (object)array('sName' => 'sc_del', 'title' => 'Deleted', 'data' => 'sc_del', 'type'=> 'bool'),
                        ];
                    }
                    if($part=="subsubcategories") {
                        $_TITLE="SubSubcategories";
                        $_TABLE ="subsubcategories";
                        $_FIELDS = [
                            (object)array('sName' => 'ss_id', 'title' => 'No', 'data' => 'ss_id', 'type'=>'number' ),
                            (object)array('sName' => 'ss_subcategory', 'title' => 'Sub Category', 'data' => 'sc_name', 'type'=> 'foreign',
                                'foreign'=>'subcategories', 'foreign_id'=>'sc_id', 'foreign_name'=>'sc_name','foreign_filter'=>'sc_del=0'
                            ),
                            (object)array('sName' => 'ss_name', 'title' => 'Name', 'data' => 'ss_name', 'type'=> 'string'),
                            (object)array('sName' => 'ss_del', 'title' => 'Deleted', 'data' => 'ss_del', 'type'=> 'bool'),
                        ];
                    }
                    if($part=="aproducts") {
                        $_TITLE="Accessory Products";
                        $_TABLE ="aproducts";
                        $_FIELDS = [
                            (object)array('sName' => 'apr_id', 'title' => 'No', 'data' => 'apr_id', 'type'=>'number' ),
                            (object)array('sName' => 'apr_image', 'title' => 'Image', 'data' => 'apr_image', 'type'=> 'image'),
                            (object)array('sName' => 'apr_sku', 'title' => 'SKU', 'data' => 'apr_sku', 'type'=> 'string'),
                            (object)array('sName' => 'apr_name', 'title' => 'Name', 'data' => 'apr_name', 'type'=> 'string'),
                            (object)array('sName' => 'apr_box_label', 'title' => 'Box Label', 'data' => 'apr_box_label', 'type'=> 'string'),
                            (object)array('sName' => 'apr_box_subtitle', 'title' => 'Box Subtitle Label', 'data' => 'apr_box_subtitle', 'type'=> 'string'),


                            (object)array('sName' => 'apr_condition', 'title' => 'Condition', 'data' => 'apr_condition', 'type'=> 'string'),
                            (object)array('sName' => 'apr_mpn', 'title' => 'MPN', 'data' => 'apr_mpn', 'type'=> 'string'),

                            (object)array('sName' => 'apr_category', 'title' => 'Category', 'data' => 'ct_name', 'type'=> 'foreign',
                                'foreign'=>'categories', 'foreign_id'=>'ct_id', 'foreign_name'=>'ct_name','foreign_filter'=>'ct_del=0'),
                            (object)array('sName' => 'apr_del', 'title' => 'Deleted', 'data' => 'apr_del', 'type'=> 'bool'),
                            (object)array('sName' => 'apr_isassembled', 'title' => 'Is Assembled', 'data' => 'apr_isassembled', 'type'=> 'bool'),
                        ];
                    }
                    if($part=="newitemproducts") {
                        $_TITLE="New Products (Unique Items)";
                        $_TABLE ="newitemproducts";
                        $_FIELDS = [
                            (object)array('sName' => 'npr_id', 'title' => 'No', 'data' => 'npr_id', 'type'=>'number' ),
                            (object)array('sName' => 'npr_image', 'title' => 'Image', 'data' => 'npr_image', 'type'=> 'image'),
                            (object)array('sName' => 'npr_sku', 'title' => 'SKU', 'data' => 'npr_sku', 'type'=> 'string'),
                            (object)array('sName' => 'npr_name', 'title' => 'Name', 'data' => 'npr_name', 'type'=> 'string'),
                            (object)array('sName' => 'npr_magqty', 'title' => 'QTY', 'data' => 'npr_magqty', 'type'=> 'string'),
                            (object)array('sName' => 'npr_lowstock', 'title' => 'Low', 'data' => 'npr_lowstock', 'type'=> 'function', 'fn'=> function($ad) {

                                $r="";
                                $r.='{"data" : "'.$ad->data.'", "render":function(dat,type,row){ return "<input type=hidden  name=\"hlow_"+row.npr_id+"\"  iden=\"s"+row.npr_id+"\" class=\"hcomment form-control form-control-sm\" value=\'"+dat+"\'><input type=text  name=\"low_"+row.npr_id+"\" onkeyup=\'LowkeyUp(event, "+"\"npr_lowstock\", "+row["npr_id"]+")\' iden=\"s"+row.npr_id+"\" class=\"comment form-control form-control-sm\" value=\'"+dat+"\'>";  }},'."\r\n";

                                return $r;
                             } ),
                            (object)array('sName' => 'npr_box_label', 'title' => 'Box Label', 'data' => 'npr_box_label', 'type'=> 'string'),
                            (object)array('sName' => 'npr_box_subtitle', 'title' => 'Box Subtitle Label', 'data' => 'npr_box_subtitle', 'type'=> 'string'),

                            (object)array('sName' => 'npr_mpn', 'title' => 'MPN', 'data' => 'npr_mpn', 'type'=> 'string'),
                            (object)array('sName' => 'npr_condition', 'title' => 'Condition', 'data' => 'npr_condition', 'type'=> 'string'),
                            (object)array('sName' => 'npm_pns', 'title' => 'P/N', 'data' => 'npm_pns', 'type'=> 'function', 'fn'=> function($ad) {

                                $r="";
                                $r.='{"data" : "'.$ad->data.'", "render":function(d,t,r){   return (d??"").replaceAll(",","<br/>") +" <a href=\'javascript:addNewMap("+r.npr_id+");\'><b>+</b></a>"; }},'."\r\n";

                                return $r;
                             } ),
                            (object)array('sName' => 'npr_category', 'title' => 'Category', 'data' => 'ct_name', 'type'=> 'foreign',
                                'foreign'=>'categories', 'foreign_id'=>'ct_id', 'foreign_name'=>'ct_name','foreign_filter'=>'ct_del=0'
                            ),
                            (object)array('sName' => 'npr_del', 'title' => 'Deleted', 'data' => 'npr_del', 'type'=> 'bool'),
                            (object)array('sName' => 'npr_isassembled', 'title' => 'Is Assembled', 'data' => 'npr_isassembled', 'type'=>'bool' ),
                        ];
                    }

                    if($part=="newitemproducts2") {
                        $_TITLE="New Products (Non-Unique Products Only)";
                        $_TABLE ="newitemproducts2";
                        $_FIELDS = [
                            (object)array('sName' => 'npr2_id', 'title' => 'No', 'data' => 'npr2_id', 'type'=>'number' ),
                            (object)array('sName' => 'npr2_image', 'title' => 'Image', 'data' => 'npr2_image', 'type'=> 'image'),
                            (object)array('sName' => 'npr2_sku', 'title' => 'SKU', 'data' => 'npr2_sku', 'type'=> 'string'),
                            (object)array('sName' => 'npr2_name', 'title' => 'Name', 'data' => 'npr2_name', 'type'=> 'string'),
                            (object)array('sName' => 'npr2_suppliercomments', 'title' => 'Supplier Comments', 'data' => 'npr2_suppliercomments', 'type'=> 'string'),
                            (object)array('sName' => 'npr2_magqty', 'title' => 'QTY', 'data' => 'npr2_magqty', 'type'=> 'string'),
                            (object)array('sName' => 'npr2_lowstock', 'title' => 'Low', 'data' => 'npr2_lowstock', 'type'=> 'function', 'fn'=> function($ad) {

                                $r="";
                                $r.='{"data" : "'.$ad->data.'", "render":function(dat,type,row){ return "<input type=hidden  name=\"hlow_"+row.npr2_id+"\"  iden=\"s"+row.npr2_id+"\" class=\"hcomment form-control form-control-sm\" value=\'"+dat+"\'><input type=text  name=\"low_"+row.npr2_id+"\" onkeyup=\'LowkeyUp(event, "+"\"npr2_lowstock\", "+row["npr2_id"]+")\' iden=\"s"+row.npr2_id+"\" class=\"comment form-control form-control-sm\" value=\'"+dat+"\'>";  }},'."\r\n";

                                return $r;
                             } ),
                            (object)array('sName' => 'npr2_box_label', 'title' => 'Box Label', 'data' => 'npr2_box_label', 'type'=> 'string'),
                            (object)array('sName' => 'npr2_box_subtitle', 'title' => 'Box Subtitle Label', 'data' => 'npr2_box_subtitle', 'type'=> 'string'),
                            (object)array('sName' => 'npr2_mpn', 'title' => 'MPN', 'data' => 'npr2_mpn', 'type'=> 'string'),
                            (object)array('sName' => 'npr2_condition', 'title' => 'Condition', 'data' => 'npr2_condition', 'type'=> 'string'),
                            (object)array('sName' => 'npr2_category', 'title' => 'Category', 'data' => 'ct_name', 'type'=> 'foreign',
                                'foreign'=>'categories', 'foreign_id'=>'ct_id', 'foreign_name'=>'ct_name','foreign_filter'=>'ct_del=0'
                            ),
                        ];
                    }



                   if($part=="products") {
                        $_TITLE="Laptop Products";
                        $_TABLE ="products";
                        $_FIELDS = [
                            (object)array('sName' => 'pr_id', 'title' => 'No', 'data' => 'pr_id', 'type'=>'number' ),
                            (object)array('sName' => 'pr_name', 'title' => 'Name', 'data' => 'pr_name', 'type'=> 'string'),
                            (object)array('sName' => 'pr_title', 'title' => 'Title', 'data' => 'pr_title', 'type'=> 'string'),

                            (object)array('sName' => 'pr_category', 'title' => 'Category', 'data' => 'ct_name', 'type'=> 'foreign',
                                'foreign'=>'categories', 'foreign_id'=>'ct_id', 'foreign_name'=>'ct_name','foreign_filter'=>'ct_del=0'
                            ),
                            (object)array('sName' => 'pr_manufacturer', 'title' => 'Manufacturer', 'data' => 'mf_name', 'type'=> 'foreign',
                            'foreign'=>'manufacturers', 'foreign_id'=>'mf_id', 'foreign_name'=>'mf_name','foreign_filter'=>'mf_del=0'
                            ),
                            (object)array('sName' => 'pr_description', 'title' => 'Description', 'data' => 'pr_description', 'type'=> 'string'),
                            (object)array('sName' => 'pr_del', 'title' => 'Deleted', 'data' => 'pr_del', 'type'=> 'bool'),
                        ];
                    }

                    ?>
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
							<a href="javascript:createModelShow()" class="btn btn-primary">
								<!-- Download SVG icon from http://tabler-icons.io/i/plus -->
								<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
								Create New
							</a>
						</div>
                    </div>

					<div class="table-responsive" style="padding:10px;margin:10px;">
                        <table id="dataList" class="table card-table table-vcenter hover text-nowrap datatable">
                            <thead>
                                <tr>
                                <?php
                                if(isset($_FIELDS) && count($_FIELDS)>0) {
                                    for($i=0;$i<count($_FIELDS);$i++) {
                                    $ad = $_FIELDS[$i];
                                    if($ad->type=='md5') continue;
                                    if($ad->data=='email_password') continue;
                                    if($ad->data=='npr_isassembled') continue;
                                    if($ad->data=='apr_isassembled') continue;
                                    if($ad->data=='npr2_suppliercomments') continue;
                                    echo '<th >'.$ad->title.'</th>'."\r\n";
                                    }
                                }
                                ?>

                                    <th></th>
                                    <?php if ($part!="users") { ?>
                                    <th></th>
                                    <?php } ?>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="card-footer d-flex align-items-center">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
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
            url:"/adminajax/<?php echo $part; ?>",
            type:"POST",
            data: { action:'search'},
            dataType:"json"
        },
        "columns" :[
            <?php
            if(isset($_FIELDS) && count($_FIELDS)>0) {
                for($i=0;$i<count($_FIELDS);$i++) {

                    $ad = $_FIELDS[$i];
                    if($ad->type=='md5') continue;
                    if($ad->data=='email_password') continue;

                    if($ad->data=='npr_isassembled') continue;
                    if($ad->data=='apr_isassembled') continue;
                    if($ad->data=='npr2_suppliercomments') continue;

                    if($ad->type=="image") {
                        echo '{"data": "'.$ad->data.'", render:function(d){ return  "<a href=\'https://www.ndc.co.uk/pub/media/catalog/product"+d+"\' onclick=\'return linkModelImage(this,event)\' target=\'_blank\'><img src=\'https://img.icons8.com/material-outlined/24/000000/image.png\'></a>"; } },'."\r\n";
                    }
                    else if($ad->type=="function") {
                        $action = $ad->fn;
                        echo $action($ad);

                    }
                    else if($ad->data=="npr2_name") {
                        echo '{"data": "'.$ad->data.'", render: function ( data, type, row ) {
                            var cellData = null;
                            if (row.npr2_suppliercomments != "") {
                                cellData = row.npr2_name + \'<br>\' + \'<p style=\"color:grey; margin-bottom: 0px!important;\">\' + row.npr2_suppliercomments + \'</p>\';
                            } else {
                                cellData = row.npr2_name;
                            }
                            return cellData;
                        }},'."\r\n";
                    }
                    else
                        echo '{"data" : "'.$ad->data.'"},'."\r\n";
                }
            }
            ?>


            {"data" : null, "searchable":false,"orderable":false, "width": "70px","render": function ( data, type, row, meta ) { return "<button onClick='updateModelShow(\""+f1+"\","+ row[f1] +")' type='button' class='btn btn-sm btn-primary'>Update<Update</button>" } },
        ],


    });
});


function addNewMap(apr_id) {
    $("#apraddmodal").data("apr",apr_id).modal('show');
    $("#newpn").val("");
}
function newPartNumber(tar) {

    var act = tar =="newitemproducts" ? "addaprnpn": "addaprpn";

    var apr_id = $("#apraddmodal").data("apr");
    var pd = { action: act, newpn:$('#newpn').val(), id: apr_id };

    $.ajax({
        url:"/adminajax/"+act,
        data:pd,
        type:'POST',
        success:function(a) {
          a = JSON.parse(a);

          if(a.success) {
            new_toast("success","Success.");
            $('#apraddmodal').modal("hide");
          }
          else
            new_toast("danger","Error! Reason is "+a.error);
          window.ETable.fnDraw();

        }
    });


    $("#apraddmodal").modal('show');
}
function linkModelImage(el,ev) {
    $('#imagemodal').modal("show");
    var rhref = $(el).attr("href");
    console.log(el, rhref);
    $("img.modal-content").prop("src",rhref);
    $("#imagemodal").show();
    return false;

}

function LowkeyUp(ev, column, npr2_id){
    console.log("Event here ",ev);
    if(ev.code=="Enter") {
        var data = {};
        data[column] = ev.target.value;
        var pd = { action: "update_low", data: data, id: npr2_id};

            $.ajax({
            url:"/adminajax/<?php echo $_TABLE;?>",
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


<div class="modal " id="apraddmodal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">New Part Number</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
        <div class="form-group">
            <label class="control-label">Part Number</label>
            <input type="text" value="" name="newpn" id="newpn" class="form-control" />
        </div>
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="$('#apraddmodal').modal('hide')">Close</button>
        <button type="button" class="btn btn-primary" onclick='newPartNumber("<?php echo $part;?>")'>Save</button>
      </div>

        </div>
    </div>
</div>

<div class="modal " id="imagemodal" tabindex="-1" role="dialog" aria-hidden="true" style="height: 900px">
    <div class="modal-dialog"  style="width: 1200px;height: 900px;max-width: 1200px;">
        <div class="modal-content">
            <img class="modal-content" src="" width="1200px" height="900px"/>
        </div>
    </div>
</div>
<?php
require_once("admin_update.php");
