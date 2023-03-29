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
				'AMOUNT',
				'STORE_ID',
			],
		]
	);

	$amount = [];
	while ($arStoreProduct = $rsStoreProduct->fetch()) {
		if (!isset($amount[$arStoreProduct['STORE_ID']])) {
			$amount[$arStoreProduct['STORE_ID']] = $arStoreProduct['AMOUNT'];
		} elseif($amount[$arStoreProduct['STORE_ID']] > $arStoreProduct['AMOUNT']) {
			$amount[$arStoreProduct['STORE_ID']] = $arStoreProduct['AMOUNT'];
		}
	}
	foreach ($amount as $id => $value) {
		$buffValue = $value;
		$amount[$id] = Intervolga\Custom\Tools\RestsUtil::getQuantityArray($value)['HTML'];
		$amount[$id] = str_replace('#REST#', $buffValue, $amount[$id]);
	}

	echo json_encode($amount);
}
