<?php
$replace = [
    '臺中市' => '台中市',
    '臺北市' => '台北市',
    '臺南市' => '台南市',
    '臺東縣' => '台東縣',
];
$fullReplace = [
    '三峽區' => '新北市三峽區',
    '三重區' => '新北市三重區',
    '中和區' => '新北市中和區',
    '三峽區' => '新北市三峽區',
    '中壢區' => '桃園市中壢區',
    '五股區' => '新北市五股區',
    '中市北區' => '台中市北區',
    '中市西屯區' => '台中市西屯區',
    '三民區' => '高雄市三民區',
    '仁武區' => '高雄市仁武區',
    '前鎮區' => '高雄市前鎮區',
    '北屯區' => '台中市北屯區',
    '北市文山區' => '台北市文山區',
    '北市板橋區' => '新北市板橋區',
    '北投區' => '台北市北投區',
    '南市東區' => '台南市東區',
    '台中南屯區' => '台中市南屯區',
    '台中后里區' => '台中市后里區',
    '台中市五權三' => '台中市西區',
    '台中市忠明南' => '台中市西區',
    '台中市華美街' => '台中市西區',
    '台北市八德路' => '台北市松山區',
    '台北市南昌路' => '台北市中正區',
    '台北市南港東' => '台北市南港區',
    '台北市合江街' => '台北市中山區',
    '台北市安和路' => '台北市大安區',
    '台北市延平北' => '台北市士林區',
    '台南市健康三' => '台南市安平區',
    '台南市崇善七' => '台南市東區',
    '台南市北園街' => '台南市北區',
    '台南市中華南' => '台南市南區',
    '台北市葫蘆街' => '台北市士林區',
    '台北市金山南' => '台北市大安區',
    '台中市大里路' => '台中市大里區',
    '台中市三民路' => '台中市西區',
    '台北市忠孝東' => '台北市中正區',
    '台北市忠誠路' => '台北市士林區',
    '台北市建國南' => '台北市大安區',
    '台北市文山木' => '台北市文山區',
    '台北市民權東' => '台北市中山區',
    '台北市甘谷街' => '台北市大同區',
    '台北市石牌路' => '台北市北投區',
    '北市中正區' => '台北市中正區',
    '北市北投區' => '台北市北投區',
    '北市土城區' => '新北市土城區',
    '北市大安區' => '台北市大安區',
    '台北士林區' => '台北市士林區',
    '台中西屯區' => '台中市西屯區',
    '台中豐原區' => '台中市豐原區',
    '台北市投區' => '台北市北投區',
    '台北市汀州路' => '台北市中正區',
];
$zip = new ZipArchive;
$areaPath = dirname(__DIR__) . '/report/areas';
foreach (glob(dirname(__DIR__) . '/data/individual/account/109年總統、副總統選舉/*_1.zip') as $zipFile) {
    $pathParts = explode('/', $zipFile);
    $reportPath = $areaPath . '/' . $pathParts[8];
    if (!file_exists($reportPath)) {
        mkdir($reportPath, 0777, true);
    }
    $fh = fopen("zip://{$zipFile}#incomes.csv", 'r');
    fgetcsv($fh, 2048);
    $count = [];
    $candidate = '';
    while ($line = fgetcsv($fh, 2048)) {
        if (!isset($line[12])) {
            continue;
        }
        $candidate = $line[1];
        $line[8] = intval($line[8]);
        $line[12] = preg_replace('/[^\x{4e00}-\x{9fa5}]+/u', '', $line[12]);
        $line[12] = strtr($line[12], $replace);
        $areaPos = strpos($line[12], '區');
        if (false !== $areaPos) {
            $line[12] = substr($line[12], 0, $areaPos + 3);
        }
        if (isset($fullReplace[$line[12]])) {
            $line[12] = $fullReplace[$line[12]];
        }
        if (empty($line[12])) {
            continue;
        }
        if (!isset($count[$line[12]])) {
            $count[$line[12]] = [
                'count' => 0,
                'total' => 0,
            ];
        }
        $count[$line[12]]['count']++;
        $count[$line[12]]['total'] += $line[8];
    }
    if (empty($candidate)) {
        continue;
    }
    ksort($count);
    $oFh = fopen($reportPath . '/' . $candidate . '.csv', 'w');
    fputcsv($oFh, ['area', 'count', 'total']);
    foreach ($count as $area => $data) {
        fputcsv($oFh, [$area, $data['count'], $data['total']]);
    }
}

foreach (glob(dirname(__DIR__) . '/data/individual/account/113年總統、副總統選舉/*_1.zip') as $zipFile) {
    $pathParts = explode('/', $zipFile);
    $reportPath = $areaPath . '/' . $pathParts[8];
    if (!file_exists($reportPath)) {
        mkdir($reportPath, 0777, true);
    }
    $fh = fopen("zip://{$zipFile}#incomes.csv", 'r');
    fgetcsv($fh, 2048);
    $count = [];
    $candidate = '';
    while ($line = fgetcsv($fh, 2048)) {
        if (!isset($line[15])) {
            continue;
        }
        $candidate = $line[1];
        $line[8] = intval($line[8]);
        $line[15] = preg_replace('/[^\x{4e00}-\x{9fa5}]+/u', '', $line[15]);
        $line[15] = strtr($line[15], $replace);
        $areaPos = strpos($line[15], '區');
        if (false !== $areaPos) {
            $line[15] = substr($line[15], 0, $areaPos + 3);
        }
        if (isset($fullReplace[$line[15]])) {
            $line[15] = $fullReplace[$line[15]];
        }
        if (empty($line[15])) {
            continue;
        }
        if (!isset($count[$line[15]])) {
            $count[$line[15]] = [
                'count' => 0,
                'total' => 0,
            ];
        }
        $count[$line[15]]['count']++;
        $count[$line[15]]['total'] += $line[8];
    }
    if (empty($candidate)) {
        continue;
    }
    ksort($count);
    $oFh = fopen($reportPath . '/' . $candidate . '.csv', 'w');
    fputcsv($oFh, ['area', 'count', 'total']);
    foreach ($count as $area => $data) {
        fputcsv($oFh, [$area, $data['count'], $data['total']]);
    }
}
