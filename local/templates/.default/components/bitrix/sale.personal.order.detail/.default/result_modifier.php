<?
use CIBlockElement;
use Bitrix\Main\Config\Option;
use CNextCache;
use Intervolga\Custom\Import\Sets;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if (is_array($arResult["BASKET"])) {
	$catalogIblockID = Option::get(
		'aspro.next',
		'CATALOG_IBLOCK_ID',
		CNextCache::$arIBlocks[SITE_ID]['aspro_next_catalog']['aspro_next_catalog'][0]
	);
	$sets = [];
	foreach ($arResult["BASKET"] as $key => $arItem) {
		$productId = $arItem['PRODUCT_ID'];
		$rsProperty = CIBlockElement::GetProperty(
			$catalogIblockID,
			$productId,
			[],
			["CODE" => "COMPOSITION"]
		);
		if ($property = $rsProperty->Fetch()) {
			if (is_array($value = $property['VALUE'])) {
				$set = Sets::getSet($value['TEXT']);
				$set = array_column ($set['SET'], 'NAME');
				if (count ($set) > 0) {
					$sets[$key] = $set;
					$arResult["BASKET"][$key]["SET"] = $set;
				}
			}
		}
	}
	foreach ($arResult['SHIPMENT'] as $shipmentKey => $shipment) {
		foreach ($shipment['ITEMS'] as $key => $item) {
			if (isset($sets[$item['BASKET_ID']])) {
				$arResult['SHIPMENT'][$shipmentKey]['ITEMS'][$key]['SET'] = $sets[$item['BASKET_ID']];
			}
		}
	}
}
