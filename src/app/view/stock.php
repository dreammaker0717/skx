<?php
$id = intval($id);
include(PATH_CONFIG."/constants.php");

?>
<div class="container-fluid">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
            <h2 class="page-title">
                STOCK #<?php echo $id; ?>
            </h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
               
              </div>
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-fluid">
        <div class="row row-cards">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                        <?php


                        $query=
                        "select st_id,st_product,st_order,st_servicetag,st_lastcomment,st_date,st_status,
                            st_requested,st_specs,st_retail,st_allocated,st_allocatedto,st_allocatedemail,st_onsale,st_soldprice,st_actionreq,
                            mf_name,ct_name,pr_name,pr_title
                        from stock, manufacturers, products, categories
                        where id=$id
                            AND  mf_id=pr_manufacturer AND ct_id=pr_category AND st_product=pr_id";

                        $data = M::db()->query("SELECT stock_history.*, users.fullname FROM stock_history left join users on users.user_id = sh_user WHERE sh_stock=".$id." ORDER BY sh_date ASC")->fetchAll();


                        echo "<table class='table table-vcenter card-table'>";
                        echo "<thead><tr><th>Comment</th><th>Status</th><th>Date</th><th>Technician</th></tr></thead><tbody>";

                        foreach($data as $k=>$v) {

                            
                            echo "<tr><td>".$v["sh_comment"]."</td><td style='color:". @$_STATUSES[$v["sh_status"]]["Color"]."'>". @$_STATUSES[$v["sh_status"]]["Name"]."</td><td>".$v["sh_date"]."</td><td>".$v["fullname"]."</td></tr>";

                        }

                        echo "</tbody></table>";

                        ?>
                        </div>
                    </div>
                </div>
            </div>

             <div class="col-md-12">
             <div class="card">
                    <div class="card-body">
                            <form>
                                    <div class="form-group mb-3 row">
                                    <label class="form-label col-1 col-form-label">Comment</label>
                                    <div class="col">
                                    <input type="text" class="form-control" aria-describedby="commentHelp" id="commentBox" placeholder="Enter comment">
                                        <small class="form-hint">Please enter new comment here...</small>
                                    </div>
                                    <div class="col-1"><button type="button" onclick="newcomment()" class="btn btn-primary">Comment</button></div>
                                    </div>                  
                                
                            </form>                
                     
                     
                            <form>
                                    <div class="form-group mb-3 row">
                                    <label class="form-label col-1 col-form-label">Request</label>
                                    <div class="col">
                                    <input type="text" class="form-control" aria-describedby="requestHelp" id="requestBox" placeholder="Enter request">
                                        <small class="form-hint">Please enter new request here...</small>
                                    </div>
                                    <div class="col-1"><button type="button" onclick="newrequest()" class="btn btn-primary">Request</button></div>
                                    </div>                  
                                
                            </form> 
                        </div>
                    </div>
            </div>

        </div>
    </div>
</div>
<script>
function newcomment() {
    var pd = { action: "comment", data: { "cm": $('#commentBox').val() } };
    $.ajax({ 
        url:"/stocksajax/<?php echo $id;?>",
        data:pd,
        type:'POST', 
        success:function(a) {
        a = JSON.parse(a);                                   
        if(a.success) {            
            new_toast("success","Success.");                      
        }
        else 
            new_toast("danger","Error! Reason is "+a.error);
        
        
        } 
    });  
}
function newrequest() {
    var pd = { action: "request", data: { "req" : $('#requestBox').val() } };
    $.ajax({ 
        url:"/stocksajax/<?php echo $id;?>",
        data:pd,
        type:'POST', 
        success:function(a) {
        a = JSON.parse(a);                                   
        if(a.success) {            
            new_toast("success","Success.");                      
        }
        else 
            new_toast("danger","Error! Reason is "+a.error);
        
        
        } 
    });  
}

    
$(function() {
    document.title="Stock # <?php echo $id; ?> "+ document.title;
});
</script>