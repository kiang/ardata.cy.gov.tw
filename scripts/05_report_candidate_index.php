<?php
$pathCandidate = dirname(__DIR__) . '/report/incomes/candidate';
if (!file_exists($pathCandidate)) {
    mkdir($pathCandidate, 0777, true);
}

// Collect all rows from business and individual donor files, grouped by candidate
$candidateRows = [];
foreach (['business', 'individual'] as $type) {
    foreach (glob(dirname(__DIR__) . '/report/incomes/' . $type . '/*.csv') as $csvFile) {
        $fh = fopen($csvFile, 'r');
        $head = fgetcsv($fh, 2048);
        while ($line = fgetcsv($fh, 2048)) {
            $data = array_combine($head, $line);
            $candidate = $data['捐贈對象'];
            if (!isset($candidateRows[$candidate])) {
                $candidateRows[$candidate] = [];
            }
            $candidateRows[$candidate][] = $data;
        }
        fclose($fh);
    }
}

// Write per-candidate detail files and build index
$pool = [];
foreach ($candidateRows as $candidate => $rows) {
    $safeFilename = $pathCandidate . '/' . $candidate . '.csv';
    $oFh = fopen($safeFilename, 'w');
    fputcsv($oFh, ['選舉', '捐贈對象', '捐贈人', '捐贈日期', '捐贈金額']);
    $amount = 0;
    foreach ($rows as $row) {
        fputcsv($oFh, [$row['選舉'], $row['捐贈對象'], $row['捐贈人'], $row['捐贈日期'], $row['捐贈金額']]);
        $amount += $row['捐贈金額'];
    }
    fclose($oFh);

    $amount = intval($amount);
    if (!isset($pool[$amount])) {
        $pool[$amount] = [];
    }
    $pool[$amount][] = $candidate;
}

krsort($pool);
$oFh = fopen(dirname(__DIR__) . '/report/incomes/candidate.csv', 'w');
foreach ($pool as $k => $lines) {
    foreach ($lines as $line) {
        fputcsv($oFh, [$k, $line]);
    }
}
fclose($oFh);
