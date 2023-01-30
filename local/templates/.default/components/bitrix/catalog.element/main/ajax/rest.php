<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

use \Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

global $APPLICATION;
$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();

if ($request->isPost())
{
	$ID = $request->get("ELEMENT_ID");
	$stores = $request->get("STORES");

	\Bitrix\Main\Diag\Debug::writeToFile(__FILE__ . ':' . __LINE__ . "\n(" . date('Y-m-d H:i:s').")\n" . print_r($stores, TRUE) . "\n\n", '', 'log/__trutnev_debug.log');

	$rsStoreProduct = \Bitrix\Catalog\StoreProductTable::getList(array(
		'filter' => array('=PRODUCT_ID'=>$ID,'=STORE.ACTIVE'=>'Y', '=STORE_ID'=>$stores),
		'select' => array('AMOUNT'),
	));
	$amount = 0;

	while($arStoreProduct=$rsStoreProduct->fetch())
	{
		$amount += $arStoreProduct['AMOUNT'];
	}

	$arQuantityData = CNext::GetQuantityArray($amount);
	$displayQuantity = "";

	if ($amount <= 0 || $amount >= $arQuantityData["OPTIONS"]["MAX_AMOUNT"])
	{
		$displayQuantity = $arQuantityData["HTML"];
	}
	else
	{
		$displayQuantity = "<div class='item-stock-qnty'><span class='value'>" . $amount .
			" " .
			\Bitrix\Main\Localization\Loc::getMessage("PIECES_SHORT_CAPTURE") . "</span></div>";
	}

	echo $displayQuantity;
}
