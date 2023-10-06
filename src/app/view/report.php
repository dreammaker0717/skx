<?php
include(PATH_CONFIG."/constants.php");
$db=M::db();

if($part=="profit_loss_by_order")
{

    
    $odg = $db->query("select 
	p.or_id ,p.or_date, sp.sp_name,p.or_total_items,
    (orange+purple+red+lightblue+darkblue+lightgreen+darkgreen+black+stripped+gray+brown+sold+actionreq+actioncmp ) as or_fix_rate, 
    sold+gray as or_sold, 
    darkgreen as or_readysell,
    (orange+purple+red+lightblue+darkblue+lightgreen+darkgreen+gray+brown+sold+actionreq+actioncmp ) as or_inprogress, 
    stripped+black as or_written,
    o.st_total_cost,st_total_soldprice, st_profit_sold, 0 as st_net_profit, 0 as projected_profit
    from orders p left join  order_distribution o on p.or_id = o.st_order left join suppliers sp on sp.sp_id = p.or_supplier")->fetchAll(PDO::FETCH_ASSOC);

    echo '<div class="page-body">
        <div class="container-fluid">
            <div class="row row-cards">
                <div class="col-12">
                    <div class="card">
                         <div class="card-header">
                            <h3 class="card-title">Profit Loss By Order</h3>
                            <div class="ms-2">
                          
                            </div>
                        </div>                  
                        <div class="table-responsive" style="padding:10px;margin:10px;">
                            <table id="dataList" class="table hover card-table table-vcenter text-nowrap datatable">';
                            echo "<thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Date</th>
                                        <th>Supplier</th>
                                        <th>QTY</th>
                                        <th>Fix Rate</th>
                                        <th>Sold</th>
                                        <th>Ready To Sell</th>
                                        <th>In Progress</th>
                                        <th>Written Off</th>
                                        <th>Total Cost</th>
                                        <th>Total Sold</th>
                                        <th>Profit on Sold Items</th>
                                        <th>Net Profit</th>
                                        <th>Projected Profit</th>
                                    </tr>                            
                                </thead>";
                                echo "<tbody>";
                                foreach($odg as $val){
                                    echo "<tr>";
                                    $ck=0;
                                    foreach($val as $k=>$v) {
                                        if($ck==0) {
                                            echo "<td><a href='../report/profit_loss_by_order_$v'><b>$v</b></a></td>";
                                        }
                                        else 
                                        echo "<td>$v</td>";
                                        $ck++;
                                    }
                                    echo "</tr>";
                                }
                                echo "</tbody>";
    
                            echo "</table>";
                        echo "</div>";
                    echo "</div>";
                echo "</div>";
            echo "</div>";
        echo "</div>";
    echo "</div>";
            


}
elseif(stripos($part,"profit_loss_by_order_")===0){
    $id = intval(str_replace("profit_loss_by_order_","",$part));

    $query="select s.st_id, m.mf_name, p.pr_name,s.st_status, s.st_cost,  s.st_soldprice, if(s.st_status IN(9,16), s.st_soldprice-s.st_cost, 0) as st_profit from stock s 
	left join products p on p.pr_id = s.st_product 
    left join categories c on c.ct_id = p.pr_category
    left join manufacturers m on m.mf_id = p.pr_manufacturer where s.st_order = ".$id;
    $odg = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);

    echo '<div class="page-body">
    <div class="container-fluid">
        <div class="row row-cards">
            <div class="col-12">
                <div class="card">
                     <div class="card-header">
                        <h3 class="card-title">Profit Loss By Order # '.$id.'</h3>
                        <div class="ms-2">
                      
                        </div>
                    </div>';         

    echo ' 
        <div class="table-responsive" style="padding:10px;margin:10px;">
            <table id="dataList" class="table hover card-table table-vcenter text-nowrap datatable">
                <thead>
                    <tr>
                        <th>Stock ID</th><th>Manufacturer</th><th>Product Name</th><th>Status</th><th>Price	Cost (£)</th><th>Sold(£)</th><th>Profit</th>
                    </tr>                    
                </thead>
                <tbody>
        ';
        $v4=0;
        $v5=0;
        $v6=0;
                foreach($odg as $val){
                    echo "<tr>";
                    $ck=0;
                    foreach($val as $k=>$v) {
                        if($ck==0) {
                            echo "<td><a href='../stock/$v'><b>$v</b></a></td>";
                        }
                        else 
                            echo "<td>$v</td>";
                        if($ck==4) $v4+=$v;
                        if($ck==5) $v5+=$v;
                        if($ck==6) $v6+=$v;
                        
                         $ck++;

                    }
                    echo "</tr>";
                }

        echo '
                </tbody>
                <foot>
                    <tr><th style="text-align:right;font-size:1rem" colspan=4>Totals</th><th style="font-size:1rem">'.$v4.'</th><th style="font-size:1rem">'.$v5.'</th><th style="font-size:1rem">'.$v6.'</th></tr>
                </tfoot>
            </table>
        </div>
    ';
    echo "</div>";
    echo "</div>";
    echo "</div>";
    echo "</div>";
    echo "</div>";  
}