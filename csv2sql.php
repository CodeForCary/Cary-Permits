<?php
/**
 * Created by PhpStorm.
 * User: nterry
 * Date: 2/21/15
 * Time: 10:47 PM
 */
$fpc = fopen('permits.csv', 'r');
$fps = fopen('2014_permits.sql', 'w');
$fp2 = fopen('2014_permits.csv', 'w');

$head = 'INSERT INTO `permit` (`year`, `permit_number`, `address`, `application_date`, `owner`, `application_number`, `application_type`, `valuation`, `square_footage`, `tenant_name`, `application_status`, `tenant_unit_number`, `general_contractor`, `zoning_description`) VALUES';
fwrite($fps, $head . "\n");

while(($line = fgets($fpc)) !== false){
    $line = rtrim(str_replace('2014,', '2014",', $line), ",\n");
    fwrite($fp2, $line . "\n");
    fwrite($fps, '(' . trim($line) . '),' . "\n");
}
fclose($fpc);
fclose($fps);
fclose($fp2);
