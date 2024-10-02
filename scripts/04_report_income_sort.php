<?php
$pool = [];
foreach (glob(dirname(__DIR__) . '/report/incomes/business/*.csv') as $csvFile) {
    $p = pathinfo($csvFile);
    $fh = fopen($csvFile, 'r');
    $head = fgetcsv($fh, 2048);
    $amount = 0;
    while ($line = fgetcsv($fh, 2048)) {
        $data = array_combine($head, $line);
        $amount += $data['捐贈金額'];
    }
    $amount = intval($amount);
    if (!isset($pool[$amount])) {
        $pool[$amount] = [];
    }
    $pool[$amount][] = $p['filename'];
}

krsort($pool);
$oFh = fopen(dirname(__DIR__) . '/report/incomes/business.csv', 'w');
foreach ($pool as $k => $lines) {
    foreach ($lines as $line) {
        fputcsv($oFh, [$k, $line]);
    }
}

$pool = [];
foreach (glob(dirname(__DIR__) . '/report/incomes/individual/*.csv') as $csvFile) {
    $p = pathinfo($csvFile);
    $fh = fopen($csvFile, 'r');
    $head = fgetcsv($fh, 2048);
    $amount = 0;
    while ($line = fgetcsv($fh, 2048)) {
        $data = array_combine($head, $line);
        $amount += $data['捐贈金額'];
    }
    $amount = intval($amount);
    if (!isset($pool[$amount])) {
        $pool[$amount] = [];
    }
    $pool[$amount][] = $p['filename'];
}

krsort($pool);
$oFh = fopen(dirname(__DIR__) . '/report/incomes/individual.csv', 'w');
foreach ($pool as $k => $lines) {
    foreach ($lines as $line) {
        fputcsv($oFh, [$k, $line]);
    }
}
