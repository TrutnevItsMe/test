<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Config\Option;
use CNextCache;
use CSaleOrderUserProps;
use Bitrix\Main\UserTable;
use Intervolga\Custom\Import\Sets;
use Intervolga\Common\Highloadblock\HlbWrap;
use \Bitrix\Main\Localization\Loc;

/**
 * @var array $arParams
 * @var array $arResult
 * @var SaleOrderAjax $component
 */

$component = $this->__component;
$component::scaleImages($arResult['JS_DATA'], $arParams['SERVICES_IMAGES_SCALING']);

$arProductValues = array_values($arResult['JS_DATA']["GRID"]["ROWS"]);
$arProductValues = array_column($arProductValues, "data");
$arProductId = array_column($arProductValues, "PRODUCT_ID");

$rsStoreProduct = \Bitrix\Catalog\StoreProductTable::getList([
	'filter' => [
		'=PRODUCT_ID' => $arProductId,
		'=STORE.ACTIVE' => 'Y',
		"!=AMOUNT" => 0,
	],
	'select' => ['AMOUNT', 'STORE_ID', 'STORE_TITLE' => 'STORE.TITLE', "PRODUCT_ID"],
]);

$arStores = [];

while ($store = $rsStoreProduct->fetch())
{
	$arStores[$store["PRODUCT_ID"]][$store["STORE_ID"]] = $store;
}

const STORE_CODE = "STORE";
const RESTS_CODE = "RESTS";

$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
$postPrices = [];
$postDiscounts = [];
$postQuantities = [];
$postXmlIds = [];

// Выбиравем предыдущего контрагента после получения инд. цен
if ($request->isPost() && $request->get("USER_PROFILE"))
{
	if (\Bitrix\Main\Loader::includeModule("sale"))
	{
		$mapCodesUserPropIndex = [];

		foreach ($arResult['JS_DATA']["ORDER_PROP"]["properties"] as $index => $arUserProp)
		{
			$mapCodesUserPropIndex[$arUserProp["CODE"]] = $index;
		}

		$dbProfileProps = CSaleOrderUserPropsValue::GetList(
			["ID" => "ASC"],
			[
				"USER_PROPS_ID" => $request->get("USER_PROFILE"),
				"PROP_CODE" => array_keys($mapCodesUserPropIndex)
			]
		);

		while ($profileProp = $dbProfileProps->Fetch())
		{
			$index = $mapCodesUserPropIndex[$profileProp["PROP_CODE"]];
			$arResult['JS_DATA']["ORDER_PROP"]["properties"][$index]["VALUE"] = [$profileProp["VALUE"]];
		}
	}

	foreach ($arResult['JS_DATA']["USER_PROFILES"] as $xmlId => &$userProfile)
	{
		if ($xmlId == $request->get("USER_PROFILE"))
		{
			$userProfile["CHECKED"] = "Y";
		}
		else
		{
			$userProfile["CHECKED"] = "N";
		}
	}
}

session_start();

if ($request->isPost() &&
	$request->get("price") &&
	$request->get("discount") &&
	$request->get("quantity") &&
	$request->get("xml_id"))
{
	$_SESSION["price"] = $request->get("price");
	$_SESSION["discount"] = $request->get("discount");
	$_SESSION["quantity"] = $request->get("quantity");
	$_SESSION["xml_id"] = $request->get("xml_id");

	$postPrices = $request->get("price");
	$postDiscounts = $request->get("discount");
	$postQuantities = $request->get("quantity");
	$postXmlIds = $request->get("xml_id");
}
else
{
	$postPrices = $_SESSION["price"];
	$postDiscounts = $_SESSION["discount"];
	$postQuantities = $_SESSION["quantity"];
	$postXmlIds = $_SESSION["xml_id"];
}

session_commit();

$arResult['JS_DATA']["TOTAL"]["PRICE_WITHOUT_DISCOUNT"] = 0;
$arResult['JS_DATA']["TOTAL"]["ORDER_PRICE"] = 0;
$arResult['JS_DATA']["TOTAL"]["DISCOUNT_PRICE"] = 0;

