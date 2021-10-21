<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("iblock");



for($i=6; $i<=400; $i++){

$arFields = Array(
        "ACTIVE" => "N",
    );
    
    $ID = CCatalogStore::Update($i, $arFields);
	
	
}
?>