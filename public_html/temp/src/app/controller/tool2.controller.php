<?php

$db = M::db();


if($action =="acc_export_ajax") {

        $data= $db->query("select apr_sku,apr_name,apr_condition,apr_box_label,apr_box_subtitle,apr_image from aproducts")->fetchAll(PDO::FETCH_ASSOC);
       header('Content-Type: text/csv');
       header('Content-Disposition: attachment; filename="components.csv"');       
       $fp = fopen('php://output', 'wb');
       fputcsv($fp, ["sku","name","condition","box_label","box_subtitle","image"],',');
        foreach ($data as $line) {            
            fputcsv($fp, $line, ',');
        }
        fclose($fp);    

}
