<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

global $APPLICATION;
$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();

if ($request->isPost()) {

	$APPLICATION->IncludeComponent("bitrix:catalog.store.amount", "main", array(
		"PER_PAGE" => "10",
		"USE_STORE_PHONE" => $request->get("USE_STORE_PHONE")?:"N",
		"SCHEDULE" => $request->get("SCHEDULE"),
		"USE_MIN_AMOUNT" => $request->get("USE_MIN_AMOUNT")?:"N",
		"MIN_AMOUNT" => $request->get("MIN_AMOUNT")?:1,
		"OFFER_ID" => $request->get("OFFER_ID"),
		"ELEMENT_ID" => $request->get("ELEMENT_ID"),
		"STORE_PATH" => $request->get("STORE_PATH")?:"",
		"MAIN_TITLE" => $request->get("MAIN_TITLE")?:"",
		"MAX_AMOUNT" => $request->get("MAX_AMOUNT")?:1,
		"USE_ONLY_MAX_AMOUNT" => $request->get("USE_ONLY_MAX_AMOUNT")?:"N",
		"SHOW_EMPTY_STORE" => $request->get('SHOW_EMPTY_STORE')?:"N",
		"SHOW_GENERAL_STORE_INFORMATION" => $request->get('SHOW_GENERAL_STORE_INFORMATION')?:"N",
		"USER_FIELDS" => $request->get('USER_FIELDS')!=='undefined'?$request->get('USER_FIELDS'):[],
		"FIELDS" => $request->get('FIELDS')!=='undefined'?$request->get('FIELDS'):[],
		"STORES" => \Intervolga\Custom\Helpers\StoreHelper::SHOP_STORE_IDS,
		"SET_ITEMS" => $request->get("SET_ITEMS")!=='undefined'?$request->get("SET_ITEMS"):[],
		"CACHE_TYPE" => "N"
	),
		false
	);
}