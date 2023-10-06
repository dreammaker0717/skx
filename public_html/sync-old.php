<?php
ini_set('max_execution_time', 20000);
ini_set('memory_limit', '4G');

// Config initialisation start
$sourceEnvironmentsData = [
    [
        'url' => 'https://www.seebay.co.uk/index.php/',
        'token'=> 'rl3t7nhue8w77xrx2cql6kc5ixdm3xir'
    ],
    [
        'url' => 'https://www.ndc.co.uk/index.php/',
        'token'=> 'd10bg1hjluug3do4s9gd9udz7mg7cysd'
    ],
    [
        'url' => 'https://www.skunkwurx.co/index.php/',
        'token'=> 'xhyl8p974x5et9c98vo0sxdaw24kay8p'
    ]
];

$pageSize = 200;
$maxNumberOfPages = 500;

$validationEndOfTheLinePattern = '_X';

$destinationDatabaseCredentials = [
    'host' => '127.0.0.1',
    'db'   => 'skx_ndc3',
    'user' => 'skx_alper',
    'pass' => 'NW}5Bc,JZH?l'
];
// Config initialisation end

// Script execution start
$dsn = "mysql:host=".$destinationDatabaseCredentials['host'].";dbname=".$destinationDatabaseCredentials['db'].";charset=utf8";
$pdo = new PDO($dsn, $destinationDatabaseCredentials['user'], $destinationDatabaseCredentials['pass']);

$timeStart = microtime(true);

function updateLog($record, $refresh = false) {
    $flags = 8;
    if ($refresh === true) {
        $flags = 0;
    }
    file_put_contents('./sync_log.txt', $record . "\n", $flags);
}
updateLog('', true);

$ch = curl_init();
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

