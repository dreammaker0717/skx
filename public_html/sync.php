<?php
ini_set('max_execution_time', 10000);
ini_set('memory_limit', '2G');

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

function updateTablesInformation($sku, $qty) {
    global $sourceEnvironmentData, $pdo, $nwpSkus, $nwp2Skus, $aproductsSkus, $dellPartSkus;

    // Update nwp_products table start
    if (in_array($sku, $nwpSkus)) {
        $nwpProductsResult = $pdo->exec('UPDATE `nwp_products` SET `npr_magqty`=' . $qty . ' WHERE `npr_sku`="' . $sku . '"');
        if ($nwpProductsResult === false) {
            updateLog($sku . ' - ' . $qty . ' - nwp_products updating Error. Query execution returned false - ' . $sourceEnvironmentData['url']);
        } else {
            updateLog($sku . ' - ' . $qty . ' - nwp_products ' . $nwpProductsResult . ' updated - ' . $sourceEnvironmentData['url']);
        }
    }
    // Update nwp_products table end

    // Update nwp_products2 table start
    if (in_array($sku, $nwp2Skus)) {
        $nwp2ProductsResult = $pdo->exec('UPDATE `nwp_products2` SET `npr2_magqty`=' . $qty . ' WHERE `npr2_sku`="' . $sku . '"');
        if ($nwp2ProductsResult === false) {
            updateLog($sku . ' - ' . $qty . ' - nwp_products2 updating Error. Query execution returned false - ' . $sourceEnvironmentData['url']);
        } else {
            updateLog($sku . ' - ' . $qty . ' - nwp_products2 ' . $nwp2ProductsResult . ' updated - ' . $sourceEnvironmentData['url']);
        }
    }
    // Update nwp_products2 table end

    // Update aproducts table start
    if (in_array($sku, $aproductsSkus)) {
        $aproductsProductsResult = $pdo->exec('UPDATE `aproducts` SET `apr_magqty`=' . $qty . ' WHERE `apr_sku`="' . $sku . '"');
        if ($aproductsProductsResult === false) {
            updateLog($sku . ' - ' . $qty . ' - aproducts updating Error. Query execution returned false - ' . $sourceEnvironmentData['url']);
        } else {
            updateLog($sku . ' - ' . $qty . ' - aproducts ' . $aproductsProductsResult . ' updated - ' . $sourceEnvironmentData['url']);
        }
    }
    // Update aproducts table end

    // Update dell_part table start
    if (in_array($sku, $dellPartSkus)) {
        $dellPartProductsResult = $pdo->exec('UPDATE `dell_part` SET `dp_magqty`=' . $qty . ' WHERE `dp_sku`="' . $sku . '"');
        if ($dellPartProductsResult === false) {
            updateLog($sku . ' - ' . $qty . ' - dell_part updating Error. Query execution returned false - ' . $sourceEnvironmentData['url']);
        } else {
            updateLog($sku . ' - ' . $qty . ' - dell_part ' . $dellPartProductsResult . ' updated - ' . $sourceEnvironmentData['url']);
        }
    }
    // Update dell_part table end
}

updateLog('', true);

$timeStart = microtime(true);

