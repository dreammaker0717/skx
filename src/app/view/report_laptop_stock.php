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
                     <h1 style="margin-left:1rem;">Laptop Stocks</h1>
                  </div>
               </div>
               <div class="row justify-content-between">
                  <div class="col-auto">
                     <h2 style="margin-left:1rem;">
                        Green States
                     </h2>
                  </div>
               </div>
               <div class="table-responsive d-flex justify-content-center" style="padding:10px;margin:10px;">
                  <table class="table table-hover" style="width: 700px;">
                     <thead>
                        <tr>
                           <th rowspan="2">Laptop Range</th>
                           <th colspan="5" style="text-align:center">Green</th>
                           <th colspan="2" style="text-align:center">Green - Not on Sale</th>
                        </tr>
                        <tr>
                           <th style="text-align:center">Qty</th>
                           <th style="text-align:center">Cost Price</th>
                           <th style="text-align:center">Sale Price</th>
                           <th style="text-align:center">Profit £</th>
                           <th style="text-align:center">Profit %</th>
                           <th style="text-align:center">Qty</th>
                           <th style="text-align:center">Cost Price</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php 
                        $query = "SELECT REGEXP_SUBSTR(st_allocatedto, 'LAP\/[^\/]*\/') AS laptoprange, COUNT(st_advertised > 0 OR NULL) AS ready_qty, SUM(CASE WHEN st_advertised > 0 THEN st_cost ELSE 0 END) ready_cost, SUM(CASE WHEN st_advertised > 0 THEN st_retail ELSE 0 END) ready_sale, COUNT(st_advertised = 0 OR NULL) AS notready_qty, SUM(CASE WHEN st_advertised = 0 THEN st_cost ELSE 0 END) notready_cost FROM stock WHERE st_status = 7 GROUP BY laptoprange";
                        $data = $db->query($query)->fetchAll();
                        $othersItem = $data[0];
                        if ($othersItem['laptoprange'] == '') {
                           $othersItem['laptoprange'] = "Others";
                           unset($data[0]);
                           array_push($data, $othersItem);
                        }
                        $readyQtyTotal = 0;
                        $readyCostTotal = 0;
                        $readySaleTotal = 0;
                        $notReadyQtyTotal = 0;
                        $notReadyCostTotal = 0;
                        foreach ($data as $entry) {
                           $profitPercent = 0;
                           if ($entry['ready_cost'] != 0) {
                              $profitPercent = (($entry['ready_sale'] - $entry['ready_cost'])/$entry['ready_cost'])*100;
                           }
                           echo "<tr><td>" . $entry['laptoprange'] . "</td><td style=\"text-align:center\">" . $entry['ready_qty'] . "</td><td style=\"text-align:center\">£" . number_format($entry['ready_cost']) . "</td><td style=\"text-align:center\">£" . number_format($entry['ready_sale']) . "</td><td style=\"text-align:center\">£" . number_format($entry['ready_sale'] - $entry['ready_cost']) . "</td><td style=\"text-align:center\">" . number_format($profitPercent) . "%</td><td style=\"text-align:center\">" . $entry['notready_qty'] . "</td><td style=\"text-align:center\">£" . number_format($entry['notready_cost']) . "</td></tr>";
                           $readyQtyTotal += $entry['ready_qty'];
                           $readyCostTotal += $entry['ready_cost'];
                           $readySaleTotal += $entry['ready_sale'];
                           $notReadyQtyTotal += $entry['notready_qty'];
                           $notReadyCostTotal += $entry['notready_cost'];
                        }
                        $profitPercentTotal = 0;
                        if ($readyCostTotal != 0) {
                           $profitPercentTotal = (($readySaleTotal - $readyCostTotal)/$readyCostTotal)*100;
                        }
                        echo "<tr><td><span style=\"font-weight:bold\">Total: </span></td><td style=\"text-align:center\"><span style=\"font-weight:bold;\">" . $readyQtyTotal . "</span></td><td style=\"text-align:center\"><span style=\"font-weight:bold;\">£" . number_format($readyCostTotal) . "</span></td><td style=\"text-align:center\"><span style=\"font-weight:bold;\">£" . number_format($readySaleTotal) . "</span></td><td style=\"text-align:center\"><span style=\"font-weight:bold;\">£" . number_format($readySaleTotal - $readyCostTotal) . "</span></td><td style=\"text-align:center\"><span style=\"font-weight:bold;\">" . number_format($profitPercentTotal) . "%</span></td><td style=\"text-align:center\"><span style=\"font-weight:bold;\">" . $notReadyQtyTotal . "</span></td><td style=\"text-align:center\"><span style=\"font-weight:bold;\">£" . number_format($notReadyCostTotal) . "</span></td></tr>";
                        ?>
                     </tbody>
                  </table>
                  </div>
                  <div class="table-responsive d-flex justify-content-center my-2" style="padding:10px;margin:10px;">
                     <table class="table" style="width: 700px;">
                        <thead>
                           <tr>
                              <th style="text-align:center">Total Laptops</th>
                              <th style="text-align:center">Total Cost Price</th>
                              <th style="text-align:center">Total Sale Price</th>
                           </tr>
                        </thead>
                        <tbody>
                           <tr><?php echo "<tr><td style=\"text-align:center\"><span style=\"font-weight:bold\">" . ($readyQtyTotal + $notReadyQtyTotal) . "</span></td><td style=\"text-align:center\"><span style=\"font-weight:bold\">£" . number_format($readyCostTotal + $notReadyCostTotal) . "</span></td><td style=\"text-align:center\"><span style=\"font-weight:bold\">£" . number_format($readySaleTotal) . "</span></td></tr>"; ?></tr>
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>