/*
 *
 * Вычисление скидки
 * */
foreach ($arResult["BASKET_ITEMS"] as $ID => $arRow)
{
	$oldPrice = $arResult["BASKET_ITEMS"][$ID]["SUM_BASE"];
	$newPrice = $arResult["BASKET_ITEMS"][$ID]["SUM_NUM"];
	$arResult["BASKET_ITEMS"][$ID]["DISCOUNT_PRICE_PERCENT"] = 1 - $newPrice / $oldPrice;
	$arResult["BASKET_ITEMS"][$ID]["DISCOUNT_PRICE_PERCENT_FORMATED"] = round($arResult["BASKET_ITEMS"][$ID]["DISCOUNT_PRICE_PERCENT"] * 100, 2) . "%";
}

if (is_array($arResult["GRID"]["ROWS"]))
{
	// Перестраиваем положение столбцов
	$newIndexPicture = ($arParams["INDEX_PICTURE"] >= 0) ? $arParams["INDEX_PICTURE"] : 0;
	$newIndexPicture = ($newIndexPicture < count($arResult['JS_DATA']["GRID"]["HEADERS"])) ? $newIndexPicture : count($arResult['JS_DATA']["GRID"]["HEADERS"]) - 1;

	$headerCodes = array_column($arResult['JS_DATA']["GRID"]["HEADERS"], "id");
	$previewPictureIndex = array_search("PREVIEW_PICTURE", $headerCodes);

	if ($previewPictureIndex !== false)
	{
		$tmp = $arResult['JS_DATA']["GRID"]["HEADERS"][$previewPictureIndex];
		$arResult['JS_DATA']["GRID"]["HEADERS"][$previewPictureIndex] =
			$arResult['JS_DATA']["GRID"]["HEADERS"][$newIndexPicture];
		$arResult['JS_DATA']["GRID"]["HEADERS"][$newIndexPicture] = $tmp;
	}
	else
	{
		$detailPictureIndex = array_search("PREVIEW_PICTURE", $headerCodes);

		if ($detailPictureIndex !== false)
		{
			$tmp = $arResult['JS_DATA']["GRID"]["HEADERS"][$detailPictureIndex];
			$arResult['JS_DATA']["GRID"]["HEADERS"][$detailPictureIndex] =
				$arResult['JS_DATA']["GRID"]["HEADERS"][$newIndexPicture];
			$arResult['JS_DATA']["GRID"]["HEADERS"][$newIndexPicture] = $tmp;
		}
	}
}

$headerCodes = array_column($arResult['JS_DATA']["GRID"]["HEADERS"], "id");

// Выводим склад
if ($arParams["SHOW_STORE"])
{
	$nameIndex = array_search("NAME", $headerCodes);
	$sliceBeforeName = array_slice($arResult['JS_DATA']["GRID"]["HEADERS"], 0, $nameIndex + 2);
	$sliceAfterName = array_slice($arResult['JS_DATA']["GRID"]["HEADERS"], $nameIndex + 2,
		count($arResult['JS_DATA']["GRID"]["HEADERS"]));
	$arResult['JS_DATA']["GRID"]["HEADERS"] = array_merge($sliceBeforeName, [["id" => STORE_CODE, "name" =>
		Loc::getMessage("SOA_PICKUP_STORE")]]);
	$arResult['JS_DATA']["GRID"]["HEADERS"] = array_merge($arResult['JS_DATA']["GRID"]["HEADERS"], $sliceAfterName);
}

$headerCodes = array_column($arResult['JS_DATA']["GRID"]["HEADERS"], "id");

// Перемещаем артикул  перед названием
$headerCodes = array_column($arResult['JS_DATA']["GRID"]["HEADERS"], "id");
$nameIndex = array_search("NAME", $headerCodes);
$articleIndex = array_search("PROPERTY_CML2_ARTICLE_VALUE", $headerCodes);

