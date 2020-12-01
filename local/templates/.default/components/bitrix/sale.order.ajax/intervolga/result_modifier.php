<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
use Bitrix\Main\Config\Option;
use CNextCache;
use CSaleOrderUserProps;
use Intervolga\Custom\Import\Sets;
use Intervolga\Common\Highloadblock\HlbWrap;

/**
 * @var array $arParams
 * @var array $arResult
 * @var SaleOrderAjax $component
 */

$component = $this->__component;
$component::scaleImages($arResult['JS_DATA'], $arParams['SERVICES_IMAGES_SCALING']);

// https://youtrack.ivsupport.ru/issue/iberisweb-8
if (is_array($arResult["GRID"]["ROWS"])) {
	$catalogIblockID = Option::get(
		'aspro.next',
		'CATALOG_IBLOCK_ID',
		CNextCache::$arIBlocks[SITE_ID]['aspro_next_catalog']['aspro_next_catalog'][0]
	);
	foreach ($arResult['JS_DATA']["GRID"]["ROWS"] as $key => $arItem) {
		$productId = $arItem["data"]["PRODUCT_ID"];
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
				if (count ($set) > 0) {//Унитаз подвесной BELBAGNO BINGO
					$arResult['JS_DATA']["GRID"]["ROWS"][$key]["data"]['SET'] = $set;
				}
			}
		}
	}
}
$dbProfiles = CSaleOrderUserProps::GetList(
	[],
	['ID' => array_keys($arResult["ORDER_PROP"]['USER_PROFILES'])],
	false,
	false,
	['ID', 'XML_ID']
);
$profiles = [];
while ($profile = $dbProfiles -> Fetch()) {
	$profiles[$profile['XML_ID']] =  $profile['ID'];
}
$soglasheniyaSKlientami = new HlbWrap('SoglasheniyaSKlientami');
$dbSoglashenia = $soglasheniyaSKlientami->getList([
	'filter' => ['UF_KONTRAGENT' => array_keys($profiles)],
	'select' => ['UF_KONTRAGENT', 'UF_NAME']
]);
$soglashenia = [];
while ($soglashenie = $dbSoglashenia->Fetch()) {
	$key = $profiles[$soglashenie['UF_KONTRAGENT']];
	if (!isset($soglashenia[$key])) {
		$soglashenia[$key] = [];
	}
	$soglashenia[$key][] = $soglashenie;
}
$arResult['AGREEMENTS'] = $soglashenia;
foreach ($arResult['ORDER_PROP']['USER_PROPS_N'] as $prop) {
	if($prop['CODE'] == 'AGREEMENT') {
		$arResult['AGREEMENT_FIELD'] = $prop;
	}
}