$allSystemSkus = [];
$nwpSkus = [];
$nwpProductsSkus = $pdo->query('SELECT `npr_id`, `npr_sku` FROM `nwp_products` WHERE 1')->fetchAll(PDO::FETCH_ASSOC);
if (count($nwpProductsSkus) > 0) {
    foreach ($nwpProductsSkus as $nwpProductSku) {
        if (isset($nwpProductSku['npr_sku']) && !in_array($nwpProductSku['npr_sku'], $allSystemSkus) && matchesPattern($nwpProductSku['npr_sku'])) {
            $allSystemSkus[] = $nwpProductSku['npr_sku'];
            $nwpSkus[] = $nwpProductSku['npr_sku'];
        }
    }
}
$nwp2Skus = [];
$nwp2ProductsSkus = $pdo->query('SELECT `npr2_id`, `npr2_sku` FROM `nwp_products2` WHERE 1')->fetchAll(PDO::FETCH_ASSOC);
if (count($nwp2ProductsSkus) > 0) {
    foreach ($nwp2ProductsSkus as $nwp2ProductSku) {
        if (isset($nwp2ProductSku['npr2_sku']) && !in_array($nwp2ProductSku['npr2_sku'], $allSystemSkus) && matchesPattern($nwp2ProductSku['npr2_sku'])) {
            $allSystemSkus[] = $nwp2ProductSku['npr2_sku'];
            $nwp2Skus[] = $nwp2ProductSku['npr2_sku'];
        }
    }
}
$aproductsSkus = [];
$aproductsProductsSkus = $pdo->query('SELECT `apr_id`, `apr_sku` FROM `aproducts` WHERE 1')->fetchAll(PDO::FETCH_ASSOC);
if (count($aproductsProductsSkus) > 0) {
    foreach ($aproductsProductsSkus as $aproductsProductSku) {
        if (isset($aproductsProductSku['apr_sku']) && !in_array($aproductsProductSku['apr_sku'], $allSystemSkus) && matchesPattern($aproductsProductSku['apr_sku'])) {
            $allSystemSkus[] = $aproductsProductSku['apr_sku'];
            $aproductsSkus[] = $aproductsProductSku['apr_sku'];
        }
    }
}
$dellPartSkus = [];
$dellPartProductsSkus = $pdo->query('SELECT `dp_id`, `dp_sku` FROM `dell_part` WHERE 1')->fetch();
if (count($dellPartProductsSkus) > 0) {
    foreach ($dellPartProductsSkus as $dellPartProductSku) {
        if (isset($dellPartProductSku['dp_sku']) && !in_array($dellPartProductSku['dp_sku'], $allSystemSkus) && matchesPattern($dellPartProductSku['dp_sku'])) {
            $allSystemSkus[] = $dellPartProductSku['dp_sku'];
            $dellPartSkus[] = $dellPartProductSku['dp_sku'];
        }
    }
}

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
    foreach ($stockDecodedData['items'] as $stockItem) {
        $productIdQty[$stockItem['product_id']] = $stockItem['qty'];
    }

    $totallyProcessed = 0;
    $totallyFetched = 0;
    for ($currentPage = 1; $currentPage <= $maxNumberOfPages; $currentPage++) {
        $firstElementForSlice = $currentPage * $pageSize - $pageSize;
        $slicedSystemSkus = array_slice($allSystemSkus, $firstElementForSlice, $pageSize);
        if (count($slicedSystemSkus) === 0) {
            break;
        }

        $skusForUrl = array_map(function ($value) { return urlencode($value); }, $slicedSystemSkus);
        curl_setopt($ch, CURLOPT_URL, $sourceEnvironmentData['url']."/rest/V1/products?searchCriteria[filter_groups][0][filters][0][field]=sku&searchCriteria[filter_groups][0][filters][0][value]=".implode(',', $skusForUrl)."&searchCriteria[filter_groups][0][filters][0][condition_type]=in&fields=items[id,sku]&searchCriteria[pageSize]=$pageSize");
        $productsResult = curl_exec($ch);
        if ($productsResult === false) {
            updateLog("Error: Products batch fetching error - " . $sourceEnvironmentData['url']);
            break;
        }
        $decodedProductsData = @json_decode($productsResult, true);

        $slicedSystemSkus = array_fill_keys($slicedSystemSkus, null);
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
                $totallyFetched++;
            } else {
                updateLog($item['sku'] . " Error: Not found in stock data - " . $sourceEnvironmentData['url']);
            }
        }
        foreach ($slicedSystemSkus as $systemSku => $qty) {
            curl_setopt($ch, CURLOPT_URL, $sourceEnvironmentData['url']."/rest/V1/products/".urlencode($systemSku));

            if ($qty === null) {
                $productResult = curl_exec($ch);
                if ($productResult === false) {
                    updateLog($systemSku . " Error: Product fetching error - " . $sourceEnvironmentData['url']);
                    continue;
                }
                $productsResult = json_decode($productResult, true);
                if (isset($productsResult['extension_attributes']['stock_item']['qty'])) {
                    $qty = $productsResult['extension_attributes']['stock_item']['qty'];
                }
            }
            if ($qty !== null) {
                updateTablesInformation($systemSku, $qty);
                $totallyFetched++;
            } else {
                updateLog($systemSku . ' - Not found in store - ' . $sourceEnvironmentData['url']);
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