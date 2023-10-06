<?php
$ch = curl_init();
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization: Bearer 70o2ddj8ln10yep97lebmlngy2bhjmca"));
curl_setopt($ch, CURLOPT_URL, "http://www.5-5-5.eu/index.php/rest/V1/stockItems/lowStock/?scopeId=0&qty=999999999999&pageSize=10000");
$stockResult = curl_exec($ch);
curl_close($ch);

echo $stockResult;