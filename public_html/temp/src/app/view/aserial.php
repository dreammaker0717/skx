					

<div class="page-body">
	<div class="container-fluid">
		<div class="col-lg-9" style="margin:0 auto;">
			<div class="card card-lg">
				<div class="card-body">
					<h2>Accessories - Change Serial Number</h2>
					<div class="text-muted" style="margin-bottom:20px;">Use this form to update the serial number of any accessories that have incorrect or temporary serial numbers in the database.<br />ALL products with the new type of barcodes need to be scanned out.</div>

					<div class="row align-items-center">                              
							<div class="col-auto width:200px;">
								<label for="ast_id" class="form-label">Stock ID</label>
								<input type="text" class="form-control" id="ast_id" name="ast_id" placeholder="Enter Stock Id" required>
							</div>                               
							<div class="col-auto" style="margin-top:1.6rem;">
								<button type="button" onclick="fetchStock()" class="btn btn-primary">Fetch</button>
							</div>                                
                      
					</div>

					<div class="row" id="edit" style="display:none">
						<br />
						<hr />
						<div class="col-12" id="editinfo" style="border:1px solid #e6e7e9; background-color:rgba(245,159,0,.1) !important;padding:2rem 2rem 1rem;width:600px;margin:0 auto;">
							<p>SKU:</p>
							<p>Name:</p>
							<p>Condition:</p>
							<p>Category:</p>
						</div>
						<br />
						<hr />
					<div class="row align-items-center">                              
						<div class="col-auto width:200px;">
							<label class="form-label">New Serial Number:</label>
							<input type=text class="form-control" id="serial" name="serial"/> 
						</div>
						<div class="col-auto" style="margin-top:1.6rem;">
							<button type="button" onclick="updateSerial()" class="btn btn-success">Update</button>
						</div>                                
					</div>



				</div>
			</div>
		</div>
	</div>
</div>



<script>
    function fetchStock() {
        var stock_id = $('#ast_id').val();
        SendAction({action: "getstockbyid", data: { ast_id: stock_id } }, stock_id, function(a,b,c){
            var da = (c.data[0]);
            
        var cx = "<p><b>Name:</b> "+da.apr_name+"</p>"+
            "<p><b>SKU:</b> "+da.apr_sku+"</p>"+
            "<p><b>Box Label:</b> "+da.apr_box_label+"</p>"+
            "<p><b>Box Subtitle:</b> "+da.apr_box_subtitle+"</p>"+
            "<p><b>Current S/N:</b> "+da.ast_servicetag+"</p>";

            $('#editinfo').html(cx);
            $('#serial').val(da.ast_servicetag);

            $('#edit').css("display","block");
successSound();
        });

    }
   

 function updateSerial() {
        var stock_id = $('#ast_id').val();
        var serial = $('#serial').val();

		SendAction({action:"getstock", data: {"serial" : serial}}, 0, function(a,b,c){ 
			
			if(c.data.length==0) {
				SendAction({action: "updatestockbyid", data: { serial: serial, ast_id: stock_id } }, stock_id, function(a,b,c){
					new_toast("success","Serial number updated.");
					$('#serial').val("");
					$('#ast_id').val("");
					$('#editinfo').html("");
					$('#edit').css("display","none");
					setTimeout(successSound(),1000);
				});

			}
			else new_toast("warning","Serial already used!");

		});


		
 }
    function SendAction(pd, ast_id, cb) {
		$.ajax({
			url: "/accstocksajax/" + ast_id,
			data: pd,

			type: 'POST',
			success: function(a) {
				if (typeof(a) == "string")
					a = JSON.parse(a);				
				if (cb)
					cb(pd, ast_id, a);
			},
			error: function(a) {
                if (typeof(a) == "string")
				    a = JSON.parse(a.responseJSON);
				if (cb)
					cb(pd, ast_id, a);
			}
		});
	}
</script>   