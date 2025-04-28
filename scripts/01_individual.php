<?php
$dataPath = dirname(__DIR__) . '/data/individual';
$accountPath = dirname(__DIR__) . '/data/individual/account';
if (!file_exists($accountPath)) {
    mkdir($accountPath, 0777, true);
}
$pageCount = 1;
for ($i = 1; $i <= $pageCount; $i++) {
    $listFile = $dataPath . "/page_{$i}.json";
    $c = exec("curl 'https://ardata.cy.gov.tw/api/v1/search/individuals?page={$i}&pageSize=1000&keyword=&' -H 'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:78.0) Gecko/20100101 Firefox/78.0' -H 'Accept: application/json, text/plain, */*' -H 'Accept-Language: en-US,en;q=0.5' --compressed -H 'Connection: keep-alive' -H 'Referer: https://ardata.cy.gov.tw/data/search/individual' -H 'Save-Data: on' -H 'Pragma: no-cache' -H 'Cache-Control: no-cache'");
    file_put_contents($listFile, json_encode(json_decode($c), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    $accounts = json_decode(file_get_contents($listFile), true);
    if ($pageCount == 1) {
        $pageCount = $accounts['paging']['pageCount'];
    }
    foreach ($accounts['data'] as $account) {
        if (!isset($account['electionArea'])) {
            $account['electionArea'] = '';
        }
        $individualPath = $accountPath . '/' . implode('/', array(
            $account['electionName'], $account['electionArea']
        )
        );
        if (!file_exists($individualPath)) {
            mkdir($individualPath, 0777, true);
        }
        if ($account['version'] > 1) {
            $individualZip = $individualPath . '/' . $account['name'] . '_' . $account['yearOrSerial'] . '_v' . $account['version'] . '.zip';
        } else {
            $individualZip = $individualPath . '/' . $account['name'] . '_' . $account['yearOrSerial'] . '.zip';
        }
        if (!file_exists($individualZip)) {
            $part1 = explode('?', $account['downloadZip']);
            $part2 = explode('&', $part1[1]);
            foreach ($part2 as $k => $v) {
                $part3 = explode('=', $v);
                $part3[1] = urlencode($part3[1]);
                $part2[$k] = implode('=', $part3);
            }
            $url = 'https://ardata.cy.gov.tw' . $part1[0] . '?' . implode('&', $part2);
            $individualZipPath = str_replace(array('(', ')', ' '), array('\\(', '\\)', '\\ '), $individualZip);
            exec("curl '{$url}' -H 'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:78.0) Gecko/20100101 Firefox/78.0' -H 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8' -H 'Accept-Language: en-US,en;q=0.5' --compressed -H 'Connection: keep-alive' -H 'Referer: https://ardata.cy.gov.tw/data/search/individual' -H 'Upgrade-Insecure-Requests: 1' -H 'Save-Data: on' -H 'Pragma: no-cache' -H 'Cache-Control: no-cache' > {$individualZipPath}");
        }
    }
}