<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

use \Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

global $APPLICATION;
$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();

if ($request->isPost()) {
	$ID = $request->get("product_id");
	$stores = \Intervolga\Custom\Helpers\StoreHelper::SHOP_STORE_IDS;;

	$rsStoreProduct = \Bitrix\Catalog\StoreProductTable::getList(
		[
			'filter' => [
				'=PRODUCT_ID' => $ID,
				'=STORE.ACTIVE' => 'Y',
				'=STORE_ID' => $stores
			],
			'select' => [
				'AMOUNT'
			],
		]
	);

	$amount = 0;
	while ($arStoreProduct = $rsStoreProduct->fetch()) {
		if ($amount === 0) {
			$amount = $arStoreProduct['AMOUNT'];
		}
		if ($arStoreProduct['AMOUNT'] < $amount) {
			$amount = $arStoreProduct['AMOUNT'];
		}
	}

	$displayQuantity = \Intervolga\Custom\Tools\RestsUtil::getQuantityArray($amount)["HTML"];
	$displayQuantity = str_replace("#REST#", $amount, $displayQuantity);

	echo $displayQuantity;
}
