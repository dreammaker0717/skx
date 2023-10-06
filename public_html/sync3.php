<?php
ini_set('max_execution_time', 10000);
ini_set('memory_limit', '2G');

// Config initialisation start
$sourceEnvironmentsData = [
    [
        'code' => 'seebay',
        'url' => 'https://www.seebay.co.uk/index.php',
        'token'=> 'rl3t7nhue8w77xrx2cql6kc5ixdm3xir'
    ],
    [
        'url' => 'second store',
        'token'=> ''
    ],
    [
        'url' => 'third store',
        'token'=> ''
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

function updateLog($record, $refresh = false) {
    $flags = 8;
    if ($refresh === true) {
        $flags = 0;
    }
    file_put_contents('./sync_log.txt', $record . "\n", $flags);
}

function matchesPattern($sku) {
    global $validationEndOfTheLinePattern;
    return substr($sku, -strlen($validationEndOfTheLinePattern)) === $validationEndOfTheLinePattern;
}

function updateTablesInformation($sku, $qty, $sourceEnvironmentData) {
    global $pdo;

    // Update nwp_products table start
    $nwpProductsSkuExistsResult = $pdo->query('SELECT `npr_id` FROM `nwp_products` WHERE `npr_sku`="' . $sku . '"')->fetch();
    $nwpProductsSkuExists = $nwpProductsSkuExistsResult && isset($nwpProductsSkuExistsResult['npr_id']);
    if ($nwpProductsSkuExists) {
        $nwpProductsResult = $pdo->exec('UPDATE `nwp_products` SET `npr_magqty`=' . $qty . ' WHERE `npr_sku`="' . $sku . '"');
        if ($nwpProductsResult === false) {
            updateLog($sku . ' - ' . $qty . ' - nwp_products updating Error. Query execution returned false - ' . $sourceEnvironmentData['url']);
        } else {
            updateLog($sku . ' - ' . $qty . ' - nwp_products ' . $nwpProductsResult . ' updated - ' . $sourceEnvironmentData['url']);
        }
    }
    // Update nwp_products table end

    // Update nwp_products2 table start
    $nwp2ProductsSkuExistsResult = $pdo->query('SELECT `npr2_id` FROM `nwp_products2` WHERE `npr2_sku`="' . $sku . '"')->fetch();
    $nwp2ProductsSkuExists = $nwp2ProductsSkuExistsResult && isset($nwp2ProductsSkuExistsResult['npr2_id']);
    if ($nwp2ProductsSkuExists) {
        $nwp2ProductsResult = $pdo->exec('UPDATE `nwp_products2` SET `npr2_magqty`=' . $qty . ' WHERE `npr2_sku`="' . $sku . '"');
        if ($nwp2ProductsResult === false) {
            updateLog($sku . ' - ' . $qty . ' - nwp_products2 updating Error. Query execution returned false - ' . $sourceEnvironmentData['url']);
        } else {
            updateLog($sku . ' - ' . $qty . ' - nwp_products2 ' . $nwp2ProductsResult . ' updated - ' . $sourceEnvironmentData['url']);
        }
    }
    // Update nwp_products2 table end

    // Update aproducts table start
    $aproductsProductsSkuExistsResult = $pdo->query('SELECT `apr_id` FROM `aproducts` WHERE `apr_sku`="' . $sku . '"')->fetch();
    $aproductsProductsSkuExists = $aproductsProductsSkuExistsResult && isset($aproductsProductsSkuExistsResult['apr_id']);
    if ($aproductsProductsSkuExists) {
        $aproductsProductsResult = $pdo->exec('UPDATE `aproducts` SET `apr_magqty`=' . $qty . ' WHERE `apr_sku`="' . $sku . '"');
        if ($aproductsProductsResult === false) {
            updateLog($sku . ' - ' . $qty . ' - aproducts updating Error. Query execution returned false - ' . $sourceEnvironmentData['url']);
        } else {
            updateLog($sku . ' - ' . $qty . ' - aproducts ' . $aproductsProductsResult . ' updated - ' . $sourceEnvironmentData['url']);
        }
    }
    // Update aproducts table end

    // Update dell_part table start
    $dellPartProductsSkuExistsResult = $pdo->query('SELECT `dp_id` FROM `dell_part` WHERE `dp_sku`="' . $sku . '"')->fetch();
    $dellPartProductsSkuExists = $dellPartProductsSkuExistsResult && isset($dellPartProductsSkuExistsResult['dp_id']);
    if ($dellPartProductsSkuExists) {
        $dellPartProductsResult = $pdo->exec('UPDATE `dell_part` SET `dp_magqty`=' . $qty . ' WHERE `dp_sku`="' . $sku . '"');
        if ($dellPartProductsResult === false) {
            updateLog($sku . ' - ' . $qty . ' - dell_part updating Error. Query execution returned false - ' . $sourceEnvironmentData['url']);
        } else {
            updateLog($sku . ' - ' . $qty . ' - dell_part ' . $dellPartProductsResult . ' updated - ' . $sourceEnvironmentData['url']);
        }
    }
    // Update dell_part table end
}

function getSkusForStore($storeCode) {
    global $pdo;
    $allSkus = [];
    $query = $pdo->query('SELECT `sku` FROM `stores_products` WHERE `code` = "'.$storeCode.'"');
    if ($query === false) {
        return $allSkus;
    }
    $allResultSkus = $query->fetchAll(PDO::FETCH_ASSOC);
    if ($allResultSkus === false) {
        return $allSkus;
    }
    foreach ($allResultSkus as $skuResult) {
        $allSkus[] = $skuResult['sku'];
    }
    return $allSkus;
}

updateLog('', true);

$timeStart = microtime(true);

$ch = curl_init();
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

foreach ($sourceEnvironmentsData as $sourceEnvironmentData) {

    $allSystemSkus = getSkusForStore($sourceEnvironmentData['code']);

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
    foreach ($stockDecodedData['items'] as $stockItem) {
        $productIdQty[$stockItem['product_id']] = $stockItem['qty'];
    }

    $totallyProcessed = 0;
    $totallyFetched = 0;
    for ($currentPage = 1; $currentPage <= $maxNumberOfPages; $currentPage++) {
        $firstElementForSlice = $currentPage * $pageSize - $pageSize;
        $slicedSystemSkusRaw = array_slice($allSystemSkus, $firstElementForSlice, $pageSize);
        if (count($slicedSystemSkusRaw) === 0) {
            break;
        }

        $skusForUrl = array_map(function ($value) { return urlencode($value); }, $slicedSystemSkusRaw);
        curl_setopt($ch, CURLOPT_URL, $sourceEnvironmentData['url']."/rest/V1/products?searchCriteria[filter_groups][0][filters][0][field]=sku&searchCriteria[filter_groups][0][filters][0][value]=".implode(',', $skusForUrl)."&searchCriteria[filter_groups][0][filters][0][condition_type]=in&fields=items[id,sku]&searchCriteria[pageSize]=$pageSize");
        $productsResult = curl_exec($ch);
        if ($productsResult === false) {
            updateLog("Error: Products batch fetching error - " . $sourceEnvironmentData['url']);
            break;
        }
        $decodedProductsData = @json_decode($productsResult, true);

        $slicedSystemSkus = [];
        foreach ($slicedSystemSkusRaw as $sku) {
            $slicedSystemSkus[$sku] = null;
        }
        if (!is_array($decodedProductsData['items'])) {
            $decodedProductsData['items'] = [];
        }
        foreach ($decodedProductsData['items'] as $item) {
            if (!isset($item['sku']) || !isset($item['id'])) {
                updateLog("Error: Wrong data. Skipping - " . $sourceEnvironmentData['url']);
                continue;
            }
            if (isset($productIdQty[$item['id']])) {
                $slicedSystemSkus[$item['sku']] = $productIdQty[$item['id']];
            } else {
                updateLog($item['sku'] . " Error: Not found in stock data - " . $sourceEnvironmentData['url']);
            }
        }

        $curlHandles = [];
        $master = curl_multi_init();
        foreach ( $slicedSystemSkus as $sku => $qty) {
            if ($qty !== null) {
                continue;
            }

            $curlHandle = curl_init($sourceEnvironmentData['url']."/rest/V1/products/".urlencode($sku) );
            $curlHandles[$sku] = $curlHandle;
            curl_setopt($curlHandle, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization: Bearer " . $sourceEnvironmentData['token']));
            curl_setopt($curlHandle , CURLOPT_RETURNTRANSFER , true );
            curl_multi_add_handle($master , $curlHandle );
        }
        $running = null;
        $mrc = null;
        do {
            $mrc = curl_multi_exec($master, $running);
        }
        while ($mrc == CURLM_CALL_MULTI_PERFORM);
        while ($running && $mrc == CURLM_OK) {
            if (curl_multi_select($master) != - 1) {
                do {
                    $mrc = curl_multi_exec($master , $running);
                }
                while ($mrc == CURLM_CALL_MULTI_PERFORM);
            }
        }
        foreach ($curlHandles as $sku => $curlHandle)
        {
            if (curl_error($curlHandle) == '') {
                $productResult = curl_multi_getcontent($curlHandle);
                if ($productResult === false) {
                    continue;
                }
                $productsResult = json_decode($productResult, true);
                if (isset($productsResult['extension_attributes']['stock_item']['qty'])) {
                    updateLog($sku . " Fetched by individual request - " . $sourceEnvironmentData['url']);
                    $qty = $productsResult['extension_attributes']['stock_item']['qty'];
                    $slicedSystemSkus[$sku] = $qty;
                }
            }
            curl_multi_remove_handle($master, $curlHandle);
        }
        curl_multi_close($master);

        foreach ($slicedSystemSkus as $systemSku => $qty) {
            if ($qty === null) {
                $notFoundSkus[] = $systemSku;
                updateLog($systemSku . " Error: Sku not found in store - " . $sourceEnvironmentData['url']);
            } else {
                $totallyFetched++;
                updateTablesInformation($systemSku, $qty, $sourceEnvironmentData);
            }
            $totallyProcessed++;
        }

        $timePoint = microtime(true);
        updateLog('Batch ' . $currentPage . ' is processed. Totally requested: ' . $totallyProcessed . ' products. Fetched qty for: ' . $totallyFetched . ' products. Time spent ' . round($timePoint-$timeStart) . 's');
    }
    $timePoint = microtime(true);
    updateLog('Environment ' . $sourceEnvironmentData['url'] . ' is processed. Totally requested' . $totallyProcessed . ' products. Fetched qty for: ' . $totallyFetched . ' products. Time spent ' . round($timePoint-$timeStart) . 's');
}
$timePoint = microtime(true);
updateLog('Synchronisation is done. Time spent ' . round($timePoint-$timeStart) . 's');
curl_close($ch);
// Script execution end