$tmp = $arResult['JS_DATA']["GRID"]["HEADERS"][$nameIndex];
$arResult['JS_DATA']["GRID"]["HEADERS"][$nameIndex] = $arResult['JS_DATA']["GRID"]["HEADERS"][$articleIndex];
$arResult['JS_DATA']["GRID"]["HEADERS"][$articleIndex] = $tmp;

$headerCodes = array_column($arResult['JS_DATA']["GRID"]["HEADERS"], "id");

// Перемещаем кол-во после склада

$storeIndex = array_search(STORE_CODE, $headerCodes);
$qntyIndex = array_search("QUANTITY", $headerCodes);

if ($storeIndex != count($arResult['JS_DATA']["GRID"]["HEADERS"]) - 1)
{

	$tmp = $arResult['JS_DATA']["GRID"]["HEADERS"][$storeIndex + 1];
	$arResult['JS_DATA']["GRID"]["HEADERS"][$storeIndex + 1] = $arResult['JS_DATA']["GRID"]["HEADERS"][$qntyIndex];
	$arResult['JS_DATA']["GRID"]["HEADERS"][$qntyIndex] = $tmp;
}

// Вывод остатков
if ($arParams["SHOW_RESTS"])
{
	$qntyIndex = array_search("QUANTITY", $headerCodes);
	$sliceBeforeQnty = array_slice($arResult['JS_DATA']["GRID"]["HEADERS"], 0, $qntyIndex + 1);
	$sliceAfterQnty = array_slice($arResult['JS_DATA']["GRID"]["HEADERS"], $qntyIndex + 1,
		count($arResult['JS_DATA']["GRID"]["HEADERS"]));
	$arResult['JS_DATA']["GRID"]["HEADERS"] = array_merge($sliceBeforeQnty, [["id" => RESTS_CODE, "name" =>
		Loc::getMessage("RESTS")]]);
	$arResult['JS_DATA']["GRID"]["HEADERS"] = array_merge($arResult['JS_DATA']["GRID"]["HEADERS"], $sliceAfterQnty);
}

// Ставим цену перед суммой
$headerCodes = array_column($arResult['JS_DATA']["GRID"]["HEADERS"], "id");
$priceIndex = array_search("PRICE_FORMATED", $headerCodes);
$sumIndex = array_search("SUM", $headerCodes);

if ($sumIndex != 0)
{

	$tmp = $arResult['JS_DATA']["GRID"]["HEADERS"][$priceIndex];
	$arResult['JS_DATA']["GRID"]["HEADERS"][$priceIndex] = $arResult['JS_DATA']["GRID"]["HEADERS"][$sumIndex - 1];
	$arResult['JS_DATA']["GRID"]["HEADERS"][$sumIndex - 1] = $tmp;
}

