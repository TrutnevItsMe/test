<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$arParams["POPUP_POSITION"] = (isset($arParams["POPUP_POSITION"]) && in_array($arParams["POPUP_POSITION"], array("left", "right"))) ? $arParams["POPUP_POSITION"] : "left";

// Убираем разделы из фильтра
$arHiddenProps = [];

$properties = array_column($arResult["ITEMS"], "CODE", "ID");

foreach ($arParams["HIDDEN_PROP"] as $hiddenProp)
{
	$searchedProp = array_search($hiddenProp, $properties);

	if ($searchedProp !== false)
	{
		$arHiddenProps[] = $searchedProp;
	}
}

foreach ($arResult["ITEMS"] as $key => &$arItem)
{
	/*unset empty values*/
	if (
		(
			($arItem["DISPLAY_TYPE"] == "A" || isset($arItem["PRICE"]))
			&& ($arItem["VALUES"]["MAX"]["VALUE"] - $arItem["VALUES"]["MIN"]["VALUE"] <= 0)
		)
		|| !$arItem["VALUES"]
	)
		unset($arResult["ITEMS"][$key]);

	if (in_array($key, $arHiddenProps))
	{
		unset($arResult["ITEMS"][$key]);
	}

	if ($arItem["CODE"] == "IN_STOCK")
	{
		if ($arResult["ITEMS"][$key]["VALUES"])
			$arResult["ITEMS"][$key]["VALUES"][0]["VALUE"] = $arItem["NAME"];
	}

	if ($arItem["CODE"] == "KATEGORIYA_DLYA_SAYTA")
	{
		foreach ($arItem["VALUES"] as $id => $arValue)
		{
			if (strpos($arValue["UPPER"], "КОМПЛЕКТУЮЩИЕ") !== false)
			{
				unset($arItem["VALUES"][$id]);
			}
		}
	}
}

\Bitrix\Main\Localization\Loc::loadLanguageFile(__FILE__);

include 'sort.php';

global $sotbitFilterResult;
$sotbitFilterResult = $arResult;