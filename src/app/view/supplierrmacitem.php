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

    .all-images {
     padding:4px;
    }
    .all-images > a {
     padding:3px;
    }
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
							<h2 style="margin-left:1rem;">Supplier Returns for <?php 
                                $spp = M::db()->exec("select sp_name from suppliers where sp_id =" . $id)->fetchAll();
                                echo $spp[0]['sp_name'];
                            ?></h2>
						</div>
					</div>
                
                    <div class="table-responsive" style="padding:5px;margin:5px;">
						
						<table id="dataList" class="table stripe card-table table-vcenter hover text-nowrap datatable table-sm">
							<thead>
								<tr>
                                    <th>ID</th>
                                    <th>SKU</th>
                                    <th>IMAGES</th>
                                    <th>PRODUCT</th>
                                    <th>SERVICE TAG</th>
                                    <th>ORDER NUMBER</th>
                                    <th>CUSTOMER NAME</th>
                                    <th>PUCHASED ON</th>
                                    <th>ORDER DATE</th>
								</tr>
							</thead>
						</table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade " id="imagemodal" tabindex="-1" role="dialog" aria-hidden="true" style="height: 900px">
    <div class="modal-dialog"  style="width: 1200px;height: 900px;max-width: 1200px;">
        <div class="modal-content">
        <div class="all-images"></div>    
        <img class="modal-content" src="" width="1200px" height="900px"/>
            
        </div>
    </div>
</div>

<script>
$(function() {
    window.ETable = $('#dataList').dataTable({
     
        "lengthChange": true,
        "processing":true,
        "serverSide":true,
        "buttons": [],
        "infoEmpty": "No records available",
        "sProcessing": "DataTables is currently busy",
        "aLengthMenu": [[5, 25, 50,100,200], [5, 25, 50,100,200]],
        "iDisplayLength": 25,
        "dom": '<<<lB>f>rt<ip>>',
        "search": { "search" : new URLSearchParams(window.location.search).get("search")},
        "ajax":{
            url:"/supplierrmacajax",
            type:"POST",
            data: {action: 'search_items', id: <?php echo $id; ?>},
            dataType:"json"
        },
        
        "columns" :[
            {"data" : "rmac_ID", "searchable":false},
            {"data" : "rmac_sku"},
            {"data" : "rmac_images", "searchable":false, "render":function(dat, type, row){
                return  "<a href='#' onclick='popImages(\""+dat+"\")'><img src='https://img.icons8.com/material-outlined/24/000000/image.png'></a>"; 
            }},
            {"data" : "rmac_product", "searchable":false},
            {"data" : "rmac_servicetag", "searchable":false},
            {"data" : "supp_ordernumber", "orderable":false, "searchable":false},
            {"data" : "rmac_fullname"},
            {"data" : "rmac_purchasedon", "searchable":false},
            {"data" : "supp_orderdate", "orderable":false, "searchable":false},
        ],
    });
});


function popImages(l) {
    $('div.all-images').empty();
    if(l==null) return;
    if(l=='null') return;
    var t = l.split(',');

    if(t.length==0) return;    

    for(var i=0;i<t.length;i++) if(t[i]=="") delete t[i];

    if(t[0]=="" || t[0]=="null") return;

    $('#imagemodal').modal("show");
    $("img.modal-content").prop("src","../"+t[0]);

    for(var i=0;i<t.length;i++) {
        if(!t[i]) continue;
        var el =$("<a href='#' im='"+t[i]+"' >img_"+i+"</a>");
        el.bind("click", function() {
            $("img.modal-content").prop("src","../"+$(this).attr("im"));                
        });
        el.appendTo($('div.all-images'));
    }

    $("#imagemodal").show();
    return false;
}

</script>

 