// https://youtrack.ivsupport.ru/issue/iberisweb-8
if (is_array($arResult["GRID"]["ROWS"]))
{
	$catalogIblockID = Option::get(
		'aspro.next',
		'CATALOG_IBLOCK_ID',
		CNextCache::$arIBlocks[SITE_ID]['aspro_next_catalog']['aspro_next_catalog'][0]
	);

	foreach ($arResult['JS_DATA']["GRID"]["ROWS"] as $key => $arItem)
	{
		$arResult['JS_DATA']["TOTAL"]["PRICE_WITHOUT_DISCOUNT"] +=
			$arResult['JS_DATA']["GRID"]["ROWS"][$key]["data"]["SUM_NUM"];

		if (in_array($arResult['JS_DATA']["GRID"]["ROWS"][$key]["data"]["PRODUCT_XML_ID"], $postXmlIds))
		{

			$index = array_search($arResult['JS_DATA']["GRID"]["ROWS"][$key]["data"]["PRODUCT_XML_ID"], $postXmlIds);

			if ($postPrices[$index] > 0)
			{
				$price = $postPrices[$index] - ($postDiscounts[$index] / $postQuantities[$index]);
				$sum = $price * $postQuantities[$index];
				$arResult['JS_DATA']["GRID"]["ROWS"][$key]["data"]["PRICE"] = $price;
				$arResult['JS_DATA']["GRID"]["ROWS"][$key]["data"]["PRICE_FORMATED"] = number_format($price) . " Р";
				$arResult['JS_DATA']["GRID"]["ROWS"][$key]["data"]["SUM_NUM"] = $sum;
				$arResult['JS_DATA']["GRID"]["ROWS"][$key]["data"]["SUM"] = number_format($sum) . " Р";
				$arResult['JS_DATA']["GRID"]["ROWS"][$key]["data"]["CUSTOM_PRICE"] = "Y";

				if (array_search("NOTES", $headerCodes) !== false)
				{
					$arResult['JS_DATA']["GRID"]["ROWS"][$key]["data"]["NOTES"] = "Ваша цена";
				}
			}
		}

		$arResult['JS_DATA']["TOTAL"]["ORDER_PRICE"] += $arResult['JS_DATA']["GRID"]["ROWS"][$key]["data"]["PRICE"];

		/*
		 *
 		* Вычисление скидки
 		* */
		$oldPrice = $arResult['JS_DATA']["GRID"]["ROWS"][$key]["data"]["SUM_BASE"];
		$newPrice = $arResult['JS_DATA']["GRID"]["ROWS"][$key]["data"]["SUM_NUM"];
		$arResult['JS_DATA']["GRID"]["ROWS"][$key]["data"]["DISCOUNT_PRICE_PERCENT"] = 1 - $newPrice / $oldPrice;
		$arResult['JS_DATA']["GRID"]["ROWS"][$key]["data"]["DISCOUNT_PRICE_PERCENT_FORMATED"] = round($arResult['JS_DATA']["GRID"]["ROWS"][$key]["data"]["DISCOUNT_PRICE_PERCENT"] * 100, 2) . "%";

		$productId = $arItem["data"]["PRODUCT_ID"];

		if ($arParams["SHOW_STORE"])
		{
			if ($arParams["DEF_STORE_ID"])
			{
				$arResult['JS_DATA']["GRID"]["ROWS"][$key]["data"][STORE_CODE] = $arStores[$productId][$arParams["DEF_STORE_ID"]]["STORE_TITLE"];
			}
			else
			{
				$arResult['JS_DATA']["GRID"]["ROWS"][$key]["data"][STORE_CODE] = current($arStores[$productId])["STORE_TITLE"];
			}
		}

		if ($arParams["SHOW_RESTS"])
		{
			$quantity = "";

			if ($arParams["DEF_STORE_ID"])
			{
				$quantity = $arStores[$productId][$arParams["DEF_STORE_ID"]]["AMOUNT"];
			}
			else
			{
				$quantity = current($arStores[$productId])["AMOUNT"];
			}

			$displayQuantity = \Intervolga\Custom\Tools\RestsUtil::getQuantityArray($quantity)["HTML"];
			$displayQuantity = str_replace("#REST#", $quantity, $displayQuantity);

			$arResult['JS_DATA']["GRID"]["ROWS"][$key]["data"][RESTS_CODE] = $displayQuantity;
		}

		$rsProperty = CIBlockElement::GetProperty(
			$catalogIblockID,
			$productId,
			[],
			["CODE" => "COMPOSITION"]
		);

		if ($property = $rsProperty->Fetch())
		{
			if (is_array($value = $property['VALUE']))
			{
				$set = Sets::getSet($value['TEXT']);
				$set = array_column($set['SET'], 'NAME');
				if (count($set) > 0)
				{
					$arResult['JS_DATA']["GRID"]["ROWS"][$key]["data"]['SET'] = $set;
				}
			}
		}
	}
}


$arResult['JS_DATA']["TOTAL"]["PRICE_WITHOUT_DISCOUNT_FORMATED"] = number_format($arResult['JS_DATA']["TOTAL"]["PRICE_WITHOUT_DISCOUNT"],
		0, ".", " ") . " Р";
$arResult['JS_DATA']["TOTAL"]["PRICE_WITHOUT_DISCOUNT_VALUE"] = $arResult['JS_DATA']["TOTAL"]["PRICE_WITHOUT_DISCOUNT"];

