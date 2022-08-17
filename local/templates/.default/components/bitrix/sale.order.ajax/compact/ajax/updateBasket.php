<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
use Intervolga\Custom\Import\CustomPrices;

$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
$result = [];

if ($request->isPost() && $request->get("prices")){
	CustomPrices::set($request->get("prices"), $request->get("basket"));
	$result["message"] = "ok";
}
else{
	$result["message"] = "error";
}

echo \Bitrix\Main\Web\Json::encode($result);
