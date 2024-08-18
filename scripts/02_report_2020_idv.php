<?php
$zip = new ZipArchive;
$incomes = [];
$oFh = fopen(dirname(__DIR__) . '/report/idv.csv', 'w');
fputcsv($oFh, ['election', 'candidate', 'count', 'total']);
foreach (glob(dirname(__DIR__) . '/data/individual/account/109年總統、副總統選舉/*_1.zip') as $zipFile) {
    $fh = fopen("zip://{$zipFile}#incomes.csv", 'r');
    fgetcsv($fh, 2048);
    while ($line = fgetcsv($fh, 2048)) {
        if (!isset($line[8])) {
            continue;
        }
        $candidate = $line[1];
        $election = $line[2];
        $line[8] = intval($line[8]);
        if ($line[5] === '個人捐贈收入' || $line[5] === '匿名捐贈') {
            if (!isset($incomes[$candidate])) {
                $incomes[$candidate] = [
                    'count' => 0,
                    'total' => 0,
                ];
            }
            $incomes[$candidate]['count'] += 1;
            $incomes[$candidate]['total'] += $line[8];
        }
    }
}

foreach ($incomes as $candidate => $data) {
    fputcsv($oFh, [$election, $candidate, $data['count'], $data['total']]);
}

$incomes = [];

foreach (glob(dirname(__DIR__) . '/data/individual/account/113年總統、副總統選舉/*_1.zip') as $zipFile) {
    $fh = fopen("zip://{$zipFile}#incomes.csv", 'r');
    fgetcsv($fh, 2048);
    while ($line = fgetcsv($fh, 2048)) {
        if (!isset($line[8])) {
            continue;
        }
        $candidate = $line[1];
        $election = $line[2];
        $line[8] = intval($line[8]);
        if ($line[5] === '個人捐贈收入' || $line[5] === '匿名捐贈') {
            if (!isset($incomes[$candidate])) {
                $incomes[$candidate] = [
                    'count' => 0,
                    'total' => 0,
                ];
            }
            $incomes[$candidate]['count'] += 1;
            $incomes[$candidate]['total'] += $line[8];
        }
    }
}

foreach ($incomes as $candidate => $data) {
    fputcsv($oFh, [$election, $candidate, $data['count'], $data['total']]);
}
