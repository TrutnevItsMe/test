<?php

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("viewed_show", "Y");
$APPLICATION->SetTitle("Сайт поставщика сантехники");

$USER->Authorize(1);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>