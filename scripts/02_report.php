<?php
$zip = new ZipArchive;
$expenditures = $incomes = array();
foreach(glob(dirname(__DIR__) . '/data/indifidual/account/109年立法委員選舉/*/*.zip') AS $zipFile) {
    $fh = fopen("zip://{$zipFile}#expenditures.csv", 'r');
    fgetcsv($fh, 2048);
    while($line = fgetcsv($fh, 2048)) {
        if(!isset($line[7])) {
            continue;
        }
        $line[7] = trim($line[7]);
        if(!empty($line[7]) && false === strpos($line[7], '*')) {
            if(!isset($expenditures[$line[7]])) {
                $expenditures[$line[7]] = array(
                    'id' => $line[7],
                    'name' => $line[6],
                    'money' => 0,
                );
            }
            $expenditures[$line[7]]['money'] += intval($line[9]);
        }
    }

    $fh = fopen("zip://{$zipFile}#incomes.csv", 'r');
    fgetcsv($fh, 2048);
    while($line = fgetcsv($fh, 2048)) {
        if(!isset($line[7])) {
            continue;
        }
        $line[7] = trim($line[7]);
        if(!empty($line[7]) && false === strpos($line[7], '*')) {
            if(!isset($incomes[$line[7]])) {
                $incomes[$line[7]] = array(
                    'id' => $line[7],
                    'name' => $line[6],
                    'money' => 0,
                );
            }
            $incomes[$line[7]]['money'] += intval($line[8]);
        }
    }
}
foreach(glob(dirname(__DIR__) . '/data/indifidual/account/109年總統、副總統選舉/*.zip') AS $zipFile) {
    $fh = fopen("zip://{$zipFile}#expenditures.csv", 'r');
    fgetcsv($fh, 2048);
    while($line = fgetcsv($fh, 2048)) {
        if(!isset($line[7])) {
            continue;
        }
        $line[7] = trim($line[7]);
        if(!empty($line[7]) && false === strpos($line[7], '*')) {
            if(!isset($expenditures[$line[7]])) {
                $expenditures[$line[7]] = array(
                    'id' => $line[7],
                    'name' => $line[6],
                    'money' => 0
                );
            }
            $expenditures[$line[7]]['money'] += intval($line[9]);
        }
    }

    $fh = fopen("zip://{$zipFile}#incomes.csv", 'r');
    fgetcsv($fh, 2048);
    while($line = fgetcsv($fh, 2048)) {
        if(!isset($line[7])) {
            continue;
        }
        $line[7] = trim($line[7]);
        if(!empty($line[7]) && false === strpos($line[7], '*')) {
            if(!isset($incomes[$line[7]])) {
                $incomes[$line[7]] = array(
                    'id' => $line[7],
                    'name' => $line[6],
                    'money' => 0,
                );
            }
            $incomes[$line[7]]['money'] += intval($line[8]);
        }
    }
}

function cmp($a, $b)
{
    if ($a['money'] == $b['money']) {
        return 0;
    }
    return ($a['money'] > $b['money']) ? -1 : 1;
}
usort($expenditures, "cmp");
usort($incomes, "cmp");
$fh = fopen(dirname(__DIR__) . '/report/incomes_sort.csv', 'w');
fputcsv($fh, array('id', 'name', 'money'));
foreach($incomes AS $line) {
    fputcsv($fh, $line);
}
$fh = fopen(dirname(__DIR__) . '/report/expenditures_sort.csv', 'w');
fputcsv($fh, array('id', 'name', 'money'));
foreach($expenditures AS $line) {
    fputcsv($fh, $line);
}