<?php
echo '<div class="page-body">
        <div class="container-fluid">
            <div class="row row-cards">
                <div class="col-12">
                    <div class="card">
                         <div class="card-header">
                            <h3 class="card-title">New Product  - Change Serial Number</h3>
                            <div class="ms-2">
                          
                            </div>
                        </div>';
?>
                    <div class="card-body">
                        <div class="row">
                            <div class="col">                                
                                <div class="mb-3">
                                <label for="nst_id" class="form-label">Stock Id</label>
                                <input type="text" class="form-control" id="nst_id" name="nst_id" placeholder="Enter Stock Id" required>
                                </div>                               
                                <div class="col-12">
                                    <button type="button" onclick="fetchStock()" class="btn btn-primary">Fetch</button>
                                </div>                                
                             </div>                         
                        </div>
                        <hr />
                        <div class="row" id="edit" style="display:none">
                            <div class="col-12" id="editinfo">
                                <p>SKU:</p>
                                <p>Name:</p>
                                <p>Condition:</p>
                                <p>Category:</p>
                            </div>
                            <div class="col">
                                <div class="mb-3">
                                    <label class="form-label">Serial</label>
                                    <input type=text class="form-control" id="serial" name="serial"/> 
                                </div>
                            </div>
                            <div class="col-12">
                                    <button type="button" onclick="updateSerial()" class="btn btn-success">Update</button>
                                </div>                                
                        </div>
                    </div>
                     
<?php                      
                    echo "</div>";
                echo "</div>";
            echo "</div>";
        echo "</div>";
    echo "</div>";

?>
<script>
    function fetchStock() {
        var stock_id = $('#nst_id').val();
        SendAction({action: "getstockbyid", data: { nst_id: stock_id } }, stock_id, function(a,b,c){
            var da = (c.data[0]);
            
        var cx = "<p>Name: "+da.npr_name+"</p>"+
            "<p>SKU: "+da.npr_sku+"</p>"+
            "<p>Box Label: "+da.npr_box_label+"</p>"+
            "<p>Box Subtitle: "+da.npr_box_subtitle+"</p>"+
            "<p>Serial: "+da.nst_servicetag+"</p>";

            $('#editinfo').html(cx);
            $('#serial').val(da.nst_servicetag);

            $('#edit').css("display","block");

        });

    }
    function updateSerial() {
        var stock_id = $('#nst_id').val();
        var serial = $('#serial').val();
        SendAction({action: "updatestockbyid", data: { serial: serial, nst_id: stock_id } }, stock_id, function(a,b,c){
            new_toast("success","Updated!");
        });
    }

    function SendAction(pd, nst_id, cb) {
		$.ajax({
			url: "/newitemstocksajax/" + nst_id,
			data: pd,

			type: 'POST',
			success: function(a) {
				if (typeof(a) == "string")
					a = JSON.parse(a);				
				if (cb)
					cb(pd, nst_id, a);
			},
			error: function(a) {
                if (typeof(a) == "string")
				    a = JSON.parse(a.responseJSON);
				if (cb)
					cb(pd, nst_id, a);
			}
		});
	}
</script>   