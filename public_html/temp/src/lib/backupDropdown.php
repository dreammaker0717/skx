<?php

function Dropdown($table, $id,$name, $filter= null, $selected=null,$order=null) {

    $filter_array=[];
    if($filter!=null && $filter!="1=1")
        $filter_array[] = $filter;

        
        
$data = M::db()->select($table,[$id, $name], $filter_array);   

($order);

if($data!=null)  {
    if($order!=null) {
       
        usort($data, fn($x,$y)=>  strcmp($x[$order],$y[$order]) );
    }
}

foreach($data as $val)  {
  $s = $selected!=null && $selected == $val[$id] ? "selected" : "";
  echo "<option $s value='".$val[$id]."'>".$val[$name]."</option>";
}
return $data;
}
