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

	$rsStoreProduct = \Bitrix\Catalog\StoreProductTable::getList(array(
		'filter' => array('=PRODUCT_ID'=>$ID,'=STORE.ACTIVE'=>'Y', '=STORE_ID'=>$stores),
		'select' => array('AMOUNT'),
	));
	$amount = 0;

	while($arStoreProduct=$rsStoreProduct->fetch())
	{
		$amount += $arStoreProduct['AMOUNT'];
	}

	$displayQuantity = \Intervolga\Custom\Tools\RestsUtil::getQuantityArray($amount)["HTML"];
	$displayQuantity = str_replace("#REST#", $amount, $displayQuantity);

	echo $displayQuantity;
}
