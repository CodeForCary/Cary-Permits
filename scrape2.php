<?php
/**
 * Created by PhpStorm.
 * User: nterry
 * Date: 2/21/15
 * Time: 3:57 PM
 */
$permit = 1;
$year1 = 'A';
do {
    $year2 = 'A';
    do {
        $url = 'https://click2gov.townofcary.org/Click2GovBP/Validate.jsp';
        $params = [
            'FunctionCode' => 'I',
            'errorJspPage' => 'SelectPermit',
            'jspPage' => 'StatusOptions',
            'ApplicationYear' => $year1.$year2,
            'ApplicationNumber' => $permit,
            'Submit.x' => '29',
            'Submit.y' => '9'
        ];

        $page = httpPost($url, $params);
//    file_put_contents('rawFile.html', $page);
        $page = strip_tags($page);
//    file_put_contents('strippedFile.html', $page);
        $page = str_replace('&nbsp;', ' ', $page);
        $lines = explode("\n", $page);
        $cleanPage = '';
        for ($i = 0; $i < count($lines); $i++) {
            $lines[$i] = trim($lines[$i]);
            if ($lines[$i] != '') $cleanPage .= $lines[$i] . "\n";
        }
//    echo $cleanPage;
        if (strpos($page, 'Invalid ')) {
//            echo $year1.$year2 . 'Not Valid' . "\n";
        } else {
            echo $year1.$year2 . 'Valid' . "\n";
        }
        $lines = explode("\n", $cleanPage);
        $count = count($lines);
        $record = $year1 . $year2 . '","' . $permit . '",';
        for ($i = 0; $i < $count; $i++) {
            if (substr($lines[$i], -1) == ':') {
                $var = str_replace([' ', ':'], ['', ''], $lines[$i]);
                if ($i != $count && substr($lines[$i + 1], -1) != ':') {
                    $$var = $lines[$i + 1];
                } else {
                    $$var = '';
                }
                $record .= '"' . $$var . '",';
            }
        }
        $record = trim($record, ',');
        echo $record . "\n";
        file_put_contents('permits2.csv', $record . "\n", FILE_APPEND);

        $year2 = chr(ord($year2) + 1);
    } while (ord($year2) <= ord('Z'));
    $year1 = chr(ord($year1) + 1);
} while (ord($year1) <= ord('Z'));
echo $permit - 1 . 'Records Retrieved';

function httpPost($url, $params)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_COOKIEJAR, 'CookieJar.txt');
    curl_setopt($ch, CURLOPT_COOKIEFILE, 'CookieJar.txt');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_0);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}