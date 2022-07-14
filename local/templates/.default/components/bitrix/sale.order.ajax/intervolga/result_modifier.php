<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
use Bitrix\Main\Config\Option;
use CNextCache;
use CSaleOrderUserProps;
use Bitrix\Main\UserTable;
use Intervolga\Custom\Import\Sets;
use Intervolga\Common\Highloadblock\HlbWrap;

/**
 * @var array $arParams
 * @var array $arResult
 * @var SaleOrderAjax $component
 */

$component = $this->__component;
$component::scaleImages($arResult['JS_DATA'], $arParams['SERVICES_IMAGES_SCALING']);


/*
 *
 * Вычисление скидки
 * */
foreach ($arResult["BASKET_ITEMS"] as $ID => $arRow){
	$oldPrice = $arResult["BASKET_ITEMS"][$ID]["SUM_BASE"];
	$newPrice = $arResult["BASKET_ITEMS"][$ID]["SUM_NUM"];
	$arResult["BASKET_ITEMS"][$ID]["DISCOUNT_PRICE_PERCENT"] = 1 - $newPrice / $oldPrice;
	$arResult["BASKET_ITEMS"][$ID]["DISCOUNT_PRICE_PERCENT_FORMATED"] = round($arResult["BASKET_ITEMS"][$ID]["DISCOUNT_PRICE_PERCENT"] * 100, 2) . "%";
}

// https://youtrack.ivsupport.ru/issue/iberisweb-8
if (is_array($arResult["GRID"]["ROWS"])) {
	$catalogIblockID = Option::get(
		'aspro.next',
		'CATALOG_IBLOCK_ID',
		CNextCache::$arIBlocks[SITE_ID]['aspro_next_catalog']['aspro_next_catalog'][0]
	);
	foreach ($arResult['JS_DATA']["GRID"]["ROWS"] as $key => $arItem) {

		/*
		 *
 		* Вычисление скидки
 		* */
		$oldPrice = $arResult['JS_DATA']["GRID"]["ROWS"][$key]["data"]["SUM_BASE"];
		$newPrice = $arResult['JS_DATA']["GRID"]["ROWS"][$key]["data"]["SUM_NUM"];
		$arResult['JS_DATA']["GRID"]["ROWS"][$key]["data"]["DISCOUNT_PRICE_PERCENT"] = 1 - $newPrice / $oldPrice;
		$arResult['JS_DATA']["GRID"]["ROWS"][$key]["data"]["DISCOUNT_PRICE_PERCENT_FORMATED"] = round($arResult['JS_DATA']["GRID"]["ROWS"][$key]["data"]["DISCOUNT_PRICE_PERCENT"] * 100, 2) . "%";

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
				if (count ($set) > 0) {
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
	['ID', 'XML_ID', 'USER_ID']
);
$profiles = [];
while ($profile = $dbProfiles -> Fetch()) {
	if($profile['XML_ID']) {
		$profiles[$profile['ID']] = $profile;
	}
}

$user = UserTable::getRow([
	'filter' => ['=ID' => (reset($profiles))['USER_ID']],
	'select' => ['ID', 'XML_ID']
]);

if ($user) {
	$userXmlId = $user['XML_ID'];
}

$soglasheniyaSKlientami = new HlbWrap('SoglasheniyaSKlientami');
$dbSoglashenia = $soglasheniyaSKlientami->getList([
	'filter' => ['=UF_KONTRAGENT' => array_column($profiles, 'XML_ID'), '!UF_KONTRAGENT' => false],
	'select' => ['UF_KONTRAGENT', 'UF_NAME', "UF_XML_ID"]
]);
$soglashenia = [];
while ($soglashenie = $dbSoglashenia->fetch()) {
	$key = $soglashenie['UF_KONTRAGENT'];
	$soglashenia[$key][] = $soglashenie;
}
$agreementField = false;
foreach ($arResult['ORDER_PROP']['USER_PROPS_N'] as $prop) {
	if($prop['CODE'] == 'AGREEMENT') {
		$agreementField = $prop;
	}
}
foreach ($arResult['ORDER_PROP']['USER_PROPS_Y'] as $prop) {
	if($prop['IS_PROFILE_NAME'] == 'Y') {
		$arResult['PROFILE_FIELD'] = $prop;
	}
}
$basketItems = [];
foreach ($arResult['BASKET_ITEMS'] as $row => $item) {
	$xmlId = $item['PRODUCT_XML_ID'];
	$basketItems[$xmlId] = [
		'row' => $row,
		'dbId' => $item['ID'],
		'xmlId' => $xmlId,
		'quantity' => $item['QUANTITY'],
	];
}
$arResult['PARTNERS'] = [
	'userXmlId' => $userXmlId,
	'agreementFieldId' => $agreementField['ID'],
	'counterparties' => $profiles,
	'agreements' => $soglashenia,
	'basket' => $basketItems,
];


$rsUser = UserTable::GetByID($USER->GetID());
$arUser = $rsUser->fetch();
$HlBlock = new HlbWrap(HL_BLOCK_CODE_PARTNERY);
$idPartneryHL = $HlBlock->getHlbId();
$dbPartnery = $HlBlock->getList(["filter" => ["UF_XML_ID" => $arUser["XML_ID"]]]);

$partner = $dbPartnery->fetch();
$arResult["PARTNER"] = $partner;
