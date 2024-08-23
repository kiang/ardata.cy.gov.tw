<?php
$zip = new ZipArchive;
$dupPath = dirname(__DIR__) . '/report/dup';

foreach (glob(dirname(__DIR__) . '/data/individual/account/113年總統、副總統選舉/*_1.zip') as $zipFile) {
    $pathParts = explode('/', $zipFile);
    $reportPath = $dupPath . '/' . $pathParts[8];
    if (!file_exists($reportPath)) {
        mkdir($reportPath, 0777, true);
    }
    $fh = fopen("zip://{$zipFile}#expenditures.csv", 'r');
    fgetcsv($fh, 2048);
    $count = [];
    $candidate = '';
    while ($line = fgetcsv($fh, 2048)) {
        if (!isset($line[5]) || $line[5] !== '雜支支出') {
            continue;
        }
        $key = $line[4] . $line[5] . $line[6];
        if (!isset($count[$key])) {
            $count[$key] = [];
        }
        $candidate = $line[1];
        $count[$key][] = $line;
    }
    if (empty($candidate)) {
        continue;
    }
    $oFh = fopen($reportPath . '/' . $candidate . '.csv', 'w');
    foreach ($count as $key => $lines) {
        if (count($lines) > 3) {
            foreach ($lines as $line) {
                fputcsv($oFh, $line);
            }
        }
    }
}


foreach (glob(dirname(__DIR__) . '/data/individual/account/109年總統、副總統選舉/*_1.zip') as $zipFile) {
    $pathParts = explode('/', $zipFile);
    $reportPath = $dupPath . '/' . $pathParts[8];
    if (!file_exists($reportPath)) {
        mkdir($reportPath, 0777, true);
    }
    $fh = fopen("zip://{$zipFile}#expenditures.csv", 'r');
    fgetcsv($fh, 2048);
    $count = [];
    $candidate = '';
    while ($line = fgetcsv($fh, 2048)) {
        if (!isset($line[5]) || $line[5] !== '雜支支出') {
            continue;
        }
        $key = $line[4] . $line[5] . $line[6];
        if (!isset($count[$key])) {
            $count[$key] = [];
        }
        $candidate = $line[1];
        $count[$key][] = $line;
    }
    if (empty($candidate)) {
        continue;
    }
    $oFh = fopen($reportPath . '/' . $candidate . '.csv', 'w');
    foreach ($count as $key => $lines) {
        if (count($lines) > 3) {
            foreach ($lines as $line) {
                fputcsv($oFh, $line);
            }
        }
    }
}
