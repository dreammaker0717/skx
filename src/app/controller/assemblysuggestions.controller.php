<?php
use alhimik1986\PhpExcelTemplator\params\CallbackParam;
use alhimik1986\PhpExcelTemplator\params\ExcelParam;
use alhimik1986\PhpExcelTemplator\PhpExcelTemplator;
use alhimik1986\PhpExcelTemplator\setters\CellSetterArrayValueSpecial;

require_once("vendor/autoload.php");

$db=M::db();

if ($action == "assemblysuggestions_excel") {

	define("SPECIAL_ARRAY_TYPE", CellSetterArrayValueSpecial::class);

	$sku = array();
	$name = array();
	$suggestedSKU = array();
	$suggestedQty = array();
	$suggestedKeyboard = array();

	$query = "SELECT p.dp_sku, p.dp_name, p.dp_mpn FROM dell_part p WHERE p.dp_sku REGEXP '^PLM\/DELL\/P1[0-9]{3}_X*$' AND NOT EXISTS (SELECT null FROM dco_stock s WHERE p.dp_id = s.dst_product AND s.dst_status IN (7,22,6))";
	$data = $db->query($query)->fetchAll();
	for ($i=0; $i < count($data); $i++) {
	   preg_match("/PLM\/DELL\/P1(.+)_X/s", $data[$i]['dp_sku'], $matches);
	   $suggestionQuery = "SELECT dp_sku, COUNT(dst_product) AS qty FROM dell_part LEFT JOIN dco_stock ON dp_id = dst_product WHERE dp_sku REGEXP '^PLM\/DELL\/P[2-9A-Z]{1}" . $matches[1] . "_X*$' AND dst_status IN (7,22,6) GROUP BY dst_product";
	   $suggestionData = $db->query($suggestionQuery)->fetchAll();
	   $suggestionSku = "";
	   $suggestionQty = "";
	   for ($j=0; $j < count($suggestionData); $j++) { 
	      if ($j == 0) {
	      	$suggestionSku .= $suggestionData[$j]['dp_sku'];
	      	$suggestionQty .= $suggestionData[$j]['qty'];
	      } else {
	      	$suggestionSku .= "\n" . $suggestionData[$j]['dp_sku'];
	      	$suggestionQty .= "\n" . $suggestionData[$j]['qty'];
	      }
	   }
	   $data[$i]['suggested_sku'] = $suggestionSku;
	   $data[$i]['suggested_qty'] = $suggestionQty;

	   $suggestionRedQuery = "SELECT dp_sku, COUNT(dst_product) AS qty FROM dell_part LEFT JOIN dco_stock ON dp_id = dst_product WHERE dp_sku REGEXP '^PLM\/DELL\/P1" . $matches[1] . "N_X*$' AND dst_status IN (7,22,6) GROUP BY dst_product";
	   $suggestionRedData = $db->query($suggestionRedQuery)->fetchAll();
	   for ($k=0; $k < count($suggestionRedData); $k++) {
	      $data[$i]['dp_name'] .= "\n" . $suggestionRedData[$k]['dp_sku'] . " " . $suggestionRedData[$k]['qty'] . " in stock.";
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
	foreach ($data as $entry) {
	   if ($entry['suggested_sku'] != "") {
	      	$sku[] = $entry['dp_sku'];
			$name[] = $entry['dp_name'];
			$suggestedSKU[] = $entry['suggested_sku'];
			$suggestedQty[] = $entry['suggested_qty'];
			$suggestedKeyboard[] = $entry['suggested_keyboard'];
	   }
	}

	$params = [
		"[dp_sku]" => new ExcelParam(SPECIAL_ARRAY_TYPE, $sku),
		"[dp_name]" => new ExcelParam(SPECIAL_ARRAY_TYPE, $name),
		"[suggested_sku]" => new ExcelParam(SPECIAL_ARRAY_TYPE, $suggestedSKU),
		"[suggested_qty]" => new ExcelParam(SPECIAL_ARRAY_TYPE, $suggestedQty),
		"[suggested_keyboard]" => new ExcelParam(SPECIAL_ARRAY_TYPE, $suggestedKeyboard),
	];
	PhpExcelTemplator::outputToFile(__DIR__ . "/assembly_suggestions_excel_template.xlsx", "./assembly_suggestions.xlsx", $params);
} else if ($action == "assemblysuggestions_excel_new") {
	
	define("SPECIAL_ARRAY_TYPE", CellSetterArrayValueSpecial::class);

	$sku = array();
	$name = array();
	$suggestedSKU = array();
	$suggestedQty = array();
	$suggestedKeyboard = array();

   $newSkuList = array();
   $existingOthersQuery = "SELECT p.dp_sku, COUNT(dst_product) AS qty FROM dell_part p LEFT JOIN dco_stock s ON p.dp_id = s.dst_product WHERE p.dp_sku REGEXP '^PLM\/DELL\/P[2-9A-Z]{1}[0-9]{3}_X*$' AND s.dst_status IN (7,22,6) GROUP BY s.dst_product";
   $existingOthersData = $db->query($existingOthersQuery)->fetchAll();
   for ($i=0; $i < count($existingOthersData); $i++) {
      preg_match("/PLM\/DELL\/P[2-9A-Z]{1}(.+)_X/s", $existingOthersData[$i]['dp_sku'], $matches);
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
      preg_match("/PLM\/DELL\/P1(.+)_X/s", strtoupper($newSkuList[$j]), $matches);
      $suggestionQuery = "SELECT dp_sku, dp_mpn, dp_name COUNT(dst_product) AS qty FROM dell_part LEFT JOIN dco_stock ON dp_id = dst_product WHERE dp_sku REGEXP '^PLM\/DELL\/P[2-9A-Z]{1}" . $matches[1] . "_X*$' AND dst_status IN (7,22,6) GROUP BY dst_product";
      $suggestionData = $db->query($suggestionQuery)->fetchAll();

      $suggestionSku = "";
      $suggestionQty = "";
      $data[$j]['dp_name'] = $suggestionData[0]['dp_name'];
      for ($k=0; $k < count($suggestionData); $k++) { 
         if ($k == 0) {
         	$suggestionSku .= $suggestionData[$k]['dp_sku'];
            $suggestionQty .= $suggestionData[$k]['qty'];
         } else {
         	$suggestionSku .= "\n" . $suggestionData[$k]['dp_sku'];
            $suggestionQty .= "\n" . $suggestionData[$k]['qty'];
         }
      }
      $data[$j]['suggested_sku'] = $suggestionSku;
      $data[$j]['suggested_qty'] = $suggestionQty;

      $suggestionRedQuery = "SELECT dp_sku, COUNT(dst_product) AS qty FROM dell_part LEFT JOIN dco_stock ON dp_id = dst_product WHERE dp_sku REGEXP '^PLM\/DELL\/P1" . $matches[1] . "N_X*$' AND dst_status IN (7,22,6) GROUP BY dst_product";
      $suggestionRedData = $db->query($suggestionRedQuery)->fetchAll();
      for ($k=0; $k < count($suggestionRedData); $k++) {
         $data[$j]['dp_name'] .= "\n" . $suggestionRedData[$k]['dp_sku'] . " " . $suggestionRedData[$k]['qty'] . " in stock.";
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

	foreach ($data as $entry) {
	   $sku[] = $entry['dp_sku'];
	   $name[] = $entry['dp_name'];
	   $suggestedSKU[] = $entry['suggested_sku'];
	   $suggestedQty[] = $entry['suggested_qty'];
	   $suggestedKeyboard[] = $entry['suggested_keyboard'];
	}

	$params = [
		"[dp_sku]" => new ExcelParam(SPECIAL_ARRAY_TYPE, $sku),
		"[dp_name]" => new ExcelParam(SPECIAL_ARRAY_TYPE, $name),
		"[suggested_sku]" => new ExcelParam(SPECIAL_ARRAY_TYPE, $suggestedSKU),
		"[suggested_qty]" => new ExcelParam(SPECIAL_ARRAY_TYPE, $suggestedQty),
		"[suggested_keyboard]" => new ExcelParam(SPECIAL_ARRAY_TYPE, $suggestedKeyboard),
	];
	PhpExcelTemplator::outputToFile(__DIR__ . "/assembly_suggestions_excel_template.xlsx", "./assembly_suggestions_new.xlsx", $params);
} else if($action == "updateignored"){
	try {
		$type = $_POST['data']['type'];
		$condition = $_POST['data']['condition'];
		$sku = $_POST['data']['sku'];

		if ($condition == 1) {
			$db->insert("assembly_ignored", ["ig_sku" => $sku, "ig_type" => $type]);
		} else{
			$db->delete("assembly_ignored", ["ig_sku" => $sku]);
		}

		header('Content-Type: application/json; charset=utf-8');
	    http_response_code(200);
	    echo json_encode('{ "success" : true }');
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode('{ "success" : false, "error": "'. $e->getMessage().'" }');
    }
}