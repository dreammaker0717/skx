<?php


include(PATH_CONFIG."/constants.php");
$db=M::db();


//sku,name,condition,box_label,box_subtitle,image

if($part=="acc_loader") {
    
    if(isset($_POST["import"]) && isset($_POST["inlineRadioOptions"]) && $_POST["inlineRadioOptions"]=="updatecreate") {
        $fileName = $_FILES["csv_input"]["tmp_name"];
        if ($_FILES["csv_input"]["size"] > 0) {                       
            $file = fopen($fileName, "r");
            while (($column = fgetcsv($file, 10000, ",")) !== FALSE) {
                if($column[0]==="sku") continue;
                $iv = $db->count("aproducts",["apr_sku"=>$column[0] ]);
                if($iv==1) {
                 $db->update("aproducts",
                    [                        
                        "apr_name"=>$column[1], 
                        "apr_condition"=>$column[2],
                        "apr_box_label"=>$column[3],
                        "apr_box_subtitle"=>$column[4],
                        "apr_image"=>$column[5],                        
                        "apr_del"=>0                    
                    ],["apr_sku"=> $column[0]]);
                }
                if($iv==0) {
                    $db->insert("aproducts",
                    [
                        "apr_sku"=> $column[0], 
                        "apr_name"=>$column[1], 
                        "apr_condition"=>$column[2],
                        "apr_box_label"=>$column[3],
                        "apr_image"=>$column[4],
                        "apr_image"=>$column[5],
                        "apr_del"=>0                    
                    ]);
                }
            }            
        }       
    }
    if(isset($_POST["import"]) && isset($_POST["inlineRadioOptions"]) && $_POST["inlineRadioOptions"]=="update") {
        $fileName = $_FILES["csv_input"]["tmp_name"];
        if ($_FILES["csv_input"]["size"] > 0) {                       
            $file = fopen($fileName, "r");
            while (($column = fgetcsv($file, 10000, ",")) !== FALSE) {
                if($column[0]==="sku") continue;
                $iv = $db->count("aproducts",["apr_sku"=>$column[0] ]);
                echo $iv; echo $column[0];
                if($iv==1) {
                    $db->update("aproducts",
                    [                        
                        "apr_name"=>$column[1], 
                        "apr_condition"=>$column[2],
                        "apr_box_label"=>$column[3],
                        "apr_box_subtitle"=>$column[4],
                        "apr_image"=>$column[5],                        
                        "apr_del"=>0                    
                    ],["apr_sku"=> $column[0]]);
                }
            }            
        }       
    }
    if(isset($_POST["import"]) && isset($_POST["inlineRadioOptions"]) && $_POST["inlineRadioOptions"]=="createonly") {
        $fileName = $_FILES["csv_input"]["tmp_name"];
        if ($_FILES["csv_input"]["size"] > 0) {                       
            $file = fopen($fileName, "r");
            while (($column = fgetcsv($file, 10000, ",")) !== FALSE) {
                if($column[0]==="sku") continue;
                $iv = $db->count("aproducts",["apr_sku"=>$column[0] ]);
                if($iv==0) {
                    $db->insert("aproducts",
                    [
                        "apr_sku"=> $column[0], 
                        "apr_name"=>$column[1], 
                        "apr_condition"=>$column[2],
                        "apr_box_label"=>$column[3],
                        "apr_image"=>$column[4],
                        "apr_image"=>$column[5],
                        "apr_del"=>0                    
                    ]);
                }
            }            
        }       
    }
    if(isset($_POST["import"]) && isset($_POST["inlineRadioOptions"]) && $_POST["inlineRadioOptions"]=="recreate") {


       $sp1c= $db->query("select user_id from users where user_id=:u AND `password`=:p",
        [":p"=> md5( $_POST["sp1"]), ":u"=>$_SESSION["user_id"]  ])->fetchAll();

       
       
       if(count($sp1c) == 1) {
            $fileName = $_FILES["csv_input"]["tmp_name"];
            if ($_FILES["csv_input"]["size"] > 0) {       
                $db->exec("delete from aproducts");
                $file = fopen($fileName, "r");
                while (($column = fgetcsv($file, 10000, ",")) !== FALSE) {
                    if($column[0]==="sku") continue;
                    $db->insert("aproducts",
                    [
                        "apr_sku"=> $column[0], 
                        "apr_name"=>$column[1], 
                        "apr_condition"=>$column[2],
                        "apr_box_label"=>$column[3],
                        "apr_image"=>$column[4],
                        "apr_image"=>$column[5],
                        "apr_del"=>0                    
                    ]);
                }            
            }   
        }    
    }
    if(isset($_POST["import"]) && isset($_POST["inlineRadioOptions"]) && $_POST["inlineRadioOptions"]=="download") {
        echo "<form style='display:none;' method=post target='_blank' action='/tool2ajax/acc_export_ajax'><input type='hidden' name=action value='acc_export_ajax'/><input id='exp1' type=submit /></form>";
        echo "<script>document.getElementById('exp1').click();</script>";
        
    }
    

    
    echo '<div class="page-body">
    <div class="container-fluid">
		<div class="col-lg-11" style="margin:0 auto;">
			<div class="card card-lg">
				<div class="card-body" style="padding:3rem 1rem;">
					<div class="row align-items-center">
						<div class="col-auto">
							<h2 style="margin-left:1rem;">Accessory Database Management</h2>
							<div class="text-muted" style="color:#d63939 !important; padding-left:1rem;">

<!-- Download SVG icon from http://tabler-icons.io/i/alert-triangle -->
	<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9v2m0 4v.01" /><path d="M5 19h14a2 2 0 0 0 1.84 -2.75l-7.1 -12.25a2 2 0 0 0 -3.5 0l-7.1 12.25a2 2 0 0 0 1.75 2.75" /></svg>
WARNING - Use with Care - Permanent Damage Possible</div>
						</div>
					</div>';
    ?>
    <div class="card-body">
        <div class="row">
            <?php if($_SESSION["user_role"]=="3") { ?>

                <script>
                    function selectedOne(el) {
                        if(el.checked) {
                            if(el.value=="recreate"){
                                    $('.second1').css("display","block");
                                    $('.second1').find("input").attr("required","required");
                            }
                            else {
                                $('.second1').css("display","none");
                                $('.second1').find("input").attr("required",false);
                            }
                        }
                    }
                </script>

            <div class="col-auto">
                <form method="post" enctype="multipart/form-data"  action="/tool2/acc_loader">


                   <div class="form-group">
                        <div class="form-check">
                        <input class="form-check-input" onchange="selectedOne(this)" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="update">
                        <label class="form-check-label" for="inlineRadio1">Update Existing Products Only</label>
                        </div>
                        <div class="form-check">
                        <input class="form-check-input" onchange="selectedOne(this)" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="updatecreate">
                        <label class="form-check-label" for="inlineRadio2">Update Existing Products and Create New Products</label>
                        </div>
                        <div class="form-check">
                        <input class="form-check-input" onchange="selectedOne(this)" type="radio" name="inlineRadioOptions" id="inlineRadio3" value="createonly">
                        <label class="form-check-label" for="inlineRadio3">Create New Product Only</label>
                        </div>
                        <div class="form-check">
                        <input class="form-check-input"  onchange="selectedOne(this)" type="radio" name="inlineRadioOptions" id="inlineRadio4" value="recreate">
                        <label class="form-check-label" for="inlineRadio4">Delete All Existing Products And Create New</label>
                        </div>
                        <div class="form-check">
                        <input class="form-check-input" onchange="selectedOne(this)" type="radio" name="inlineRadioOptions" id="inlineRadio5" value="download">
                        <label class="form-check-label" for="inlineRadio5">Download Products</label>
                        </div>
                    </div>



                    <div class="form-group second1" style='display:none'>
                        <label class="form-label">Password</label>
                        <input type="password" name="sp1" class="form-control" value="">
                    </div>
                    <div class="form-group">                        
                        <label  class="mr-sm-2" for="csv_input">Upload Bulk CSV</label>
                        <input type="file" name="csv_input" class="form-control form-control-file" id="csv_input">   
                    </div>
                <button type="submit" name="import" class="btn btn-primary mt-3">Continue...</button>                                                                         
                </form>
            </div>
            <?php } ?>
        </div>
    </div>

    <?php                      
                echo "</div>";
            echo "</div>";
        echo "</div>";
    echo "</div>";
echo "</div>";    
}
