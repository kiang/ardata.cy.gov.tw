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
foreach (glob(dirname(__DIR__) . '/data/mirror-media/*.csv') as $csvFile) {
    $fh = fopen($csvFile, 'r');
    $head = fgetcsv($fh, 2048);
    while ($line = fgetcsv($fh, 2048)) {
        $data = array_combine($head, $line);
        if (isset($data['屆數'])) {
            /**
             * [屆數] => 7
    [姓名] => 丁守中
    [捐贈者/支出對象] => 富廣開發股份有限公司
    [身份證/統一編] => 80015808
    [收入金額] => 300000
    [交易日期] => 096/12/26
    [地址] => 新竹縣竹北市
    [填答數] => 7
    [其他] => 身份證/統一編：80015808=7 =1
             */
            if ($data['收入金額'] < 10000) {
                continue;
            }
            if (strlen($data['身份證/統一編']) !== 8) {
                continue;
            }
            if($data['屆數'] == 7) {
                $data['屆數'] = '097年立法委員選舉';
            } else {
                $data['屆數'] = '101年立法委員選舉';
            }
            $targetFile = $pathBiz . '/' . $data['身份證/統一編'] . '.csv';
            if (!file_exists($targetFile)) {
                $oFh = fopen($targetFile, 'w');
                fputcsv($oFh, ['選舉', '捐贈對象', '捐贈人', '捐贈日期', '捐贈金額']);
                fclose($oFh);
            }
            $oFh = fopen($targetFile, 'a');
            fputcsv($oFh, [$data['屆數'], $data['姓名'], $data['捐贈者/支出對象'], $data['交易日期'], $data['收入金額']]);
            fclose($oFh);
        } else {
            /**
             *     [候選人] => 黃珊珊
    [推薦政黨] => 親民黨
    [當選註記] => 
    [序號] => 1
    [交易日期] => 105/01/15
    [收支科目] => 營利事業捐贈收入
    [統一編號] => 38018607
    [捐贈者／支出對象] => 正大尼龍工業股份有限公司
    [收入金額] => 200000
    [支出金額] => 
    [金錢類] => 是
    [地址] => 臺北市中山區
    [P] =>
             */
            if ($data['收入金額'] < 10000) {
                continue;
            }
            if($data['收支科目'] == '營利事業捐贈收入') {
                $targetFile = $pathBiz . '/' . $data['統一編號'] . '.csv';
            } else {
                $targetFile = $pathIdv . '/' . substr($data['統一編號'], 0, 3) . $data['捐贈者／支出對象'] . '.csv';
            }
            
            if (!file_exists($targetFile)) {
                $oFh = fopen($targetFile, 'w');
                fputcsv($oFh, ['選舉', '捐贈對象', '捐贈人', '捐贈日期', '捐贈金額']);
                fclose($oFh);
            }
            $oFh = fopen($targetFile, 'a');
            fputcsv($oFh, ['105年立法委員選舉', $data['候選人'], $data['捐贈者／支出對象'], $data['交易日期'], $data['收入金額']]);
            fclose($oFh);
        }
    }
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
            fputcsv($oFh, ['選舉', '捐贈對象', '捐贈人', '捐贈日期', '捐贈金額']);
            fclose($oFh);
        }
        $oFh = fopen($targetFile, 'a');
        fputcsv($oFh, [$line[3] . '政黨', $line[1], $line[6], $line[4], $line[8]]);
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
            fputcsv($oFh, ['選舉', '捐贈對象', '捐贈人', '捐贈日期', '捐贈金額']);
            fclose($oFh);
        }
        $oFh = fopen($targetFile, 'a');
        fputcsv($oFh, [$line[2], $line[1], $line[6], $line[4], $line[8]]);
        fclose($oFh);
    }
}
