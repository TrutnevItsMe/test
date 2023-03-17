<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$arParams["POPUP_POSITION"] = (isset($arParams["POPUP_POSITION"]) && in_array($arParams["POPUP_POSITION"], array("left", "right"))) ? $arParams["POPUP_POSITION"] : "left";

// Убираем разделы из фильтра
$arHiddenProps = [];

foreach ($arParams["HIDDEN_PROP"] as $hiddenProp)
{
	//Ни один параметр фильтра не принимает массивы
	$rsProp = CIBlockProperty::GetList(
		$arOrder=[],
		$arFilter=[
			"CODE" => $hiddenProp
	]);

	if ($prop = $rsProp->Fetch())
	{
		$arHiddenProps[] = $prop["ID"];
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
			if (strpos($arValue["UPPER"], "КОМПЛЕКТУЮЩИЕ ДЛЯ") !== false)
			{
				unset($arItem["VALUES"][$id]);
			}
		}
	}

	// Оставляем только те значения, для которых есть хотя бы 1 товар
	if ($arItem["PROPERTY_TYPE"] == "L")
	{
		foreach ($arItem["VALUES"] as $id => $arValue)
		{
			$filter = [
				"IBLOCK_ID" => $arParams["IBLOCK_ID"],
				"PROPERTY_".$arItem["CODE"]."_VALUE" => $arValue["VALUE"],
			];

			if ($arParams["LINKED_BRAND_PROPERTY"])
			{
				$filter["PROPERTY_".$arParams["LINKED_BRAND_PROPERTY"]] = $arParams["LINKED_BRAND_VALUE"];
			}

			$rsElem = CIBlockElement::GetList(
				[],
				$filter,
				false,
				false,
				["ID"]
			);

			if (!$rsElem->GetNext())
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