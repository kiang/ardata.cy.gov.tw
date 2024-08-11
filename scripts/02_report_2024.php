<?php
$zip = new ZipArchive;
$expenditures = $incomes = array();
foreach (glob(dirname(__DIR__) . '/data/individual/account/113年總統、副總統選舉/*.zip') as $zipFile) {
    $fh = fopen("zip://{$zipFile}#expenditures.csv", 'r');
    fgetcsv($fh, 2048);
    while ($line = fgetcsv($fh, 2048)) {
        if (!isset($line[7])) {
            continue;
        }
        $line[7] = trim($line[7]);
        if (!empty($line[7]) && false === strpos($line[7], '*')) {
            if (!isset($expenditures[$line[7]])) {
                $expenditures[$line[7]] = array(
                    'money' => 0,
                    'name' => $line[6],
                    'id' => $line[7],
                );
            }
            $expenditures[$line[7]]['money'] += intval($line[9]);
        }
    }

    $fh = fopen("zip://{$zipFile}#incomes.csv", 'r');
    fgetcsv($fh, 2048);
    while ($line = fgetcsv($fh, 2048)) {
        if (!isset($line[7])) {
            continue;
        }
        $line[7] = trim($line[7]);
        if (!empty($line[7]) && false === strpos($line[7], '*')) {
            if (!isset($incomes[$line[7]])) {
                $incomes[$line[7]] = array(
                    'money' => 0,
                    'name' => $line[6],
                    'id' => $line[7],
                );
            }
            $incomes[$line[7]]['money'] += intval($line[8]);
        }
    }
}

foreach (glob(dirname(__DIR__) . '/data/individual/account/113年立法委員選舉/*/*.zip') as $zipFile) {
    $fh = fopen("zip://{$zipFile}#expenditures.csv", 'r');
    fgetcsv($fh, 2048);
    while ($line = fgetcsv($fh, 2048)) {
        if (!isset($line[9])) {
            continue;
        }
        $line[7] = trim($line[7]);
        if (!empty($line[7]) && false === strpos($line[7], '*')) {
            if (!isset($expenditures[$line[7]])) {
                $expenditures[$line[7]] = array(
                    'money' => 0,
                    'name' => $line[6],
                    'id' => $line[7],
                );
            }
            $expenditures[$line[7]]['money'] += intval($line[9]);
        }
    }

    $fh = fopen("zip://{$zipFile}#incomes.csv", 'r');
    fgetcsv($fh, 2048);
    while ($line = fgetcsv($fh, 2048)) {
        if (!isset($line[8])) {
            continue;
        }
        if (false !== strpos($line[7], ' ')) {
            $parts = explode(' ', $line[7]);
            $line[7] = array_pop($parts);
        }
        $line[7] = trim($line[7]);
        if (!empty($line[7]) && false === strpos($line[7], '*')) {
            if (!isset($incomes[$line[7]])) {
                $incomes[$line[7]] = array(
                    'money' => 0,
                    'name' => $line[6],
                    'id' => $line[7],
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
$fh = fopen(dirname(__DIR__) . '/report/2024_incomes_sort.csv', 'w');
fputcsv($fh, array('money', 'name', 'id', 'owner', 'capital', 'status', 'date_of_establishment'));
$gcisPath = dirname(__DIR__) . '/gcis.nat.g0v.tw';
foreach ($incomes as $line) {
    if (false !== strpos($line['id'], '政黨')) {
        continue;
    }
    $gcisFile = $gcisPath . '/' . $line['id'] . '.json';
    if (!file_exists($gcisFile)) {
        file_put_contents($gcisFile, file_get_contents('http://gcis.nat.g0v.tw/api/show/' . $line['id']));
    }
    $json = json_decode(file_get_contents($gcisFile), true);
    $json = $json['data'];
    if (isset($json['負責人姓名'])) {
        $json['代表人姓名'] = $json['負責人姓名'];
    }
    if (isset($json['資本額(元)'])) {
        $json['資本總額(元)'] = $json['資本額(元)'];
    }
    if (isset($json['現況'])) {
        $json['公司狀況'] = $json['現況'];
    }
    $line[] = isset($json['代表人姓名']) ? $json['代表人姓名'] : '';
    $line[] = isset($json['資本總額(元)']) ? $json['資本總額(元)'] : '';
    $line[] = isset($json['公司狀況']) ? $json['公司狀況'] : '';
    $line[] = isset($json['核准設立日期']) ? implode('-', $json['核准設立日期']) : '';
    fputcsv($fh, $line);
}
$fh = fopen(dirname(__DIR__) . '/report/2024_expenditures_sort.csv', 'w');
fputcsv($fh, array('money', 'name', 'id', 'owner', 'capital', 'status', 'date_of_establishment'));
foreach ($expenditures as $line) {
    if (false !== strpos($line['id'], '政黨')) {
        continue;
    }
    $gcisFile = $gcisPath . '/' . $line['id'] . '.json';
    if (!file_exists($gcisFile)) {
        file_put_contents($gcisFile, file_get_contents('http://gcis.nat.g0v.tw/api/show/' . $line['id']));
    }
    $json = json_decode(file_get_contents($gcisFile), true);
    $json = $json['data'];
    if (isset($json['負責人姓名'])) {
        $json['代表人姓名'] = $json['負責人姓名'];
    }
    if (isset($json['資本額(元)'])) {
        $json['資本總額(元)'] = $json['資本額(元)'];
    }
    if (isset($json['現況'])) {
        $json['公司狀況'] = $json['現況'];
    }
    $line[] = isset($json['代表人姓名']) ? $json['代表人姓名'] : '';
    $line[] = isset($json['資本總額(元)']) ? $json['資本總額(元)'] : '';
    $line[] = isset($json['公司狀況']) ? $json['公司狀況'] : '';
    $line[] = isset($json['核准設立日期']) ? implode('-', $json['核准設立日期']) : '';
    fputcsv($fh, $line);
}