foreach ($sourceEnvironmentsData as $sourceEnvironmentData) {
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization: Bearer " . $sourceEnvironmentData['token']));

    curl_setopt($ch, CURLOPT_URL, $sourceEnvironmentData['url'] . "/rest/V1/stockItems/lowStock/?scopeId=0&qty=999999999999&pageSize=".$pageSize * $maxNumberOfPages);
    $stockResult = curl_exec($ch);

    if ($stockResult === false) {
        continue;
    }
    $stockDecodedData = json_decode($stockResult, true);
    if (empty($stockDecodedData['items'])) {
        updateLog("Error: Stock data fetching error - " . $sourceEnvironmentData['url']);
        continue;
    }
    $productIdQty = [];
    $productIdIsInStock = [];
    foreach ($stockDecodedData['items'] as $stockItem) {
        $productIdQty[$stockItem['product_id']] = $stockItem['qty'];
        $productIdIsInStock[$stockItem['product_id']] = $stockItem['is_in_stock'];
    }

    $totallyProcessed = 0;
    $totallyFetched = 0;
    for ($currentPage = 1; $currentPage <= $maxNumberOfPages; $currentPage++) {
        curl_setopt($ch, CURLOPT_URL, $sourceEnvironmentData['url'] . "/rest/V1/products?searchCriteria[filter_groups][0][filters][0][field]=sku&searchCriteria[filter_groups][0][filters][0][value]=&searchCriteria[filter_groups][0][filters][0][condition_type]=neq&fields=items[id,sku,status,price]&searchCriteria[pageSize]=$pageSize&searchCriteria[currentPage]=$currentPage");
        $productsResult = curl_exec($ch);

        if ($productsResult === false) {
            updateLog("Error: Product fetching error - " . $sourceEnvironmentData['url']);
            break;
        }
        $decodedProductsData = @json_decode($productsResult, true);
        if (empty($decodedProductsData['items'])) {
            break;
        }

        foreach ($decodedProductsData['items'] as $item) {
            $totallyFetched++;
            if (!isset($item['status']) || !isset($item['sku']) || !isset($item['id'])) {
                updateLog("Error: Wrong data. Skipping - " . $sourceEnvironmentData['url']);
                continue;
            }
            $sku = $item['sku'];
            $status = $item['status'];

            if (substr($sku, 0, 4) !== "LAP/") {
                if (substr($sku, -strlen($validationEndOfTheLinePattern)) !== $validationEndOfTheLinePattern) {
                    continue;
                }
            }

            if (isset($productIdQty[$item['id']])) {
                $qty = $productIdQty[$item['id']];
            } else {
                updateLog($sku . "Error: Not found in stock - " . $sourceEnvironmentData['url']);
                continue;
            }

            if (isset($productIdIsInStock[$item['id']])) {
                if($productIdIsInStock[$item['id']]){
                    $isInStock = 1;
                } else {
                    $isInStock = 0;
                }
            } else {
                updateLog($sku . "Error: Not found in stock - " . $sourceEnvironmentData['url']);
                continue;
            }

            if ($isInStock == 1 && $status == 1) {
                $price = $item['price'];
            } else {
                $price = 0;
            }

            // Update nwp_products table start
            $nwpProductsSkuExistsResult = $pdo->query('SELECT `npr_id` FROM `nwp_products` WHERE `npr_sku`="' . $sku . '"')->fetch();
            $nwpProductsSkuExists = isset($nwpProductsSkuExistsResult['npr_id']);

            if ($nwpProductsSkuExists) {
                $pdo->exec('UPDATE `nwp_products` SET `npr_magqty`=' . $qty . ', `npr_magprice`=' . $price . ', `npr_is_in_stock`=' . $isInStock . ', `npr_is_disabled`=' . $status . ' WHERE `npr_id`=' . $nwpProductsSkuExistsResult['npr_id']);
                updateLog($sku . ' - ' . $qty . ' - ' . $price . ', is in stock -' . $isInStock . ', is disabled -' . $status . ' - nwp_products updated - ' . $sourceEnvironmentData['url']);
            }
            // Update nwp_products table end

            // Update nwp_products2 table start
            $nwp2ProductsSkuExistsResult = $pdo->query('SELECT `npr2_id` FROM `nwp_products2` WHERE `npr2_sku`="' . $sku . '"')->fetch();
            $nwp2ProductsSkuExists = isset($nwp2ProductsSkuExistsResult['npr2_id']);

            if ($nwp2ProductsSkuExists) {
                $pdo->exec('UPDATE `nwp_products2` SET `npr2_magqty`=' . $qty . ', `npr2_magprice`=' . $price . ', `npr2_is_in_stock`=' . $isInStock . ', `npr2_is_disabled`=' . $status . ' WHERE `npr2_id`=' . $nwp2ProductsSkuExistsResult['npr2_id']);
                updateLog($sku . ' - ' . $qty . ' - ' . $price . ', is in stock -' . $isInStock . ', is disabled -' . $status . ' - nwp_products2 updated - ' . $sourceEnvironmentData['url']);
            }
            // Update nwp_products2 table end

            // Update aproducts table start
            $aproductsProductsSkuExistsResult = $pdo->query('SELECT `apr_id` FROM `aproducts` WHERE `apr_sku`="' . $sku . '"')->fetch();
            $aproductsProductsSkuExists = isset($aproductsProductsSkuExistsResult['apr_id']);

            if ($aproductsProductsSkuExists) {
                $pdo->exec('UPDATE `aproducts` SET `apr_magqty`=' . $qty . ', `apr_magprice`=' . $price . ', `apr_is_in_stock`=' . $isInStock . ', `apr_is_disabled`=' . $status . ' WHERE `apr_id`=' . $aproductsProductsSkuExistsResult['apr_id']);
                updateLog($sku . ' - ' . $qty . ' - ' . $price . ', is in stock -' . $isInStock . ', is disabled -' . $status . ' - aproducts updated - ' . $sourceEnvironmentData['url']);
            }
            // Update aproducts table end

            // Update dell_part table start
            $dellPartProductsSkuExistsResult = $pdo->query('SELECT `dp_id` FROM `dell_part` WHERE `dp_sku`="' . $sku . '"')->fetch();
            $dellPartProductsSkuExists = isset($dellPartProductsSkuExistsResult['dp_id']);

            if ($dellPartProductsSkuExists) {
                $pdo->exec('UPDATE `dell_part` SET `dp_magqty`=' . $qty . ', `dp_magprice`=' . $price . ', `dp_is_in_stock`=' . $isInStock . ', `dp_is_disabled`=' . $status . ' WHERE `dp_id`=' . $dellPartProductsSkuExistsResult['dp_id']);
                updateLog($sku . ' - ' . $qty . ' - ' . $price . ', is in stock -' . $isInStock . ', is disabled -' . $status . ' - dell_part updated - ' . $sourceEnvironmentData['url']);
            }
            // Update dell_part table end

            // Update laptop table start
            $laptopProductsSkuResult = $pdo->query('SELECT `st_id` FROM `stock` WHERE `st_status` = 7 AND `st_magsku`="' . $sku . '"');
            while ($laptopProduct = $laptopProductsSkuResult->fetch(PDO::FETCH_ASSOC)) {
                $pdo->exec('UPDATE `stock` SET `st_magenabled`=' . $status . ', `st_retail`=' . $price . ' WHERE `st_id`=' . $laptopProduct['st_id']);
                updateLog($sku . ' - ' . $price . ', is enabled -' . $status . ' - laptop updated - ' . $sourceEnvironmentData['url']);
            }
            // Update laptop table end

            $totallyProcessed++;
        }
        $timePoint = microtime(true);
        updateLog('Batch ' . $currentPage . ' is processed. Totally ' . $totallyProcessed . ' items matched of ' . $totallyFetched . '. Time spent ' . round($timePoint-$timeStart) . 's');
    }
    $timePoint = microtime(true);
    updateLog('Environment ' . $sourceEnvironmentData['url'] . ' is processed. Totally ' . $totallyProcessed . ' items matched of ' . $totallyFetched . '. Time spent ' . round($timePoint-$timeStart) . 's');
}
$timePoint = microtime(true);
updateLog('Synchronisation is done. Time spent ' . round($timePoint-$timeStart) . 's');
curl_close($ch);
// Script execution end