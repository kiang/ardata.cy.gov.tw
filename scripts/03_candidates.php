<?php
$candidatePath = dirname(__DIR__) . '/elections.olc.tw/2020';
if(!file_exists($candidatePath)) {
    mkdir($candidatePath, 0777, true);
}
$accountPath = dirname(__DIR__) . '/data/individual/account';
$accountPathLenth = strlen($accountPath);
$fh = fopen(dirname(__DIR__) . '/data/candidates.csv', 'w');
fputcsv($fh, array('election', 'candidate', 'zip_file', 'election_id', 'candidate_id'));
foreach(glob(dirname(__DIR__) . '/data/individual/page_*.json') AS $jsonFile) {
    $json = json_decode(file_get_contents($jsonFile));
    foreach($json->data AS $candidate) {
        $candidateFile = $candidatePath . '/' . $candidate->name . '.json';
        if(filesize($candidateFile) == 0) {
            unlink($candidateFile);
        }
        if(!file_exists($candidateFile)) {
            file_put_contents($candidateFile, file_get_contents('https://elections.olc.tw/api/candidates/s/' . urlencode($candidate->name)));
        }
        $candidateJson = json_decode(file_get_contents($candidateFile), true);
        if(!isset($candidate->electionArea)) {
            $candidate->electionArea = '';
        }

        $individualZip = $accountPath . '/' . implode('/', array(
            $candidate->electionName,
            $candidate->electionArea,
            $candidate->name . '_' . $candidate->yearOrSerial . '.zip'
        ));

        $found = false;
        switch($candidate->electionName) {
            case '109年立法委員選舉':
            case '109年總統、副總統選舉':
                foreach($candidateJson AS $c) {
                    if(false !== strpos($c['Election']['name'], '2020-01')) {
                        $found = $c;
                    }
                }
            break;
            case '107年縣(市)議員選舉':
            case '107年直轄市市長選舉':
            case '107年縣(市)長選舉':
            case '107年鄉(鎮、市)民代表選舉':
            case '107年鄉(鎮、市)長選舉':
            case '107年村(里)長選舉':
            case '107年直轄市山地原住民區長選舉':
            case '107年直轄市山地原住民區民代表選舉':
                foreach($candidateJson AS $c) {
                    if(false !== strpos($c['Election']['name'], '2018-11')) {
                        $found = $c;
                    }
                }
            break;
            break;
        }

        if(false !== $found && file_exists($individualZip)) {
            fputcsv($fh, array($found['Election']['name'], $found['Candidate']['name'], substr($individualZip, $accountPathLenth), $found['Election']['id'], $found['Candidate']['id']));
        }
    }
}