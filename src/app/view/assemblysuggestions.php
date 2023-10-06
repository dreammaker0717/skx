<?php
$db = M::db();
include PATH_CONFIG . "/constants.php";
?>
<style type="text/css">
.table-hover tbody tr:hover td, .table-hover tbody tr:hover th {
   background-color: #ececec;
}
</style>
<div class="page-body">
   <div class="container-fluid">
      <div class="col-lg-11" style="margin:0 auto;">
         <div class="card card-lg">
            <div class="card-body" style="padding:3rem 1rem;">
               <div class="row justify-content-between">
                  <div class="col-auto">
                     <?php 
                     if ($new == 0) {
                        echo "<h1 style=\"margin-left:1rem;\">Assembly Suggestions</h1>";
                     } else {
                        echo "<h1 style=\"margin-left:1rem;\">New P1 Suggestions</h1>";
                     }
                     ?>
                  </div>
               </div>
               <div class="form-group row" style="margin-left:1rem;">
                  <div class="col-sm-2">
                     <button type="button" onclick="location.href='/assemblysuggestions/existing_allowed';" class="btn <?php if($new == 0) echo "btn-success"; else echo "btn-outline-success";?>">Existing SKU</button>
                     <button type="button" onclick="location.href='/assemblysuggestions/new_allowed';" class="btn <?php if($new == 0) echo "btn-outline-success"; else echo "btn-success";?>">New SKU</button>
                  </div>
               </div>

               <div class="form-group row mt-3" style="margin-left:1rem;">
                  <div class="col-sm-2">
                     <button type="button" onclick="openLink(0)" class="btn <?php if($ignored == 0) echo "btn-primary"; else echo "btn-outline-primary";?>">Allowed</button>
                     <button type="button" onclick="openLink(1)" class="btn <?php if($ignored == 0) echo "btn-outline-primary"; else echo "btn-primary";?>">Ignored</button>
                  </div>
               </div>
               <div class="table-responsive d-flex justify-content-center" style="padding:10px;margin:10px;">

                  <table class="table table-hover" id="suggestionsList">
                     <thead>
                        <tr>
                           <th style="width: 200px;">SKU</th>
                           <th style="width: 500px;">Name</th>
                           <th style="text-align:center; width: 200px;">Suggested Sku</th>
                           <th style="text-align:center; width: 100px;">Qty</th>
                           <th style="text-align:center; width: 300px;">Suggested Keyboard</th>
                           <th style="text-align:center; width: 150px;">Action</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php 
                        if ($new == 0) {
                           $query = "SELECT p.dp_sku, p.dp_name, p.dp_mpn FROM dell_part p WHERE p.dp_sku REGEXP '^PLM\/DELL\/P1[0-9]{3}_X*$' AND NOT EXISTS (SELECT null FROM dco_stock s WHERE p.dp_id = s.dst_product AND s.dst_status IN (7,22,6))";
                           $data = $db->query($query)->fetchAll();
                           for ($i=0; $i < count($data); $i++) {
                              preg_match("/PLM\/DELL\/P1(.+)_X/s", $data[$i]['dp_sku'], $matches);
                              $suggestionQuery = "SELECT dp_sku, COUNT(dst_product) AS qty FROM dell_part LEFT JOIN dco_stock ON dp_id = dst_product WHERE dp_sku REGEXP '^PLM\/DELL\/P[2-9A-Z]{1}" . $matches[1] . "_X*$' AND dst_status IN (7,22,6) GROUP BY dst_product";
                              $suggestionData = $db->query($suggestionQuery)->fetchAll();
                              $suggestionSku = "";
                              $suggestionQty = "";
                              for ($j=0; $j < count($suggestionData); $j++) { 
                                 $suggestionSku .= "<div>" . $suggestionData[$j]['dp_sku'] . "</div>";
                                 $suggestionQty .= "<div>" . $suggestionData[$j]['qty'] . "</div>";
                              }
                              $data[$i]['suggested_sku'] = $suggestionSku;
                              $data[$i]['suggested_qty'] = $suggestionQty;

                              $suggestionRedQuery = "SELECT dp_sku, COUNT(dst_product) AS qty FROM dell_part LEFT JOIN dco_stock ON dp_id = dst_product WHERE dp_sku REGEXP '^PLM\/DELL\/P1" . $matches[1] . "N_X*$' AND dst_status IN (7,22,6) GROUP BY dst_product";
                              $suggestionRedData = $db->query($suggestionRedQuery)->fetchAll();
                              for ($k=0; $k < count($suggestionRedData); $k++) {
                                 $data[$i]['dp_name'] .= "<div style='color: red;'>" . $suggestionRedData[$k]['dp_sku'] . " " . $suggestionRedData[$k]['qty'] . " in stock.</div>";
                              }

                              $data[$i]['suggested_keyboard'] = "";
                              $keyboardExists = preg_match("/\+ (.+)/s", $data[$i]['dp_mpn'], $kbmatches);
                              if ($keyboardExists) {
                                 $kbmatches[1] = ltrim($kbmatches[1], '0');
                                 $suggestedKeyboardQuery = "SELECT p.npr_sku, (SELECT COUNT(*) FROM nwp_stock s WHERE p.npr_id = s.nst_product AND nst_status IN (7,22,6)) AS qty FROM `nwp_products` p WHERE p.npr_mpn = '" . $kbmatches[1] . "'";
                                 $suggestedKeyboardData = $db->query($suggestedKeyboardQuery)->fetchAll();
                                 if ($suggestedKeyboardData) {
                                    $data[$i]['suggested_keyboard'] = $suggestedKeyboardData[0]['npr_sku'] . " - Qty " . $suggestedKeyboardData[0]['qty'];
                                 }
                              }
                           }

                           $finalData = array();
                           $ignoredQuery = "SELECT ig_sku FROM assembly_ignored WHERE ig_type = 0";
                           $ignoredData = $db->query($ignoredQuery)->fetchAll();
                           foreach ($data as $entry) {
                              if ($entry['suggested_sku'] != "") {
                                 $exists = false;
                                 foreach ($ignoredData as $ignoredItem) {
                                    if ($ignoredItem['ig_sku'] == $entry['dp_sku']) {
                                       $exists = true;
                                    }
                                 }
                                 if ($ignored == 0) {
                                    if ($exists == false) {
                                       $finalData[] = $entry;
                                    }
                                 } else {
                                    if ($exists == true) {
                                       $finalData[] = $entry;
                                    }
                                 }
                              }
                           }

                           foreach ($finalData as $entry) {
                              if ($ignored == 0) {
                                 echo "<tr><td>" . $entry['dp_sku'] . "</td><td>" . $entry['dp_name'] . "</td><td style=\"text-align:center\">" . $entry['suggested_sku'] . "</td><td style=\"text-align:center\">" . $entry['suggested_qty'] . "</td><td style=\"text-align:center\">" . $entry['suggested_keyboard'] . "</td><td class=\"align-middle text-center\"><button class=\"btn btn-sm btn-danger\" type=\"button\" onclick=\"updateIgnored(this, 0, 1, '" . $entry['dp_sku'] . "')\">Ignore</button></td></tr>";
                              } else{
                                 echo "<tr><td>" . $entry['dp_sku'] . "</td><td>" . $entry['dp_name'] . "</td><td style=\"text-align:center\">" . $entry['suggested_sku'] . "</td><td style=\"text-align:center\">" . $entry['suggested_qty'] . "</td><td style=\"text-align:center\">" . $entry['suggested_keyboard'] . "</td><td class=\"align-middle text-center\"><button class=\"btn btn-sm btn-danger\" type=\"button\" onclick=\"updateIgnored(this, 0, 0, '" . $entry['dp_sku'] . "')\">Un-ignore</button></td></tr>";
                              }
                           }
                        } else {
                           $newSkuList = array();
                           $existingOthersQuery = "SELECT p.dp_sku, COUNT(dst_product) AS qty FROM dell_part p LEFT JOIN dco_stock s ON p.dp_id = s.dst_product WHERE p.dp_sku REGEXP '^PLM\/DELL\/P[2-9A-Z]{1}[0-9]{3}_X*$' AND s.dst_status IN (7,22,6) GROUP BY s.dst_product";
                           $existingOthersData = $db->query($existingOthersQuery)->fetchAll();
                           for ($i=0; $i < count($existingOthersData); $i++) {
                              preg_match("/PLM\/DELL\/P[2-9A-Z]{1}(.+)_X/s", strtoupper($existingOthersData[$i]['dp_sku']), $matches);
                              $existsEnQuery = "SELECT dp_sku FROM dell_part WHERE dp_sku REGEXP '^PLM\/DELL\/P1" . $matches[1] . "_X*$'";
                              $existsEnData = $db->query($existsEnQuery)->fetchAll();
                              if(!$existsEnData){
                                 if(!in_array("PLM/DELL/P1" . $matches[1] . "_X", $newSkuList)){
                                    $newSkuList[] = "PLM/DELL/P1" . $matches[1] . "_X";
                                 }
                              }
                           }

                           $data = array();
                           for ($j=0; $j < count($newSkuList); $j++) {
                              $data[$j]['dp_sku'] = $newSkuList[$j];
                              preg_match("/PLM\/DELL\/P1(.+)_X/s", $newSkuList[$j], $matches);
                              $suggestionQuery = "SELECT dp_sku, dp_mpn, dp_name, COUNT(dst_product) AS qty FROM dell_part LEFT JOIN dco_stock ON dp_id = dst_product WHERE dp_sku REGEXP '^PLM\/DELL\/P[2-9A-Z]{1}" . $matches[1] . "_X*$' AND dst_status IN (7,22,6) GROUP BY dst_product";
                              $suggestionData = $db->query($suggestionQuery)->fetchAll();

                              $suggestionSku = "";
                              $suggestionQty = "";
                              $data[$j]['dp_name'] = $suggestionData[0]['dp_name'];
                              for ($k=0; $k < count($suggestionData); $k++) { 
                                 $suggestionSku .= "<div>" . $suggestionData[$k]['dp_sku'] . "</div>";
                                 $suggestionQty .= "<div>" . $suggestionData[$k]['qty'] . "</div>";
                              }
                              $data[$j]['suggested_sku'] = $suggestionSku;
                              $data[$j]['suggested_qty'] = $suggestionQty;

                              $suggestionRedQuery = "SELECT dp_sku, COUNT(dst_product) AS qty FROM dell_part LEFT JOIN dco_stock ON dp_id = dst_product WHERE dp_sku REGEXP '^PLM\/DELL\/P1" . $matches[1] . "N_X*$' AND dst_status IN (7,22,6) GROUP BY dst_product";
                              $suggestionRedData = $db->query($suggestionRedQuery)->fetchAll();
                              for ($k=0; $k < count($suggestionRedData); $k++) {
                                 $data[$j]['dp_name'] .= "<div style='color: red;'>" . $suggestionRedData[$k]['dp_sku'] . " " . $suggestionRedData[$k]['qty'] . " in stock.</div>";
                              }

                              $data[$j]['suggested_keyboard'] = "";
                              for ($l=0; $l < count($suggestionData); $l++) { 
                                 $keyboardExists = preg_match("/\+ (.+)/s", $suggestionData[$l]['dp_mpn'], $kbmatches);
                                 if ($keyboardExists) {
                                    $kbmatches[1] = ltrim($kbmatches[1], '0');
                                    $suggestedKeyboardQuery = "SELECT p.npr_sku FROM `nwp_products` p WHERE p.npr_mpn = '" . $kbmatches[1] . "'";
                                    $suggestedKeyboardData = $db->query($suggestedKeyboardQuery)->fetchAll();
                                    if ($suggestedKeyboardData) {
                                       $requiredKeyboard = substr_replace($suggestedKeyboardData[0]['npr_sku'], "1", 9, 1);
                                       $requiredKeyboardQuery = "SELECT p.npr_sku, (SELECT COUNT(*) FROM nwp_stock s WHERE p.npr_id = s.nst_product AND nst_status IN (7,22,6)) AS qty FROM `nwp_products` p WHERE p.npr_sku = '" . $requiredKeyboard . "'";
                                       $requiredKeyboardData = $db->query($requiredKeyboardQuery)->fetchAll();
                                       if ($requiredKeyboardData) {
                                          $data[$j]['suggested_keyboard'] = $requiredKeyboardData[0]['npr_sku'] . " - Qty " . $requiredKeyboardData[0]['qty'];
                                       }
                                       break;
                                    }
                                 }
                              }
                           }

                           usort($data, function($a, $b) {
                               return $a['dp_name'] <=> $b['dp_name'];
                           });

                           $finalData = array();
                           $ignoredQuery = "SELECT ig_sku FROM assembly_ignored WHERE ig_type = 1";
                           $ignoredData = $db->query($ignoredQuery)->fetchAll();

                           foreach ($data as $entry) {
                              $exists = false;
                              foreach ($ignoredData as $ignoredItem) {
                                 if ($ignoredItem['ig_sku'] == $entry['dp_sku']) {
                                    $exists = true;
                                 }
                              }
                              if ($ignored == 0) {
                                 if ($exists == false) {
                                    $finalData[] = $entry;
                                 }
                              } else {
                                 if ($exists == true) {
                                    $finalData[] = $entry;
                                 }
                              }
                           }

                           foreach ($finalData as $entry) {
                              if ($ignored == 0) {
                                 echo "<tr><td>" . $entry['dp_sku'] . "</td><td>" . $entry['dp_name'] . "</td><td style=\"text-align:center\">" . $entry['suggested_sku'] . "</td><td style=\"text-align:center\">" . $entry['suggested_qty'] . "</td><td style=\"text-align:center\">" . $entry['suggested_keyboard'] . "</td><td class=\"align-middle text-center\"><button class=\"btn btn-sm btn-danger\" type=\"button\" onclick=\"updateIgnored(this, 1, 1, '" . $entry['dp_sku'] . "')\">Ignore</button></td></tr>";
                              } else {
                                 echo "<tr><td>" . $entry['dp_sku'] . "</td><td>" . $entry['dp_name'] . "</td><td style=\"text-align:center\">" . $entry['suggested_sku'] . "</td><td style=\"text-align:center\">" . $entry['suggested_qty'] . "</td><td style=\"text-align:center\">" . $entry['suggested_keyboard'] . "</td><td class=\"align-middle text-center\"><button class=\"btn btn-sm btn-danger\" type=\"button\" onclick=\"updateIgnored(this, 1, 0, '" . $entry['dp_sku'] . "')\">Un-ignore</button></td></tr>";
                              }
                           }
                        }
                        ?>
                     </tbody>
                  </table>
               </div>
               <div class="row justify-content-between">
                  <div class="col-auto" style='margin-top:20px; margin-left:1rem;'>
                     <button class='btn btn-success' onClick="DownloadExcel()" type='button'>Download Excel</button>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<script>
var skuType = <?php echo $new; ?>;
function DownloadExcel(){
   <?php 
   if ($new == 0) {
      echo "window.location=\"/assemblysuggestionsexcelajax/\";";
   } else {
      echo "window.location=\"/newassemblysuggestionsexcelajax/\";";
   }
   ?>
}

function updateIgnored(e, type, condition, sku){
    var data = {};
    data["type"] = type;
    data["condition"] = condition;
    data["sku"] = sku;
    var pd = {action: "updateignored", data: data};

    $.ajax({ 
        url:"/assemblysuggestionsajax/",
        data:pd,
        type:'POST', 
        success:function(restResponse){
        var response = JSON.parse(restResponse);
        if(response.success) {
           new_toast("success","SKU state has been updated.");
           e.closest("tr").remove();
        }
        else 
            new_toast("danger","Error! Reason is "+a.error);
        } 
    });
}

function openLink(condition){
   if (skuType == 0) {
      if (condition == 0) {
         window.location="existing_allowed";
      } else{
         window.location="existing_ignored";
      }
   } else{
      if (condition == 0) {
         window.location="new_allowed";
      } else{
         window.location="new_ignored";
      }
   }
}
</script>