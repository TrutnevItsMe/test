<?php
global $APPLICATION;
$APPLICATION->RestartBuffer();

$csv = fopen("php://output", 'w');
foreach ($arResult["CSV"] as $csvString){
	fputcsv($csv, $csvString);
}

$file = "catalog.csv";
header('Content-Description: File Transfer');
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="' . basename($file) . '"');
header('Content-Transfer-Encoding: binary');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
die;
