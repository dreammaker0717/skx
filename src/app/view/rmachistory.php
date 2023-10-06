<?php
$id = intval($id);
include(PATH_CONFIG."/constants.php");

?>
<div class="container-fluid">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
            <h2 class="page-title">
                Return #<?php echo $id; ?>
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
                        $data = M::db()->query("SELECT rmac_items_history.*, users.fullname FROM rmac_items_history left join users on users.user_id = rmac_items_history.rmac_user WHERE rmac_items_history.rmac_ID = " . $id . " ORDER BY rmac_items_history.rmac_date ASC")->fetchAll();

                        echo "<table class='table table-vcenter card-table'>";
                        echo "<thead><tr><th>Comment</th><th>Status</th><th>Date</th><th>Technician</th></tr></thead><tbody>";

                        foreach($data as $k=>$v) {
                            echo "<tr><td>".$v["rmac_comment"]."</td><td style='color:". @$_RMACSTATUSES[$v["rmac_status"]]["Color"]."'>". @$_STATUSES[$v["rmac_status"]]["Name"]."</td><td>".$v["rmac_date"]."</td><td>".$v["fullname"]."</td></tr>";
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
        url:"/newrmacstocksajax/<?php echo $id;?>",
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
    document.title="Return # <?php echo $id; ?>";
});
</script>