<?php

$url = "https://www.seebay.co.uk/index.php/";
$token = "rl3t7nhue8w77xrx2cql6kc5ixdm3xir";

$ch = curl_init();
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization: Bearer " . $token));

curl_setopt($ch, CURLOPT_URL, $url . "/rest/V1/products?searchCriteria[filter_groups][0][filters][0][field]=sku&searchCriteria[filter_groups][0][filters][0][value]=&searchCriteria[filter_groups][0][filters][0][condition_type]=neq&fields=items[id,sku,status]&searchCriteria[pageSize]=200&searchCriteria[currentPage]=1");
$stockResult = curl_exec($ch);
curl_close($ch);
// Script execution end
echo $stockResult;