$arResult['JS_DATA']["TOTAL"]["ORDER_PRICE_FORMATED"] = number_format($arResult['JS_DATA']["TOTAL"]["ORDER_PRICE"],
		0, ".", " ") . " Р";

$arResult['JS_DATA']["TOTAL"]["DISCOUNT_PRICE"] = $arResult['JS_DATA']["TOTAL"]["PRICE_WITHOUT_DISCOUNT"] -
	$arResult['JS_DATA']["TOTAL"]["ORDER_PRICE"];
$arResult['JS_DATA']["TOTAL"]["DISCOUNT_PRICE_FORMATED"] = number_format($arResult['JS_DATA']["TOTAL"]["DISCOUNT_PRICE"],
		0, ",", " ") . " Р";

$arResult['JS_DATA']["TOTAL"]["ORDER_TOTAL_PRICE"] = $arResult['JS_DATA']["TOTAL"]["ORDER_PRICE"];
$arResult['JS_DATA']["TOTAL"]["ORDER_TOTAL_PRICE_FORMATED"] = $arResult['JS_DATA']["TOTAL"]["ORDER_PRICE_FORMATED"];

$dbProfiles = CSaleOrderUserProps::GetList(
	[],
	['ID' => array_keys($arResult["ORDER_PROP"]['USER_PROFILES'])],
	false,
	false,
	['ID', 'XML_ID', 'USER_ID']
);
$profiles = [];
while ($profile = $dbProfiles->Fetch())
{
	if ($profile['XML_ID'])
	{
		$profiles[$profile['ID']] = $profile;
	}
}

$user = UserTable::getRow([
	'filter' => ['=ID' => (reset($profiles))['USER_ID']],
	'select' => ['ID', 'XML_ID']
]);

if ($user)
{
	$userXmlId = $user['XML_ID'];
}

$soglasheniyaSKlientami = new HlbWrap('SoglasheniyaSKlientami');
$dbSoglashenia = $soglasheniyaSKlientami->getList([
	'filter' => ['=UF_KONTRAGENT' => array_column($profiles, 'XML_ID'), '!UF_KONTRAGENT' => false],
	'select' => ['UF_KONTRAGENT', 'UF_NAME', "UF_XML_ID"]
]);
$soglashenia = [];
while ($soglashenie = $dbSoglashenia->fetch())
{
	$key = $soglashenie['UF_KONTRAGENT'];

	if ($request->isPost() && $request->get("AGREEMENT_XML_ID"))
	{
		if ($soglashenie["UF_XML_ID"] == $request->get("AGREEMENT_XML_ID"))
		{
			$soglashenie["CHECKED"] = "Y";
		}

	}

	$soglashenia[$key][] = $soglashenie;
}
$agreementField = false;
foreach ($arResult['ORDER_PROP']['USER_PROPS_N'] as $prop)
{
	if ($prop['CODE'] == 'AGREEMENT')
	{
		$agreementField = $prop;
	}
}

foreach ($arResult['ORDER_PROP']['USER_PROPS_Y'] as $prop)
{
	if ($prop['IS_PROFILE_NAME'] == 'Y')
	{
		$arResult['PROFILE_FIELD'] = $prop;
	}
}
$basketItems = [];
foreach ($arResult['BASKET_ITEMS'] as $row => $item)
{
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
$dbPartnery = $HlBlock->getList(["filter" => ["UF_XML_ID" => $arUser["XML_ID"]]]);

$arResult["UPDATE_BASKET_DATA"] = [
	"prices" => [],
	"basket" => $basketItems];

if ($postPrices)
{

	for ($i = 0; $i < count($postPrices); ++$i)
	{
		$product = [];
		$product["price"] = $postPrices[$i];
		$product["discount"] = $postDiscounts[$i];
		$product["quantity"] = $postQuantities[$i];
		$product["xml_id"] = $postXmlIds[$i];
		$arResult["UPDATE_BASKET_DATA"]["prices"][] = $product;
	}
}