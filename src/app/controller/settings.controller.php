<?php
include(PATH_CONFIG."/constants.php");

$db = M::db();

if($action=="get_printers") {
  $printersSQL = "SELECT * FROM devices";
  $printersResult = $db->query($printersSQL);
  if ($printersResult) {
    $printers = $printersResult->fetchAll();
    if ($printers) {
      $index = 0;
      $result = array();
      foreach ($printers as $printer) {
        $newArr = ['id' => $printer['id'], 'printer_name' => $printer['printer_name'], 'printer_ip' => $printer['printer_ip']];
        $result[$index] = $newArr;
        $index++;
      }
      header('Content-Type: application/json; charset=utf-8');
      http_response_code(200);
      echo json_encode(utf8ize($result));
    } else {
      exit();
    }
  } else {
    exit();
  }
} else if ($action=="delete_printer") {
  $deleteID = intval($_POST["printerID"]);
  $db->delete("devices", ["id" => $deleteID]);
  header('Content-Type: application/json; charset=utf-8');
  http_response_code(200);
  echo json_encode('{ "success" : true }');
} else if ($action=="add_printer") {
  $name = $_POST["printerName"];
  $printerIP = $_POST["printerIP"];

  $dataPrinter = array(
    'printer_name'=>$name,
    'printer_ip'=>$printerIP
  );
  $db->insert('devices',$dataPrinter);

  header('Content-Type: application/json; charset=utf-8');
  http_response_code(200);
  echo json_encode('{ "success" : true }');
} else if($action=="get_ship_from") {
  $shipFromSQL = "SELECT * FROM ship_from";
  $shipFromResult = $db->query($shipFromSQL);
  if ($shipFromResult) {
    $shipFroms = $shipFromResult->fetchAll();
    if ($shipFroms) {
      $index = 0;
      $result = array();
      foreach ($shipFroms as $shipFrom) {
        $newArr = ['id' => $shipFrom['id'], 'ship_from_name' => $shipFrom['ship_from_name']];
        $result[$index] = $newArr;
        $index++;
      }
      header('Content-Type: application/json; charset=utf-8');
      http_response_code(200);
      echo json_encode(utf8ize($result));
    } else {
      exit();
    }
  } else {
    exit();
  }
} else if ($action=="delete_ship_from") {
  $deleteID = intval($_POST["shipFromID"]);
  $db->delete("ship_from", ["id" => $deleteID]);
  header('Content-Type: application/json; charset=utf-8');
  http_response_code(200);
  echo json_encode('{ "success" : true }');
} else if ($action=="add_ship_from") {
  $locationName = $_POST["locationName"];
  $fullName = $_POST["fullName"];
  $companyName = $_POST["companyName"];
  $street1 = $_POST["street1"];
  $street2 = $_POST["street2"];
  $street3 = $_POST["street3"];
  $city = $_POST["city"];
  $state = $_POST["state"];
  $postalCode = $_POST["postalCode"];
  $country = $_POST["country"];
  $phone = $_POST["phone"];
  $isResidential = $_POST["isResidential"];
  $residential = 0;
  if ($isResidential) {
    $residential = 1;
  }

  $dataShipFrom = array(
    'ship_from_name'=>$locationName,
    'name'=>$fullName,
    'company'=>$companyName,
    'street1'=>$street1,
    'street2'=>$street2,
    'street3'=>$street3,
    'city'=>$city,
    'state'=>$state,
    'postal_code'=>$postalCode,
    'country'=>$country,
    'phone'=>$phone,
    'residential'=>$residential
  );

  $db->insert('ship_from', $dataShipFrom);

  header('Content-Type: application/json; charset=utf-8');
  http_response_code(200);
  echo json_encode('{ "success" : true }');
} else if($action=="get_preset_data"){
  $listUrl = "https://ssapi.shipstation.com/carriers";
  $listCurl = curl_init($listUrl);
  curl_setopt($listCurl, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($listCurl, CURLOPT_SSL_VERIFYPEER, true); // 証明書の検証を行わない
  curl_setopt($listCurl, CURLOPT_HTTPHEADER, array('Authorization: Basic MDY5MWEyYTQ0MjdkNDNlNmI5MTYxOGVjZjQ3YzllODk6NDdiYTUyZjliOTVhNDhmOGE3MzAxNzBhYTdjZWJhMjk='));
  $listResponse = curl_exec($listCurl);
  curl_close($listCurl);
  $carrierCodes = json_decode($listResponse, true);
  $mhServices = curl_multi_init();
  $i = 0;
  $resultServices = array();
  $curlServices = array();
  foreach ($carrierCodes as $code) {
      $url = "https://ssapi.shipstation.com/carriers/listservices?carrierCode=" . $code['code'];
      $curlServices[$i] = curl_init("$url");
      curl_setopt($curlServices[$i], CURLOPT_RETURNTRANSFER, true);
      curl_setopt($curlServices[$i], CURLOPT_SSL_VERIFYPEER, true); // 証明書の検証を行わない
      curl_setopt($curlServices[$i], CURLOPT_HTTPHEADER, array('Authorization: Basic MDY5MWEyYTQ0MjdkNDNlNmI5MTYxOGVjZjQ3YzllODk6NDdiYTUyZjliOTVhNDhmOGE3MzAxNzBhYTdjZWJhMjk='));
      curl_multi_add_handle($mhServices, $curlServices[$i]);
      $i++;
  }
  $runningServices = null; // execute the handles
  do {
      curl_multi_exec($mhServices, $runningServices);
      curl_multi_select($mhServices);
  } while ($runningServices > 0);
  for ($j = 0;$j < $i;$j++) {
      $resultServices[$j] = curl_multi_getcontent($curlServices[$j]);
      curl_multi_remove_handle($mhServices, $curlServices[$j]);
  }
  curl_multi_close($mhServices);

  $mhPackages = curl_multi_init();
  $k = 0;
  $resultPackages = array();
  $curlPackages = array();
  foreach ($carrierCodes as $code) {
      $url = "https://ssapi.shipstation.com/carriers/listpackages?carrierCode=" . $code['code'];
      $curlPackages[$k] = curl_init("$url");
      curl_setopt($curlPackages[$k], CURLOPT_RETURNTRANSFER, true);
      curl_setopt($curlPackages[$k], CURLOPT_SSL_VERIFYPEER, true); // 証明書の検証を行わない
      curl_setopt($curlPackages[$k], CURLOPT_HTTPHEADER, array('Authorization: Basic MDY5MWEyYTQ0MjdkNDNlNmI5MTYxOGVjZjQ3YzllODk6NDdiYTUyZjliOTVhNDhmOGE3MzAxNzBhYTdjZWJhMjk='));
      curl_multi_add_handle($mhPackages, $curlPackages[$k]);
      $k++;
  }
  $runningPackages = null; // execute the handles
  do {
      curl_multi_exec($mhPackages, $runningPackages);
      curl_multi_select($mhPackages);
  } while ($runningPackages > 0);
  for ($l = 0;$l < $k;$l++) {
      $resultPackages[$l] = curl_multi_getcontent($curlPackages[$l]);
      curl_multi_remove_handle($mhPackages, $curlPackages[$l]);
  }
  curl_multi_close($mhPackages);

  $finalResult = array();
  $m = 0;
  foreach ($resultServices as $result) {
      $arr = json_decode($result, true);
      foreach ($carrierCodes as $carrierCode) {
        if ($arr[0]['carrierCode'] == $carrierCode['code']) {
          error_log("yes");
            $finalResult['services'][$m]['carrierName'] = $carrierCode['name'];
            $finalResult['services'][$m]['isCarrierName'] = true;
            $m++;
        }
      }
      foreach ($arr as $entry) {
        foreach ($carrierCodes as $carrierCode) {
          if ($entry['carrierCode'] == $carrierCode['code']) {
              $finalResult['services'][$m]['carrierName'] = $carrierCode['name'];
              $finalResult['services'][$m]['carrierCode'] = $entry['carrierCode'];
              $finalResult['services'][$m]['serviceCode'] = $entry['code'];
              $finalResult['services'][$m]['serviceName'] = $entry['name'];
              $finalResult['services'][$m]['isCarrierName'] = false;
              $m++;
          }
        }
      }
  }

  $n = 0;
  foreach ($resultPackages as $result) {
      $arr = json_decode($result, true);
      foreach ($carrierCodes as $carrierCode) {
        if ($arr[0]['carrierCode'] == $carrierCode['code']) {
            $finalResult['packages'][$n]['carrierName'] = $carrierCode['name'];
            $finalResult['packages'][$n]['isCarrierName'] = true;
            $n++;
        }
      }
      foreach ($arr as $entry) {
        foreach ($carrierCodes as $carrierCode) {
          if ($entry['carrierCode'] == $carrierCode['code']) {
              $finalResult['packages'][$n]['carrierName'] = $carrierCode['name'];
              $finalResult['packages'][$n]['carrierCode'] = $entry['carrierCode'];
              $finalResult['packages'][$n]['packageCode'] = $entry['code'];
              $finalResult['packages'][$n]['packageName'] = $entry['name'];
              $finalResult['packages'][$n]['isCarrierName'] = false;
              $n++;
          }
        }
      }
  }
  $shipFromSQL = "SELECT * FROM ship_from";
  $shipFromResult = $db->query($shipFromSQL);
  if ($shipFromResult) {
    $shipFroms = $shipFromResult->fetchAll();
    if ($shipFroms) {
      $o = 0;
      foreach ($shipFroms as $shipFrom) {
        $finalResult['shipFrom'][$o]['id'] = $shipFrom['id'];
        $finalResult['shipFrom'][$o]['ship_from_name'] = $shipFrom['ship_from_name'];
        $finalResult['shipFrom'][$o]['name'] = $shipFrom['name'];
        $finalResult['shipFrom'][$o]['company'] = $shipFrom['company'];
        $finalResult['shipFrom'][$o]['street1'] = $shipFrom['street1'];
        $finalResult['shipFrom'][$o]['street2'] = $shipFrom['street2'];
        $finalResult['shipFrom'][$o]['street3'] = $shipFrom['street3'];
        $finalResult['shipFrom'][$o]['city'] = $shipFrom['city'];
        $finalResult['shipFrom'][$o]['state'] = $shipFrom['state'];
        $finalResult['shipFrom'][$o]['postal_code'] = $shipFrom['postal_code'];
        $finalResult['shipFrom'][$o]['country'] = $shipFrom['country'];
        $finalResult['shipFrom'][$o]['phone'] = $shipFrom['phone'];
        $finalResult['shipFrom'][$o]['residential'] = $shipFrom['residential'];
        $o++;
      }
    }
  }

  header('Content-Type: application/json; charset=utf-8');
  http_response_code(200);
  echo json_encode($finalResult);
} else if($action=="get_presets") {
  $presetsSQL = "SELECT * FROM shipping_presets ORDER BY sort_order ASC";
  $presetsResult = $db->query($presetsSQL);
  if ($presetsResult) {
    $presets = $presetsResult->fetchAll();
    if ($presets) {
      $index = 0;
      $result = array();
      foreach ($presets as $preset) {
        $newArr = ['id' => $preset['id'], 'preset_name' => $preset['preset_name'], 'sort_order' => $preset['sort_order'], 'color' => $preset['color'], 'weight' => $preset['weight'], 'length' => $preset['length'], 'width' => $preset['width'], 'height' => $preset['height'], 'carrier_code' => $preset['carrier_code'], 'service_code' => $preset['service_code'], 'package_code' => $preset['package_code'], 'ship_from_name' => $preset['ship_from_name'], 'international' => $preset['international'], 'residential' => $preset['residential']];
        $result[$index] = $newArr;
        $index++;
      }
      header('Content-Type: application/json; charset=utf-8');
      http_response_code(200);
      echo json_encode(utf8ize($result));
    } else {
      exit();
    }
  } else {
    exit();
  }
} else if ($action=="delete_preset") {
  $deleteID = intval($_POST["presetID"]);
  $db->delete("shipping_presets", ["id" => $deleteID]);
  header('Content-Type: application/json; charset=utf-8');
  http_response_code(200);
  echo json_encode('{ "success" : true }');
} else if ($action=="add_preset" || $action=="update_preset") {
  $presetName = $_POST["presetName"];
  $sortOrder = $_POST["sortOrder"];
  $color = $_POST["color"];
  $weight = $_POST["weight"];
  $length = $_POST["length"];
  $width = $_POST["width"];
  $height = $_POST["height"];
  $carrierCode = $_POST["carrierCode"];
  $carrierName = $_POST["carrierName"];
  $serviceCode = $_POST["serviceCode"];
  $serviceName = $_POST["serviceName"];
  $packageCode = $_POST["packageCode"];
  $packageName = $_POST["packageName"];
  $locationName = $_POST["locationName"];
  $fullName = $_POST["fullName"];
  $companyName = $_POST["companyName"];
  $street1 = $_POST["street1"];
  $street2 = $_POST["street2"];
  $street3 = $_POST["street3"];
  $city = $_POST["city"];
  $state = $_POST["state"];
  $postalCode = $_POST["postalCode"];
  $country = $_POST["country"];
  $phone = $_POST["phone"];
  $isInternational = $_POST["isInternational"];
  $residential = $_POST["isResidential"];
  $international = 0;
  if ($isInternational == "true") {
    $international = 1;
  }
  $dataShippingPreset = array(
    'preset_name'=>$presetName,
    'sort_order'=>$sortOrder,
    'color'=>$color,
    'weight'=>$weight,
    'length'=>$length,
    'width'=>$width,
    'height'=>$height,
    'carrier_code'=>$carrierCode,
    'carrier_name'=>$carrierName,
    'service_code'=>$serviceCode,
    'service_name'=>$serviceName,
    'package_code'=>$packageCode,
    'package_name'=>$packageName,
    'ship_from_name'=>$locationName,
    'name'=>$fullName,
    'company'=>$companyName,
    'street1'=>$street1,
    'street2'=>$street2,
    'street3'=>$street3,
    'city'=>$city,
    'state'=>$state,
    'postal_code'=>$postalCode,
    'country'=>$country,
    'phone'=>$phone,
    'international'=>$international,
    'residential'=>$residential
  );
  if ($action=="add_preset") {
    $db->insert('shipping_presets', $dataShippingPreset);
  } else if ($action=="update_preset") {
    $db->update('shipping_presets', $dataShippingPreset, ['id' => $_POST["presetID"]]);
  }

  header('Content-Type: application/json; charset=utf-8');
  http_response_code(200);
  echo json_encode('{ "success" : true }');
} else {
    exit();
}

function utf8ize($d) {
    if (is_array($d)) {
        foreach ($d as $k => $v) {
            $d[$k] = utf8ize($v);
        }
    } else if (is_string ($d)) {
        return utf8_encode($d);
    }
    return $d;
}
