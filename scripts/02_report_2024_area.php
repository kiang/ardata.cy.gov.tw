<?php
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
