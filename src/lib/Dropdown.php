<?php

function Dropdown($table, $id,$name, $filter= null, $selected=null,$order=null, $multiple=null) {

    $filter_array=[];
    if($filter!=null && $filter!="1=1")
        $filter_array[] = $filter;

$selects = [];
array_push($selects,$id);
if(is_array($name))
{
    foreach($name as $k=>$v) {
        $selects[$k]=$v;
    }
}
else array_push($selects,$name);

$data = M::db()->select($table, $selects , $filter_array);   

//($order);

if($data!=null)  {
    if($order!=null) {
       $orderf= $order;
        if(is_array($order)) {
            foreach($order as $k=>$v) 
            $orderf = $k;
        }
        
        usort($data, fn($x,$y)=>  strcmp($x[$orderf],$y[$orderf]) );
    }
}

foreach($data as $val)  {
    if($multiple){
        $s = $selected!=null && in_array($val[$id], $selected) ? "selected" : "";
    }
    else{
        $s = $selected!=null && $selected == $val[$id] ? "selected" : "";
    }
  if(is_array($name)) {
    $namek="";
    foreach($name as $k=>$v) {
        $namek=$k;
    }

    echo "<option $s value='".$val[$id]."'>".$val[$namek]."</option>";
  }
  else 
    echo "<option $s value='".$val[$id]."'>".$val[$name]."</option>";
}
return $data;
}
