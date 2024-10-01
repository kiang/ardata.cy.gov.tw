<?php
$zip = new ZipArchive;
$pathIdv = dirname(__DIR__) . '/report/incomes/individual';
if (!file_exists($pathIdv)) {
    mkdir($pathIdv, 0777, true);
}
$pathBiz = dirname(__DIR__) . '/report/incomes/business';
if (!file_exists($pathBiz)) {
    mkdir($pathBiz, 0777, true);
}
/**
 *     [0] => 序號
    [1] => 擬參選人／政黨
    [2] => 選舉名稱
    [3] => 申報序號／年度
    [4] => 交易日期
    [5] => 收支科目
    [6] => 捐贈者／支出對象
    [7] => 身分證／統一編號
    [8] => 收入金額
    [9] => 支出金額
    [10] => 支出用途
    [11] => 金錢類
    [12] => 地址
    [13] => 聯絡電話
    [14] => 資料更正日期
 */
foreach (glob(dirname(__DIR__) . '/data/parties/account/*/*.zip') as $zipFile) {
    $fh = fopen("zip://{$zipFile}#incomes.csv", 'r');
    fgetcsv($fh, 2048);
    while ($line = fgetcsv($fh, 2048)) {
        if (empty($line[7]) || $line[8] < 10000) {
            continue;
        }
        $line[7] = trim($line[7]);
        if ($line[5] === '個人捐贈收入') {
            $targetFile = $pathIdv . '/' . substr($line[7], 0, 3) . $line[6] . '.csv';
        } else {
            if (strlen($line[7]) !== 8) {
                continue;
            }
            $targetFile = $pathBiz . '/' . $line[7] . '.csv';
        }
        if (!file_exists($targetFile)) {
            $oFh = fopen($targetFile, 'w');
            fputcsv($oFh, ['選舉', '捐贈對象', '捐贈日期', '捐贈金額']);
            fclose($oFh);
        }
        $oFh = fopen($targetFile, 'a');
        fputcsv($oFh, [$line[3] . '政黨', $line[1], $line[4], $line[8]]);
        fclose($oFh);
    }
}

foreach (glob(dirname(__DIR__) . '/data/individual/account/*/*_1.zip') as $zipFile) {
    $fh = fopen("zip://{$zipFile}#incomes.csv", 'r');
    fgetcsv($fh, 2048);
    while ($line = fgetcsv($fh, 2048)) {
        if (!isset($line[7])) {
            continue;
        }
        $line[7] = trim($line[7]);
        if ($line[8] < 10000) {
            continue;
        }
        if ($line[5] === '個人捐贈收入') {
            $targetFile = $pathIdv . '/' . substr($line[7], 0, 3) . $line[6] . '.csv';
        } else {
            if (strlen($line[7]) !== 8) {
                continue;
            }
            $targetFile = $pathBiz . '/' . $line[7] . '.csv';
        }
        if (!file_exists($targetFile)) {
            $oFh = fopen($targetFile, 'w');
            fputcsv($oFh, ['選舉', '捐贈對象', '捐贈日期', '捐贈金額']);
            fclose($oFh);
        }
        $oFh = fopen($targetFile, 'a');
        fputcsv($oFh, [$line[2], $line[1], $line[4], $line[8]]);
        fclose($oFh);
    }
}
